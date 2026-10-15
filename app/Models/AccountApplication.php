<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountApplication extends Model
{
    use HasFactory;

    protected $table = 'account_applications';

    protected $fillable = [
        'contact_person_name',
        'full_legal_name',
        'trading_name',
        'business_type',
        'company_registration_no',
        'vat_number',
        'registered_address',
        'trading_address',
        'telephone',
        'email',
        'website',
        'status',
        'admin_notes',
    ];
}
