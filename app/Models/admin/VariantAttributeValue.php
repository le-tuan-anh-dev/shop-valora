<?php

namespace App\Models\admin;

use App\Models\admin\AttributeValue;
use App\Models\Admin\ProductVariant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariantAttributeValue extends Model
{
    use HasFactory;

    protected $table = 'variant_attribute_values';
    public $timestamps = false;

    protected $fillable = [
        'variant_id',
        'attribute_value_id',
    ];

    /**
     * Biến thể sản phẩm mà giá trị này thuộc về
     */
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    /**
     * Giá trị thuộc tính (ví dụ: Đỏ, XL...)
     */
    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class, 'attribute_value_id');
    }
}