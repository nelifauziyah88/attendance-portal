<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    // Tentukan koneksi database khusus untuk model ini
    protected $connection = 'pgsql_portal';
    protected $table = 'users';

    protected $fillable = [
        'badge_id',
        'name',
        'email',
        'password',
        'department',
        'position',
        'role',
    ];
}


// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\HasOne;

// class User extends Model
// {
//     const UPDATED_AT = null;

//     protected $dateFormat = 'Y-m-d H:i:s.uP';

//     protected $table = 'users';

//     protected $fillable = [
//         'badge_id',
//         'name',
//         'department',
//         'position',
//         'email',
//     ];

//     public function invitation(): HasOne
//     {
//         return $this->hasOne(Invitation::class);
//     }
// }
