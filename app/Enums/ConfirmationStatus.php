<?php

namespace App\Enums;

enum ConfirmationStatus: string
{
    case Pending = 'PENDING';
    case Hadir = 'HADIR';
    case TidakHadir = 'TIDAK_HADIR';
}
