<?php

namespace App\Models\Admin;

use App\Models\Admin\Attributes;
use App\Models\admin\AttributeValue;
use App\Models\Admin\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductVariant extends Model
{
    protected $table = 'product_variants';
    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'stock',
        'image_url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'float',
        'stock' => 'integer',
    ];

    // Quan hệ tới Product
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Quan hệ tới AttributeValue (qua VariantAttributeValue)
    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(
            AttributeValue::class,
            'variant_attribute_values',
            'variant_id',
            'attribute_value_id'
        )->withPivot(['variant_id', 'attribute_value_id']);
    }

    //  Quan hệ tới Attribute (qua AttributeValue)
    public function attributes()
    {
        return $this->hasManyThrough(
            Attributes::class,
            AttributeValue::class,
            'id',
            'id',
            'id',
            'attribute_id'
        )->distinct();
    }

    // Lấy thông tin chi tiết variant 
    public function getVariantDetails()
    {
        $details = [];
        
        $variantAttributes = VariantAttributeValue::where('variant_id', $this->id)
            ->with(['attributeValue' => function ($q) {
                $q->with('attribute');
            }])
            ->get();

        foreach ($variantAttributes as $vav) {
            $av = $vav->attributeValue;
            $details[] = [
                'attribute_id' => $av->attribute_id,
                'attribute_name' => $av->attribute->name,
                'attribute_value_id' => $av->id,
                'attribute_value' => $av->value,
            ];
        }

        return $details;
    }
}
?>