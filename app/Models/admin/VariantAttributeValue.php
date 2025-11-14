<?php

namespace App\Models\Admin;

use App\Models\admin\AttributeValue;
use Illuminate\Database\Eloquent\Relations\Pivot;

class VariantAttributeValue extends Pivot
{
    protected $table = 'variant_attribute_values';
    public $timestamps = false;

    protected $primaryKey = ['variant_id', 'attribute_value_id'];
    public $incrementing = false;

    protected $fillable = [
        'variant_id',
        'attribute_value_id',  
    ];

    // Quan hệ tới ProductVariant
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    //  Quan hệ tới AttributeValue 
    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class, 'attribute_value_id');
    }
}
?>