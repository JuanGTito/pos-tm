<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Product;
use App\Models\ProductPurchase;
use App\Models\Sale;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $metrics = [
            'sales' => ProductPurchase::totalSalesRevenue(),
            'available' => ProductPurchase::availableSalesBalance(),
            'expenses' => (float) Expense::fromSales()->sum('amount'),
            'reinvested' => ProductPurchase::totalReinvestedFromSales(),
            'external_investment' => ProductPurchase::totalNewInvestment(),
            'products' => Product::count(),
            'units' => Product::sum('stock'),
            'low_stock' => Product::whereColumn('stock', '<=', 'min_stock')->count(),
            'inventory_cost' => (float) Product::selectRaw('COALESCE(SUM(stock * purchase_price), 0) AS total')->value('total'),
            'sales_today' => (float) Sale::where('status', 'completed')->whereDate('sale_date', today())->sum('total'),
        ];
        $recentProducts = Product::latest()->limit(8)->get();
        $recentExpenses = Expense::latest('expense_date')->latest('id')->limit(5)->get();

        return view('dashboard', compact('metrics', 'recentProducts', 'recentExpenses'));
    }
}
