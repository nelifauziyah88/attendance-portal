<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['badge_id', 'check_in_at'];

    protected $casts = ['check_in_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class, 'badge_id', 'badge_id');
    }
}