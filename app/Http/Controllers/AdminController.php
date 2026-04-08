<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Testimonial;
use App\Services\DailyRecapService;
use App\Services\OrderWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function dashboard()
    {
        $orders = Order::query()->latest()->get();

        $totalOrders      = $orders->count();
        $pendingOrders    = $orders->where('status', Order::STATUS_PENDING)->count();
        $processingOrders = $orders->where('status', Order::STATUS_PROCESSING)->count();
        $completedOrders  = $orders->where('status', Order::STATUS_COMPLETED)->count();
        $totalRevenue     = $orders->where('status', Order::STATUS_COMPLETED)->sum('total_price');
        $pendingTestimonials = Testimonial::where('is_approved', false)->count();

        [$chartLabels, $chartOrders, $chartRevenue] = $this->buildChartData();

        $recentOrders = $orders->take(8);
        $topProducts  = $this->topProducts($orders);
        $dailyRecap   = app(DailyRecapService::class)->buildMessage(now('Asia/Jakarta'));

        // ★ Statistik customer untuk dashboard
        $totalCustomers  = User::where('role', 'customer')->count();
        $newCustomers    = User::where('role', 'customer')
                              ->whereDate('created_at', today())
                              ->count();

        return view('admin.dashboard', compact(
            'totalOrders', 'pendingOrders', 'processingOrders', 'completedOrders',
            'totalRevenue', 'pendingTestimonials', 'chartLabels', 'chartOrders',
            'chartRevenue', 'recentOrders', 'topProducts', 'dailyRecap',
            'totalCustomers', 'newCustomers'
        ));
    }

    // =========================================================================
    // ★ CUSTOMER MANAGEMENT
    // =========================================================================

    /**
     * Daftar semua customer dengan rincian.
     */
    public function customers(Request $request)
    {
        $query = User::where('role', 'customer')->withCount('orders')->latest();

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name',  'like', $search)
                  ->orWhere('phone', 'like', $search)
                  ->orWhere('email', 'like', $search);
            });
        }

        $customers = $query->paginate(20)->withQueryString();

        $stats = [
            'total'   => User::where('role', 'customer')->count(),
            'today'   => User::where('role', 'customer')->whereDate('created_at', today())->count(),
            'week'    => User::where('role', 'customer')->whereBetween('created_at', [now()->startOfWeek(), now()])->count(),
        ];

        return view('admin.customers', compact('customers', 'stats'));
    }

    /**
     * Detail customer + riwayat pesanannya.
     */
    public function customerDetail(User $customer)
    {
        abort_if($customer->role !== 'customer', 404);

        $orders = Order::where('customer_phone', $customer->phone)
                       ->orWhere('user_id', $customer->id)
                       ->latest()
                       ->get();

        $totalSpent = $orders->where('status', Order::STATUS_COMPLETED)->sum('total_price');

        return view('admin.customer_detail', compact('customer', 'orders', 'totalSpent'));
    }

    /**
     * Update data customer (nama, email, phone).
     */
    public function updateCustomer(Request $request, User $customer)
    {
        abort_if($customer->role !== 'customer', 404);

        $validated = $request->validate([
            'name'  => 'required|string|min:2|max:100',
            'email' => 'nullable|email|max:150|unique:users,email,' . $customer->id,
            'phone' => 'required|string|min:8|max:20',
        ]);

        $customer->update($validated);

        return back()->with('success', 'Data customer berhasil diperbarui.');
    }

    /**
     * Hapus customer.
     */
    public function deleteCustomer(User $customer)
    {
        abort_if($customer->role !== 'customer', 404);

        try {
            $customer->delete();
            return redirect()->route('admin.customers')->with('success', 'Customer berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error('Customer deletion failed', ['customer_id' => $customer->id, 'message' => $e->getMessage()]);
            return back()->with('error', 'Gagal menghapus customer.');
        }
    }

    // =========================================================================
    // ★ REALTIME ORDERS API
    // =========================================================================

    /**
     * API endpoint untuk polling realtime pesanan terkini di dashboard admin.
     * Dipanggil setiap 5 detik oleh JS.
     */
    public function realtimeOrders()
    {
        $orders = Order::query()
            ->latest()
            ->take(8)
            ->get()
            ->map(fn (Order $order) => [
                'id'            => $order->id,
                'reference'     => $order->reference ?? 'N/A',
                'customer_name' => $order->customer_name ?? 'N/A',
                'customer_phone'=> $order->customer_phone ?? '-',
                'status'        => $order->status,
                'status_label'  => $order->status_label ?? ucfirst($order->status),
                'status_color'  => $order->status_color ?? 'text-slate-600',
                'total_price'   => $order->total_price ?? 0,
                'total_formatted' => 'Rp ' . number_format($order->total_price ?? 0, 0, ',', '.'),
                'items_count'   => is_array($order->items_summary) ? count($order->items_summary) : 0,
                'created_at'    => $order->created_at?->toISOString(),
                'created_ago'   => $order->created_at?->diffForHumans(),
                'url'           => route('admin.orders') . '?reference=' . urlencode($order->reference ?? ''),
            ]);

        $latestId = Order::latest()->value('id') ?? 0;

        return response()->json([
            'orders'    => $orders,
            'latest_id' => $latestId,
            'total'     => Order::count(),
            'pending'   => Order::where('status', Order::STATUS_PENDING)->count(),
        ]);
    }

    // =========================================================================
    // ORDERS
    // =========================================================================

    public function orders(Request $request)
    {
        $query = Order::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($builder) use ($search): void {
                $builder->where('reference',      'like', $search)
                        ->orWhere('customer_name',  'like', $search)
                        ->orWhere('customer_email', 'like', $search)
                        ->orWhere('customer_phone', 'like', $search);
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $orders = $query->paginate(15)->withQueryString();
        $statusCounts = [
            Order::STATUS_PENDING    => Order::where('status', Order::STATUS_PENDING)->count(),
            Order::STATUS_PROCESSING => Order::where('status', Order::STATUS_PROCESSING)->count(),
            Order::STATUS_DELIVERING => Order::where('status', Order::STATUS_DELIVERING)->count(),
            Order::STATUS_COMPLETED  => Order::where('status', Order::STATUS_COMPLETED)->count(),
            Order::STATUS_CANCELLED  => Order::where('status', Order::STATUS_CANCELLED)->count(),
        ];

        return view('admin.orders', compact('orders', 'statusCounts'));
    }

    public function updateOrderStatus(Request $request, Order $order, OrderWorkflowService $workflow)
    {
        $validated = $request->validate([
            'status'        => 'required|in:' . implode(',', Order::statusOptions()),
            'cancel_reason' => 'nullable|string|max:500',
        ]);

        try {
            $previousStatus = $order->status;

            $order->update([
                'status'        => $validated['status'],
                'cancel_reason' => $validated['status'] === Order::STATUS_CANCELLED
                    ? ($validated['cancel_reason'] ?? $order->cancel_reason)
                    : null,
                'completed_at'  => $validated['status'] === Order::STATUS_COMPLETED
                    ? now('Asia/Jakarta')
                    : null,
            ]);

            $workflow->handleStatusChange($order->fresh(), $previousStatus);

            return back()->with('success', 'Status pesanan berhasil diperbarui.');
        } catch (\Throwable $exception) {
            Log::error('Order status update failed', ['order_id' => $order->id, 'message' => $exception->getMessage()]);
            return back()->with('error', 'Gagal memperbarui status pesanan.');
        }
    }

    public function deleteOrder(Order $order, OrderWorkflowService $workflow)
    {
        try {
            $workflow->handleDeleted($order);
            $order->delete();
            return back()->with('success', 'Pesanan berhasil dihapus.');
        } catch (\Throwable $exception) {
            Log::error('Order deletion failed', ['order_id' => $order->id, 'message' => $exception->getMessage()]);
            return back()->with('error', 'Gagal menghapus pesanan.');
        }
    }

    // =========================================================================
    // TESTIMONIALS
    // =========================================================================

    public function testimonials(Request $request)
    {
        $query = Testimonial::query()->latest();

        if ($request->filled('approval')) {
            $query->where('is_approved', $request->approval === 'approved');
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($builder) use ($search): void {
                $builder->where('customer_name', 'like', $search)
                        ->orWhere('message', 'like', $search);
            });
        }

        $testimonials   = $query->paginate(15)->withQueryString();
        $approvalCounts = [
            'pending'  => Testimonial::where('is_approved', false)->count(),
            'approved' => Testimonial::where('is_approved', true)->count(),
        ];

        return view('admin.testimonials', compact('testimonials', 'approvalCounts'));
    }

    public function approveTestimonial(Testimonial $testimonial)
    {
        try {
            $testimonial->update(['is_approved' => true]);
            return back()->with('success', 'Testimoni berhasil disetujui.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menyetujui testimoni.');
        }
    }

    public function deleteTestimonial(Testimonial $testimonial)
    {
        try {
            $testimonial->delete();
            return back()->with('success', 'Testimoni berhasil dihapus.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal menghapus testimoni.');
        }
    }

    public function editTestimonial(Request $request, Testimonial $testimonial)
    {
        try {
            $validated = $request->validate([
                'message'     => 'required|string|min:10|max:1000',
                'rating'      => 'required|integer|min:1|max:5',
                'is_approved' => 'nullable|boolean',
            ]);

            $testimonial->update([
                'message'     => $validated['message'],
                'rating'      => $validated['rating'],
                'is_approved' => (bool) ($validated['is_approved'] ?? $testimonial->is_approved),
            ]);

            return back()->with('success', 'Testimoni berhasil diperbarui.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal memperbarui testimoni.');
        }
    }

    public function sendDailyRecap(DailyRecapService $dailyRecapService)
    {
        try {
            $dailyRecapService->send(now('Asia/Jakarta'));
            return back()->with('success', 'Rekap harian berhasil dikirim.');
        } catch (\Throwable $exception) {
            return back()->with('error', 'Gagal mengirim rekap harian.');
        }
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    protected function buildChartData(): array
    {
        $labels = $orders = $revenue = [];

        for ($offset = 6; $offset >= 0; $offset--) {
            $date = now('Asia/Jakarta')->subDays($offset)->startOfDay();
            $labels[]  = $date->translatedFormat('d M');
            $orders[]  = Order::whereDate('created_at', $date->toDateString())->count();
            $revenue[] = (int) Order::whereDate('created_at', $date->toDateString())
                ->where('status', Order::STATUS_COMPLETED)
                ->sum('total_price');
        }

        return [$labels, $orders, $revenue];
    }

    protected function topProducts(Collection $orders): Collection
    {
        return $orders
            ->flatMap(fn (Order $order) => $order->items_summary)
            ->groupBy('product_name')
            ->map(fn (Collection $items, string $name) => [
                'name'     => $name,
                'quantity' => (float) $items->sum('quantity'),
                'revenue'  => (float) $items->sum('subtotal'),
            ])
            ->sortByDesc('quantity')
            ->take(5)
            ->values();
    }
}