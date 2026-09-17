<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionRequest extends Model
{
    use HasFactory;

    protected $table = 'auction_requests';

    protected $fillable = [
        'auction_product_id',
        'name',
        'email',
        'phone',
        'bid_price',
        'qty',
        'message',
        'status',
    ];

    protected $casts = [
        'bid_price' => 'float',
    ];

    public function auctionProduct()
    {
        return $this->belongsTo(AuctionProduct::class, 'auction_product_id');
    }
}
