<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin\PostComment;
use App\Models\admin\Post;

use Illuminate\Http\Request;
use App\Models\admin\BannedWord;

class CommentController extends Controller
{
    // --------------------------
    // 1) Danh sách bình luận
    // --------------------------
  public function indexComments(Request $request)
{
    // Query
    $commentsQuery = PostComment::with(['user:id,name,image', 'post:id,title'])
        ->when($request->post_id, fn($q) => $q->where('post_id', $request->post_id))
        // Cursor bắt buộc phải order theo column unique (thường là ID) để làm mốc
        ->orderBy('id', 'desc'); 

    // Dùng cursorPaginate + withQueryString để giữ bộ lọc trên URL
    $comments = $commentsQuery->cursorPaginate(10)->withQueryString();

    $posts = Post::select('id', 'title')->orderBy('title')->get();

    return view('admin.comments.comments-list', compact('comments', 'posts'));
}   

    /**
     * Xóa bình luận
     */
    public function destroy($id)
    {
        $comment = PostComment::find($id);
        if (!$comment) {
            return back()->with('error', 'Không tìm thấy bình luận.');
        }

        $comment->delete();

        return back()->with('success', 'Đã xóa bình luận thành công!');
    }




    // --------------------------
    // 2) Danh sách từ ngữ cấm
    // --------------------------
    public function indexBannedWords()
    {
        $bannedWords = BannedWord::orderByDesc('id')->get();
        return view('admin.comments.banned-list', compact('bannedWords'));
    }

    public function addBannedWord(Request $request)
    {
        $request->validate([
            'word' => 'required|string|max:255|unique:banned_words,word',
        ]);

        BannedWord::create(['word' => $request->word]);

        return back()->with('success', 'Đã thêm từ cấm thành công!');
    }

    public function updateBannedWord(Request $request, $id)
    {
        $word = BannedWord::findOrFail($id);

        $request->validate([
            'word' => 'required|string|max:255|unique:banned_words,word,' . $word->id,
        ]);

        $word->update(['word' => $request->word]);

        return back()->with('success', 'Cập nhật thành công!');
    }

    public function deleteBannedWord($id)
    {
        $word = BannedWord::find($id);

        if (!$word) {
            return back()->with('error', 'Không tìm thấy từ cấm.');
        }

        $word->delete();

        return back()->with('success', 'Đã xóa thành công!');
    }
}






