<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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

        case 'views_asc':
            $query->orderBy('views', 'ASC')->orderBy('id', 'ASC');
            break;

        case 'views_desc':
            $query->orderBy('views', 'DESC')->orderBy('id', 'DESC');
            break;

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
    public function show(Post $post)
    {
        $post->load('author');
        return view('admin.posts.show', compact('post'));
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
