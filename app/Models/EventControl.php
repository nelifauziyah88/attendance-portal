<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventControl extends Model
{
    protected $table = 'event_control';
    public $timestamps = false;
    protected $fillable = [
        'event_start',
        'event_end',
    ];

    protected $casts = [
        'event_start' => 'datetime',
        'event_end' => 'datetime',
    ];
}