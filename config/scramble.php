<?php

use App\OpenApi\ValidationExceptionToResponseExtension;

return [
    'api_path' => 'api',

    'info' => [
        'version' => env('API_VERSION', '1.0.0'),
        'description' => 'REST API untuk pendaftaran peserta, undangan, dan konfirmasi kehadiran.',
    ],

    'extensions' => [
        ValidationExceptionToResponseExtension::class,
    ],

    'ui' => [
        'title' => 'Attendance Portal API',
    ],
];
