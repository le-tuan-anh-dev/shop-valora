<?php 
namespace App\Models\admin;

use App\Models\admin\AttributeValue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attributes extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug','created_at','updated_at'];

    public function values()
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id');
    }
}