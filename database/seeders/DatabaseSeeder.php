<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            ['name' => 'Strawberry Bliss', 'description' => 'Kue lembut dengan topping stroberi segar pilihan', 'price' => 120000, 'emoji' => '🍓', 'category' => 'cake', 'badge' => 'BEST', 'rating' => 4.9, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Choco Heaven', 'description' => 'Lelehan coklat belgia di setiap lapisan', 'price' => 95000, 'emoji' => '🍫', 'category' => 'cake', 'badge' => null, 'rating' => 4.8, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Birthday Special', 'description' => 'Custom kue ulang tahun sesuai permintaan', 'price' => 200000, 'emoji' => '🎂', 'category' => 'cake', 'badge' => 'NEW', 'rating' => 5.0, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Vanilla Dream', 'description' => 'Cupcake vanilla klasik dengan buttercream premium', 'price' => 45000, 'emoji' => '🧁', 'category' => 'cupcake', 'badge' => null, 'rating' => 4.7, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Matcha Latte Cake', 'description' => 'Perpaduan matcha premium dengan krim lembut', 'price' => 135000, 'emoji' => '🍵', 'category' => 'cake', 'badge' => 'NEW', 'rating' => 4.8, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Red Velvet', 'description' => 'Klasik red velvet dengan cream cheese frosting', 'price' => 110000, 'emoji' => '❤️', 'category' => 'cake', 'badge' => 'BEST', 'rating' => 4.9, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Taro Purple', 'description' => 'Kue talas ungu dengan tekstur lembut dan wangi', 'price' => 125000, 'emoji' => '💜', 'category' => 'cake', 'badge' => null, 'rating' => 4.6, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Caramel Crunch', 'description' => 'Lapisan karamel renyah di atas kue butter lembut', 'price' => 105000, 'emoji' => '🍮', 'category' => 'cake', 'badge' => null, 'rating' => 4.7, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mango Mousse', 'description' => 'Mousse mangga segar dengan lapisan jelly buah', 'price' => 90000, 'emoji' => '🥭', 'category' => 'mousse', 'badge' => 'NEW', 'rating' => 4.8, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Choco Cupcake', 'description' => 'Cupcake coklat mini dengan topping sprinkles warna-warni', 'price' => 35000, 'emoji' => '🧁', 'category' => 'cupcake', 'badge' => null, 'rating' => 4.6, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lemon Zest', 'description' => 'Kesegaran lemon dalam setiap gigitan, cocok untuk summer', 'price' => 98000, 'emoji' => '🍋', 'category' => 'cake', 'badge' => null, 'rating' => 4.5, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rose Milk Cake', 'description' => 'Kue susu dengan aroma bunga mawar yang harum', 'price' => 145000, 'emoji' => '🌹', 'category' => 'cake', 'badge' => 'BEST', 'rating' => 4.9, 'is_available' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}