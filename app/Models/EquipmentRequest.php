<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gaffer',
        'email',
        'contact',
        'production_company',
        'production_title',
        'production_contact',
        'dop',
        'rig_from',
        'rig_to',
        'prelight_from',
        'prelight_to',
        'shoot_from',
        'shoot_to',
        'derig_from',
        'derig_to',
        'address_line_1',
        'address_line_2',
        'address_line_3_postcode',
        'location_address',
        'status'
    ];

    public function items()
    {
        return $this->hasMany(EquipmentRequestItem::class, 'equipment_request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
