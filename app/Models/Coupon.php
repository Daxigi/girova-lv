<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    //
        /**
     * @var string
     */
    protected $table = 'Coupon';

    public $incrementing = false;
    
    protected $keyType = 'string';
}
