<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionStep extends Model
{
    use HasFactory;

    protected $table = 'auction_steps';

    protected $fillable = [
        'step_number',
        'title',
        'description',
        'sort_order',
        'status',
    ];
}
