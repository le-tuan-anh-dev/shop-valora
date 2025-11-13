<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Tạm tắt kiểm tra khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Tạo admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@valora.com',
            'password' => Hash::make('password'),
            'phone' => '0123456789',
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Tạo 20 customer users
        $customers = [
            ['name' => 'Nguyễn Văn An', 'email' => 'nguyenvanan@example.com', 'phone' => '0912345678'],
            ['name' => 'Trần Thị Bình', 'email' => 'tranthibinh@example.com', 'phone' => '0923456789'],
            ['name' => 'Lê Văn Cường', 'email' => 'levancuong@example.com', 'phone' => '0934567890'],
            ['name' => 'Phạm Thị Dung', 'email' => 'phamthidung@example.com', 'phone' => '0945678901'],
            ['name' => 'Hoàng Văn Em', 'email' => 'hoangvanem@example.com', 'phone' => '0956789012'],
            ['name' => 'Vũ Thị Phương', 'email' => 'vuthiphuong@example.com', 'phone' => '0967890123'],
            ['name' => 'Đặng Văn Giang', 'email' => 'dangvangiang@example.com', 'phone' => '0978901234'],
            ['name' => 'Bùi Thị Hoa', 'email' => 'buithihoa@example.com', 'phone' => '0989012345'],
            ['name' => 'Đỗ Văn Hùng', 'email' => 'dovanhung@example.com', 'phone' => '0990123456'],
            ['name' => 'Ngô Thị Lan', 'email' => 'ngothilan@example.com', 'phone' => '0901234567'],
            ['name' => 'Phan Văn Minh', 'email' => 'phanvanminh@example.com', 'phone' => '0912345679'],
            ['name' => 'Võ Thị Nga', 'email' => 'vothinga@example.com', 'phone' => '0923456780'],
            ['name' => 'Trương Văn Oanh', 'email' => 'truongvanoanh@example.com', 'phone' => '0934567891'],
            ['name' => 'Lý Thị Phượng', 'email' => 'lythiphuong@example.com', 'phone' => '0945678902'],
            ['name' => 'Dương Văn Quang', 'email' => 'duongvanquang@example.com', 'phone' => '0956789013'],
            ['name' => 'Đinh Thị Quyên', 'email' => 'dinhthiquyen@example.com', 'phone' => '0967890124'],
            ['name' => 'Mai Văn Sơn', 'email' => 'maivanson@example.com', 'phone' => '0978901235'],
            ['name' => 'Hồ Thị Tâm', 'email' => 'hothitam@example.com', 'phone' => '0989012346'],
            ['name' => 'Lưu Văn Tuấn', 'email' => 'luovantuan@example.com', 'phone' => '0990123457'],
            ['name' => 'Chu Thị Uyên', 'email' => 'chuthiuyen@example.com', 'phone' => '0901234568'],
        ];

        foreach ($customers as $customer) {
            User::create([
                'name' => $customer['name'],
                'email' => $customer['email'],
                'password' => Hash::make('password'),
                'phone' => $customer['phone'],
                'role' => 'customer',
                'status' => 'active',
            ]);
        }

        $this->command->info('Đã tạo 1 admin và 20 customer users.');
    }
}
