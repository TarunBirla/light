<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_request_id',
        'category_id',
        'product_id',
        'quantity'
    ];

    public function request()
    {
        return $this->belongsTo(EquipmentRequest::class, 'equipment_request_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function product()
    {
        return $this->belongsTo(Item::class, 'product_id');
    }
}
