<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Bebidas (10A)
            ['code' => '10A001', 'name' => 'Coca Cola 1.5L', 'purchase_price' => 1.50],
            ['code' => '10A002', 'name' => 'Agua Mineral 500ml', 'purchase_price' => 0.80],
            
            // Galletas (20B)
            ['code' => '20B001', 'name' => 'Galletas Oreo', 'purchase_price' => 0.50],
            ['code' => '20B002', 'name' => 'Galletas de Avena', 'purchase_price' => 0.70],
            
            // Lacteos (30C)
            ['code' => '30C001', 'name' => 'Leche Entera 1L', 'purchase_price' => 1.10],
            ['code' => '30C002', 'name' => 'Yogurt Griego', 'purchase_price' => 1.40],
            
            // Limpieza (40D)
            ['code' => '40D001', 'name' => 'Detergente Multiuso', 'purchase_price' => 3.50],
            ['code' => '40D002', 'name' => 'Limpiavidrios Spray', 'purchase_price' => 2.20],
            
            // Medicamentos (50E)
            ['code' => '50E001', 'name' => 'Paracetamol 500mg', 'purchase_price' => 0.20],
            ['code' => '50E002', 'name' => 'Alcohol Etílico 70%', 'purchase_price' => 1.80],
        ];

        foreach ($products as $item) {
            Product::create([
                'code'           => $item['code'],
                'name'           => $item['name'],
                'description'    => 'Descripción de ejemplo para ' . $item['name'],
                'purchase_price' => $item['purchase_price'],
                'sale_price'     => $item['purchase_price'] * 1.20, // Aplicando el 20% de margen
                'stock'          => rand(10, 50), // Stock aleatorio inicial
                'min_stock'      => 5,
            ]);
        }
    }
}