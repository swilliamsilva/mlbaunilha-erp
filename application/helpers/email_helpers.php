<?php
if (!function_exists('obfuscate_email')) {
    function obfuscate_email($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            list($name, $domain) = explode('@', $email);
            return substr($name, 0, 2) . '****@' . $domain;
        }
        return '****@****.***';
    }
}
