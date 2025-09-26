<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasUuid;
    /**
     * @var string
     */
        protected $table = 'products';

    public $incrementing = false;
    
    protected $keyType = 'string';

    public $timestamps = true;

    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    protected $fillable = [
        'name',
        'description',
        'price',
        'purchasePrice',
        'stock',
        'imageUrl',
        'status',
        'CategoryId',
        'TypeId',
    ];

    //Un producto puede pertenecer a una categoria
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'CategoryId', 'id');
    }
    //Un producto puede tener un typo
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'TypeId', 'id');
    }
}
