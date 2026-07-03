<?php
/**
 * Plugin Name: Tranter Engine
 * Description: Sales-focused, market-aware website engine for Tranter IT. Custom admin app, shortcodes, services, partner solutions and market routing.
 * Version: 1.6.0
 * Author: Tranter IT
 */

if (!defined('ABSPATH')) exit;

define('TRANTER_ENGINE_VERSION', '1.6.0');
define('TRANTER_ENGINE_FILE', __FILE__);
define('TRANTER_ENGINE_PATH', plugin_dir_path(__FILE__));
define('TRANTER_ENGINE_URL', plugin_dir_url(__FILE__));

require_once TRANTER_ENGINE_PATH . 'includes/class-tranter-engine.php';

register_activation_hook(__FILE__, ['Tranter_Engine', 'activate']);

// Safety net: register Tranter custom post types as early as possible on every request.
// This prevents WordPress admin URLs like post-new.php?post_type=tranter_insight
// from failing with "Invalid post type" if the main plugin bootstrap loads later than expected.
add_action('init', function () {
    if (!post_type_exists('tranter_insight')) {
        require_once TRANTER_ENGINE_PATH . 'includes/helpers.php';
        require_once TRANTER_ENGINE_PATH . 'includes/post-types.php';
        if (class_exists('Tranter_Post_Types')) {
            Tranter_Post_Types::register();
        }
    }
}, 0);

add_action('plugins_loaded', ['Tranter_Engine', 'init']);
