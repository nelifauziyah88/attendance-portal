<?php

return [
    'base_url' => rtrim(env('INVITATION_BASE_URL', 'http://localhost:3000/invitation'), '/'),
    'event_name' => env('INVITATION_EVENT_NAME', 'Acara Seatrium Batam'),
    'capacity' => (int) env('INVITATION_CAPACITY', 990),
];
