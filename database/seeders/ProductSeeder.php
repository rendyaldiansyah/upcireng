<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Cireng Ayam Krispy',
                'price' => 2000,
                'description' => 'Cireng isi daging ayam suwir, tekstur renyah dengan isian lembut dan gurih. Sempurna untuk snack atau lauk nasi.',
                'image' => 'assets/assets/cireng ayam.png',
                'status' => 'active',
                'stock_status' => 'available',
                'is_open' => true,
                'variants' => ['Normal', 'Pedas Sedang', 'Pedas Ekstra'],
                'sort_order' => 1,
            ],
            [
                'name' => 'Cireng Ati Empuk',
                'price' => 2500,
                'description' => 'Cireng premium dengan isi hati ayam yang sudah ditumis sempurna. Cita rasa kaya dan tekstur super empuk di dalam.',
                'image' => 'assets/assets/ati.jpg',
                'status' => 'active',
                'stock_status' => 'available',
                'is_open' => true,
                'variants' => ['Normal', 'Pedas'],
                'sort_order' => 2,
            ],
            [
                'name' => 'Cireng Jamur Mozzarella',
                'price' => 3000,
                'description' => 'Cireng vegetarian dengan jamur tiram segar dan mozarella leleh. Kombinasi sempurna jamur dan keju.',
                'image' => 'assets/assets/jamut.jpg',
                'status' => 'active',
                'stock_status' => 'available',
                'is_open' => true,
                'variants' => ['Standard', 'Extra Keju'],
                'sort_order' => 3,
            ],
            [
                'name' => 'Cireng Bakso Spesial',
                'price' => 3000,
                'description' => 'Cireng dengan isi bakso premium buatan sendiri yang gurih dan empuk.',
                'image' => 'assets/assets/bakso.jpg',
                'status' => 'active',
                'stock_status' => 'available',
                'is_open' => true,
                'variants' => ['Bakso Utuh', 'Bakso Hancur'],
                'sort_order' => 4,
            ],
            [
                'name' => 'Cireng Original Klasik',
                'price' => 1000,
                'description' => 'Cireng original dengan resep tradisional, isi oncom dan sayuran pilihan.',
                'image' => 'assets/assets/oriiii.jpg',
                'status' => 'active',
                'stock_status' => 'available',
                'is_open' => true,
                'variants' => ['Tanpa Pedas', 'Pedas Rendah', 'Pedas Medium'],
                'sort_order' => 5,
            ],
            [
                'name' => 'Paket Hemat Mix Spesial',
                'price' => 5000,
                'description' => 'Paket hemat berisi beberapa varian cireng pilihan.',
                'image' => 'assets/assets/cireng ayam.png',
                'status' => 'active',
                'stock_status' => 'available',
                'is_open' => true,
                'variants' => ['Mix Otomatis', 'Pilih Sendiri'],
                'sort_order' => 6,
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['name' => $product['name']],
                $product
            );
        }
    }
}