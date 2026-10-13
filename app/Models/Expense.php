<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'user_id',
        'product_purchase_id',
        'expense_date',
        'category',
        'description',
        'amount',
        'payment_source',
        'photo_path',
        'notes',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public static function categories(): array
    {
        return [
            'transport' => 'Transporte y flete',
            'services' => 'Servicios',
            'rent' => 'Alquiler',
            'maintenance' => 'Mantenimiento',
            'supplies' => 'Insumos y embalaje',
            'marketing' => 'Publicidad',
            'payroll' => 'Personal',
            'taxes' => 'Impuestos y trámites',
            'other' => 'Otros',
        ];
    }

    public function scopeFromSales(Builder $query): Builder
    {
        return $query->where('payment_source', 'sales_revenue');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productPurchase()
    {
        return $this->belongsTo(ProductPurchase::class);
    }

    public function getCategoryLabelAttribute(): string
    {
        if ($this->category === 'inventory_purchase') {
            return 'Compra de mercadería';
        }

        return self::categories()[$this->category] ?? ucfirst(str_replace('_', ' ', $this->category));
    }

    public function getPaymentSourceLabelAttribute(): string
    {
        return $this->payment_source === 'sales_revenue'
            ? 'Fondo de ventas'
            : 'Aporte externo';
    }
}
