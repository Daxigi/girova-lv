<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
        /**
     * @var string
     */
    protected $table = 'Product';

    public $incrementing = false;
    
    protected $keyType = 'string';

    //Un producto puede pertenecer a una categoria
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    //Un producto puede tener un typo
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'type_id', 'id');
    }
}
