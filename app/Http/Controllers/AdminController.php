<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Testimonial;
use App\Services\DailyRecapService;
use App\Services\GoogleSheetService;
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

        $totalCustomers = User::where('role', 'customer')->count();
        $newCustomers   = User::where('role', 'customer')
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
    // ★ ANALYTICS — v2 (multi-metric, tidak bergantung ke selesai)
    // =========================================================================

    public function analytics(Request $request)
    {
        $period    = (int) $request->get('period', 30);
        $startDate = now('Asia/Jakarta')->subDays($period)->startOfDay();
        $endDate   = now('Asia/Jakarta')->endOfDay();

        $periodOrders = Order::whereBetween('created_at', [$startDate, $endDate])->get();

        // ── Revenue breakdown ──────────────────────────────────────────────────
        $grossRevenue   = $periodOrders->sum('total_price');
        $netRevenue     = $periodOrders->where('status', Order::STATUS_COMPLETED)->sum('total_price');
        $pendingRevenue = $periodOrders
            ->whereNotIn('status', [Order::STATUS_COMPLETED, Order::STATUS_CANCELLED])
            ->sum('total_price');

        // ── Order counts ───────────────────────────────────────────────────────
        $totalOrders     = $periodOrders->count();
        $completedOrders = $periodOrders->where('status', Order::STATUS_COMPLETED)->count();
        $cancelledOrders = $periodOrders->where('status', Order::STATUS_CANCELLED)->count();
        $processingCount = $periodOrders->where('status', Order::STATUS_PROCESSING)->count();
        $deliveringCount = $periodOrders->where('status', Order::STATUS_DELIVERING)->count();
        $pendingCount    = $periodOrders->where('status', Order::STATUS_PENDING)->count();

        // ── Conversion & avg (dari semua order, bukan cuma selesai) ───────────
        $conversionRate = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0;
        $avgOrderValue  = $totalOrders > 0 ? round($grossRevenue / $totalOrders) : 0;

        // ── Repeat customer % ──────────────────────────────────────────────────
        $phoneGroups     = $periodOrders->whereNotNull('customer_phone')->groupBy('customer_phone');
        $uniqueCustomers = $phoneGroups->count();
        $repeatCustomers = $phoneGroups->filter(fn($g) => $g->count() > 1)->count();
        $repeatRate      = $uniqueCustomers > 0 ? round($repeatCustomers / $uniqueCustomers * 100, 1) : 0;

        // ── Conversion Funnel ─────────────────────────────────────────────────
        $funnel = [
            ['label' => 'Pesanan Masuk', 'count' => $totalOrders,     'color' => 'orange'],
            ['label' => 'Diproses',       'count' => $processingCount, 'color' => 'blue'],
            ['label' => 'Dikirim',        'count' => $deliveringCount, 'color' => 'purple'],
            ['label' => 'Selesai',        'count' => $completedOrders, 'color' => 'green'],
            ['label' => 'Dibatalkan',     'count' => $cancelledOrders, 'color' => 'red'],
        ];

        // ── Customer stats ─────────────────────────────────────────────────────
        $totalCustomers = User::where('role', 'customer')->count();
        $newCustomers   = User::where('role', 'customer')
            ->whereBetween('created_at', [$startDate, $endDate])->count();

        // ── Daily revenue trend (gross + net) ──────────────────────────────────
        $trendLabels = $trendRevenue = $trendNetRevenue = $trendOrders = [];
        $days = min($period, 30);
        for ($i = $days - 1; $i >= 0; $i--) {
            $day       = now('Asia/Jakarta')->subDays($i);
            $dayOrders = $periodOrders->filter(fn($o) => $o->created_at->isSameDay($day));

            $trendLabels[]     = $day->translatedFormat('d M');
            $trendRevenue[]    = (int) $dayOrders->sum('total_price');
            $trendNetRevenue[] = (int) $dayOrders->where('status', Order::STATUS_COMPLETED)->sum('total_price');
            $trendOrders[]     = $dayOrders->count();
        }

        // ── Status distribution ────────────────────────────────────────────────
        $statusCounts = [
            'pending'    => $pendingCount,
            'processing' => $processingCount,
            'delivering' => $deliveringCount,
            'completed'  => $completedOrders,
            'cancelled'  => $cancelledOrders,
        ];

        // ── Top products (ALL orders, bukan cuma selesai) ──────────────────────
        $topProducts = $periodOrders
            ->flatMap(fn(Order $o) => $o->items_summary)
            ->groupBy('product_name')
            ->map(fn($items, $name) => [
                'name'     => $name,
                'quantity' => (float) $items->sum('quantity'),
                'revenue'  => (float) $items->sum('subtotal'),
            ])
            ->sortByDesc('revenue')
            ->take(8)
            ->values();

        // ── Payment methods ────────────────────────────────────────────────────
        $paymentMethods = $periodOrders
            ->groupBy('payment_method')
            ->map(fn($items, $method) => [
                'method' => $method ?: 'Tidak Diketahui',
                'count'  => $items->count(),
            ])
            ->sortByDesc('count')
            ->values();

        // ── Hourly ────────────────────────────────────────────────────────────
        $hourlyOrders = array_fill(0, 24, 0);
        foreach ($periodOrders as $order) {
            $hourlyOrders[(int) $order->created_at->setTimezone('Asia/Jakarta')->format('G')]++;
        }

        // ── Weekly ────────────────────────────────────────────────────────────
        $weeklyLabels  = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        $weeklyOrders  = array_fill(0, 7, 0);
        $weeklyRevenue = array_fill(0, 7, 0);
        foreach ($periodOrders as $order) {
            $dow = (int) $order->created_at->format('w');
            $weeklyOrders[$dow]++;
            $weeklyRevenue[$dow] += $order->total_price; // gross
        }

        // ── Top orders (ALL, bukan cuma selesai) ──────────────────────────────
        $topOrders = $periodOrders->sortByDesc('total_price')->take(5);

        return view('admin.analytics', compact(
            'period',
            'grossRevenue', 'netRevenue', 'pendingRevenue',
            'totalOrders', 'completedOrders', 'cancelledOrders',
            'processingCount', 'deliveringCount', 'pendingCount',
            'conversionRate', 'avgOrderValue',
            'uniqueCustomers', 'repeatCustomers', 'repeatRate',
            'funnel',
            'totalCustomers', 'newCustomers',
            'trendLabels', 'trendRevenue', 'trendNetRevenue', 'trendOrders',
            'statusCounts', 'topProducts', 'paymentMethods',
            'hourlyOrders', 'weeklyLabels', 'weeklyOrders', 'weeklyRevenue',
            'topOrders'
        ));
    }

    // =========================================================================
    // ★ KIRIM KE GOOGLE SHEET
    // =========================================================================

    public function sendToSheet(Request $request)
    {
        try {
            $period    = (int) $request->get('period', 30);
            $startDate = now('Asia/Jakarta')->subDays($period)->startOfDay();
            $endDate   = now('Asia/Jakarta')->endOfDay();

            $periodOrders = Order::whereBetween('created_at', [$startDate, $endDate])->latest()->get();

            // Build rows
            $rows = $periodOrders->map(fn(Order $o) => [
                $o->id,
                $o->reference ?? '',
                $o->created_at?->setTimezone('Asia/Jakarta')->format('d/m/Y H:i'),
                $o->customer_name  ?? '',
                $o->customer_phone ?? '',
                $o->customer_email ?? '',
                collect($o->items_summary)->pluck('product_name')->join(', '),
                (float) collect($o->items_summary)->sum('quantity'),
                (float) $o->total_price,
                $o->payment_method ?? '',
                $o->status_label   ?? ucfirst($o->status),
                $o->delivery_address ?? '',
                $o->notes            ?? '',
                $o->cancel_reason    ?? '',
                $o->completed_at?->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') ?? '',
            ])->values()->toArray();

            // Build analytics payload
            $grossRevenue    = $periodOrders->sum('total_price');
            $netRevenue      = $periodOrders->where('status', Order::STATUS_COMPLETED)->sum('total_price');
            $pendingRevenue  = $periodOrders->whereNotIn('status', [Order::STATUS_COMPLETED, Order::STATUS_CANCELLED])->sum('total_price');
            $totalOrders     = $periodOrders->count();
            $completedOrders = $periodOrders->where('status', Order::STATUS_COMPLETED)->count();
            $cancelledOrders = $periodOrders->where('status', Order::STATUS_CANCELLED)->count();

            $phoneGroups     = $periodOrders->whereNotNull('customer_phone')->groupBy('customer_phone');
            $uniqueCustomers = $phoneGroups->count();
            $repeatCustomers = $phoneGroups->filter(fn($g) => $g->count() > 1)->count();

            $dailyRevenue = [];
            for ($i = min($period, 30) - 1; $i >= 0; $i--) {
                $day       = now('Asia/Jakarta')->subDays($i);
                $dayOrders = $periodOrders->filter(fn($o) => $o->created_at->isSameDay($day));
                $dailyRevenue[] = [
                    'date'    => $day->format('d M'),
                    'revenue' => (float) $dayOrders->sum('total_price'),
                    'orders'  => $dayOrders->count(),
                ];
            }

            $topProducts = $periodOrders
                ->flatMap(fn(Order $o) => $o->items_summary)
                ->groupBy('product_name')
                ->map(fn($items, $name) => [
                    'name'    => $name,
                    'revenue' => (float) $items->sum('subtotal'),
                    'qty'     => (float) $items->sum('quantity'),
                ])
                ->sortByDesc('revenue')->take(10)->values()->toArray();

            $analytics = [
                'gross_revenue'        => (float) $grossRevenue,
                'net_revenue'          => (float) $netRevenue,
                'pending_revenue'      => (float) $pendingRevenue,
                'total_revenue'        => (float) $grossRevenue,
                'total_orders'         => $totalOrders,
                'completed_orders'     => $completedOrders,
                'cancelled_orders'     => $cancelledOrders,
                'processing_orders'    => $periodOrders->where('status', Order::STATUS_PROCESSING)->count(),
                'shipped_orders'       => $periodOrders->where('status', Order::STATUS_DELIVERING)->count(),
                'pending_orders'       => $periodOrders->where('status', Order::STATUS_PENDING)->count(),
                'avg_order_value'      => $totalOrders > 0 ? round($grossRevenue / $totalOrders) : 0,
                'conversion_rate'      => $totalOrders > 0 ? round($completedOrders / $totalOrders * 100, 1) : 0,
                'unique_customers'     => $uniqueCustomers,
                'repeat_customers'     => $repeatCustomers,
                'repeat_customer_rate' => $uniqueCustomers > 0 ? round($repeatCustomers / $uniqueCustomers * 100, 1) : 0,
                'daily_revenue'        => $dailyRevenue,
                'top_products'         => $topProducts,
                'status_counts'        => [
                    'Pending'    => $periodOrders->where('status', Order::STATUS_PENDING)->count(),
                    'Diproses'   => $periodOrders->where('status', Order::STATUS_PROCESSING)->count(),
                    'Dikirim'    => $periodOrders->where('status', Order::STATUS_DELIVERING)->count(),
                    'Selesai'    => $completedOrders,
                    'Dibatalkan' => $cancelledOrders,
                ],
            ];

            $result = app(GoogleSheetService::class)->bulkSync($rows, $analytics);

            return $result['success'] ?? false
                ? back()->with('success', '✅ Berhasil dikirim ke Google Sheet! (' . count($rows) . ' order, periode ' . $period . ' hari)')
                : back()->with('error', 'Gagal: ' . ($result['message'] ?? 'Unknown error'));

        } catch (\Throwable $e) {
            Log::error('sendToSheet error', ['msg' => $e->getMessage()]);
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // ORDERS
    // =========================================================================

    public function orders(Request $request)
    {
        $query = Order::query()->latest();
        if ($request->filled('status'))    $query->where('status', $request->status);
        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(fn($b) => $b->where('reference','like',$s)->orWhere('customer_name','like',$s)->orWhere('customer_email','like',$s)->orWhere('customer_phone','like',$s));
        }
        if ($request->filled('from_date')) $query->whereDate('created_at', '>=', $request->from_date);
        if ($request->filled('to_date'))   $query->whereDate('created_at', '<=', $request->to_date);

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
            $prev = $order->status;
            $order->update([
                'status'        => $validated['status'],
                'cancel_reason' => $validated['status'] === Order::STATUS_CANCELLED ? ($validated['cancel_reason'] ?? $order->cancel_reason) : null,
                'completed_at'  => $validated['status'] === Order::STATUS_COMPLETED ? now('Asia/Jakarta') : null,
            ]);
            $workflow->handleStatusChange($order->fresh(), $prev);
            return back()->with('success', 'Status pesanan berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error('Order status update failed', ['id' => $order->id, 'msg' => $e->getMessage()]);
            return back()->with('error', 'Gagal memperbarui status pesanan.');
        }
    }

    public function deleteOrder(Order $order, OrderWorkflowService $workflow)
    {
        try {
            $workflow->handleDeleted($order);
            $order->delete();
            return back()->with('success', 'Pesanan berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error('Order deletion failed', ['id' => $order->id, 'msg' => $e->getMessage()]);
            return back()->with('error', 'Gagal menghapus pesanan.');
        }
    }

    // =========================================================================
    // TESTIMONIALS
    // =========================================================================

    public function testimonials(Request $request)
    {
        $query = Testimonial::query()->latest();
        if ($request->filled('approval')) $query->where('is_approved', $request->approval === 'approved');
        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $query->where(fn($b) => $b->where('customer_name','like',$s)->orWhere('message','like',$s));
        }
        $testimonials   = $query->paginate(15)->withQueryString();
        $approvalCounts = ['pending' => Testimonial::where('is_approved', false)->count(), 'approved' => Testimonial::where('is_approved', true)->count()];
        return view('admin.testimonials', compact('testimonials', 'approvalCounts'));
    }

    public function approveTestimonial(Testimonial $testimonial)
    {
        try { $testimonial->update(['is_approved' => true]); return back()->with('success', 'Testimoni disetujui.'); }
        catch (\Throwable $e) { return back()->with('error', 'Gagal.'); }
    }

    public function deleteTestimonial(Testimonial $testimonial)
    {
        try { $testimonial->delete(); return back()->with('success', 'Testimoni dihapus.'); }
        catch (\Throwable $e) { return back()->with('error', 'Gagal.'); }
    }

    public function editTestimonial(Request $request, Testimonial $testimonial)
    {
        try {
            $v = $request->validate(['message' => 'required|string|min:10|max:1000', 'rating' => 'required|integer|min:1|max:5', 'is_approved' => 'nullable|boolean']);
            $testimonial->update(['message' => $v['message'], 'rating' => $v['rating'], 'is_approved' => (bool) ($v['is_approved'] ?? $testimonial->is_approved)]);
            return back()->with('success', 'Testimoni diperbarui.');
        } catch (\Throwable $e) { return back()->with('error', 'Gagal.'); }
    }

    public function sendDailyRecap(DailyRecapService $svc)
    {
        try { $svc->send(now('Asia/Jakarta')); return back()->with('success', 'Rekap harian dikirim.'); }
        catch (\Throwable $e) { return back()->with('error', 'Gagal mengirim rekap.'); }
    }

    public function realtimeOrders()
    {
        $orders = Order::query()->latest()->take(8)->get()->map(fn(Order $o) => [
            'id' => $o->id, 'reference' => $o->reference ?? 'N/A',
            'customer_name' => $o->customer_name ?? 'N/A', 'customer_phone' => $o->customer_phone ?? '-',
            'status' => $o->status, 'status_label' => $o->status_label ?? ucfirst($o->status),
            'status_color' => $o->status_color ?? 'text-slate-600',
            'total_price' => $o->total_price ?? 0,
            'total_formatted' => 'Rp ' . number_format($o->total_price ?? 0, 0, ',', '.'),
            'items_count' => is_array($o->items_summary) ? count($o->items_summary) : 0,
            'created_at' => $o->created_at?->toISOString(), 'created_ago' => $o->created_at?->diffForHumans(),
            'url' => route('admin.orders') . '?reference=' . urlencode($o->reference ?? ''),
        ]);
        return response()->json(['orders' => $orders, 'latest_id' => Order::latest()->value('id') ?? 0, 'total' => Order::count(), 'pending' => Order::where('status', Order::STATUS_PENDING)->count()]);
    }

    // =========================================================================
    // CUSTOMER MANAGEMENT
    // =========================================================================

    public function customers(Request $request)
    {
        $query = User::where('role', 'customer')->withCount('orders');

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }

        $customers = $query->latest()->paginate(20);

        // Stats
        $stats = [
            'total' => User::where('role', 'customer')->count(),
            'today' => User::where('role', 'customer')->whereDate('created_at', today())->count(),
            'week'  => User::where('role', 'customer')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        return view('admin.customers', compact('customers', 'stats'));
    }

    public function customerDetail(User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $orders = $customer->orders()->latest()->get();
        $totalSpent = $customer->orders()->where('status', Order::STATUS_COMPLETED)->sum('total_price');
        $totalOrders = $customer->orders()->count();

        return view('admin.customers.detail', compact('customer', 'orders', 'totalSpent', 'totalOrders'));
    }

    public function updateCustomer(Request $request, User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $customer->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $customer->update($validated);

        return back()->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function deleteCustomer(User $customer)
    {
        if ($customer->role !== 'customer') {
            abort(404);
        }

        $customer->delete();

        return redirect()->route('admin.customers')->with('success', 'Pelanggan berhasil dihapus.');
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
            $revenue[] = (int) Order::whereDate('created_at', $date->toDateString())->where('status', Order::STATUS_COMPLETED)->sum('total_price');
        }
        return [$labels, $orders, $revenue];
    }

    protected function topProducts(Collection $orders): Collection
    {
        return $orders->flatMap(fn(Order $o) => $o->items_summary)
            ->groupBy('product_name')
            ->map(fn(Collection $items, string $name) => ['name' => $name, 'quantity' => (float) $items->sum('quantity'), 'revenue' => (float) $items->sum('subtotal')])
            ->sortByDesc('quantity')->take(5)->values();
    }
}