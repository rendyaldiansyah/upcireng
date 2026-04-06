<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Baru Masuk</title>
</head>
<body style="margin:0;background:#f8fafc;font-family:Arial,sans-serif;color:#0f172a;">
    <div style="max-width:680px;margin:0 auto;padding:24px;">
        <div style="background:#ffffff;border-radius:24px;overflow:hidden;box-shadow:0 20px 40px rgba(15,23,42,0.08);">
            <div style="padding:32px;background:linear-gradient(135deg,#0f172a 0%,#111827 55%,#f97316 100%);color:#ffffff;">
                <p style="margin:0;font-size:12px;letter-spacing:0.3em;text-transform:uppercase;opacity:0.8;">Admin Alert</p>
                <h1 style="margin:12px 0 0;font-size:32px;line-height:1.2;">Pesanan baru masuk.</h1>
            </div>

            <div style="padding:32px;">
                <div style="padding:18px 20px;border-radius:18px;background:#fff7ed;border:1px solid #fdba74;">
                    <p style="margin:0 0 6px;"><strong>Reference:</strong> {{ $order->reference }}</p>
                    <p style="margin:0 0 6px;"><strong>Pelanggan:</strong> {{ $order->customer_name }}</p>
                    <p style="margin:0 0 6px;"><strong>Kontak:</strong> {{ $order->customer_phone }} | {{ $order->customer_email }}</p>
                    <p style="margin:0;"><strong>Total:</strong> {{ $order->formatPrice() }}</p>
                </div>

                <div style="margin-top:24px;">
                    <p style="margin:0 0 12px;font-weight:bold;">Item order</p>
                    @foreach($order->items_summary as $item)
                        <div style="margin-bottom:10px;padding:14px 16px;border-radius:16px;background:#f8fafc;border:1px solid #e2e8f0;">
                            <strong>{{ $item['product_name'] }}</strong>
                            @if($item['variant']) | {{ $item['variant'] }} @endif
                            x {{ $item['quantity'] }}
                            <div style="margin-top:4px;color:#475569;">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</div>
                        </div>
                    @endforeach
                </div>

                <p style="margin-top:24px;"><strong>Alamat:</strong> {{ $order->delivery_address }}</p>
                @if($order->notes)
                    <p style="margin-top:12px;"><strong>Catatan:</strong> {{ $order->notes }}</p>
                @endif

                <div style="margin-top:28px;">
                    <a href="{{ route('admin.orders') }}" style="display:inline-block;padding:14px 22px;border-radius:999px;background:#0f172a;color:#ffffff;text-decoration:none;font-weight:bold;">
                        Buka daftar pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
