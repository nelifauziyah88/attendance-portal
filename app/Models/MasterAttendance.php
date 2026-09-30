<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterAttendance extends Model
{
    protected $table = 'master_attendance';

    protected $fillable = [
        'badge_id',
        'name',
        'department',
        'position',
        'project',
        'company',
        'is_manager',
    ];

    protected function casts(): array
    {
        return ['is_manager' => 'boolean'];
    }
}