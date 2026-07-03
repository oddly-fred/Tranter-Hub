<?php
if (!defined('ABSPATH')) exit;

class Tranter_Market {
    public static function current() {
        // Explicit preview/testing override. This is useful for admins and QA links.
        if (isset($_GET['tranter_market'])) {
            $chosen = sanitize_key($_GET['tranter_market']);
            if (in_array($chosen, ['ng', 'global'], true)) {
                setcookie('tranter_market', $chosen, time() + MONTH_IN_SECONDS, COOKIEPATH ?: '/', COOKIE_DOMAIN ?: '');
                $_COOKIE['tranter_market'] = $chosen;
                return $chosen;
            }
        }

        $country = self::country_code();

        // If the hosting/CDN layer clearly tells us the visitor is in Nigeria, always show Nigeria content.
        // This prevents an old preview cookie from hiding Nigeria-only services from Nigerian visitors.
        if ($country === 'NG') return 'ng';

        // If the hosting/CDN layer clearly tells us the visitor is outside Nigeria, show the global experience.
        if ($country && $country !== 'NG') return 'global';

        // Cookie is only used when no reliable GeoIP header is available.
        if (!empty($_COOKIE['tranter_market']) && in_array($_COOKIE['tranter_market'], ['ng', 'global'], true)) {
            return sanitize_key($_COOKIE['tranter_market']);
        }

        // Nigeria-first fallback. If GeoIP headers are missing, do not hide Nigeria-only content by mistake.
        return 'ng';
    }

    public static function country_code() {
        $headers = [
            'HTTP_CF_IPCOUNTRY',
            'HTTP_X_COUNTRY_CODE',
            'HTTP_X_GEO_COUNTRY',
            'HTTP_X_APPENGINE_COUNTRY',
            'GEOIP_COUNTRY_CODE',
            'HTTP_GEOIP_COUNTRY_CODE',
            'HTTP_CLOUDFRONT_VIEWER_COUNTRY',
            'HTTP_X_VERCEL_IP_COUNTRY',
            'HTTP_X_NETLIFY_COUNTRY',
            'HTTP_FASTLY_CLIENT_GEO_COUNTRY_CODE',
        ];

        foreach ($headers as $header) {
            if (empty($_SERVER[$header])) continue;
            $country = strtoupper(substr(sanitize_text_field(wp_unslash($_SERVER[$header])), 0, 2));
            if (preg_match('/^[A-Z]{2}$/', $country)) return $country;
        }

        // Unknown. Returning an empty string allows current() to use Nigeria-first fallback safely.
        return '';
    }
}
