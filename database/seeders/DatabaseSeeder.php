<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // Create sample customers
        $customers = [
            ['name' => 'Budi Santoso', 'email' => 'budi@email.com', 'phone' => '081234567890', 'address' => 'Jl. Sudirman No. 123, Jakarta'],
            ['name' => 'Siti Aminah', 'email' => 'siti@email.com', 'phone' => '081234567891', 'address' => 'Jl. Thamrin No. 456, Jakarta'],
            ['name' => 'Ahmad Wijaya', 'email' => 'ahmad@email.com', 'phone' => '081234567892', 'address' => 'Jl. Gatot Subroto No. 789, Jakarta'],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        // Create sample products with stock
        $products = [
            ['name' => 'Laptop ASUS VivoBook', 'sku' => 'LAP-001', 'price' => 8500000, 'stock' => 10, 'description' => 'Laptop ASUS VivoBook 14 inch'],
            ['name' => 'Mouse Logitech M185', 'sku' => 'MOU-001', 'price' => 150000, 'stock' => 50, 'description' => 'Mouse wireless Logitech M185'],
            ['name' => 'Keyboard Mechanical RGB', 'sku' => 'KEY-001', 'price' => 450000, 'stock' => 25, 'description' => 'Keyboard mechanical dengan lampu RGB'],
            ['name' => 'Monitor LG 24 inch', 'sku' => 'MON-001', 'price' => 2500000, 'stock' => 15, 'description' => 'Monitor LG 24 inch Full HD'],
            ['name' => 'Headset Gaming', 'sku' => 'HEA-001', 'price' => 350000, 'stock' => 30, 'description' => 'Headset gaming dengan mic'],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
