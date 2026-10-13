<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('category', 50)->nullable()->after('name');
        });

        Schema::table('product_purchases', function (Blueprint $table) {
            $table->date('purchase_date')->nullable()->after('user_id');
            $table->string('supplier')->nullable()->after('purchase_date');
            $table->string('reference')->nullable()->after('supplier');
            $table->string('photo_path')->nullable()->after('notes');
        });

        Schema::create('product_purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_purchase_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_code', 100);
            $table->string('product_name');
            $table->string('lot_number', 100)->nullable();
            $table->unsignedInteger('quantity');
            $table->decimal('unit_cost', 12, 2);
            $table->decimal('sale_price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            $table->index(['product_id', 'lot_number']);
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_purchase_id')->nullable()->unique()->constrained()->nullOnDelete();
            $table->date('expense_date');
            $table->string('category', 50);
            $table->string('description');
            $table->decimal('amount', 12, 2);
            $table->string('payment_source', 30)->default('sales_revenue');
            $table->string('photo_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['expense_date', 'category']);
            $table->index('payment_source');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('product_purchase_items');

        Schema::table('product_purchases', function (Blueprint $table) {
            $table->dropColumn(['purchase_date', 'supplier', 'reference', 'photo_path']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
