<?php

namespace App\Models;
use App\Models\admin\PostComment;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * Các cột cho phép gán giá trị hàng loạt (mass assignable)
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'image',
        'role',
        'status',
    ];

    /**
     * Ẩn các cột khi trả về dạng mảng hoặc JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Ép kiểu dữ liệu tự động
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'boolean',
    ];

    /**
     * Mối quan hệ: Một user có nhiều bình luận
     */
    public function comments()
    {
        return $this->hasMany(PostComment::class, 'user_id');
    }

    /**
     * Tự động mã hoá password khi set giá trị
     */
    public function setPasswordAttribute($value)
    {
        // Chỉ mã hoá nếu chưa được hash
        if (!empty($value) && substr($value, 0, 4) !== '$2y$') {
            $this->attributes['password'] = bcrypt($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    /**
     * Lấy URL ảnh đại diện hợp lệ
     */
    public function getImageUrlAttribute()
    {
        if ($this->image && filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        return asset('images/users/' . ($this->image ?: 'default.png'));
    }
}
