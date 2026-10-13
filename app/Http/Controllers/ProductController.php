<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Product;
use App\Models\ProductPurchase;
use App\Models\ProductPurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));
        $category = trim((string) $request->get('category', ''));

        $products = Product::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subquery) use ($search) {
                    $subquery->where('code', 'LIKE', "%{$search}%")
                        ->orWhere('name', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%");
                });
            })
            ->when($category, fn ($query) => $query->where('category', $category))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        if ($request->ajax()) {
            return view('products.partials.table', compact('products', 'search'));
        }

        $categories = Product::categories();

        return view('products.index', compact('products', 'search', 'category', 'categories'));
    }

    public function create()
    {
        $categories = Product::categories();
        $availableSalesBalance = ProductPurchase::availableSalesBalance();
        $totalNewInvestment = ProductPurchase::totalNewInvestment();
        $totalSalesRevenue = ProductPurchase::totalSalesRevenue();
        $productsCatalog = Product::orderBy('name')->get([
            'id', 'code', 'name', 'category', 'description', 'purchase_price', 'sale_price', 'stock',
        ]);

        return view('products.partials.create', compact(
            'categories',
            'availableSalesBalance',
            'totalNewInvestment',
            'totalSalesRevenue',
            'productsCatalog',
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_date' => ['required', 'date'],
            'supplier' => ['nullable', 'string', 'max:255'],
            'reference' => ['nullable', 'string', 'max:255'],
            'funding_source' => ['required', Rule::in(['new_investment', 'sales_revenue'])],
            'notes' => ['nullable', 'string', 'max:1000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['nullable', 'integer', 'exists:products,id'],
            'products.*.code' => ['required', 'string', 'max:20'],
            'products.*.name' => ['required', 'string', 'max:255'],
            'products.*.category' => ['required', Rule::in(array_keys(Product::categories()))],
            'products.*.description' => ['nullable', 'string'],
            'products.*.lot_number' => ['nullable', 'string', 'max:100'],
            'products.*.purchase_price' => ['required', 'numeric', 'min:0'],
            'products.*.sale_price' => ['nullable', 'numeric', 'min:0'],
            'products.*.stock' => ['required', 'integer', 'min:1'],
        ]);

        $seenProducts = [];
        $totalCost = 0.0;
        $totalQuantity = 0;

        foreach ($validated['products'] as $index => $item) {
            $identity = 'code:'.strtoupper(trim($item['code']));
            if (isset($seenProducts[$identity])) {
                throw ValidationException::withMessages([
                    "products.{$index}.code" => 'El mismo producto no puede repetirse en un ingreso. Agrupa las unidades en una sola fila.',
                ]);
            }

            $seenProducts[$identity] = true;
            $totalCost += (float) $item['purchase_price'] * (int) $item['stock'];
            $totalQuantity += (int) $item['stock'];
        }

        if ($validated['funding_source'] === 'sales_revenue' && $totalCost > ProductPurchase::availableSalesBalance()) {
            return back()->withInput()->with('error', sprintf(
                'El fondo de ventas no alcanza. Disponible: S/. %s; costo del ingreso: S/. %s.',
                number_format(ProductPurchase::availableSalesBalance(), 2),
                number_format($totalCost, 2),
            ));
        }

        $photoPath = $request->file('photo')?->store('inventory-receipts', 'public');

        try {
            $purchase = DB::transaction(function () use ($validated, $totalCost, $totalQuantity, $photoPath) {
                $purchase = ProductPurchase::create([
                    'user_id' => auth()->id(),
                    'purchase_date' => $validated['purchase_date'],
                    'supplier' => $validated['supplier'] ?? null,
                    'reference' => $validated['reference'] ?? null,
                    'total_cost' => $totalCost,
                    'funding_source' => $validated['funding_source'],
                    'items_count' => count($validated['products']),
                    'total_items_quantity' => $totalQuantity,
                    'notes' => $validated['notes'] ?? null,
                    'photo_path' => $photoPath,
                ]);

                foreach ($validated['products'] as $index => $item) {
                    $code = strtoupper(trim($item['code']));
                    $salePrice = ! empty($item['sale_price'])
                        ? (float) $item['sale_price']
                        : round((float) $item['purchase_price'] * 1.20, 2);

                    $product = ! empty($item['product_id'])
                        ? Product::lockForUpdate()->findOrFail($item['product_id'])
                        : Product::lockForUpdate()->where('code', $code)->first();

                    if ($product && $product->code !== $code && Product::where('code', $code)->whereKeyNot($product->id)->exists()) {
                        throw ValidationException::withMessages([
                            "products.{$index}.code" => "El código {$code} ya pertenece a otro producto.",
                        ]);
                    }

                    if ($product) {
                        $product->update([
                            'code' => $code,
                            'name' => $item['name'],
                            'category' => $item['category'],
                            'description' => $item['description'] ?? $product->description,
                            'purchase_price' => $item['purchase_price'],
                            'sale_price' => $salePrice,
                        ]);
                        $product->increment('stock', (int) $item['stock']);
                    } else {
                        $product = Product::create([
                            'code' => $code,
                            'name' => $item['name'],
                            'category' => $item['category'],
                            'description' => $item['description'] ?? null,
                            'purchase_price' => $item['purchase_price'],
                            'sale_price' => $salePrice,
                            'stock' => (int) $item['stock'],
                            'min_stock' => 5,
                        ]);
                    }

                    ProductPurchaseItem::create([
                        'product_purchase_id' => $purchase->id,
                        'product_id' => $product->id,
                        'product_code' => $product->code,
                        'product_name' => $product->name,
                        'lot_number' => $item['lot_number'] ?: 'LOT-'.$purchase->purchase_date->format('Ymd').'-'.str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                        'quantity' => (int) $item['stock'],
                        'unit_cost' => (float) $item['purchase_price'],
                        'sale_price' => $salePrice,
                        'subtotal' => (float) $item['purchase_price'] * (int) $item['stock'],
                    ]);
                }

                if ($validated['funding_source'] === 'sales_revenue') {
                    Expense::create([
                        'user_id' => auth()->id(),
                        'product_purchase_id' => $purchase->id,
                        'expense_date' => $validated['purchase_date'],
                        'category' => 'inventory_purchase',
                        'description' => "Compra de mercadería #{$purchase->id}",
                        'amount' => $totalCost,
                        'payment_source' => 'sales_revenue',
                        'photo_path' => $photoPath,
                        'notes' => $validated['notes'] ?? null,
                    ]);
                }

                return $purchase;
            });
        } catch (Throwable $exception) {
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }

            throw $exception;
        }

        return redirect()->route('inventory-entries.show', $purchase)
            ->with('success', sprintf(
                'Ingreso registrado: %d unidades por S/. %s. El stock y los precios actuales fueron actualizados.',
                $totalQuantity,
                number_format($totalCost, 2),
            ));
    }

    public function edit(Product $product)
    {
        $categories = Product::categories();

        return view('products.partials.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', Rule::unique('products', 'code')->ignore($product)],
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', Rule::in(array_keys(Product::categories()))],
            'description' => ['nullable', 'string'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $product)
    {
        if ($product->purchaseItems()->exists() || $product->saleDetails()->exists()) {
            return back()->with('error', 'No se puede eliminar un producto con movimientos históricos. Puedes dejar su stock en cero.');
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Producto eliminado.');
    }
}
