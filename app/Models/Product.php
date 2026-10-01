<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'purchase_price',
        'sale_price',
        'stock',
        'min_stock',
    ];

    public static function categories()
    {
        return [
            '10A' => 'Bebidas',
            '20B' => 'Galletas',
            '30C' => 'Lacteos',
            '40D' => 'Limpieza',
            '50E' => 'Medicamentos',
        ];
    }

    public function getCategoryNameAttribute()
    {
        $prefix = substr($this->code, 0, 3);
        $categories = self::categories();
        return $categories[$prefix] ?? 'Sin Categoria';
    }
}