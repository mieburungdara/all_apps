<?php

if (!function_exists('xss_clean')) {
    /**
     * Basic XSS cleaning function.
     * This is a simple implementation and can be expanded.
     *
     * @param  string|array $data The data to clean.
     * @return string|array The cleaned data.
     */
    function xss_clean($data) {
        if (is_array($data)) {
            return array_map('xss_clean', $data);
        }

        // 1. Convert special characters to HTML entities
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

        // 2. Remove tags
        $data = strip_tags($data);

        // 3. Remove unwanted attributes (simple regex, can be improved)
        $data = preg_replace('/(\<[^>]+) style=".*?"/i', '$1', $data);
        $data = preg_replace('/(\<[^>]+) onclick=".*?"/i', '$1', $data);
        $data = preg_replace('/(\<[^>]+) onmouseover=".*?"/i', '$1', $data);

        return $data;
    }
}
