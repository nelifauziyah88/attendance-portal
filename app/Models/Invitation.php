<?php

namespace App\Models;

use App\Enums\ConfirmationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitation extends Model
{
    const UPDATED_AT = null;

    protected $dateFormat = 'Y-m-d H:i:s.uP';

    protected $table = 'invitations';

    protected $fillable = [
        'event_id',
        'user_id',
        'confirmation_status',
        'confirmed_at',
    ];

    protected function casts(): array
    {
        return [
            'confirmation_status' => ConfirmationStatus::class,
            'confirmed_at' => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
