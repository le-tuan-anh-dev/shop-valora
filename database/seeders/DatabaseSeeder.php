<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 🔧 Tạm tắt kiểm tra khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 🔥 Xóa dữ liệu cũ
        DB::table('product_variants')->truncate();
        DB::table('products')->truncate();
        DB::table('categories')->truncate();

        // 🔁 Bật lại kiểm tra khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 🧱 --- CATEGORIES ---
        $categories = [
            ['name' => 'Áo', 'slug' => 'ao', 'parent_id' => null, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Quần', 'slug' => 'quan', 'parent_id' => null, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Phụ kiện', 'slug' => 'phu-kien', 'parent_id' => null, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Áo thun', 'slug' => 'ao-thun', 'parent_id' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Quần jeans', 'slug' => 'quan-jeans', 'parent_id' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('categories')->insert($categories);

        // 🛒 --- PRODUCTS ---
        $products = [
            [
                'category_id' => 4,
                'name' => 'Áo thun nam basic',
                'description' => 'Áo thun nam cổ tròn, chất cotton mềm mại',
                'cost_price' => 120000,
                'base_price' => 200000,
                'discount_price' => 180000,
                'stock' => 50,
                'sold_count' => 10,
                'image_main' => 'ao-thun-nam.jpg',
                'is_active' => 1,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 5,
                'name' => 'Quần jeans nữ slimfit',
                'description' => 'Quần jeans nữ ôm vừa, co giãn thoải mái',
                'cost_price' => 200000,
                'base_price' => 350000,
                'discount_price' => null,
                'stock' => 30,
                'sold_count' => 5,
                'image_main' => 'quan-jeans-nu.jpg',
                'is_active' => 1,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 3,
                'name' => 'Mũ lưỡi trai unisex',
                'description' => 'Mũ thời trang phù hợp mọi giới tính',
                'cost_price' => 60000,
                'base_price' => 120000,
                'discount_price' => 100000,
                'stock' => 40,
                'sold_count' => 8,
                'image_main' => 'mu-luoi-trai.jpg',
                'is_active' => 1,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('products')->insert($products);

        // 🎨 --- PRODUCT VARIANTS ---
        $productVariants = [
            // Áo thun nam
            [
                'product_id' => 1,
                'sku' => 'ATN-BLACK-M',
                'title' => 'Áo thun nam đen size M',
                'price' => 180000,
                'stock' => 20,
                'image_url' => 'ao-thun-nam-black-m.jpg',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 1,
                'sku' => 'ATN-WHITE-L',
                'title' => 'Áo thun nam trắng size L',
                'price' => 180000,
                'stock' => 15,
                'image_url' => 'ao-thun-nam-white-l.jpg',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Quần jeans nữ
            [
                'product_id' => 2,
                'sku' => 'QJN-BLUE-28',
                'title' => 'Quần jeans nữ xanh size 28',
                'price' => 350000,
                'stock' => 15,
                'image_url' => 'quan-jeans-nu-blue-28.jpg',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 2,
                'sku' => 'QJN-BLUE-30',
                'title' => 'Quần jeans nữ xanh size 30',
                'price' => 350000,
                'stock' => 15,
                'image_url' => 'quan-jeans-nu-blue-30.jpg',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Mũ lưỡi trai
            [
                'product_id' => 3,
                'sku' => 'MULUOI-BLACK',
                'title' => 'Mũ lưỡi trai đen',
                'price' => 100000,
                'stock' => 20,
                'image_url' => 'mu-luoi-trai-black.jpg',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 3,
                'sku' => 'MULUOI-WHITE',
                'title' => 'Mũ lưỡi trai trắng',
                'price' => 100000,
                'stock' => 20,
                'image_url' => 'mu-luoi-trai-white.jpg',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('product_variants')->insert($productVariants);

        $this->call([
            UsersTableSeeder::class,
            OrdersTableSeeder::class,
        ]);
    }
}
