<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPurchaseItem extends Model
{
    protected $fillable = [
        'product_purchase_id',
        'product_id',
        'product_code',
        'product_name',
        'lot_number',
        'quantity',
        'unit_cost',
        'sale_price',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_cost' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function purchase()
    {
        return $this->belongsTo(ProductPurchase::class, 'product_purchase_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
