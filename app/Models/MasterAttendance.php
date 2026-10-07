<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function attendance(): HasOne
    {
        return $this->hasOne(Attendance::class, 'badge_id', 'badge_id');
    }
}