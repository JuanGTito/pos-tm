<?php

namespace App\Http\Controllers;

use App\Models\ProductPurchase;
use Illuminate\Http\Request;

class ProductPurchaseController extends Controller
{
    public function index(Request $request)
    {
        $source = $request->string('source')->toString();
        $entries = ProductPurchase::with('user')
            ->withCount('items')
            ->when(in_array($source, ['new_investment', 'sales_revenue'], true), fn ($query) => $query->where('funding_source', $source))
            ->latest('purchase_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('inventory-entries.index', compact('entries', 'source'));
    }

    public function show(ProductPurchase $inventoryEntry)
    {
        $inventoryEntry->load(['items.product', 'user', 'expense']);

        return view('inventory-entries.show', compact('inventoryEntry'));
    }
}
