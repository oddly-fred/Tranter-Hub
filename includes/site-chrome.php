<?php
if (!defined('ABSPATH')) exit;

class Tranter_Site_Chrome {
    public static function init() {
        add_shortcode('te_site_header', [__CLASS__, 'header_shortcode']);
        add_shortcode('te_site_footer', [__CLASS__, 'footer_shortcode']);
        add_shortcode('te_header', [__CLASS__, 'header_shortcode']);
        add_shortcode('te_footer', [__CLASS__, 'footer_shortcode']);
    }

    public static function header_shortcode($atts = []) {
        $html = self::load_template('site-header.html');
        return self::apply_market_rules($html, 'header');
    }

    public static function footer_shortcode($atts = []) {
        $html = self::load_template('site-footer.html');
        return self::apply_market_rules($html, 'footer');
    }

    private static function load_template($file) {
        $path = TRANTER_ENGINE_PATH . 'templates/' . $file;
        if (!file_exists($path)) return '';
        return file_get_contents($path);
    }

    private static function current_market() {
        if (class_exists('Tranter_Market')) return Tranter_Market::current();
        return 'ng';
    }

    private static function apply_market_rules($html, $context = 'header') {
        $market = self::current_market();

        // The supplied header/footer are the canonical Tranter site chrome.
        // Keep Campaigns out of navigation by default. Campaigns remain direct landing pages.
        $html = str_replace(['>Campaigns<', '>Campaign<'], '><', $html);

        // Central navigation map. Every new public page should be added here so
        // header/footer links remain connected, including WordPress subdirectory installs.
        $html = self::normalize_site_links($html);

        if ($context === 'footer') {
            $html = self::connect_footer_service_links($html, $market);
        }

        if ($market !== 'ng') {
            // Global visitors should not see Nigeria/event-specific navigation by default.
            $html = self::remove_anchor_by_href($html, '/event/');
            $html = self::remove_anchor_by_href($html, '/wp/event/');
            $html = self::remove_anchor_by_href($html, '/itgOV-2026/');
            $html = self::remove_anchor_by_href($html, '/itgov-2026/');
            $html = self::remove_anchor_by_href($html, '/wp/itgov-2026/');
            $html = self::remove_anchor_by_text($html, 'Events & Webinars');

            // Smart Solutions is Nigeria-only.
            $html = self::remove_anchor_by_href($html, '/smart-solutions/');
            $html = self::remove_anchor_by_href($html, '/wp/smart-solutions/');
            $html = self::remove_anchor_by_text($html, 'Smart Solutions');

            // Keep the footer globally relevant while preserving the supplied design.
            $html = str_replace('3-6 Alhaji Adejumo Avenue, Ilupeju, Lagos, Nigeria.', 'Remote global delivery with onsite engagement where required.', $html);
        }

        return $html;
    }

    private static function normalize_site_links($html) {
        $links = [
            '/who-we-are/' => '/wp/who-we-are/',
            '/company/' => '/wp/who-we-are/',
            '/about/' => '/wp/who-we-are/',
            '/services/' => '/wp/what-we-do/',
            '/solutions/' => '/wp/what-we-do/',
            '/what-we-do/' => '/wp/what-we-do/',
            '/it-support-services/' => '/wp/it-support-services/',
            '/smart-solutions/' => '/wp/smart-solutions/',
            '/cybersecurity/' => '/wp/cybersecurity/',
            '/hr-support-services/' => '/wp/hr-support-services/',
            '/business-process-outsourcing/' => '/wp/business-process-outsourcing/',
            '/website-development-optimization/' => '/wp/website-development-optimization/',
            '/digital-marketing-brand-development/' => '/wp/digital-marketing-brand-development/',
            '/knowledge-hub/' => '/wp/insights/',
            '/insights/' => '/wp/insights/',
            '/resources/' => '/wp/insights/',
            '/contact/' => '/wp/contact/',
            '/event/' => '/wp/event/',
            '/privacy-policy/' => '/wp/privacy-policy/',
            '/quality-statement-policy/' => '/wp/quality-statement-policy/',
            '/information-security-policy/' => '/wp/information-security-policy/',
            '/service-management-policy/' => '/wp/service-management-policy/',
            '/manageengine/' => '/wp/manageengine/',
            '/zoho-solutions/' => '/wp/zoho-solutions/',
            '/sophos/' => '/wp/sophos/',
            '/partners/' => '/wp/partners/',
        ];

        foreach ($links as $from => $to) {
            $html = str_replace('href="' . $from . '"', 'href="' . $to . '"', $html);
            $html = str_replace("href='" . $from . "'", "href='" . $to . "'", $html);
        }

        // Public naming correction: the Services page is now labelled What We Do.
        $html = str_replace('View all services →', 'View What We Do →', $html);
        $html = str_replace('View All Services', 'View What We Do', $html);

        // Public naming correction: Knowledge Hub links should lead to the active Insights page.
        $html = str_replace('Knowledge Hub', 'Insights', $html);
        $html = str_replace('View All Insights', 'View Insights', $html);
        $html = str_replace('View all insights →', 'View Insights →', $html);

        return $html;
    }

    private static function connect_footer_service_links($html, $market = 'ng') {
        $smart_link = $market === 'ng' ? '<a href="/wp/smart-solutions/">Smart Solutions</a>' : '';
        $services = '<nav class="tfc-links">' .
            '<a href="/wp/it-support-services/">IT Support Services</a>' .
            $smart_link .
            '<a href="/wp/hr-support-services/">HR Support Services</a>' .
            '<a href="/wp/digital-marketing-brand-development/">Digital Marketing & Brand Development</a>' .
            '<a href="/wp/what-we-do/">View What We Do</a>' .
            '</nav>';

        return preg_replace('~(<h3>Services</h3>\s*)<nav class="tfc-links">.*?</nav>~is', '$1' . $services, $html, 1);
    }

    private static function remove_anchor_by_href($html, $href) {
        $quoted = preg_quote($href, '~');
        // Remove simple anchor tags matching the href. This covers top links, dropdown links and mobile links.
        return preg_replace('~<a\b[^>]*href=["\']' . $quoted . '["\'][^>]*>.*?</a>~is', '', $html);
    }

    private static function remove_anchor_by_text($html, $text) {
        $quoted = preg_quote($text, '~');
        return preg_replace('~<a\b[^>]*>\s*' . $quoted . '.*?</a>~is', '', $html);
    }
}
