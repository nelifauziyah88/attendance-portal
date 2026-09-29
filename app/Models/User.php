<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    const UPDATED_AT = null;

    protected $dateFormat = 'Y-m-d H:i:s.uP';

    protected $table = 'users';

    protected $fillable = [
        'badge_id',
        'name',
        'department',
        'position',
    ];

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }
}
