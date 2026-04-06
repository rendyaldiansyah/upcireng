<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    private function checkAdmin(): void
    {
        if (!Session::has('admin_id')) {
            abort(403, 'Unauthorized');
        }
    }

    public function index()
    {
        $this->checkAdmin();

        $products = Product::query()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $this->checkAdmin();

        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $this->checkAdmin();

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:products,name|min:3|max:100',
                'price' => 'required|numeric|min:1000',
                'description' => 'nullable|string|max:1000',
                'variants_input' => 'nullable|string|max:1000',
                'sort_order' => 'nullable|integer|min:0|max:999',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
                'status' => 'required|in:active,inactive',
                'stock_status' => 'required|in:available,out_of_stock',
                'is_open' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            Product::create($this->buildPayload($request));

            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Throwable $exception) {
            Log::error('Product creation failed', [
                'message' => $exception->getMessage(),
            ]);

            return back()->with('error', 'Gagal menambahkan produk. Silakan coba lagi nanti.')->withInput();
        }
    }

    public function edit(Product $product)
    {
        $this->checkAdmin();

        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $this->checkAdmin();

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:products,name,' . $product->id . '|min:3|max:100',
                'price' => 'required|numeric|min:1000',
                'description' => 'nullable|string|max:1000',
                'variants_input' => 'nullable|string|max:1000',
                'sort_order' => 'nullable|integer|min:0|max:999',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
                'status' => 'required|in:active,inactive',
                'stock_status' => 'required|in:available,out_of_stock',
                'is_open' => 'required|boolean',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $product->update($this->buildPayload($request, $product));

            return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
        } catch (\Throwable $exception) {
            Log::error('Product update failed', [
                'product_id' => $product->id,
                'message' => $exception->getMessage(),
            ]);

            return back()->with('error', 'Gagal memperbarui produk. Silakan coba lagi nanti.')->withInput();
        }
    }

    public function destroy(Product $product)
    {
        $this->checkAdmin();

        try {
            if ($product->image && str_starts_with($product->image, 'products/')) {
                Storage::disk('public')->delete($product->image);
            }

            $product->delete();

            return back()->with('success', 'Produk berhasil dihapus.');
        } catch (\Throwable $exception) {
            Log::error('Product deletion failed', [
                'product_id' => $product->id,
                'message' => $exception->getMessage(),
            ]);

            return back()->with('error', 'Gagal menghapus produk. Silakan coba lagi nanti.');
        }
    }

    public function toggleStockStatus(Product $product)
    {
        $this->checkAdmin();

        try {
            $product->update([
                'stock_status' => $product->stock_status === 'available' ? 'out_of_stock' : 'available',
            ]);

            return back()->with('success', 'Status stok produk diperbarui.');
        } catch (\Throwable $exception) {
            Log::error('Product stock status toggle failed', [
                'product_id' => $product->id,
                'message' => $exception->getMessage(),
            ]);

            return back()->with('error', 'Gagal memperbarui status stok. Silakan coba lagi nanti.');
        }
    }

    public function toggleOpenStatus(Product $product)
    {
        $this->checkAdmin();

        try {
            $product->update([
                'is_open' => !$product->is_open,
            ]);

            return back()->with('success', 'Status buka produk diperbarui.');
        } catch (\Throwable $exception) {
            Log::error('Product open status toggle failed', [
                'product_id' => $product->id,
                'message' => $exception->getMessage(),
            ]);

            return back()->with('error', 'Gagal memperbarui status produk. Silakan coba lagi nanti.');
        }
    }

    public function getAvailable()
    {
        return Product::query()
            ->where('status', 'active')
            ->where('stock_status', 'available')
            ->where('is_open', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    protected function buildPayload(Request $request, ?Product $product = null): array
    {
        $variants = collect(preg_split('/[\r\n,]+/', (string) $request->variants_input))
            ->map(fn ($variant) => trim((string) $variant))
            ->filter()
            ->values()
            ->all();

        $data = [
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'status' => $request->status,
            'stock_status' => $request->stock_status,
            'is_open' => (bool) $request->is_open,
            'variants' => $variants,
            'sort_order' => $request->integer('sort_order', 0),
        ];

        if ($request->hasFile('image')) {
            if ($product?->image && str_starts_with($product->image, 'products/')) {
                Storage::disk('public')->delete($product->image);
            }

            $data['image'] = $request->file('image')->store('products', 'public');
        }

        return $data;
    }
}
