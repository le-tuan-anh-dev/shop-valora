<?php
namespace App\Models\Admin;

use App\Models\Admin\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'is_active',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}