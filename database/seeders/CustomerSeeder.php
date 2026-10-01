<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            ['name' => 'Juan Pérez García', 'document_id' => '12345678'],
            ['name' => 'María López Rodríguez', 'document_id' => '87654321'],
            ['name' => 'Carlos Martínez Silva', 'document_id' => '23456789'],
            ['name' => 'Ana González Flores', 'document_id' => '34567890'],
            ['name' => 'Roberto Sánchez Moreno', 'document_id' => '45678901'],
            ['name' => 'Laura Fernández Díaz', 'document_id' => '56789012'],
            ['name' => 'Pedro Ramírez Gómez', 'document_id' => '67890123'],
            ['name' => 'Sofía Castillo López', 'document_id' => '78901234'],
            ['name' => 'Felipe Vargas Ríos', 'document_id' => '89012345'],
            ['name' => 'Elena Ruiz Cabrera', 'document_id' => '90123456'],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
