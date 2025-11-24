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
            'address' => '123 Đường ABC, Quận 1, TP.HCM',
            'date_birth' => '1990-01-01',
            'gender' => 'male',
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Tạo 20 customer users với dữ liệu đầy đủ
        $customers = [
            ['name' => 'Nguyễn Văn An', 'email' => 'nguyenvanan@example.com', 'phone' => '0912345678', 'gender' => 'male', 'date_birth' => '1995-03-15', 'address' => '123 Đường Nguyễn Huệ, Quận 1, TP.HCM'],
            ['name' => 'Trần Thị Bình', 'email' => 'tranthibinh@example.com', 'phone' => '0923456789', 'gender' => 'female', 'date_birth' => '1998-07-22', 'address' => '456 Đường Lê Lợi, Quận 3, TP.HCM'],
            ['name' => 'Lê Văn Cường', 'email' => 'levancuong@example.com', 'phone' => '0934567890', 'gender' => 'male', 'date_birth' => '1992-11-08', 'address' => '789 Đường Trần Hưng Đạo, Quận 5, TP.HCM'],
            ['name' => 'Phạm Thị Dung', 'email' => 'phamthidung@example.com', 'phone' => '0945678901', 'gender' => 'female', 'date_birth' => '1996-05-30', 'address' => '321 Đường Võ Văn Tần, Quận 3, TP.HCM'],
            ['name' => 'Hoàng Văn Em', 'email' => 'hoangvanem@example.com', 'phone' => '0956789012', 'gender' => 'male', 'date_birth' => '1993-09-14', 'address' => '654 Đường Điện Biên Phủ, Quận Bình Thạnh, TP.HCM'],
            ['name' => 'Vũ Thị Phương', 'email' => 'vuthiphuong@example.com', 'phone' => '0967890123', 'gender' => 'female', 'date_birth' => '1997-12-25', 'address' => '987 Đường Cách Mạng Tháng 8, Quận 10, TP.HCM'],
            ['name' => 'Đặng Văn Giang', 'email' => 'dangvangiang@example.com', 'phone' => '0978901234', 'gender' => 'male', 'date_birth' => '1994-02-18', 'address' => '147 Đường Nguyễn Trãi, Quận 1, TP.HCM'],
            ['name' => 'Bùi Thị Hoa', 'email' => 'buithihoa@example.com', 'phone' => '0989012345', 'gender' => 'female', 'date_birth' => '1999-08-05', 'address' => '258 Đường Pasteur, Quận 3, TP.HCM'],
            ['name' => 'Đỗ Văn Hùng', 'email' => 'dovanhung@example.com', 'phone' => '0990123456', 'gender' => 'male', 'date_birth' => '1991-04-12', 'address' => '369 Đường Hai Bà Trưng, Quận 1, TP.HCM'],
            ['name' => 'Ngô Thị Lan', 'email' => 'ngothilan@example.com', 'phone' => '0901234567', 'gender' => 'female', 'date_birth' => '1996-10-20', 'address' => '741 Đường Lý Tự Trọng, Quận 1, TP.HCM'],
            ['name' => 'Phan Văn Minh', 'email' => 'phanvanminh@example.com', 'phone' => '0912345679', 'gender' => 'male', 'date_birth' => '1993-06-28', 'address' => '852 Đường Nguyễn Đình Chiểu, Quận 3, TP.HCM'],
            ['name' => 'Võ Thị Nga', 'email' => 'vothinga@example.com', 'phone' => '0923456780', 'gender' => 'female', 'date_birth' => '1998-01-15', 'address' => '963 Đường Nam Kỳ Khởi Nghĩa, Quận 3, TP.HCM'],
            ['name' => 'Trương Văn Oanh', 'email' => 'truongvanoanh@example.com', 'phone' => '0934567891', 'gender' => 'male', 'date_birth' => '1995-11-03', 'address' => '159 Đường Đinh Tiên Hoàng, Quận Bình Thạnh, TP.HCM'],
            ['name' => 'Lý Thị Phượng', 'email' => 'lythiphuong@example.com', 'phone' => '0945678902', 'gender' => 'female', 'date_birth' => '1997-03-19', 'address' => '357 Đường Xô Viết Nghệ Tĩnh, Quận Bình Thạnh, TP.HCM'],
            ['name' => 'Dương Văn Quang', 'email' => 'duongvanquang@example.com', 'phone' => '0956789013', 'gender' => 'male', 'date_birth' => '1992-09-07', 'address' => '468 Đường Phạm Văn Đồng, Quận Thủ Đức, TP.HCM'],
            ['name' => 'Đinh Thị Quyên', 'email' => 'dinhthiquyen@example.com', 'phone' => '0967890124', 'gender' => 'female', 'date_birth' => '1999-05-24', 'address' => '579 Đường Hoàng Diệu, Quận 4, TP.HCM'],
            ['name' => 'Mai Văn Sơn', 'email' => 'maivanson@example.com', 'phone' => '0978901235', 'gender' => 'male', 'date_birth' => '1994-12-11', 'address' => '680 Đường Tôn Đức Thắng, Quận 1, TP.HCM'],
            ['name' => 'Hồ Thị Tâm', 'email' => 'hothitam@example.com', 'phone' => '0989012346', 'gender' => 'female', 'date_birth' => '1996-08-16', 'address' => '791 Đường Nguyễn Thị Minh Khai, Quận 3, TP.HCM'],
            ['name' => 'Lưu Văn Tuấn', 'email' => 'luovantuan@example.com', 'phone' => '0990123457', 'gender' => 'male', 'date_birth' => '1993-02-28', 'address' => '802 Đường Lê Văn Việt, Quận 9, TP.HCM'],
            ['name' => 'Chu Thị Uyên', 'email' => 'chuthiuyen@example.com', 'phone' => '0901234568', 'gender' => 'female', 'date_birth' => '1997-07-09', 'address' => '913 Đường Võ Văn Ngân, Quận Thủ Đức, TP.HCM'],
        ];

        foreach ($customers as $index => $customer) {
            User::create([
                'name' => $customer['name'],
                'email' => $customer['email'],
                'password' => Hash::make('password'),
                'phone' => $customer['phone'],
                'address' => $customer['address'],
                'date_birth' => $customer['date_birth'],
                'gender' => $customer['gender'],
                'role' => 'customer',
                'status' => $index < 2 ? 'active' : ($index < 4 ? 'locked' : 'active'), // Một số user bị khóa để test
                'email_verified_at' => now(),
                'phone_verified_at' => $index % 3 == 0 ? now() : null, // Một số user đã xác thực số điện thoại
            ]);
        }

        $this->command->info('Đã tạo 1 admin và 20 customer users.');
    }
}
