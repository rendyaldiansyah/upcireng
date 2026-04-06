<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f8fafc; color: #1e293b; }
        .wrapper { max-width: 600px; margin: 32px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #f97316, #ea580c); padding: 36px 32px; text-align: center; }
        .header h1 { color: #fff; font-size: 22px; font-weight: 800; letter-spacing: -0.3px; }
        .header p { color: rgba(255,255,255,0.85); font-size: 13px; margin-top: 6px; }
        .badge { display: inline-block; background: rgba(255,255,255,0.2); color: #fff; font-size: 12px; font-weight: 700; padding: 4px 14px; border-radius: 99px; margin-top: 12px; letter-spacing: 0.5px; }
        .body { padding: 32px; }
        .greeting { font-size: 15px; color: #475569; margin-bottom: 20px; line-height: 1.6; }
        .greeting strong { color: #0f172a; }
        .section-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #94a3b8; margin-bottom: 12px; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .items-table th { background: #f1f5f9; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #64748b; padding: 10px 12px; text-align: left; }
        .items-table th:last-child { text-align: right; }
        .items-table td { padding: 12px; font-size: 13px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
        .items-table td:last-child { text-align: right; font-weight: 700; color: #0f172a; }
        .items-table .product-name { font-weight: 600; color: #0f172a; }
        .items-table .product-variant { font-size: 11px; color: #94a3b8; margin-top: 2px; }
        .total-row { background: #fff7ed; }
        .total-row td { padding: 14px 12px; font-weight: 800; font-size: 15px; color: #ea580c; border-bottom: none; }
        .info-box { background: #f8fafc; border-radius: 12px; padding: 16px 18px; margin-bottom: 20px; }
        .info-row { display: flex; justify-content: space-between; font-size: 13px; padding: 5px 0; }
        .info-row .label { color: #64748b; }
        .info-row .value { font-weight: 600; color: #0f172a; text-align: right; max-width: 60%; }
        .status-badge { display: inline-block; background: #fef3c7; color: #d97706; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 99px; }
        .note-box { background: #fffbeb; border-left: 3px solid #f59e0b; padding: 12px 16px; border-radius: 0 8px 8px 0; font-size: 13px; color: #92400e; margin-bottom: 24px; }
        .footer-cta { text-align: center; margin: 28px 0 8px; }
        .btn { display: inline-block; background: linear-gradient(135deg, #f97316, #ea580c); color: #fff !important; text-decoration: none; font-size: 14px; font-weight: 700; padding: 13px 32px; border-radius: 10px; }
        .footer { background: #f8fafc; padding: 24px 32px; text-align: center; border-top: 1px solid #e2e8f0; }
        .footer p { font-size: 12px; color: #94a3b8; line-height: 1.7; }
        .footer strong { color: #64748b; }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- Header --}}
    <div class="header">
        <h1>✅ Pesanan Berhasil!</h1>
        <p>Terima kasih sudah memesan di UP Cireng</p>
        <span class="badge">#{{ $order->reference }}</span>
    </div>

    <div class="body">

        <p class="greeting">
            Halo, <strong>{{ $order->customer_name }}</strong>! 👋<br>
            Pesanan kamu sudah kami terima dan sedang kami proses. Berikut ringkasan pesananmu:
        </p>

        {{-- Item Table --}}
        <p class="section-title">Rincian Pesanan</p>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items_summary as $item)
                <tr>
                    <td>
                        <div class="product-name">{{ $item['product_name'] }}</div>
                        @if($item['variant'])
                            <div class="product-variant">{{ $item['variant'] }}</div>
                        @endif
                    </td>
                    <td>{{ (int)$item['quantity'] }}x</td>
                    <td>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="2">Total Pembayaran</td>
                    <td>{{ $order->formatPrice() }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Order Info --}}
        <p class="section-title">Informasi Pesanan</p>
        <div class="info-box">
            <div class="info-row">
                <span class="label">Nomor Pesanan</span>
                <span class="value">{{ $order->reference }}</span>
            </div>
            <div class="info-row">
                <span class="label">Metode Pembayaran</span>
                <span class="value">{{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}</span>
            </div>
            <div class="info-row">
                <span class="label">Status</span>
                <span class="value"><span class="status-badge">{{ $order->status_label }}</span></span>
            </div>
            <div class="info-row">
                <span class="label">Alamat Pengiriman</span>
                <span class="value">{{ $order->delivery_address }}</span>
            </div>
            <div class="info-row">
                <span class="label">Tanggal Pesan</span>
                <span class="value">{{ $order->created_at->translatedFormat('d F Y, H:i') }}</span>
            </div>
        </div>

        @if($order->notes)
        <div class="note-box">
            📝 <strong>Catatan:</strong> {{ $order->notes }}
        </div>
        @endif

        <div class="footer-cta">
            <a href="{{ route('order.show', $order) }}" class="btn">Lihat Detail Pesanan →</a>
        </div>

    </div>

    <div class="footer">
        <p>
            <strong>UP Cireng</strong><br>
            Email ini dikirim otomatis, mohon jangan membalas email ini.<br>
            Butuh bantuan? Hubungi kami melalui WhatsApp.
        </p>
    </div>

</div>
</body>
</html>