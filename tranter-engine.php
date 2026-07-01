<?php
/**
 * Plugin Name: Tranter Engine
 * Description: Sales-focused, market-aware website engine for Tranter IT. Custom admin app, shortcodes, services, partner solutions and market routing.
 * Version: 1.2.0
 * Author: Tranter IT
 */

if (!defined('ABSPATH')) exit;

define('TRANTER_ENGINE_VERSION', '1.2.0');
define('TRANTER_ENGINE_FILE', __FILE__);
define('TRANTER_ENGINE_PATH', plugin_dir_path(__FILE__));
define('TRANTER_ENGINE_URL', plugin_dir_url(__FILE__));

require_once TRANTER_ENGINE_PATH . 'includes/class-tranter-engine.php';

register_activation_hook(__FILE__, ['Tranter_Engine', 'activate']);
add_action('plugins_loaded', ['Tranter_Engine', 'init']);
