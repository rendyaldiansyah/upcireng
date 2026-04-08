<?php

namespace App\Http\Controllers;

use App\Events\OrderCreated;
use App\Mail\OrderConfirmationCustomer;
use App\Mail\OrderNotificationAdmin;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use App\Services\OrderWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        $products = Product::query()
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $testimonials = Testimonial::query()
            ->where('is_approved', true)
            ->latest()
            ->limit(6)
            ->get();

        $storeOpen    = Setting::isStoreOpen();
        $hours        = Setting::getOperatingHours();
        $storeProfile = Setting::storeProfile();
        $heroContent  = Setting::heroContent();
        $user         = $this->currentUser();

        $deliverySettings = [
            'store_lat'        => Setting::getSetting('store_lat'),
            'store_lng'        => Setting::getSetting('store_lng'),
            'cod_free_km'      => Setting::getSetting('cod_free_km', 5),
            'cod_extra_per_km' => Setting::getSetting('cod_extra_per_km', 5000),
        ];

        return view('order.index', compact(
            'products',
            'testimonials',
            'storeOpen',
            'hours',
            'storeProfile',
            'heroContent',
            'deliverySettings',
            'user'
        ));
    }

    public function create()
    {
        $user = $this->requireCustomer();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $storeOpen = Setting::isStoreOpen();
        $hours     = Setting::getOperatingHours();

        $products = Product::query()
            ->where('status', 'active')
            ->where('stock_status', 'available')
            ->where('is_open', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        // Get QRIS image for checkout display
        $qrisImage = Setting::getSetting('qris_image', '');
        $qrisUrl   = null;

        if ($qrisImage) {
            $qrisImage = str_replace('\\', '/', $qrisImage);
            $fullPath  = storage_path('app/public/' . $qrisImage);

            if (file_exists($fullPath)) {
                $qrisUrl = asset('storage/' . $qrisImage);
                Log::info('QRIS found', ['qrisImage' => $qrisImage, 'qrisUrl' => $qrisUrl, 'path' => $fullPath]);
            } else {
                Log::warning('QRIS file not found', ['qrisImage' => $qrisImage, 'fullPath' => $fullPath]);
            }
        } else {
            Log::info('No QRIS image setting found');
        }

        return view('order.create', compact('user', 'products', 'storeOpen', 'hours', 'qrisUrl'));
    }

    public function store(Request $request, OrderWorkflowService $workflow)
    {
        $user = $this->requireCustomer();

        if (!$user) {
            return $this->errorResponse($request, 'Silakan login terlebih dahulu.', 401, route('login'));
        }

        if (!Setting::isStoreOpen()) {
            $hours = Setting::getOperatingHours();

            return $this->errorResponse(
                $request,
                'Toko sedang tutup. Jam operasional: ' . ($hours['start'] ?? '-') . ' - ' . ($hours['end'] ?? '-'),
                403,
                route('home')
            );
        }

        $items = $this->extractItems($request);
        $request->merge(['items' => $items]);

        $rules = [
            'customer_name'      => 'required|string|min:3|max:100',
            'customer_phone'     => 'required|string|min:9|max:15|regex:/^[0-9+\-\s()]{9,}$/',
            'customer_email'     => 'required|email|max:100',
            'delivery_address'   => 'required|string|min:10|max:500',
            'payment_method'     => 'required|in:cod,bank_transfer,ewallet,qris',
            'notes'              => 'nullable|string|max:1000',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity'   => 'required|numeric|min:1|max:999',
            'items.*.variant'    => 'nullable|string|max:100',
        ];

        if ($request->payment_method !== 'cod') {
            $rules['payment_proof'] = 'required|image|mimes:jpeg,jpg,png|max:2048';
        } else {
            $rules['payment_proof'] = 'nullable|image|mimes:jpeg,jpg,png|max:2048';
        }

        $messages = [
            'customer_name.required'     => 'Nama pelanggan wajib diisi.',
            'customer_name.min'          => 'Nama minimal 3 karakter.',
            'customer_name.max'          => 'Nama maksimal 100 karakter.',
            'customer_phone.required'    => 'Nomor WhatsApp wajib diisi.',
            'customer_phone.min'         => 'Nomor WhatsApp minimal 9 karakter.',
            'customer_phone.max'         => 'Nomor WhatsApp maksimal 15 karakter.',
            'customer_phone.regex'       => 'Format nomor WhatsApp tidak valid.',
            'customer_email.required'    => 'Email wajib diisi.',
            'customer_email.email'       => 'Format email tidak valid.',
            'customer_email.max'         => 'Email terlalu panjang.',
            'delivery_address.required'  => 'Alamat pengiriman wajib diisi.',
            'delivery_address.min'       => 'Alamat minimal 10 karakter.',
            'delivery_address.max'       => 'Alamat maksimal 500 karakter.',
            'payment_method.required'    => 'Metode pembayaran wajib dipilih.',
            'payment_method.in'          => 'Metode pembayaran tidak valid.',
            'notes.max'                  => 'Catatan maksimal 1000 karakter.',
            'items.required'             => 'Minimal 1 produk harus dipilih.',
            'items.min'                  => 'Minimal 1 produk harus dipilih.',
            'items.*.product_id.required'=> 'ID produk wajib ada.',
            'items.*.product_id.exists'  => 'Produk tidak ditemukan.',
            'items.*.product_id.integer' => 'ID produk harus berupa angka.',
            'items.*.quantity.required'  => 'Jumlah produk wajib diisi.',
            'items.*.quantity.numeric'   => 'Jumlah harus berupa angka.',
            'items.*.quantity.min'       => 'Jumlah minimal 1.',
            'items.*.quantity.max'       => 'Jumlah maksimal 999.',
            'payment_proof.required'     => 'Bukti pembayaran wajib diunggah.',
            'payment_proof.image'        => 'File harus berupa gambar.',
            'payment_proof.mimes'        => 'Format gambar harus JPEG, JPG, atau PNG.',
            'payment_proof.max'          => 'Ukuran gambar maksimal 2MB.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return $this->validationErrorResponse($request, $validator->errors()->toArray());
        }

        try {
            [$normalizedItems, $totalPrice, $totalQuantity, $summaryTitle, $primaryProductId, $primaryUnitPrice] = $this->normalizeOrderItems($items);

            $paymentProofPath = $request->hasFile('payment_proof')
                ? $request->file('payment_proof')->store('payment-proofs', 'public')
                : null;

            $order = Order::create([
                'user_id'            => $user->id,
                'product_id'         => $primaryProductId,
                'reference'          => $this->generateReference(),
                'product_name'       => $summaryTitle,
                'quantity'           => $totalQuantity,
                'price_per_unit'     => $primaryUnitPrice,
                'total_price'        => $totalPrice,
                'items'              => $normalizedItems,
                'payment_method'     => $request->payment_method,
                'payment_proof_path' => $paymentProofPath,
                'status'             => Order::STATUS_PENDING,
                'sync_status'        => Order::SYNC_PENDING,
                'customer_name'      => $request->customer_name,
                'customer_phone'     => $request->customer_phone,
                'customer_email'     => $request->customer_email,
                'delivery_address'   => $request->delivery_address,
                'notes'              => $request->notes,
                'order_time'         => now('Asia/Jakarta'),
            ]);

            // ✅ FIX: Sync email customer ke tabel users jika belum ada
            $this->syncCustomerEmail($user, $request->customer_email);

            // 🔥 Broadcast realtime order notification to admin
            broadcast(new OrderCreated($order))->toOthers();

            $workflow->handleCreated($order->fresh());

            $this->sendOrderEmails($order);

            if ($request->expectsJson()) {
                return response()->json([
                    'success'      => true,
                    'message'      => 'Pesanan berhasil dibuat.',
                    'order_id'     => $order->id,
                    'reference'    => $order->reference,
                    'redirect_url' => route('order.show', $order),
                ]);
            }

            return redirect()
                ->route('order.show', $order)
                ->with('success', 'Pesanan berhasil dibuat.');

        } catch (\Throwable $exception) {
            Log::error('Order creation failed', [
                'message' => $exception->getMessage(),
            ]);

            return $this->errorResponse($request, 'Gagal membuat pesanan.', 500, route('home'));
        }
    }

    /**
     * ✅ FIX: Sync email dari form checkout ke data user.
     * Jika user belum punya email → langsung isi.
     * Jika sudah punya email berbeda → tetap update ke yang terbaru.
     */
    protected function syncCustomerEmail(User $user, string $email): void
    {
        if (empty($email)) {
            return;
        }

        if ($user->email !== $email) {
            try {
                $user->update(['email' => $email]);
                Log::info('Customer email synced', [
                    'user_id'   => $user->id,
                    'old_email' => $user->email,
                    'new_email' => $email,
                ]);
            } catch (\Throwable $e) {
                Log::warning('Failed to sync customer email', [
                    'user_id' => $user->id,
                    'error'   => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Kirim email konfirmasi ke customer dan notifikasi ke admin.
     */
    protected function sendOrderEmails(Order $order): void
    {
        try {
            Mail::to($order->customer_email)
                ->send(new OrderConfirmationCustomer($order));
        } catch (\Throwable $e) {
            Log::error('Gagal kirim email konfirmasi ke customer', [
                'order_id' => $order->id,
                'email'    => $order->customer_email,
                'error'    => $e->getMessage(),
            ]);
        }

        try {
            $adminEmail = config('mail.admin_email', env('ADMIN_EMAIL'));

            if ($adminEmail) {
                Mail::to($adminEmail)
                    ->send(new OrderNotificationAdmin($order));
            }
        } catch (\Throwable $e) {
            Log::error('Gagal kirim email notifikasi ke admin', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);
        }
    }

    public function myOrders()
    {
        $user = $this->requireCustomer();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $orders = Order::query()
            ->where('user_id', $user->id)
            ->whereNull('deleted_by_customer_at')
            ->latest()
            ->paginate(10);

        return view('order.my_orders', [
            'orders'       => $orders,
            'storeProfile' => Setting::storeProfile(),
        ]);
    }

    public function show(Order $order)
    {
        $user = $this->requireCustomer();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if ($order->user_id !== $user->id || $order->deleted_by_customer_at) {
            abort(403, 'Unauthorized');
        }

        $storeProfile = Setting::storeProfile();
        $storePhone   = preg_replace('/\D+/', '', $storeProfile['phone'] ?? '');

        $itemsSummary = collect($order->items ?? [])
            ->map(function ($item) {
                $productName = $item['product_name'] ?? 'Produk';
                $quantity    = $item['quantity'] ?? 0;
                $subtotal    = $item['subtotal'] ?? 0;

                return '* ' . $productName . ' x' . $quantity . ' (Rp ' . number_format($subtotal, 0, ',', '.') . ')';
            })
            ->implode("\n");

        $paymentProof = $order->payment_proof_path
            ? "\n*Bukti pembayaran:* " . asset('storage/' . $order->payment_proof_path)
            : '';

        $notesText = $order->notes ? "\n\n*Catatan:* " . $order->notes : '';

        $whatsappMessage =
            "Halo UP Cireng!\n\n" .
            "*Pesanan Baru #{$order->reference}*\n\n" .
            ($itemsSummary ?: '-') . "\n\n" .
            '*Total:* Rp ' . number_format($order->total_price, 0, ',', '.') . "\n" .
            '*Pembayaran:* ' . ucwords(str_replace('_', ' ', $order->payment_method)) .
            $paymentProof . "\n\n" .
            "*Pelanggan:*\n" .
            $order->customer_name . "\n" .
            $order->customer_phone . "\n" .
            $order->customer_email . "\n\n" .
            "*Alamat:*\n" .
            $order->delivery_address .
            $notesText . "\n\n" .
            'Terima kasih!';

        $whatsappUrl = $storePhone
            ? "https://wa.me/{$storePhone}?text=" . urlencode($whatsappMessage)
            : null;

        $adminNum   = '6285189014426';
        $adminWaUrl = "https://wa.me/{$adminNum}?text=" . urlencode($whatsappMessage);

        return view('order.show', [
            'order'           => $order,
            'storeProfile'    => $storeProfile,
            'whatsappMessage' => $whatsappMessage,
            'whatsappUrl'     => $whatsappUrl,
            'adminWaUrl'      => $adminWaUrl,
            'paymentProofUrl' => $order->payment_proof_path ? asset('storage/' . $order->payment_proof_path) : null,
        ]);
    }

    public function cancel(Request $request, Order $order, OrderWorkflowService $workflow)
    {
        $user = $this->requireCustomer();

        if (!$user || $order->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        if (!$order->canBeCancelled()) {
            return back()->with('error', 'Pesanan ini tidak dapat dibatalkan.');
        }

        $request->validate([
            'cancel_reason' => 'nullable|string|max:500',
        ]);

        $previousStatus = $order->status;

        $order->update([
            'status'        => Order::STATUS_CANCELLED,
            'cancel_reason' => $request->cancel_reason,
            'completed_at'  => null,
        ]);

        $workflow->handleStatusChange($order->fresh(), $previousStatus);

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function destroy(Order $order)
    {
        $user = $this->requireCustomer();

        if (!$user || $order->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        if (!$order->canBeDeletedByCustomer()) {
            return back()->with('error', 'Pesanan ini belum dapat dihapus dari riwayat.');
        }

        $order->update([
            'deleted_by_customer_at' => now('Asia/Jakarta'),
        ]);

        return redirect()->route('orders.my')->with('success', 'Pesanan dihapus dari riwayat Anda.');
    }

    public function retrySyncOrder(Order $order, OrderWorkflowService $workflow)
    {
        $user = $this->requireCustomer();

        if (!$user || $order->user_id !== $user->id) {
            return back()->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }

        if ($order->sync_status !== Order::SYNC_FAILED) {
            return back()->with('error', 'Pesanan ini tidak perlu disinkronisasi.');
        }

        try {
            $order->update([
                'sync_status' => Order::SYNC_PENDING,
                'sync_error'  => null,
            ]);

            $workflow->handleStatusChange($order, $order->status);

            return back()->with('success', 'Pesanan sedang disinkronisasi ulang.');
        } catch (\Throwable $exception) {
            Log::error('Order sync retry failed', [
                'order_id' => $order->id,
                'message'  => $exception->getMessage(),
            ]);

            return back()->with('error', 'Gagal menyinkronisasi ulang. Silakan coba beberapa saat lagi.');
        }
    }

    protected function extractItems(Request $request): array
    {
        if ($request->filled('items')) {
            $items = $request->input('items');

            if (is_string($items)) {
                $decoded = json_decode($items, true);

                return is_array($decoded) ? $decoded : [];
            }

            return is_array($items) ? $items : [];
        }

        if ($request->filled('product_id')) {
            return [[
                'product_id' => $request->input('product_id'),
                'quantity'   => $request->input('quantity', 1),
                'variant'    => $request->input('variant'),
            ]];
        }

        return [];
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     * @return array{0: array<int, array<string, mixed>>, 1: float, 2: float, 3: string, 4: int|null, 5: float}
     */
    protected function normalizeOrderItems(array $items): array
    {
        $normalizedItems  = [];
        $totalPrice       = 0;
        $totalQuantity    = 0;
        $primaryProductId = null;
        $primaryUnitPrice = 0;

        foreach ($items as $index => $item) {
            $product = Product::findOrFail($item['product_id']);

            if (!$product->isAvailable()) {
                throw new \RuntimeException("Produk {$product->name} sedang tidak tersedia.");
            }

            $quantity  = (float) ($item['quantity'] ?? 0);
            $unitPrice = (float) $product->price;
            $subtotal  = $quantity * $unitPrice;

            if ($index === 0) {
                $primaryProductId = $product->id;
                $primaryUnitPrice = $unitPrice;
            }

            $totalPrice    += $subtotal;
            $totalQuantity += $quantity;

            $normalizedItems[] = [
                'product_id'   => $product->id,
                'product_name' => $product->name,
                'variant'      => filled($item['variant'] ?? null) ? (string) $item['variant'] : null,
                'quantity'     => $quantity,
                'unit_price'   => $unitPrice,
                'subtotal'     => $subtotal,
            ];
        }

        $summaryTitle = count($normalizedItems) === 1
            ? $normalizedItems[0]['product_name']
            : $normalizedItems[0]['product_name'] . ' + ' . (count($normalizedItems) - 1) . ' item lain';

        return [
            $normalizedItems,
            $totalPrice,
            $totalQuantity,
            $summaryTitle,
            $primaryProductId,
            $primaryUnitPrice,
        ];
    }

    protected function generateReference(): string
    {
        return 'UPC-' . now('Asia/Jakarta')->format('YmdHis') . '-' . Str::upper(Str::random(4));
    }

    protected function currentUser(): ?User
    {
        $userId = Session::get('user_id');

        return $userId ? User::find($userId) : null;
    }

    protected function requireCustomer(): ?User
    {
        return $this->currentUser();
    }

    /**
     * @param  array<string, mixed>  $errors
     */
    protected function validationErrorResponse(Request $request, array $errors)
    {
        if ($request->expectsJson()) {
            $firstErrorMessage = 'Validasi gagal.';
            foreach ($errors as $field => $messages) {
                if (is_array($messages) && !empty($messages)) {
                    $firstErrorMessage = $messages[0];
                    break;
                }
            }

            return response()->json([
                'message' => $firstErrorMessage,
                'errors'  => $errors,
            ], 422);
        }

        return back()->withErrors($errors)->withInput();
    }

    protected function errorResponse(Request $request, string $message, int $status, string $redirect)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
            ], $status);
        }

        return redirect($redirect)->with('error', $message);
    }

    public function getLatestOrders()
    {
        $user    = $this->currentUser();
        $isAdmin = Session::get('admin_id');

        if ($isAdmin) {
            $totalOrders = Order::count();
            $latestOrder = Order::latest('id')->first();

            return response()->json([
                'total_orders'        => $totalOrders,
                'latest_order_id'     => $latestOrder?->id,
                'latest_order_status' => $latestOrder?->status,
                'user_type'           => 'admin',
            ]);
        }

        if ($user) {
            $totalOrders = Order::where('user_id', $user->id)
                ->whereNull('deleted_by_customer_at')
                ->count();

            $latestOrder = Order::where('user_id', $user->id)
                ->whereNull('deleted_by_customer_at')
                ->latest('id')
                ->first();

            return response()->json([
                'total_orders'        => $totalOrders,
                'latest_order_id'     => $latestOrder?->id,
                'latest_order_status' => $latestOrder?->status,
                'user_type'           => 'customer',
            ]);
        }

        return response()->json([
            'total_orders'    => 0,
            'latest_order_id' => null,
            'user_type'       => 'guest',
        ]);
    }
}