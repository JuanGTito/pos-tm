<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone',
        'mobile',
        'email',
        'website',
        'nit_ruc',
        'business_type',
        'receipt_series',
        'logo',
        'system_icon',
        'district',
        'province',
        'department',
        'country',
    ];
}
