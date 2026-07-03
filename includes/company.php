<?php
if (!defined('ABSPATH')) exit;

class Tranter_Company {
    public static function init() {
        add_shortcode('te_about', [__CLASS__, 'about_shortcode']);
        add_shortcode('te_who_we_are_page', [__CLASS__, 'about_shortcode']);
        add_shortcode('te_company', [__CLASS__, 'about_shortcode']);

        // Section-level shortcodes are provided for backend guidance and future editing.
        // For the approved live design, use [te_about] so the page renders exactly as supplied.
        add_shortcode('te_about_hero', [__CLASS__, 'about_shortcode']);
        add_shortcode('te_company_story', [__CLASS__, 'about_shortcode']);
        add_shortcode('te_mission_vision', [__CLASS__, 'about_shortcode']);
        add_shortcode('te_company_stats', [__CLASS__, 'about_shortcode']);
        add_shortcode('te_core_values', [__CLASS__, 'about_shortcode']);
        add_shortcode('te_leadership', [__CLASS__, 'about_shortcode']);
        add_shortcode('te_about_faq', [__CLASS__, 'about_shortcode']);
        add_shortcode('te_about_cta', [__CLASS__, 'about_shortcode']);
    }

    public static function about_shortcode($atts = []) {
        $template = TRANTER_ENGINE_PATH . 'templates/company-who-we-are.html';
        if (!file_exists($template)) {
            return '<!-- Tranter Company template missing. -->';
        }
        return file_get_contents($template);
    }

    public static function shortcode_reference() {
        return [
            '[te_about]' => 'Full Who We Are page using the approved supplied design.',
            '[te_who_we_are_page]' => 'Alias for [te_about].',
            '[te_company]' => 'Alias for [te_about].',
            '[te_about_hero]' => 'Section placeholder currently mapped to approved full page output.',
            '[te_company_story]' => 'Section placeholder currently mapped to approved full page output.',
            '[te_mission_vision]' => 'Section placeholder currently mapped to approved full page output.',
            '[te_company_stats]' => 'Section placeholder currently mapped to approved full page output.',
            '[te_core_values]' => 'Section placeholder currently mapped to approved full page output.',
            '[te_leadership]' => 'Section placeholder currently mapped to approved full page output.',
            '[te_about_faq]' => 'Section placeholder currently mapped to approved full page output.',
            '[te_about_cta]' => 'Section placeholder currently mapped to approved full page output.',
        ];
    }
}
