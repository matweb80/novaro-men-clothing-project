<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['address_id', 'status', 'price', 'shipping_price', 'tracking_serial'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot(['quantity']);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
