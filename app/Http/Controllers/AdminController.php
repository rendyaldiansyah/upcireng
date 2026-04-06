<?php

namespace App\Http\Controllers;

use App\Models\Order;
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

        $totalOrders = $orders->count();
        $pendingOrders = $orders->where('status', Order::STATUS_PENDING)->count();
        $processingOrders = $orders->where('status', Order::STATUS_PROCESSING)->count();
        $completedOrders = $orders->where('status', Order::STATUS_COMPLETED)->count();
        $totalRevenue = $orders->where('status', Order::STATUS_COMPLETED)->sum('total_price');
        $pendingTestimonials = Testimonial::where('is_approved', false)->count();

        [$chartLabels, $chartOrders, $chartRevenue] = $this->buildChartData();

        $recentOrders = $orders->take(8);
        $topProducts = $this->topProducts($orders);
        $dailyRecap = app(DailyRecapService::class)->buildMessage(now('Asia/Jakarta'));

        return view('admin.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'completedOrders',
            'totalRevenue',
            'pendingTestimonials',
            'chartLabels',
            'chartOrders',
            'chartRevenue',
            'recentOrders',
            'topProducts',
            'dailyRecap'
        ));
    }

    public function orders(Request $request)
    {
        $query = Order::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($builder) use ($search): void {
                $builder->where('reference', 'like', $search)
                    ->orWhere('customer_name', 'like', $search)
                    ->orWhere('customer_email', 'like', $search);
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
            Order::STATUS_PENDING => Order::where('status', Order::STATUS_PENDING)->count(),
            Order::STATUS_PROCESSING => Order::where('status', Order::STATUS_PROCESSING)->count(),
            Order::STATUS_DELIVERING => Order::where('status', Order::STATUS_DELIVERING)->count(),
            Order::STATUS_COMPLETED => Order::where('status', Order::STATUS_COMPLETED)->count(),
            Order::STATUS_CANCELLED => Order::where('status', Order::STATUS_CANCELLED)->count(),
        ];

        return view('admin.orders', compact('orders', 'statusCounts'));
    }

    public function updateOrderStatus(Request $request, Order $order, OrderWorkflowService $workflow)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', Order::statusOptions()),
            'cancel_reason' => 'nullable|string|max:500',
        ]);

        try {
            $previousStatus = $order->status;

            $order->update([
                'status' => $validated['status'],
                'cancel_reason' => $validated['status'] === Order::STATUS_CANCELLED
                    ? ($validated['cancel_reason'] ?? $order->cancel_reason)
                    : null,
                'completed_at' => $validated['status'] === Order::STATUS_COMPLETED
                    ? now('Asia/Jakarta')
                    : null,
            ]);

            $workflow->handleStatusChange($order->fresh(), $previousStatus);

            return back()->with('success', 'Status pesanan berhasil diperbarui.');
        } catch (\Throwable $exception) {
            Log::error('Order status update failed', [
                'order_id' => $order->id,
                'message' => $exception->getMessage(),
            ]);

            return back()->with('error', 'Gagal memperbarui status pesanan. Silakan coba lagi nanti.');
        }
    }

    public function deleteOrder(Order $order, OrderWorkflowService $workflow)
    {
        try {
            $workflow->handleDeleted($order);
            $order->delete();

            return back()->with('success', 'Pesanan berhasil dihapus.');
        } catch (\Throwable $exception) {
            Log::error('Order deletion failed', [
                'order_id' => $order->id,
                'message' => $exception->getMessage(),
            ]);

            return back()->with('error', 'Gagal menghapus pesanan. Silakan coba lagi nanti.');
        }
    }

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

        $testimonials = $query->paginate(15)->withQueryString();
        $approvalCounts = [
            'pending' => Testimonial::where('is_approved', false)->count(),
            'approved' => Testimonial::where('is_approved', true)->count(),
        ];

        return view('admin.testimonials', compact('testimonials', 'approvalCounts'));
    }

    public function approveTestimonial(Testimonial $testimonial)
    {
        try {
            $testimonial->update(['is_approved' => true]);

            return back()->with('success', 'Testimoni berhasil disetujui.');
        } catch (\Throwable $exception) {
            Log::error('Testimonial approval failed', [
                'testimonial_id' => $testimonial->id,
                'message' => $exception->getMessage(),
            ]);

            return back()->with('error', 'Gagal menyetujui testimoni. Silakan coba lagi nanti.');
        }
    }

    public function deleteTestimonial(Testimonial $testimonial)
    {
        try {
            $testimonial->delete();

            return back()->with('success', 'Testimoni berhasil dihapus.');
        } catch (\Throwable $exception) {
            Log::error('Testimonial deletion failed', [
                'testimonial_id' => $testimonial->id,
                'message' => $exception->getMessage(),
            ]);

            return back()->with('error', 'Gagal menghapus testimoni. Silakan coba lagi nanti.');
        }
    }

    public function editTestimonial(Request $request, Testimonial $testimonial)
    {
        try {
            $validated = $request->validate([
                'message' => 'required|string|min:10|max:1000',
                'rating' => 'required|integer|min:1|max:5',
                'is_approved' => 'nullable|boolean',
            ]);

            $testimonial->update([
                'message' => $validated['message'],
                'rating' => $validated['rating'],
                'is_approved' => (bool) ($validated['is_approved'] ?? $testimonial->is_approved),
            ]);

            return back()->with('success', 'Testimoni berhasil diperbarui.');
        } catch (\Throwable $exception) {
            Log::error('Testimonial update failed', [
                'testimonial_id' => $testimonial->id,
                'message' => $exception->getMessage(),
            ]);

            return back()->with('error', 'Gagal memperbarui testimoni. Silakan coba lagi nanti.');
        }
    }

    public function sendDailyRecap(DailyRecapService $dailyRecapService)
    {
        try {
            $dailyRecapService->send(now('Asia/Jakarta'));

            return back()->with('success', 'Rekap harian berhasil dikirim.');
        } catch (\Throwable $exception) {
            Log::error('Daily recap send failed', [
                'message' => $exception->getMessage(),
            ]);

            return back()->with('error', 'Gagal mengirim rekap harian. Silakan coba lagi nanti.');
        }
    }

    /**
     * @return array{0: array<int, string>, 1: array<int, int>, 2: array<int, int>}
     */
    protected function buildChartData(): array
    {
        $labels = [];
        $orders = [];
        $revenue = [];

        for ($offset = 6; $offset >= 0; $offset--) {
            $date = now('Asia/Jakarta')->subDays($offset)->startOfDay();

            $labels[] = $date->translatedFormat('d M');
            $orders[] = Order::whereDate('created_at', $date->toDateString())->count();
            $revenue[] = (int) Order::whereDate('created_at', $date->toDateString())
                ->where('status', Order::STATUS_COMPLETED)
                ->sum('total_price');
        }

        return [$labels, $orders, $revenue];
    }

    /**
     * @return Collection<int, array{name: string, quantity: float, revenue: float}>
     */
    protected function topProducts(Collection $orders): Collection
    {
        return $orders
            ->flatMap(fn (Order $order) => $order->items_summary)
            ->groupBy('product_name')
            ->map(function (Collection $items, string $name): array {
                return [
                    'name' => $name,
                    'quantity' => (float) $items->sum('quantity'),
                    'revenue' => (float) $items->sum('subtotal'),
                ];
            })
            ->sortByDesc('quantity')
            ->take(5)
            ->values();
    }
}
