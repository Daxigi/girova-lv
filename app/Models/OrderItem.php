<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    //
        /**
     * @var string
     */
    protected $table = 'OrderItem';

    public $incrementing = false;
   
    protected $keyType = 'string';  

    //Un item pertenece a una orden
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
    // un item esta asociado a un producto
    public function product(): BelongsTo
    {
        return $this->belognsTo(Product::class, 'product_id', 'id');
    }
}
