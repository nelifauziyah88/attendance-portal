<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = ['badge_id'];

    public function user()
    {
        return $this->belongsTo(MasterAttendance::class, 'badge_id', 'badge_id');
    }
}