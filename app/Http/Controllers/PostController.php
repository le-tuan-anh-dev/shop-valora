<?php
namespace App\Http\Controllers;

use App\Models\Admin\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('is_published', true)
            ->latest()
            ->paginate(10);

        return view('client.posts.index', compact('posts'));
    }

    public function show($id)
{
    $post = Post::where('id', $id)
        ->where('is_published', true)
        ->firstOrFail();

    return view('client.posts.show', compact('post'));
}

}
