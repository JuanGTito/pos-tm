<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Product;
use App\Models\ProductPurchase;
use App\Models\ProductPurchaseItem;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_external_investment_creates_a_lot_and_updates_existing_stock_and_prices(): void
    {
        $user = User::factory()->create();
        $product = Product::create([
            'code' => 'NET-001',
            'name' => 'Router anterior',
            'category' => 'NET',
            'purchase_price' => 100,
            'sale_price' => 140,
            'stock' => 3,
            'min_stock' => 1,
        ]);

        $response = $this->actingAs($user)->post(route('products.store'), [
            'purchase_date' => '2026-10-13',
            'supplier' => 'Proveedor Uno',
            'funding_source' => 'new_investment',
            'products' => [[
                'product_id' => $product->id,
                'code' => 'NET-001',
                'name' => 'Router Wi-Fi 6',
                'category' => 'NET',
                'description' => 'Doble banda',
                'lot_number' => 'L-ROUTER-01',
                'purchase_price' => 120,
                'sale_price' => 175,
                'stock' => 5,
            ]],
        ]);

        $purchase = ProductPurchase::firstOrFail();
        $response->assertRedirect(route('inventory-entries.show', $purchase));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Router Wi-Fi 6',
            'purchase_price' => 120,
            'sale_price' => 175,
            'stock' => 8,
        ]);
        $this->assertDatabaseHas('product_purchase_items', [
            'product_id' => $product->id,
            'lot_number' => 'L-ROUTER-01',
            'quantity' => 5,
        ]);
        $this->assertSame(0, Expense::count());
        $this->assertSame(1, ProductPurchaseItem::count());
    }

    public function test_reinvestment_and_operating_expenses_reduce_the_sales_fund_once(): void
    {
        $user = User::factory()->create();
        $customer = Customer::create(['name' => 'Cliente', 'document_id' => 'DOC-1']);
        Sale::create([
            'user_id' => $user->id,
            'customer_id' => $customer->id,
            'sale_date' => '2026-10-13',
            'total' => 500,
            'tax' => 79.83,
            'status' => 'completed',
        ]);

        $this->actingAs($user)->post(route('products.store'), [
            'purchase_date' => '2026-10-13',
            'funding_source' => 'sales_revenue',
            'products' => [[
                'code' => 'ACC-001',
                'name' => 'Cable HDMI',
                'category' => 'ACC',
                'lot_number' => 'LOTE-A',
                'purchase_price' => 20,
                'sale_price' => 30,
                'stock' => 10,
            ]],
        ])->assertRedirect();

        $this->assertDatabaseHas('expenses', [
            'category' => 'inventory_purchase',
            'amount' => 200,
            'payment_source' => 'sales_revenue',
        ]);
        $this->assertSame(300.0, ProductPurchase::availableSalesBalance());

        $this->actingAs($user)->post(route('expenses.store'), [
            'expense_date' => '2026-10-13',
            'category' => 'transport',
            'description' => 'Flete',
            'amount' => 50,
            'payment_source' => 'sales_revenue',
        ])->assertRedirect(route('expenses.index'));

        $this->assertSame(250.0, ProductPurchase::availableSalesBalance());
        $this->assertSame(2, Expense::count());
    }
}
