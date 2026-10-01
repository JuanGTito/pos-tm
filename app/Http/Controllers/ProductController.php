<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductPurchase;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        
        if ($search) {
            $products = Product::where('code', 'LIKE', "%$search%")
                ->orWhere('name', 'LIKE', "%$search%")
                ->orWhere('description', 'LIKE', "%$search%")
                ->orderBy('id', 'desc')
                ->paginate(15);
        } else {
            $products = Product::orderBy('id', 'desc')->paginate(15);
        }
        
        // If it is an AJAX request, returns only the table
        if ($request->ajax()) {
            return view('products.partials.table', compact('products', 'search'));
        }
        
        return view('products.index', compact('products', 'search'));
    }

    public function create()
    {
        $categories = Product::categories();
        $availableSalesBalance = ProductPurchase::availableSalesBalance();
        $totalNewInvestment = ProductPurchase::totalNewInvestment();
        $totalSalesRevenue = ProductPurchase::totalSalesRevenue();
        return view('products.partials.create', compact('categories', 'availableSalesBalance', 'totalNewInvestment', 'totalSalesRevenue'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'funding_source' => 'required|in:new_investment,sales_revenue',
            'notes' => 'nullable|string|max:500',
            'products' => 'required|array|min:1',
            'products.*.code' => 'required|string|max:100',
            'products.*.name' => 'required|string|max:255',
            'products.*.description' => 'nullable|string',
            'products.*.purchase_price' => 'required|numeric|min:0',
            'products.*.sale_price' => 'nullable|numeric|min:0',
            'products.*.stock' => 'required|integer|min:1',
        ]);

        $productsData = $request->input('products');
        $fundingSource = $request->input('funding_source');
        $minStockDefault = 5;

        // Calcular costo total de la compra y cantidad total de unidades
        $totalCost = 0;
        $totalQuantity = 0;
        foreach ($productsData as $item) {
            $totalCost += ((float) $item['purchase_price']) * ((int) $item['stock']);
            $totalQuantity += (int) $item['stock'];
        }

        // Si se financia alzando de ventas acumuladas, validar saldo disponible
        if ($fundingSource === 'sales_revenue') {
            $availableBalance = ProductPurchase::availableSalesBalance();
            if ($totalCost > $availableBalance) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Saldo insuficiente en ingresos de ventas. Disponible: S/. " . number_format($availableBalance, 2) . ", pero la compra requiere S/. " . number_format($totalCost, 2) . ". Selecciona 'Nueva Inversión' o ajusta las cantidades.");
            }
        }

        DB::transaction(function () use ($productsData, $fundingSource, $totalCost, $totalQuantity, $minStockDefault, $request) {
            // 1. Crear o actualizar stock de los productos
            foreach ($productsData as $item) {
                $salePrice = !empty($item['sale_price']) && $item['sale_price'] > 0
                    ? (float) $item['sale_price']
                    : ((float) $item['purchase_price'] * 1.20);

                $existingProduct = Product::where('code', $item['code'])->first();

                if ($existingProduct) {
                    $existingProduct->increment('stock', (int) $item['stock'], [
                        'name' => $item['name'],
                        'description' => $item['description'] ?? $existingProduct->description,
                        'purchase_price' => $item['purchase_price'],
                        'sale_price' => $salePrice,
                    ]);
                } else {
                    Product::create([
                        'code'           => $item['code'],
                        'name'           => $item['name'],
                        'description'    => $item['description'] ?? '',
                        'purchase_price' => $item['purchase_price'],
                        'sale_price'     => $salePrice,
                        'stock'          => $item['stock'],
                        'min_stock'      => $minStockDefault,
                    ]);
                }
            }

            // 2. Registrar la compra y su origen de inversión
            ProductPurchase::create([
                'user_id' => auth()->id(),
                'total_cost' => $totalCost,
                'funding_source' => $fundingSource,
                'items_count' => count($productsData),
                'total_items_quantity' => $totalQuantity,
                'notes' => $request->input('notes'),
            ]);
        });

        $sourceLabel = $fundingSource === 'new_investment' 
            ? 'Nueva Inversión (Capital Externo)' 
            : 'Alzado del Ingreso Acumulado de Ventas';

        return redirect()->route('products.index')
            ->with('success', count($productsData) . " productos registrados (Total: S/. " . number_format($totalCost, 2) . ") financiados con {$sourceLabel}.");
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.partials.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:100|unique:products,code,' . $id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $salePrice = $request->filled('sale_price') 
            ? (float) $request->input('sale_price') 
            : ((float) $request->input('purchase_price') * 1.20);

        $product->update([
            'code' => $request->input('code'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'purchase_price' => $request->input('purchase_price'),
            'sale_price' => $salePrice,
            'stock' => $request->input('stock'),
        ]);

        return redirect()->route('products.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
