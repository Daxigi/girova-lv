<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
        /**
     * @var string
     */
    protected $table = 'Customer';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    //Un cliente puede tener muchas direcciones
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'customer_id', 'id');
    }
    //Un cliente puede tener muchas ordenes
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class. 'customer_id', 'id');
    }
}
