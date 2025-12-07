<?php 

namespace App\Models\Admin;     // SỬA: Admin viết hoa, đồng bộ với namespace khác

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasFactory;

    protected $fillable = ['attribute_id', 'value', 'meta'];

    protected $casts = [
        'meta' => 'array',
    ];

    public function attribute()
    {
        // SỬA: thêm use hoặc dùng FQCN \App\Models\Admin\Attributes nếu cần
        return $this->belongsTo(Attributes::class, 'attribute_id');
    }

    public function variantAttributeValues()
    {
        return $this->hasMany(VariantAttributeValue::class, 'attribute_value_id');
    }
}