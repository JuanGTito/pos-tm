<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

class SalesController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $user = Auth::user();

    $query = Sale::query();

    if (!$user->hasRole('admin')) {
        $query->where('user_id', $user->id);
    }

    $sales = $query->with(['customer', 'user'])
                   ->orderBy('sale_date', 'desc')
                   ->paginate(15);

    return view('sales.index', compact('sales'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('sales.partials.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_dni' => 'required|string',
            'customer_name' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0.01',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user(); // Obtenemos el usuario vendedor actual

            // 1. Cliente: Buscar o crear
            $customer = Customer::where('document_id', $request->input('customer_dni'))->first();
            if (!$customer) {
                $customer = Customer::create([
                    'document_id' => $request->input('customer_dni'),
                    'name' => $request->input('customer_name'),
                ]);
            }

            // 2. Crear Venta inicial vinculada al vendedor actual
            $sale = Sale::create([
                'user_id' => $user->id, // Esto asegura que la SalesPolicy reconozca al dueño
                'customer_id' => $customer->id,
                'sale_date' => Carbon::now()->toDateString(),
                'total' => 0, 
                'tax' => 0,
                'status' => 'completed',
            ]);

            $total = 0;

            // 3. Procesar ítems
            foreach ($request->input('items') as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stock insuficiente para {$product->name}");
                }

                $subtotalItem = $item['quantity'] * $item['price'];
                $total += $subtotalItem;

                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $subtotalItem,
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            // 4. LÓGICA DE IMPUESTO INVERSO (Finta)
            $baseImponible = $total / 1.19;
            $taxDesglosado = $total - $baseImponible;

            // 5. Actualizar venta con los totales finales
            $sale->update([
                'total' => $total,
                'tax' => round($taxDesglosado, 2), // Guardamos el IGV calculado hacia atrás
            ]);

            DB::commit();

            return redirect()->route('sales.show', $sale->id)
                ->with('success', "Venta #{$sale->id} registrada exitosamente por S/. {$total}");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('sales.create')
                ->with('error', 'Error al registrar venta: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        $this->authorize('view', $sale);

        $details = $sale->details()->with('product')->get();

        return view('sales.partials.show', compact('sale', 'details'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        $this->authorize('update', $sale);

        $products = Product::all();
        $customers = Customer::all();
        $details = $sale->details()->with('product')->get();

        return view('sales.partials.edit', compact('sale', 'products', 'customers', 'details'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        $this->authorize('update', $sale);

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
        ]);

        try {
            DB::beginTransaction();

            // Restore stock from deleted items - Direct DB query
            foreach ($sale->details as $detail) {
                Product::where('id', $detail->product_id)
                    ->increment('stock', $detail->quantity);
            }

            // Delete old details
            $sale->details()->delete();

            // Process new items
            $total = 0;
            foreach ($request->input('items', []) as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }

                $subtotal = $item['quantity'] * $item['price'];
                $total += $subtotal;

                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $subtotal,
                ]);

                // Decrease stock - Direct DB query
                Product::where('id', $product->id)
                    ->decrement('stock', $item['quantity']);
            }

            $tax = $total * 0.19;

            $sale->update([
                'customer_id' => $request->input('customer_id'),
                'total' => $total,
                'tax' => $tax,
            ]);

            DB::commit();

            return redirect()->route('sales.partials.show', $sale->id)
                ->with('success', 'Sale updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('sales.partials.edit', $sale->id)
                ->with('error', 'Error updating sale: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        $this->authorize('delete', $sale);

        try {
            DB::beginTransaction();

            // Restore stock
            foreach ($sale->details as $detail) {
                Product::where('id', $detail->product_id)
                    ->increment('stock', $detail->quantity);
            }

            // Delete details
            $sale->details()->delete();

            // Delete sale
            $sale->delete();

            DB::commit();

            return redirect()->route('sales.index')
                ->with('success', 'Sale deleted successfully and stock restored.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('sales.index')
                ->with('error', 'Error deleting sale: ' . $e->getMessage());
        }
    }

    /**
     * Search customers by document_id
     */
    public function searchCustomers(Request $request)
    {
        $query = $request->query('q', '');

        if (strlen($query) < 1) {
            return response()->json([]);
        }

        $customers = Customer::where('document_id', 'LIKE', '%' . $query . '%')
            ->orWhere('name', 'LIKE', '%' . $query . '%')
            ->select('id', 'document_id', 'name', 'email', 'phone', 'address')
            ->limit(10)
            ->get();

        return response()->json($customers);
    }

    /**
     * Search products by code or name
     */
    public function searchProducts(Request $request)
    {
        $query = $request->query('q', '');

        if (strlen($query) < 1) {
            return response()->json([]);
        }

        $products = Product::where('stock', '>', 0)
            ->where(function ($q) use ($query) {
                $q->where('code', 'LIKE', '%' . $query . '%')
                  ->orWhere('name', 'LIKE', '%' . $query . '%')
                  ->orWhere('description', 'LIKE', '%' . $query . '%');
            })
            ->select('id', 'code', 'name', 'sale_price', 'stock')
            ->limit(15)
            ->get();

        return response()->json($products);
    }
}

