<?php

namespace App\Services;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QrCodeService
{
    private const MARGIN = 10;

    public function png(string $content, int $size = 300): string
    {
        $qrCode = new QrCode(data: $content, size: $size - (self::MARGIN * 2), margin: self::MARGIN);

        return (new PngWriter())->write($qrCode)->getString();
    }
}
