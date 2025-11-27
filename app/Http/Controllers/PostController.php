<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

// Import các Model (Theo namespace của bạn)
use App\Models\admin\Post;
use App\Models\admin\PostComment;
use App\Models\admin\BannedWord;

class PostController extends Controller
{
    /**
     * 1. Danh sách bài viết
     */
    public function index(Request $request)
    {
        $query = Post::with('author')
                     ->withCount('comments')
                     ->where('is_published', true);

        if ($request->has('keyword') && $request->keyword != '') {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }

        $posts = $query->latest()->paginate(9)->appends(['keyword' => $request->keyword]);

        // Sidebar data
        $topPosts = Post::where('is_published', true)
    ->withCount('comments') // Thêm cột đếm comments_count
    ->orderBy('comments_count', 'desc') // Sắp xếp theo số lượng comment
    ->take(5)
    ->get();
    
$recentPosts = Post::where('is_published', true)->latest()->take(4)->get();

        return view('client.posts.index', compact('posts', 'topPosts', 'recentPosts'));
    }

    /**
     * 2. Chi tiết bài viết (ĐÂY LÀ HÀM BẠN ĐANG BỊ THIẾU)
     */
    public function show($id)
    {
        // Tìm bài viết, load kèm comment và user của comment đó
        $post = Post::with(['author', 'comments.user']) 
            ->withCount('comments')
            ->where('is_published', true)
            ->findOrFail($id);

        // Tăng lượt xem (Check session để không tăng ảo khi F5)
        $sessionKey = 'post_viewed_' . $id;
        if (!Session::has($sessionKey)) {
            $post->views += 1; // Cộng thủ công
            $post->save();
            Session::put($sessionKey, true);
        }

        // Data Sidebar
       $topPosts = Post::where('is_published', true)
    ->withCount('comments') // Thêm cột đếm comments_count
    ->orderBy('comments_count', 'desc') // Sắp xếp theo số lượng comment
    ->take(5)
    ->get();
        
        // Bài viết liên quan
        $relatedPosts = Post::where('is_published', true)
            ->where('author_id', $post->author_id)
            ->where('id', '!=', $id)
            ->take(3)
            ->get();

        return view('client.posts.show', compact('post', 'topPosts', 'relatedPosts'));
    }

    /**
     * 3. Lưu bình luận (Có chặn từ cấm)
     */
    public function storeComment(Request $request, $postId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để bình luận.');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ], [
            'content.required' => 'Nội dung không được để trống.',
        ]);

        $content = $request->input('content');

        // --- CHECK TỪ CẤM ---
        $bannedWords = BannedWord::pluck('word')->toArray();

        foreach ($bannedWords as $word) {
            if (stripos($content, $word) !== false) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Bình luận chứa từ ngữ cấm: "' . $word . '"');
            }
        }

        // Lưu comment
        PostComment::create([
            'post_id' => $postId,
            'user_id' => Auth::id(),
            'content' => $content,
            'parent_id' => null
        ]);

        return redirect()->back()->with('success', 'Bình luận đã được đăng!');
    }

    /**
     * 4. Xóa bình luận (Chỉ chủ comment mới được xóa)
     */
    public function destroyComment($id)
    {
        $comment = PostComment::find($id);

        if (!$comment) {
            return back()->with('error', 'Bình luận không tồn tại.');
        }

        // Kiểm tra quyền sở hữu
        if (Auth::id() !== $comment->user_id) {
            return back()->with('error', 'Bạn không có quyền xóa bình luận này.');
        }

        $comment->delete();

        return back()->with('success', 'Đã xóa bình luận.');
    }
}