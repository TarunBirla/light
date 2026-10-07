<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionFaq extends Model
{
    use HasFactory;

    protected $table = 'auction_faqs';

    protected $fillable = [
        'question',
        'answer',
        'sort_order',
        'status',
    ];
}
