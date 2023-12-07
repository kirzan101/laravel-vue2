<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description'
    ];

    /**
     * returns the list of permissions by user group id
     *
     * @return HasMany
     */
    public function userGroupPermissions() : HasMany
    {
        return $this->hasMany(UserGroupPermission::class);
    }
}
