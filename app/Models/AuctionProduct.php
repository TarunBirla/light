<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionProduct extends Model
{
    use HasFactory;

    protected $table = 'auction_products';

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'image',
        'qty',
        'minprice',
        'status',
        'shipping_type',
        'sort_order',
    ];

    protected $casts = [
        'image'    => 'array',
        'minprice' => 'float',
    ];

    protected $appends = [
        'available_qty',
    ];

    public function getAvailableQtyAttribute()
    {
        $approvedQty = $this->requests()
            ->whereIn('status', ['approved', 'completed'])
            ->sum('qty');

        return max(0, (int)$this->qty - (int)$approvedQty);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function requests()
    {
        return $this->hasMany(AuctionRequest::class, 'auction_product_id');
    }
}
