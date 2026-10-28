<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'billing_full_name',
        'billing_mobile',
        'province_id',
        'city_id',
        'billing_address',
        'billing_postal_code',
        'billing_extra_description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
