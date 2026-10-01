<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LuckySpin extends Model
{
    protected $fillable = ['badge_id', 'prize_id', 'winner', 'won_at'];

    public function user()
    {
        return $this->belongsTo(MasterAttendance::class, 'badge_id', 'badge_id');
    }

    public function prize()
    {
        return $this->belongsTo(Prize::class);
    }
}