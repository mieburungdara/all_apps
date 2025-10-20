<?php

return [
    'client_id'     => 'YOUR_GOOGLE_CLIENT_ID',
    'client_secret' => 'YOUR_GOOGLE_CLIENT_SECRET',
    'redirect_uri'  => 'YOUR_GOOGLE_REDIRECT_URI', // e.g., http://localhost:8000/sekolah/users/auth/google
    'scopes'        => [
        'email',
        'profile',
        'https://www.googleapis.com/auth/userinfo.email',
        'https://www.googleapis.com/auth/userinfo.profile',
    ],
];
