<?php

return [
    'users/login' => 'users/Users/login',
    'users/register' => 'users/Users/register',
    'users/logout' => 'users/Users/logout',
    'users/auth/login_google' => 'users/Auth/login_google',
    'google/callback' => 'users/auth/callback',
    'notifications/mark_as_read/(:num)' => 'notifications/notifications/mark_as_read/$1',
];
