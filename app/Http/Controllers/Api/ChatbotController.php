<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use App\Models\Admin\Brand;       // Import Model Brand
use App\Models\Admin\ProductVariant; // Import Model ProductVariant
use App\Models\admin\Review;       // Import Model Review
use App\Models\Voucher;           // Import Model Voucher
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL; // Đảm bảo import URL
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    public function askAI(Request $request)
    {
        // 1. Lấy Khóa API và tin nhắn User
        $geminiApiKey = env('GEMINI_API_KEY');
        $userMsg = $request->input('message');

        if (!$geminiApiKey) {
            return response()->json(['message' => 'Lỗi: Chưa cấu hình GEMINI_API_KEY trong file .env'], 500);
        }

        // --- BẮT ĐẦU LẤY DỮ LIỆU TỪ TẤT CẢ CÁC MODEL ---
        $limit = 5;
        $contextData = [];
        
        // 2.1. Lấy dữ liệu Sản phẩm (Product)
        $products = Product::select('id', 'name', 'base_price', 'description', 'image_main', 'brand_id')
            ->where('is_active', 1)
            ->with('brand') 
            ->limit($limit)
            ->get();
        
        // ==================================================================================
        // **ĐÃ SỬA (FIX):** Áp dụng cách xử lý ảnh an toàn từ đoạn code ngắn
        $productLinkData = $products->map(function ($p) {
            // Kiểm tra nếu có ảnh thì lấy link, nếu không thì dùng ảnh placeholder
            $imgUrl = ($p->image_main && $p->image_main !== '') 
                ? asset('storage/' . $p->image_main) 
                : 'https://placehold.co/300x300?text=No+Image';

            return [
                'id' => $p->id,
                'name' => trim($p->name), // Cắt khoảng trắng thừa để khớp key
                'image_url' => $imgUrl,   // Link ảnh đã xử lý an toàn
                'product_url' => url("/products/{$p->id}"),
            ];
        })->keyBy('name')->toArray(); // Key theo tên để JS dễ tìm
        // ==================================================================================
        

        $productList = "";
        foreach ($products as $p) {
            $brandName = $p->brand->name ?? 'Không rõ'; 
            $shortDescription = Str::limit($p->description, 70, '...'); 
            
            // Dùng trim($p->name) để đảm bảo tên trong prompt khớp với key trong mảng productLinkData
            $cleanName = trim($p->name);

            $productList .= "
            - Tên: {$cleanName}
            - ID: {$p->id}
            - Thương hiệu: {$brandName} 
            - Giá: " . number_format($p->base_price) . " VNĐ
            - Mô tả: {$shortDescription}\n"; 
        }
        $contextData[] = "DANH SÁCH SẢN PHẨM (Top {$limit}):\n" . ($productList ?: "Không có.");

        // 2.2. Lấy dữ liệu Biến thể Sản phẩm (ProductVariant) - GIỮ NGUYÊN
        $variants = ProductVariant::select('sku', 'price', 'stock', 'product_id')
            ->limit($limit)
            ->get();

        $variantList = "";
        foreach ($variants as $v) {
            $variantList .= "
            - SKU: {$v->sku}
            - Giá: " . number_format($v->price) . " VNĐ
            - Tồn kho: {$v->stock}
            - Product ID: {$v->product_id}\n";
        }
        $contextData[] = "DANH SÁCH BIẾN THỂ (Top {$limit}):\n" . ($variantList ?: "Không có.");

        // 2.3. Lấy dữ liệu Đánh giá (Review) - GIỮ NGUYÊN
        $reviews = Review::select('product_id', 'rating', 'content', 'user_id')
            ->limit($limit)
            ->get();

        $reviewList = "";
        foreach ($reviews as $r) {
            $reviewContent = Str::limit($r->content, 50, '...'); 
            $reviewList .= "
            - User ID: {$r->user_id}
            - Product ID: {$r->product_id}
            - Đánh giá: {$r->rating} sao
            - Nội dung: {$reviewContent}\n";
        }
        $contextData[] = "DANH SÁCH ĐÁNH GIÁ (Top {$limit}):\n" . ($reviewList ?: "Không có.");

        // 2.4. Lấy dữ liệu Thương hiệu (Brand) - GIỮ NGUYÊN
        $brands = Brand::select('name', 'description')
            ->limit($limit)
            ->get();

        $brandList = "";
        foreach ($brands as $b) {
            $brandDescription = Str::limit($b->description, 70, '...'); 
            $brandList .= "
            - Tên: {$b->name}
            - Mô tả: {$brandDescription}\n";
        }
        $contextData[] = "DANH SÁCH THƯƠNG HIỆU (Top {$limit}):\n" . ($brandList ?: "Không có.");


        // 2.5. Lấy dữ liệu Voucher - GIỮ NGUYÊN
        $vouchers = Voucher::select('code', 'type', 'value', 'ends_at')
            ->limit($limit)
            ->get();

        $voucherList = "";
        foreach ($vouchers as $v) {
            $voucherList .= "
            - Code: {$v->code}
            - Loại: {$v->type}
            - Giá trị: {$v->value}
            - Hết hạn: {$v->ends_at}\n";
        }
        $contextData[] = "DANH SÁCH VOUCHER (Top {$limit}):\n" . ($voucherList ?: "Không có.");
        
        // Gộp tất cả dữ liệu thành một chuỗi
        $fullContext = implode("\n----------------------\n", $contextData);
        // --- KẾT THÚC LẤY DỮ LIỆU TỪ TẤT CẢ CÁC MODEL ---

        // 3. Tạo System Instruction
        $systemContent = trim("
        Bạn là chuyên gia tư vấn bán hàng chuyên nghiệp. 
        Quy tắc BẮT BUỘC: LUÔN LUÔN trả lời bằng TIẾNG VIỆT.
        Dưới đây là DỮ LIỆU ĐẦY ĐỦ của cửa hàng (tất cả các Model):
        $fullContext
        Hãy trả lời ngắn gọn, thân thiện, và sử dụng dữ liệu trên để tư vấn, ưu tiên thông tin về Tên, Giá, và Mô tả. **KHÔNG** đề cập đến ID, Link ảnh hoặc Link chi tiết sản phẩm trong câu trả lời văn bản (Hệ thống sẽ tự hiển thị).
        ");

        // 4. CHUYỂN SYSTEM INSTRUCTION THÀNH BẢN GHI ĐẦU TIÊN CỦA CUỘC TRÒ CHUYỆN
        $contents = [
            [
                "role" => "user",
                "parts" => [["text" => $systemContent]]
            ],
            [
                "role" => "user",
                "parts" => [["text" => "Khách hàng hỏi: " . $userMsg]]
            ]
        ];

        try {
            // 5. GỌI GEMINI API
            $response = Http::timeout(30)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$geminiApiKey}", [
                'contents' => $contents,
                'generationConfig' => [
                    'temperature' => 0.2, 
                ]
            ]);

            $result = $response->json();
            
            // Xử lý lỗi từ API
            if (isset($result['error'])) {
                 return response()->json([
                     'message' => 'Lỗi API: ' . ($result['error']['message'] ?? 'Không rõ')
                 ], 500);
            }
            
            // Lấy nội dung phản hồi chính xác
            $botReply = $result['candidates'][0]['content']['parts'][0]['text'] 
                         ?? "Lỗi: Không tìm thấy phản hồi từ AI.";

            return response()->json([
                'message' => $botReply,
                // **TRẢ VỀ DỮ LIỆU LINK ĐÃ XỬ LÝ (CÓ PLACEHOLDER)**
                'product_links' => $productLinkData 
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => "Lỗi hệ thống (Kết nối Cloud): " . $e->getMessage()
            ], 500);
        }
    }
}