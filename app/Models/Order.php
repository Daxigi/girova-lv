<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
        /**
     * @var string
     */
    protected $table = 'Order';

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

    // Un pedido pertenece a un comprador
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    //Un pedido puede tener muchos items
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
    //Un pedido puede tener muchas formas de pago
    public function payment(): HasMany
    {
        return $this->hasMany(Payment::class, 'order_id', 'id');
    }
    //Un pedido puede tener un cupon
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id', 'id');
    }
}
