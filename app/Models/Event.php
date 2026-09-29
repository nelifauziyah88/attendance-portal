<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $dateFormat = 'Y-m-d H:i:sP';

    protected $table = 'events';

    protected $fillable = [
        'slug',
        'name',
        'description',
        'location',
        'event_date',
        'start_time',
        'end_time',
        'capacity',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
        ];
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    public function url(): string
    {
        return config('invitation.base_url').'/'.rawurlencode($this->slug);
    }
}
