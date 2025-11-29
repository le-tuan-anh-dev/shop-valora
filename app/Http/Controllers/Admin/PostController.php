<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\admin\PostComment;
use App\Models\admin\BannedWord;

class PostController extends Controller
{
    /** Danh sách bài viết */
    public function index(Request $request)
    {
        $query = Post::with('author');

        // Lọc cơ bản
        if ($request->filled('title')) {
            $query->where('title', 'LIKE', '%' . $request->title . '%');
        }

        if ($request->filled('status')) {
            $query->where('is_published', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // ============================
        // SORT (AN TOÀN CHO CURSOR)
        // ============================
        switch ($request->sort) {

            case 'likes_asc':
                $query->orderBy('likes', 'ASC')->orderBy('id', 'ASC');
                break;

            case 'likes_desc':
                $query->orderBy('likes', 'DESC')->orderBy('id', 'DESC');
                break;

            default:
                $query->orderBy('id', 'DESC');
        }

        $posts = $query->cursorPaginate(10)->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }


    /** Form tạo */
    public function create()
    {
        return view('admin.posts.create');
    }

    /** Lưu bài viết */
    public function store(Request $request)
    {
        $request->validate([
            'title'     => 'required|max:200',
            'content'   => 'nullable',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['title', 'content']);
        $data['author_id'] = Auth::user()->id;
        $data['is_published'] = $request->boolean('is_published');

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        Post::create($data);

        return redirect()->route('admin.posts.index')->with('success', 'Tạo bài viết thành công!');
    }

    /** Xem bài viết chi tiết */
/** Xem bài viết chi tiết */
public function show($id)
{
    // Load giống hệt bên Client - dùng findOrFail như Client
    $post = Post::with(['author', 'comments.user'])
        ->withCount('comments')
        ->findOrFail($id);
    
    return view('admin.posts.show', compact('post'));
}

/** Trả lời bình luận */
public function replyComment(Request $request, PostComment $comment)
{
    $request->validate([
        'content' => 'required|string|max:1000',
    ]);

    PostComment::create([
        'post_id' => $comment->post_id,
        'user_id' => Auth::id(),
        'content' => $request->content,
        'parent_id' => $comment->id  // Đây là reply của comment này
    ]);

    return redirect()->back()->with('success', 'Đã trả lời bình luận!');
}

/** Xóa bình luận/reply */
public function deleteComment(PostComment $comment)
{
    $comment->delete();
    
    return redirect()->back()->with('success', 'Đã xóa bình luận!');
}



    /** Form sửa */
    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    /** Cập nhật bài */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title'     => 'required|max:200',
            'content'   => 'nullable',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['title', 'content']);
        $data['is_published'] = $request->boolean('is_published');

        if ($request->hasFile('thumbnail')) {
            
            if ($post->thumbnail) { 
                Storage::disk('public')->delete($post->thumbnail);
            }
            
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $post->update($data);

        return redirect()->route('admin.posts.index')->with('success', 'Cập nhật thành công!');
    }

    /** Xóa bài */
    public function destroy(Post $post)
    {
        Storage::disk('public')->delete($post->thumbnail);
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Đã xóa bài viết!');
    }

    /** TinyMCE upload ảnh */
    public function tinymceUpload(Request $request)
    {
        if (!$request->hasFile('file'))
            return response()->json(['error' => 'Upload failed'], 500);

        $path = $request->file('file')->store('tinymce/images', 'public');
        return response()->json(['location' => Storage::url($path)]);
    }
}