<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Confirmation extends Model
{
    protected $fillable = ['badge_id', 'is_attending', 'confirmed_at'];

    public function user()
    {
        return $this->belongsTo(MasterAttendance::class, 'badge_id', 'badge_id');
    }
}