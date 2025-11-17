<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\admin\PostComment;
use App\Models\admin\Post;

use Illuminate\Http\Request;
use App\Models\admin\BannedWord;

class CommentController extends Controller
{
    
    public function index()
    {
        $comments = PostComment::with('user','post')
            ->latest()
            ->paginate(10);

       
        $bannedWords = BannedWord::orderByDesc('id')->get();

        return view('admin.comments.comments-list', compact('comments', 'bannedWords'));
    }

  
    public function addBannedWord(Request $request)
    {
        $request->validate([
            'word' => 'required|string|max:255|unique:banned_words,word',
        ], [
            'word.required' => 'Vui lòng nhập từ cần cấm.',
            'word.unique' => 'Từ này đã tồn tại trong danh sách.',
        ]);

        BannedWord::create(['word' => $request->word]);

        return redirect()->back()->with('success', 'Đã thêm từ cấm thành công!');
    }

     public function updateBannedWord(Request $request, $id)
    {
        $word = BannedWord::findOrFail($id);

        $request->validate([
            'word' => 'required|string|max:255|unique:banned_words,word,' . $word->id,
        ], [
            'word.required' => 'Vui lòng nhập từ cần cấm.',
            'word.unique' => 'Từ này đã tồn tại trong danh sách.',
        ]);

        $word->update(['word' => $request->word]);

        return redirect()->back()->with('success', 'Cập nhật từ cấm thành công!');
    }

    
    public function deleteBannedWord($id)
    {
        $word = BannedWord::find($id);
        if (!$word) {
            return redirect()->back()->with('error', 'Không tìm thấy từ cần xóa.');
        }

        $word->delete();

        return redirect()->back()->with('success', 'Đã xóa từ cấm thành công!');
    }

    
    public function destroy($id)
    {
        $comment = PostComment::find($id);

        if (!$comment) {
            return redirect()->back()->with('error', 'Không tìm thấy bình luận.');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Đã xóa bình luận thành công!');
    }
}




