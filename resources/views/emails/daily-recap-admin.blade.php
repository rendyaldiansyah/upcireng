<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Harian</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f8fafc; color: #1e293b; }
        .wrapper { max-width: 640px; margin: 32px auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #7c3aed, #4f46e5); padding: 36px 32px; text-align: center; }
        .header h1 { color: #fff; font-size: 22px; font-weight: 800; }
        .header p { color: rgba(255,255,255,0.75); font-size: 13px; margin-top: 6px; }
        .stats-row { display: flex; gap: 0; border-bottom: 1px solid #e2e8f0; }
        .stat-box { flex: 1; padding: 24px 20px; text-align: center; border-right: 1px solid #e2e8f0; }
        .stat-box:last-child { border-right: none; }
        .stat-number { font-size: 28px; font-weight: 900; color: #0f172a; }
        .stat-number.green { color: #16a34a; }
        .stat-number.orange { color: #ea580c; }
        .stat-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: #94a3b8; margin-top: 4px; }
        .body { padding: 28px 32px; }
        .section-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #94a3b8; margin: 24px 0 12px; }
        .section-title:first-child { margin-top: 0; }

        /* Product summary table */
        .product-table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        .product-table th { background: #f1f5f9; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; padding: 10px 12px; text-align: left; }
        .product-table th:not(:first-child) { text-align: right; }
        .product-table td { padding: 11px 12px; font-size: 13px; border-bottom: 1px solid #f8fafc; }
        .product-table td:not(:first-child) { text-align: right; }
        .product-table tr:last-child td { border-bottom: none; }
        .product-name { font-weight: 600; color: #0f172a; }
        .total-row td { background: #f0fdf4; font-weight: 800; color: #16a34a; }

        /* Orders list */
        .order-card { border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 16px; margin-bottom: 10px; }
        .order-card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .order-ref { font-size: 12px; font-weight: 700; color: #64748b; }
        .order-total { font-size: 14px; font-weight: 800; color: #ea580c; }
        .order-meta { font-size: 12px; color: #94a3b8; line-height: 1.6; }
        .status-badge { display: inline-block; font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 99px; }
        .status-pending { background: #fef3c7; color: #d97706; }
        .status-processing { background: #dbeafe; color: #1d4ed8; }
        .status-delivering { background: #ede9fe; color: #7c3aed; }
        .status-completed { background: #dcfce7; color: #16a34a; }
        .status-cancelled { background: #fee2e2; color: #dc2626; }

        .footer { background: #f8fafc; padding: 20px 32px; text-align: center; border-top: 1px solid #e2e8f0; }
        .footer p { font-size: 12px; color: #94a3b8; line-height: 1.7; }
        .btn { display: inline-block; background: linear-gradient(135deg, #7c3aed, #4f46e5); color: #fff !important; text-decoration: none; font-size: 13px; font-weight: 700; padding: 11px 24px; border-radius: 10px; margin-top: 20px; }
        .empty { text-align: center; padding: 32px; color: #94a3b8; font-size: 14px; }
    </style>
</head>
<body>
<div class="wrapper">

    <div class="header">
        <h1>📊 Rekap Pesanan Harian</h1>
        <p>{{ $date }} · UP Cireng</p>
    </div>

    {{-- Stats --}}
    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-number">{{ $totalOrders }}</div>
            <div class="stat-label">Total Pesanan</div>
        </div>
        <div class="stat-box">
            <div class="stat-number green">{{ $orders->where('status', 'completed')->count() }}</div>
            <div class="stat-label">Selesai</div>
        </div>
        <div class="stat-box">
            <div class="stat-number orange">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="stat-label">Total Omzet</div>
        </div>
    </div>

    <div class="body">

        @if($orders->isEmpty())
            <div class="empty">😴 Tidak ada pesanan hari ini.</div>
        @else

        {{-- Rekap per produk --}}
        <p class="section-title">Rekap Per Produk</p>
        @php
            $productSummary = $orders->flatMap(fn($o) => collect($o->items_summary))
                ->groupBy('product_name')
                ->map(fn($items, $name) => [
                    'name'     => $name,
                    'qty'      => $items->sum('quantity'),
                    'subtotal' => $items->sum('subtotal'),
                ])
                ->sortByDesc('subtotal')
                ->values();
        @endphp
        <table class="product-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Qty Terjual</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productSummary as $product)
                <tr>
                    <td class="product-name">{{ $product['name'] }}</td>
                    <td>{{ (int)$product['qty'] }}x</td>
                    <td>Rp {{ number_format($product['subtotal'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td>Total Omzet</td>
                    <td>{{ (int)$orders->flatMap(fn($o) => collect($o->items_summary))->sum('quantity') }}x</td>
                    <td>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Daftar semua pesanan --}}
        <p class="section-title">Semua Pesanan ({{ $totalOrders }})</p>
        @foreach($orders as $order)
        <div class="order-card">
            <div class="order-card-header">
                <span class="order-ref">#{{ $order->reference }}</span>
                <span class="order-total">{{ $order->formatPrice() }}</span>
            </div>
            <div class="order-meta">
                👤 {{ $order->customer_name }} · 📞 {{ $order->customer_phone }}<br>
                💳 {{ strtoupper(str_replace('_', ' ', $order->payment_method)) }} ·
                🕐 {{ $order->created_at->format('H:i') }} WIB ·
                <span class="status-badge status-{{ $order->status }}">{{ $order->status_label }}</span>
            </div>
        </div>
        @endforeach

        <div style="text-align:center">
            <a href="{{ url('/adminup') }}" class="btn">Buka Dashboard Admin →</a>
        </div>

        @endif

    </div>

    <div class="footer">
        <p>
            <strong>UP Cireng</strong> · Rekap otomatis dikirim setiap hari pukul 20.00 WIB<br>
            Email ini dikirim otomatis, mohon jangan membalas.
        </p>
    </div>

</div>
</body>
</html>