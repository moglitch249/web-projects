<?php
if (!defined('ABSPATH')) exit;

function wplm_generate_license($length = 16) {
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $license = '';
    for ($i = 0; $i < $length; $i++) {
        $license .= $chars[wp_rand(0, strlen($chars) - 1)];
    }
    return $license;
}
