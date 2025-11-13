<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Admin\Product;
use App\Models\Admin\ProductVariant;
use Carbon\Carbon;
use Faker\Factory;

class OrdersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('order_items')->truncate();
        DB::table('orders')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Lấy dữ liệu cần thiết
        $users = User::where('role', 'customer')->get();
        $products = Product::with('variants')->get();

        if ($users->isEmpty()) {
            $this->command->warn('Không có customer nào. Vui lòng chạy UsersTableSeeder trước.');
            return;
        }

        $faker = Factory::create('vi_VN');

        if ($products->isEmpty()) {
            $this->command->warn('Không có sản phẩm nào. Vui lòng chạy DatabaseSeeder trước.');
            return;
        }

        $paymentMethods = ['cod', 'bank_transfer', 'credit_card', 'momo', 'paypal'];
        $paymentStatuses = ['unpaid', 'paid', 'refunded'];
        $orderStatuses = ['pending', 'processing', 'shipped', 'completed', 'cancelled'];

        $orderItems = [];
        $orderNumber = 1000;

        // Tạo 50 đơn hàng
        for ($i = 0; $i < 50; $i++) {
            $user = $users->random();
            $createdAt = Carbon::now()->subDays(rand(0, 90))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            
            $status = $orderStatuses[array_rand($orderStatuses)];
            $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];
            $paymentMethod = $paymentMethods[array_rand($paymentMethods)];

            // Tính toán giá trị
            $subtotal = 0;
            $shippingFee = rand(20000, 50000);
            $promotionAmount = rand(0, 100000);
            $totalAmount = 0;

            // Tạo order items cho đơn hàng này
            $itemCount = rand(1, 4);
            $items = [];
            
            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products->random();
                $variant = $product->variants->isNotEmpty() ? $product->variants->random() : null;
                
                $quantity = rand(1, 3);
                $unitPrice = $variant ? $variant->price : ($product->discount_price ?? $product->base_price);
                $itemSubtotal = $unitPrice * $quantity;
                $itemDiscount = rand(0, (int)($itemSubtotal * 0.2));
                $itemTotal = $itemSubtotal - $itemDiscount;
                
                $subtotal += $itemSubtotal;

                // Lưu thông tin snapshot
                // Tạo SKU không có ký tự đặc biệt
                $productSkuBase = preg_replace('/[^a-zA-Z0-9]/', '', strtoupper(substr($product->name, 0, 5)));
                if (empty($productSkuBase)) {
                    $productSkuBase = 'PROD';
                }
                $productSku = 'SKU-' . $productSkuBase . '-' . $product->id;
                
                $items[] = [
                    'product_id' => $product->id,
                    'variant_id' => $variant ? $variant->id : null,
                    'product_name' => $product->name,
                    'product_sku' => $productSku,
                    'product_image' => $product->image_main,
                    'product_description' => $product->description,
                    'variant_name' => $variant ? $variant->title : null,
                    'variant_sku' => $variant ? $variant->sku : null,
                    'variant_attributes' => $variant ? json_encode([
                        'color' => $variant->title ? (strpos(strtolower($variant->title), 'đen') !== false ? 'Đen' : 
                                                      (strpos(strtolower($variant->title), 'trắng') !== false ? 'Trắng' : 'Xanh')) : null,
                        'size' => $variant->title ? (strpos(strtolower($variant->title), 'm') !== false ? 'M' : 
                                                    (strpos(strtolower($variant->title), 'l') !== false ? 'L' : 
                                                    (strpos(strtolower($variant->title), '28') !== false ? '28' : '30'))) : null,
                    ]) : null,
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'subtotal' => $itemSubtotal,
                    'discount_amount' => $itemDiscount,
                    'total_price' => $itemTotal,
                    'product_options' => json_encode([
                        'color' => $variant ? (strpos(strtolower($variant->title), 'đen') !== false ? 'Đen' : 
                                             (strpos(strtolower($variant->title), 'trắng') !== false ? 'Trắng' : 'Xanh')) : 'Mặc định',
                        'size' => $variant ? (strpos(strtolower($variant->title), 'm') !== false ? 'M' : 
                                            (strpos(strtolower($variant->title), 'l') !== false ? 'L' : 
                                            (strpos(strtolower($variant->title), '28') !== false ? '28' : '30'))) : 'Mặc định',
                    ]),
                    'note' => rand(0, 10) > 7 ? 'Giao hàng nhanh' : null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ];
            }

            $totalAmount = $subtotal - $promotionAmount + $shippingFee;

            // Xác định các timestamp dựa trên status
            $confirmedAt = null;
            $completedAt = null;
            $cancelledAt = null;

            if (in_array($status, ['processing', 'shipped', 'completed'])) {
                $confirmedAt = $createdAt->copy()->addHours(rand(1, 24));
            }

            if ($status === 'completed') {
                $completedAt = $confirmedAt ? $confirmedAt->copy()->addDays(rand(1, 5)) : $createdAt->copy()->addDays(rand(2, 7));
            }

            if ($status === 'cancelled') {
                $cancelledAt = $createdAt->copy()->addHours(rand(1, 48));
            }

            // Tạo order
            $orderId = DB::table('orders')->insertGetId([
                'order_number' => 'ORD-' . $orderNumber++,
                'user_id' => $user->id,
                'address_id' => null, // Có thể thêm sau
                'voucher_id' => null, // Có thể thêm sau
                'customer_name' => $user->name,
                'customer_phone' => $user->phone ?? '0' . rand(100000000, 999999999),
                'customer_email' => $user->email,
                'customer_address' => $faker->address(),
                'receiver_name' => $user->name,
                'receiver_phone' => $user->phone ?? '0' . rand(100000000, 999999999),
                'receiver_email' => $user->email,
                'shipping_address' => $faker->address(),
                'total_amount' => $totalAmount,
                'subtotal' => $subtotal,
                'promotion_amount' => $promotionAmount,
                'shipping_fee' => $shippingFee,
                'payment_method' => $paymentMethod,
                'payment_details' => json_encode([
                    'method' => $paymentMethod,
                    'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                ]),
                'payment_reference' => 'REF-' . strtoupper(uniqid()),
                'payment_status' => $paymentStatus,
                'status' => $status,
                'note' => rand(0, 10) > 7 ? $faker->sentence() : null,
                'admin_note' => rand(0, 10) > 8 ? 'Đơn hàng đặc biệt' : null,
                'confirmed_at' => $confirmedAt,
                'completed_at' => $completedAt,
                'cancelled_at' => $cancelledAt,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // Thêm order_id vào items và insert
            foreach ($items as $item) {
                $item['order_id'] = $orderId;
                $orderItems[] = $item;
            }
        }

        // Insert tất cả order items
        if (!empty($orderItems)) {
            DB::table('order_items')->insert($orderItems);
        }

        $this->command->info('Đã tạo 50 đơn hàng và ' . count($orderItems) . ' order items.');
    }
}
