<?php
if (!defined('ABSPATH')) exit;

class Tranter_Market {
    public static function current() {
        if (isset($_GET['tranter_market'])) {
            $chosen = sanitize_key($_GET['tranter_market']);
            if (in_array($chosen, ['ng', 'global'], true)) {
                setcookie('tranter_market', $chosen, time() + MONTH_IN_SECONDS, COOKIEPATH ?: '/', COOKIE_DOMAIN ?: '');
                $_COOKIE['tranter_market'] = $chosen;
                return $chosen;
            }
        }
        if (!empty($_COOKIE['tranter_market']) && in_array($_COOKIE['tranter_market'], ['ng', 'global'], true)) {
            return sanitize_key($_COOKIE['tranter_market']);
        }
        $country = self::country_code();
        return $country === 'NG' ? 'ng' : 'global';
    }

    public static function country_code() {
        $headers = [
            'HTTP_CF_IPCOUNTRY',
            'HTTP_X_COUNTRY_CODE',
            'GEOIP_COUNTRY_CODE',
            'HTTP_CLOUDFRONT_VIEWER_COUNTRY',
        ];
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) return strtoupper(substr(sanitize_text_field($_SERVER[$header]), 0, 2));
        }
        return 'US';
    }
}
