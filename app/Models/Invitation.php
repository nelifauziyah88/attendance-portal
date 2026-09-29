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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
