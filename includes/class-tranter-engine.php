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
        require_once TRANTER_ENGINE_PATH . 'includes/newsletter.php';
        require_once TRANTER_ENGINE_PATH . 'includes/resources.php';
        require_once TRANTER_ENGINE_PATH . 'includes/knowledge-hub.php';
        require_once TRANTER_ENGINE_PATH . 'includes/campaigns.php';
        require_once TRANTER_ENGINE_PATH . 'includes/site-chrome.php';
        require_once TRANTER_ENGINE_PATH . 'includes/company.php';
        require_once TRANTER_ENGINE_PATH . 'includes/services-page.php';
        require_once TRANTER_ENGINE_PATH . 'includes/it-support-page.php';
        require_once TRANTER_ENGINE_PATH . 'includes/smart-solutions-page.php';

        Tranter_Post_Types::init();
        Tranter_Admin_App::init();
        Tranter_Shortcodes::init();
        Tranter_REST_API::init();
        Tranter_Insights::init();
        Tranter_Permissions::init();
        Tranter_Newsletter::init();
        Tranter_Resources::init();
        Tranter_Knowledge_Hub::init();
        Tranter_Campaigns::init();
        Tranter_Site_Chrome::init();
        Tranter_Company::init();
        Tranter_Services_Page::init();
        Tranter_IT_Support_Page::init();
        Tranter_Smart_Solutions_Page::init();
        Tranter_Seeder::ensure_sections();
        if (method_exists('Tranter_Seeder', 'migrate_106')) Tranter_Seeder::migrate_106();
        if (method_exists('Tranter_Seeder', 'migrate_109')) Tranter_Seeder::migrate_109();
        if (method_exists('Tranter_Seeder', 'migrate_110')) Tranter_Seeder::migrate_110();
    }

    public static function activate() {
        require_once TRANTER_ENGINE_PATH . 'includes/helpers.php';
        require_once TRANTER_ENGINE_PATH . 'includes/post-types.php';
        require_once TRANTER_ENGINE_PATH . 'includes/seeder.php';
        require_once TRANTER_ENGINE_PATH . 'includes/knowledge-hub.php';
        require_once TRANTER_ENGINE_PATH . 'includes/campaigns.php';
        require_once TRANTER_ENGINE_PATH . 'includes/site-chrome.php';
        require_once TRANTER_ENGINE_PATH . 'includes/company.php';
        require_once TRANTER_ENGINE_PATH . 'includes/services-page.php';
        require_once TRANTER_ENGINE_PATH . 'includes/it-support-page.php';
        require_once TRANTER_ENGINE_PATH . 'includes/smart-solutions-page.php';
        Tranter_Post_Types::register();
        Tranter_Seeder::seed();
        if (class_exists('Tranter_Knowledge_Hub')) Tranter_Knowledge_Hub::activation_setup();
        if (method_exists('Tranter_Seeder', 'migrate_106')) Tranter_Seeder::migrate_106();
        if (method_exists('Tranter_Seeder', 'migrate_109')) Tranter_Seeder::migrate_109();
        if (method_exists('Tranter_Seeder', 'migrate_110')) Tranter_Seeder::migrate_110();
        flush_rewrite_rules();
    }
}
