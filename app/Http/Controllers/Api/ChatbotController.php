<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use App\Models\Admin\Brand;       
use App\Models\Admin\ProductVariant; 
use App\Models\admin\Review;       
use App\Models\Voucher;           
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; // [MỚI] Import DB để tính toán AVG

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
        
        // ==================================================================================
        // 2.1. SẢN PHẨM (Product) - [CẬP NHẬT: Lấy thêm created_at, sắp xếp mới nhất]
        // ==================================================================================
        $products = Product::select('id', 'name', 'base_price', 'description', 'image_main', 'brand_id', 'created_at')
            ->where('is_active', 1)
            ->with('brand') 
            ->orderBy('created_at', 'desc') // Ưu tiên sản phẩm mới nhất
            ->limit($limit)
            ->get();
        
        // Xử lý ảnh (Giữ nguyên logic cũ)
        $productLinkData = $products->map(function ($p) {
            $imgUrl = ($p->image_main && $p->image_main !== '') 
                ? asset('storage/' . $p->image_main) 
                : 'https://placehold.co/300x300?text=No+Image';

            return [
                'id' => $p->id,
                'name' => trim($p->name),
                'image_url' => $imgUrl,   
                'product_url' => url("/products/{$p->id}"),
            ];
        })->keyBy('name')->toArray(); 
        
        $productList = "";
        foreach ($products as $p) {
            $brandName = $p->brand->name ?? 'Không rõ'; 
            $shortDescription = Str::limit($p->description, 70, '...'); 
            $cleanName = trim($p->name);
            
            // Format ngày tháng để AI biết sản phẩm mới hay cũ
            $dateCreated = $p->created_at ? $p->created_at->format('d/m/Y') : 'N/A';

            $productList .= "
            - Tên: {$cleanName}
            - ID: {$p->id}
            - Thương hiệu: {$brandName} 
            - Giá: " . number_format($p->base_price) . " VNĐ
            - Ngày ra mắt: {$dateCreated}
            - Mô tả: {$shortDescription}\n"; 
        }
        $contextData[] = "DANH SÁCH SẢN PHẨM MỚI NHẤT (Top {$limit}):\n" . ($productList ?: "Không có.");

        // ==================================================================================
        // 2.2. BIẾN THỂ (ProductVariant) - GIỮ NGUYÊN
        // ==================================================================================
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

        // ==================================================================================
        // 2.3. ĐÁNH GIÁ (Review) - [CẬP NHẬT: Xóa nội dung, Tính trung bình sao]
        // ==================================================================================
        // Group by product_id để tính trung bình
        $reviews = Review::select(
                'product_id', 
                DB::raw('AVG(rating) as avg_rating'), 
                DB::raw('COUNT(id) as total_reviews')
            )
            ->groupBy('product_id')
            ->orderBy('avg_rating', 'desc') // Lấy những sản phẩm được đánh giá cao nhất
            ->limit($limit)
            ->get();

        $reviewList = "";
        foreach ($reviews as $r) {
            // Làm tròn số sao (ví dụ: 4.5)
            $avgRating = number_format($r->avg_rating, 1);
            $reviewList .= "
            - Product ID: {$r->product_id}
            - Đánh giá trung bình: {$avgRating}/5 sao
            - Số lượng đánh giá: {$r->total_reviews} lượt\n";
        }
        $contextData[] = "THỐNG KÊ ĐÁNH GIÁ SẢN PHẨM (Top {$limit} cao nhất):\n" . ($reviewList ?: "Không có.");

        // ==================================================================================
        // 2.4. THƯƠNG HIỆU (Brand) - GIỮ NGUYÊN
        // ==================================================================================
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

        // ==================================================================================
        // 2.5. VOUCHER - [CẬP NHẬT: Thêm is_active và lọc Active]
        // ==================================================================================
        $vouchers = Voucher::select('code', 'type', 'value', 'ends_at', 'is_active')
            ->where('is_active', 1) // Chỉ lấy voucher đang hoạt động
            ->limit($limit)
            ->get();

        $voucherList = "";
        foreach ($vouchers as $v) {
            $voucherList .= "
            - Code: {$v->code}
            - Loại: {$v->type}
            - Giá trị: {$v->value}
            - Hết hạn: {$v->ends_at}
            - Trạng thái: Đang hoạt động\n";
        }
        $contextData[] = "DANH SÁCH VOUCHER KHẢ DỤNG (Top {$limit}):\n" . ($voucherList ?: "Không có.");
        
        // Gộp tất cả dữ liệu thành một chuỗi
        $fullContext = implode("\n----------------------\n", $contextData);
        // --- KẾT THÚC LẤY DỮ LIỆU TỪ TẤT CẢ CÁC MODEL ---

        // 3. Tạo System Instruction
        $systemContent = trim("
        Bạn là chuyên gia tư vấn bán hàng chuyên nghiệp. 
        Quy tắc BẮT BUỘC: LUÔN LUÔN trả lời bằng TIẾNG VIỆT.
        Dưới đây là DỮ LIỆU ĐẦY ĐỦ của cửa hàng (tất cả các Model):
        $fullContext
        Hãy trả lời ngắn gọn, thân thiện, và sử dụng dữ liệu trên để tư vấn.
        - Khi nói về sản phẩm, hãy ưu tiên giới thiệu sản phẩm mới nhất (dựa trên ngày ra mắt).
        - Khi khách hỏi về chất lượng, hãy dùng số sao trung bình để trả lời.
        - Tuyệt đối KHÔNG sử dụng ký tự đánh dấu in đậm (double asterisks, ví dụ: **tên sản phẩm**) trong câu trả lời.
        - **KHÔNG** đề cập đến ID, Link ảnh hoặc Link chi tiết sản phẩm trong câu trả lời văn bản (Hệ thống sẽ tự hiển thị).
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
            
            if (isset($result['error'])) {
                 return response()->json([
                     'message' => 'Lỗi API: ' . ($result['error']['message'] ?? 'Không rõ')
                 ], 500);
            }
            
            $botReply = $result['candidates'][0]['content']['parts'][0]['text'] 
                          ?? "Lỗi: Không tìm thấy phản hồi từ AI.";

            return response()->json([
                'message' => $botReply,
                'product_links' => $productLinkData 
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => "Lỗi hệ thống (Kết nối Cloud): " . $e->getMessage()
            ], 500);
        }
    }
}