<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LuckySpin extends Model
{
    public const FORFEITED_AT = '1970-01-01 00:00:00';

    protected $fillable = ['badge_id', 'prize_id', 'won_at'];

    public function scopeActiveWinner($query)
    {
        return $query->where('won_at', '>', self::FORFEITED_AT);
    }

    public function masterAttendance()
    {
        return $this->belongsTo(MasterAttendance::class, 'badge_id', 'badge_id');
    }

    public function user()
    {
        return $this->belongsTo(MasterAttendance::class, 'badge_id', 'badge_id');
    }

    public function prize()
    {
        return $this->belongsTo(Prize::class);
    }
}