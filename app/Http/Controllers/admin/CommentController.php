<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin\PostComment;
use App\Models\admin\Post;

use Illuminate\Http\Request;

class CommentController extends Controller
{
   public function index()
{


    $comments = PostComment::with('user','post')
        ->latest()
        
        ->paginate(10);
        // ->toArray();

    return view('admin.comments.comments-list', compact('comments'));
}

}
