<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $table = 'visitors';

    protected $fillable = [
        'ip_address',
        'country',
        'country_code',
        'city',
        'region',
        'latitude',
        'longitude',
        'isp',
        'browser',
        'browser_version',
        'device',
        'os',
        'landing_page',
        'last_page',
        'referrer',
        'total_visits',
        'last_visit_at',
    ];

    protected $casts = [
        'last_visit_at' => 'datetime',
    ];

    public function pageViews()
    {
        return $this->hasMany(PageView::class, 'visitor_id');
    }
}
