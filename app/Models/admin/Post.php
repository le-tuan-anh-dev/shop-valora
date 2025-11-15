<?php

namespace App\Models\admin;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\admin\PostComment;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',   // id của user tạo bài viết
        'title',       // tiêu đề bài viết
        'content',     // nội dung
        'thumbnail',   // link ảnh đại diện
        'views',       // số lượt xem
        'likes',       // số lượt thích
        'is_published' // trạng thái xuất bản (true/false)
    ];

    /**
     * Quan hệ: một bài viết thuộc về một tác giả
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Quan hệ: một bài viết có nhiều bình luận
     */
    public function comments()
    {
        return $this->hasMany(PostComment::class);
    }
}
