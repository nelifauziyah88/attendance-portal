<?php

return [
    'base_url' => rtrim(env('INVITATION_BASE_URL', 'http://localhost:3000/invitation'), '/'),
    'capacity' => (int) env('INVITATION_CAPACITY', 990),
];
