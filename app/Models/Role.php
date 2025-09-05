<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
 //
        /**
     * @var string
     */
    protected $table = 'Role';

    //Un usuario puede ser de varios tipos de rol
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
    }
    
}
