<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'min_oder_value',
        'max_uses', 'used_count', 'start_date', 'end_date', 'active'
    ];

    //Kiểm tra voucher có hợp lệ không
    public function isValid($orderTotal)
    {
        if (!$this->active) return false;
        if ($this->used_count >= $this->max_uses) return false;
        if ($this->start_date && $this->start_date > now()) return false;
        if ($this->end_date && $this->end_date < now()) return false;
        if ($this->min_oder_value && $orderTotal < $this->min_order_value) return false;

        return true;
    }

    //Tính số tiền được giảm
    public function discountAmount($orderTotal)
    {
        if ($this->type === 'percent') {
            return $orderTotal * ($this->value / 100);
        }

        return $this->value;
    }
}

?>