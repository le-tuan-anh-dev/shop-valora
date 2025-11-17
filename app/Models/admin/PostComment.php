<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\admin\Post;

class PostComment extends Model
{
    use HasFactory;

    protected $fillable = ['post_id', 'user_id', 'content', 'parent_id'];

    // Quan hệ với user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ với post
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Nếu có replies
    public function replies()
    {
        return $this->hasMany(PostComment::class, 'parent_id');
    }

  
}
