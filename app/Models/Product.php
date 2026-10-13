<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'code',
        'name',
        'category',
        'description',
        'purchase_price',
        'sale_price',
        'stock',
        'min_stock',
    ];

    public static function categories()
    {
        return [
            'NET' => 'Redes y conectividad',
            'COM' => 'Computación',
            'ACC' => 'Accesorios',
            'ELE' => 'Electrónica',
            'SEG' => 'Seguridad',
            'TEL' => 'Telefonía',
            'HOG' => 'Hogar y oficina',
            'OTR' => 'Otros',
        ];
    }

    public function getCategoryNameAttribute()
    {
        $categories = self::categories();
        $category = $this->category ?: substr($this->code, 0, 3);

        return $categories[$category] ?? 'Sin categoría';
    }

    public function purchaseItems()
    {
        return $this->hasMany(ProductPurchaseItem::class);
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }
}
