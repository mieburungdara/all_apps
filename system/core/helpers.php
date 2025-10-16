<?php

if (!function_exists('base_url')) {
    function base_url($uri = '') {
        // Get the config instance
        $config = Config::getInstance();
        $app_config = $config->load('app');
        
        $base_url = $app_config['base_url'];
        
        return rtrim($base_url, '/') . '/' . ltrim($uri, '/');
    }
}

if (!function_exists('asset_url')) {
    function asset_url($path = '') {
        // This function assumes assets are always at the root level's public folder
        // We can make this more dynamic later if needed.
        return '/' . 'assets/' . ltrim($path, '/');
    }
}
