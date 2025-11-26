<?php

namespace App\Models;

use App\Models\Concerns\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasUuid, SoftDeletes;
    /**
     * @var string
     */
        protected $table = 'products';

    public $incrementing = false;
    
    protected $keyType = 'string';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'description',
        'price',
        'purchasePrice',
        'stock',
        'imageUrl',
        'status',
        'category_id',
        'type_id',
    ];

    /**
     * Atributos que deben agregarse a la serialización
     */
    protected $appends = ['image_url'];

    /**
     * Accessor para image_url (compatibilidad con snake_case)
     */
    public function getImageUrlAttribute()
    {
        return $this->attributes['imageUrl'] ?? null;
    }

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
