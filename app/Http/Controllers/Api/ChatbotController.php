<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\Product;
use App\Models\Admin\Brand;
use App\Models\Admin\ProductVariant;
use App\Models\admin\Review; // Lưu ý: Kiểm tra lại viết hoa/thường thư mục
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ChatbotController extends Controller
{
    public function askAI(Request $request)
    {
        // 1. Lấy Khóa API Groq và tin nhắn User
        // Bạn cần thêm GROQ_API_KEY vào file .env
        $groqApiKey = env('GROQ_API_KEY'); 
        $userMsg = $request->input('message');

        if (!$groqApiKey) {
            return response()->json(['message' => 'Lỗi: Chưa cấu hình GROQ_API_KEY trong file .env'], 500);
        }

        // --- BẮT ĐẦU LẤY DỮ LIỆU TỪ TẤT CẢ CÁC MODEL (GIỮ NGUYÊN LOGIC CŨ) ---
        $limit = 5;
        $contextData = [];
        
        // 2.1. SẢN PHẨM
        $products = Product::select('id', 'name', 'base_price', 'description', 'image_main', 'brand_id', 'created_at')
            ->where('is_active', 1)
            ->with('brand')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
        
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

        // 2.2. BIẾN THỂ
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

        // 2.3. ĐÁNH GIÁ
        $reviews = Review::select(
                'product_id', 
                DB::raw('AVG(rating) as avg_rating'), 
                DB::raw('COUNT(id) as total_reviews')
            )
            ->groupBy('product_id')
            ->orderBy('avg_rating', 'desc')
            ->limit($limit)
            ->get();

        $reviewList = "";
        foreach ($reviews as $r) {
            $avgRating = number_format($r->avg_rating, 1);
            $reviewList .= "
            - Product ID: {$r->product_id}
            - Đánh giá trung bình: {$avgRating}/5 sao
            - Số lượng đánh giá: {$r->total_reviews} lượt\n";
        }
        $contextData[] = "THỐNG KÊ ĐÁNH GIÁ SẢN PHẨM (Top {$limit} cao nhất):\n" . ($reviewList ?: "Không có.");

        // 2.4. THƯƠNG HIỆU
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

        // 2.5. VOUCHER
        $vouchers = Voucher::select('code', 'type', 'value', 'ends_at', 'is_active')
            ->where('is_active', 1)
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
        
        $fullContext = implode("\n----------------------\n", $contextData);
        // --- KẾT THÚC LẤY DỮ LIỆU ---

        // 3. Tạo System Instruction (Prompt)
        $systemContent = trim("
        Bạn là chuyên gia tư vấn bán hàng chuyên nghiệp. 
        Quy tắc BẮT BUỘC: LUÔN LUÔN trả lời bằng TIẾNG VIỆT.
        Dưới đây là DỮ LIỆU ĐẦY ĐỦ của cửa hàng (tất cả các Model):
        $fullContext
        Hãy trả lời ngắn gọn, thân thiện, và sử dụng dữ liệu trên để tư vấn.
        - Khi nói về sản phẩm, hãy ưu tiên giới thiệu sản phẩm mới nhất.
        - Khi khách hỏi về chất lượng, hãy dùng số sao trung bình để trả lời.
        - Tuyệt đối KHÔNG sử dụng ký tự đánh dấu in đậm (double asterisks) trong câu trả lời.
        - **KHÔNG** đề cập đến ID, Link ảnh hoặc Link chi tiết sản phẩm trong câu trả lời văn bản.
        ");

        // 4. CHUYỂN CẤU TRÚC MESSAGE SANG CHUẨN OPENAI (Cho Groq)
        $messages = [
            [
                "role" => "system",
                "content" => $systemContent
            ],
            [
                "role" => "user",
                "content" => $userMsg
            ]
        ];

        try {
            // 5. GỌI GROQ API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $groqApiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama-3.3-70b-versatile', // Model mạnh mẽ và nhanh của Meta trên Groq
                // Hoặc dùng: 'mixtral-8x7b-32768'
                'messages' => $messages,
                'temperature' => 0.2, // Giữ độ sáng tạo thấp để bám sát dữ liệu
                'max_tokens' => 1024,
            ]);

            $result = $response->json();
            
            // Kiểm tra lỗi từ Groq
            if (isset($result['error'])) {
                 return response()->json([
                     'message' => 'Lỗi API Groq: ' . ($result['error']['message'] ?? 'Không rõ')
                 ], 500);
            }
            
            // Parse kết quả theo chuẩn OpenAI
            $botReply = $result['choices'][0]['message']['content'] 
                        ?? "Lỗi: Không tìm thấy phản hồi từ AI.";

            return response()->json([
                'message' => $botReply,
                'product_links' => $productLinkData 
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => "Lỗi hệ thống (Kết nối Groq): " . $e->getMessage()
            ], 500);
        }
    }
}