<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all()->keyBy('name');

        $products = [
            ['name' => 'Espresso',          'price' => 25000,  'category' => 'Kopi',       'available' => true],
            ['name' => 'Americano',         'price' => 28000,  'category' => 'Kopi',       'available' => true],
            ['name' => 'Cappuccino',        'price' => 35000,  'category' => 'Kopi',       'available' => true],
            ['name' => 'Caramel Macchiato', 'price' => 45000,  'category' => 'Kopi',       'available' => true],
            ['name' => 'Matcha Latte',      'price' => 40000,  'category' => 'Non-Kopi',   'available' => true],
            ['name' => 'Teh Tarik',         'price' => 22000,  'category' => 'Non-Kopi',   'available' => true],
            ['name' => 'Strawberry Smoothie','price' => 38000,  'category' => 'Non-Kopi',   'available' => false],
            ['name' => 'Croissant',         'price' => 32000,  'category' => 'Makanan',    'available' => true],
            ['name' => 'Club Sandwich',     'price' => 55000,  'category' => 'Makanan',    'available' => true],
            ['name' => 'Cheesecake',        'price' => 48000,  'category' => 'Dessert',    'available' => true],
            ['name' => 'Tiramisu',          'price' => 52000,  'category' => 'Dessert',    'available' => true],
            ['name' => 'Cold Brew',         'price' => 42000,  'category' => 'Kopi',       'available' => true],
        ];

        foreach ($products as $p) {
            Product::create([
                'category_id' => $categories[$p['category']]->id,
                'name' => $p['name'],
                'price' => $p['price'],
                'is_available' => $p['available'],
            ]);
        }
    }
}
