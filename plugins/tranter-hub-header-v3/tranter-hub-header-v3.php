<?php
/**
 * Plugin Name: Tranter Hub Header v3.0
 * Plugin URI: https://hipterraafrica.com/
 * Description: GeoIP-aware, analytics-enabled Tranter Hub Header v3.0 shortcode for Elementor.
 * Version: 3.0.0
 * Author: Tranter IT
 * Text Domain: tranter-hub-header-v3
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Tranter_Hub_Header_V3 {
    const VERSION = '3.0.0';
    private static $rendered = false;

    public static function init() {
        add_shortcode('tranter_hub_header', array(__CLASS__, 'shortcode'));
    }

    public static function shortcode($atts = array()) {
        $atts = shortcode_atts(array(
            'region'       => 'auto',
            'geo_endpoint' => '',
            'webhook'      => '',
            'analytics'    => 'true',
            'class'        => '',
        ), $atts, 'tranter_hub_header');

        if (self::$rendered) {
            return '';
        }
        self::$rendered = true;

        $region = strtolower(sanitize_text_field($atts['region']));
        if (!in_array($region, array('auto', 'ng', 'global'), true)) {
            $region = 'auto';
        }

        $geo_endpoint = esc_url_raw($atts['geo_endpoint']);
        $webhook = esc_url_raw($atts['webhook']);
        $analytics_enabled = strtolower(sanitize_text_field($atts['analytics'])) !== 'false';
        $extra_class = sanitize_html_class($atts['class']);
        $header_html = self::get_header_html();

        if (!$header_html) {
            return '<!-- Tranter Hub Header v3.0 template could not be loaded. -->';
        }

        ob_start();
        ?>
        <div class="tranter-hub-header-v3-plugin <?php echo esc_attr($extra_class); ?>" data-plugin-version="<?php echo esc_attr(self::VERSION); ?>">
            <script>
                window.TRANTER_HEADER_CONFIG = Object.assign({}, window.TRANTER_HEADER_CONFIG || {}, {
                    geo: Object.assign({}, (window.TRANTER_HEADER_CONFIG || {}).geo || {}, {
                        endpoint: <?php echo wp_json_encode($geo_endpoint ?: null); ?>
                    }),
                    analytics: Object.assign({}, (window.TRANTER_HEADER_CONFIG || {}).analytics || {}, {
                        enabled: <?php echo $analytics_enabled ? 'true' : 'false'; ?>,
                        customWebhook: <?php echo wp_json_encode($webhook ?: null); ?>
                    })
                });
                <?php if ($region !== 'auto') : ?>
                window.TRANTER_HEADER_REGION = <?php echo wp_json_encode($region); ?>;
                <?php endif; ?>
            </script>
            <?php echo $header_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
        <?php
        return ob_get_clean();
    }

    private static function get_header_html() {
        $chunk_dir = plugin_dir_path(__FILE__) . 'chunks/';
        $files = glob($chunk_dir . 'header-v3-*.txt');
        if (!$files) {
            return '';
        }
        sort($files, SORT_NATURAL);
        $encoded = '';
        foreach ($files as $file) {
            $encoded .= trim((string) file_get_contents($file));
        }
        $compressed = base64_decode($encoded, true);
        if (!$compressed || !function_exists('gzdecode')) {
            return '';
        }
        $html = gzdecode($compressed);
        return is_string($html) ? $html : '';
    }
}

Tranter_Hub_Header_V3::init();
