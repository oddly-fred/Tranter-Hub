<?php
if (!defined('ABSPATH')) exit;

class Tranter_Engine {
    public static function init() {
        require_once TRANTER_ENGINE_PATH . 'includes/helpers.php';
        require_once TRANTER_ENGINE_PATH . 'includes/post-types.php';
        require_once TRANTER_ENGINE_PATH . 'includes/market.php';
        require_once TRANTER_ENGINE_PATH . 'includes/admin-app.php';
        require_once TRANTER_ENGINE_PATH . 'includes/shortcodes.php';
        require_once TRANTER_ENGINE_PATH . 'includes/rest-api.php';
        require_once TRANTER_ENGINE_PATH . 'includes/seeder.php';
        require_once TRANTER_ENGINE_PATH . 'includes/insights.php';
        require_once TRANTER_ENGINE_PATH . 'includes/permissions.php';

        Tranter_Post_Types::init();
        Tranter_Admin_App::init();
        Tranter_Shortcodes::init();
        Tranter_REST_API::init();
        Tranter_Insights::init();
        Tranter_Permissions::init();
        Tranter_Seeder::ensure_sections();
        if (method_exists('Tranter_Seeder', 'migrate_106')) Tranter_Seeder::migrate_106();
        if (method_exists('Tranter_Seeder', 'migrate_109')) Tranter_Seeder::migrate_109();
        if (method_exists('Tranter_Seeder', 'migrate_110')) Tranter_Seeder::migrate_110();
    }

    public static function activate() {
        require_once TRANTER_ENGINE_PATH . 'includes/helpers.php';
        require_once TRANTER_ENGINE_PATH . 'includes/post-types.php';
        require_once TRANTER_ENGINE_PATH . 'includes/seeder.php';
        Tranter_Post_Types::register();
        Tranter_Seeder::seed();
        if (method_exists('Tranter_Seeder', 'migrate_106')) Tranter_Seeder::migrate_106();
        if (method_exists('Tranter_Seeder', 'migrate_109')) Tranter_Seeder::migrate_109();
        if (method_exists('Tranter_Seeder', 'migrate_110')) Tranter_Seeder::migrate_110();
        flush_rewrite_rules();
    }
}
