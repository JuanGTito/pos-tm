<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['code' => 'NET-001', 'name' => 'Router Wi-Fi 6 AX1800', 'category' => 'NET', 'purchase_price' => 145.00],
            ['code' => 'NET-002', 'name' => 'Switch Gigabit 8 puertos', 'category' => 'NET', 'purchase_price' => 82.00],
            ['code' => 'COM-001', 'name' => 'Teclado mecánico USB', 'category' => 'COM', 'purchase_price' => 95.00],
            ['code' => 'COM-002', 'name' => 'Mouse inalámbrico', 'category' => 'COM', 'purchase_price' => 28.00],
            ['code' => 'ACC-001', 'name' => 'Cable HDMI 2 metros', 'category' => 'ACC', 'purchase_price' => 15.00],
            ['code' => 'ACC-002', 'name' => 'Cargador USB-C 30W', 'category' => 'ACC', 'purchase_price' => 38.00],
            ['code' => 'SEG-001', 'name' => 'Cámara IP interior', 'category' => 'SEG', 'purchase_price' => 110.00],
            ['code' => 'ELE-001', 'name' => 'Regleta de 6 tomas', 'category' => 'ELE', 'purchase_price' => 32.00],
        ];

        foreach ($products as $item) {
            Product::create([
                'code' => $item['code'],
                'name' => $item['name'],
                'category' => $item['category'],
                'description' => 'Descripción de ejemplo para '.$item['name'],
                'purchase_price' => $item['purchase_price'],
                'sale_price' => $item['purchase_price'] * 1.20, // Aplicando el 20% de margen
                'stock' => rand(10, 50), // Stock aleatorio inicial
                'min_stock' => 5,
            ]);
        }
    }
}
