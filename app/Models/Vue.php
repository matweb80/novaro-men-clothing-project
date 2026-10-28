<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vue extends Model
{
    protected $table = 'vue_tests';

    protected $fillable = ['userName', 'rating'];
}
