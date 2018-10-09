<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    //Model relationships ke order_detail menggunakan hasMany
    public function order_detail()
    {
        return $this->hasMany(order_detail::class);
    }
}
