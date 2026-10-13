<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ProductPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->string('category')->toString();
        $expenses = Expense::with(['user', 'productPurchase'])
            ->when($category, fn ($query) => $query->where('category', $category))
            ->latest('expense_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $categories = ['inventory_purchase' => 'Compra de mercadería'] + Expense::categories();
        $availableSalesBalance = ProductPurchase::availableSalesBalance();
        $totalExpenses = Expense::sum('amount');

        return view('expenses.index', compact('expenses', 'categories', 'category', 'availableSalesBalance', 'totalExpenses'));
    }

    public function create()
    {
        $expense = new Expense(['expense_date' => now()->toDateString(), 'payment_source' => 'sales_revenue']);
        $categories = Expense::categories();
        $availableSalesBalance = ProductPurchase::availableSalesBalance();

        return view('expenses.form', compact('expense', 'categories', 'availableSalesBalance'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateExpense($request);

        if ($validated['payment_source'] === 'sales_revenue' && (float) $validated['amount'] > ProductPurchase::availableSalesBalance()) {
            return back()->withInput()->with('error', 'El monto supera el fondo de ventas disponible.');
        }

        $validated['user_id'] = auth()->id();
        $validated['photo_path'] = $request->file('photo')?->store('expense-proofs', 'public');
        unset($validated['photo']);
        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Gasto registrado y saldo actualizado.');
    }

    public function edit(Expense $expense)
    {
        if ($expense->product_purchase_id) {
            return redirect()->route('inventory-entries.show', $expense->product_purchase_id)
                ->with('error', 'Este gasto pertenece a un ingreso de mercadería y se administra desde ese movimiento.');
        }

        $categories = Expense::categories();
        $availableSalesBalance = ProductPurchase::availableSalesBalance()
            + ($expense->payment_source === 'sales_revenue' ? (float) $expense->amount : 0);

        return view('expenses.form', compact('expense', 'categories', 'availableSalesBalance'));
    }

    public function update(Request $request, Expense $expense)
    {
        abort_if($expense->product_purchase_id, 422, 'Los gastos de mercadería se actualizan desde el ingreso asociado.');

        $validated = $this->validateExpense($request);
        $available = ProductPurchase::availableSalesBalance()
            + ($expense->payment_source === 'sales_revenue' ? (float) $expense->amount : 0);

        if ($validated['payment_source'] === 'sales_revenue' && (float) $validated['amount'] > $available) {
            return back()->withInput()->with('error', 'El monto supera el fondo de ventas disponible.');
        }

        if ($request->hasFile('photo')) {
            if ($expense->photo_path) {
                Storage::disk('public')->delete($expense->photo_path);
            }
            $validated['photo_path'] = $request->file('photo')->store('expense-proofs', 'public');
        }

        unset($validated['photo']);
        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Gasto actualizado.');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->product_purchase_id) {
            return back()->with('error', 'No se puede borrar por separado un gasto enlazado a mercadería.');
        }

        if ($expense->photo_path) {
            Storage::disk('public')->delete($expense->photo_path);
        }
        $expense->delete();

        return back()->with('success', 'Gasto eliminado y saldo recalculado.');
    }

    private function validateExpense(Request $request): array
    {
        return $request->validate([
            'expense_date' => ['required', 'date'],
            'category' => ['required', Rule::in(array_keys(Expense::categories()))],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_source' => ['required', Rule::in(['sales_revenue', 'external'])],
            'notes' => ['nullable', 'string', 'max:1000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);
    }
}
