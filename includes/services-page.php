<?php
if (!defined('ABSPATH')) exit;

class Tranter_Services_Page {
    public static function init() {
        $shortcodes = [
            'te_services_page' => 'page',
            'te_solutions_page' => 'page',
            'te_services_hero' => 'hero',
            'te_services_overview' => 'overview',
            'te_solution_categories' => 'categories',
            'te_managed_it_services' => 'managed_it',
            'te_cybersecurity_services' => 'cybersecurity',
            'te_cloud_infrastructure' => 'cloud',
            'te_smart_solutions' => 'smart_solutions',
            'te_services_cta' => 'cta',
        ];

        foreach ($shortcodes as $tag => $method) {
            add_shortcode($tag, [__CLASS__, $method]);
        }
    }

    private static function enqueue() {
        wp_enqueue_style('tranter-engine-public-font', 'https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap', [], null);
        wp_enqueue_style('tranter-engine-services-page', TRANTER_ENGINE_URL . 'assets/css/services-page.css', [], TRANTER_ENGINE_VERSION);
    }

    private static function market($atts = []) {
        return isset($atts['market']) ? sanitize_key($atts['market']) : (class_exists('Tranter_Market') ? Tranter_Market::current() : 'ng');
    }

    private static function service_post($slug, $market) {
        $post = get_page_by_path(sanitize_title($slug), OBJECT, 'tranter_service');
        if ($post && function_exists('tranter_engine_market_enabled') && !tranter_engine_market_enabled($post->ID, $market)) return null;
        return $post;
    }

    private static function section($key, $market, $fallback_title, $fallback_heading, $fallback_copy) {
        $posts = get_posts([
            'post_type' => 'tranter_section',
            'numberposts' => 1,
            'post_status' => 'publish',
            'meta_query' => [[
                'key' => '_tranter_section_key',
                'value' => sanitize_title($key),
            ]],
        ]);

        if (!$posts) {
            $page = get_page_by_path(sanitize_title($key), OBJECT, 'tranter_section');
            $posts = $page ? [$page] : [];
        }

        $post = $posts ? $posts[0] : null;
        if ($post && function_exists('tranter_engine_market_enabled') && tranter_engine_market_enabled($post->ID, $market)) {
            return [
                'title' => $post->post_title ?: $fallback_title,
                'heading' => $post->post_excerpt ?: $fallback_heading,
                'copy' => wp_strip_all_tags($post->post_content) ?: $fallback_copy,
            ];
        }

        return ['title' => $fallback_title, 'heading' => $fallback_heading, 'copy' => $fallback_copy];
    }

    public static function page($atts = []) {
        self::enqueue();
        $market = self::market($atts);
        ob_start();
        echo '<div id="tranter-services-page" class="tes-page">';
        echo self::hero(['market' => $market]);
        echo self::overview(['market' => $market]);
        echo self::categories(['market' => $market]);
        echo self::managed_it(['market' => $market]);
        echo self::cybersecurity(['market' => $market]);
        echo self::cloud(['market' => $market]);
        echo self::smart_solutions(['market' => $market]);
        echo self::cta(['market' => $market]);
        echo '</div>';
        return ob_get_clean();
    }

    public static function hero($atts = []) {
        self::enqueue();
        $market = self::market($atts);
        $s = self::section('services-hero', $market, 'Tranter IT Services', 'Enterprise technology services built for performance, security and growth.', 'From managed IT and cybersecurity to cloud infrastructure and smart solutions, Tranter helps organisations simplify operations, strengthen resilience and convert technology into measurable business value.');
        ob_start(); ?>
        <section class="tes-hero tes-full">
            <div class="tes-hero-bg"></div>
            <div class="tes-container tes-hero-grid">
                <div class="tes-hero-copy">
                    <span class="tes-eyebrow"><?php echo esc_html($s['title']); ?></span>
                    <h1><?php echo esc_html($s['heading']); ?></h1>
                    <p><?php echo esc_html($s['copy']); ?></p>
                    <div class="tes-actions">
                        <a class="tes-btn tes-btn-primary" href="/contact/" data-te-open-demo>Book a Consultation</a>
                        <a class="tes-btn tes-btn-ghost" href="#tes-solutions">Explore Solutions</a>
                    </div>
                </div>
                <div class="tes-command-card" aria-label="Services command centre visual">
                    <div class="tes-command-top"><span></span><span></span><span></span><strong>Solution Command Centre</strong></div>
                    <div class="tes-command-body">
                        <div><strong>24/7</strong><span>Managed Support</span></div>
                        <div><strong>Secure</strong><span>Cybersecurity</span></div>
                        <div><strong>Cloud</strong><span>Infrastructure</span></div>
                        <div><strong>Smart</strong><span>Solutions</span></div>
                    </div>
                    <div class="tes-signal"><i></i><i></i><i></i><i></i><i></i></div>
                </div>
            </div>
        </section>
        <?php return ob_get_clean();
    }

    public static function overview($atts = []) {
        self::enqueue();
        $market = self::market($atts);
        $s = self::section('services-overview', $market, 'What We Deliver', 'A practical service ecosystem for modern organisations.', 'Tranter combines advisory thinking, technical deployment and managed operations to help organisations run stronger, safer and more efficient technology environments.');
        $points = [
            ['Diagnose', 'Understand the business, users, risks and operational gaps before recommending technology.'],
            ['Design', 'Shape the right solution architecture across infrastructure, security, cloud and workflow needs.'],
            ['Deploy', 'Implement with structured delivery, documentation and clear handover.'],
            ['Operate', 'Provide ongoing support, monitoring, optimisation and reporting.'],
        ];
        ob_start(); ?>
        <section class="tes-section">
            <div class="tes-container">
                <?php echo self::section_header($s['title'], $s['heading'], $s['copy']); ?>
                <div class="tes-overview-grid">
                    <?php foreach ($points as $index => $point): ?>
                        <article class="tes-card tes-step-card">
                            <span><?php echo esc_html(str_pad($index + 1, 2, '0', STR_PAD_LEFT)); ?></span>
                            <h3><?php echo esc_html($point[0]); ?></h3>
                            <p><?php echo esc_html($point[1]); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php return ob_get_clean();
    }

    public static function categories($atts = []) {
        self::enqueue();
        $market = self::market($atts);
        $s = self::section('solution-categories', $market, 'Solution Categories', 'Choose the service path that matches your growth priority.', 'Each service area is designed to support sales conversations, lead generation and practical decision-making for enterprise, public sector and growth-focused organisations.');
        $cards = [
            ['Managed IT Services', 'Reliable support, infrastructure management, endpoint operations and SLA-driven service delivery.', '#managed-it-services'],
            ['Cybersecurity Services', 'Threat visibility, access control, endpoint protection, governance and security readiness.', '#cybersecurity-services'],
            ['Cloud & Infrastructure', 'Cloud adoption, hosting, infrastructure optimisation, backup, continuity and scalable operations.', '#cloud-infrastructure'],
            ['Smart Solutions', 'IoT, smart buildings, automation, smart monitoring and connected operational intelligence.', '#smart-solutions'],
        ];
        ob_start(); ?>
        <section class="tes-section tes-soft" id="tes-solutions">
            <div class="tes-container">
                <?php echo self::section_header($s['title'], $s['heading'], $s['copy']); ?>
                <div class="tes-category-grid">
                    <?php foreach ($cards as $index => $card): ?>
                        <article class="tes-card tes-category-card">
                            <div class="tes-icon"><?php echo self::icon($index + 1); ?></div>
                            <h3><?php echo esc_html($card[0]); ?></h3>
                            <p><?php echo esc_html($card[1]); ?></p>
                            <a href="<?php echo esc_url($card[2]); ?>">View service area</a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php return ob_get_clean();
    }

    public static function managed_it($atts = []) {
        self::enqueue();
        return self::service_band('managed-it-services', self::market($atts), 'Managed IT Services', 'Keep your technology environment stable, visible and supported.', 'For organisations that need dependable daily technology operations, user support, infrastructure management and clear service accountability.', ['Helpdesk and end-user support', 'Endpoint and asset management', 'Infrastructure monitoring', 'SLA reporting and service visibility', 'Onsite and remote support coverage'], 'managed-it-services');
    }

    public static function cybersecurity($atts = []) {
        self::enqueue();
        return self::service_band('cybersecurity-services', self::market($atts), 'Cybersecurity Services', 'Strengthen protection across people, devices, systems and access.', 'We help organisations reduce exposure, improve security readiness and build a more governed approach to digital risk.', ['Endpoint protection and monitoring', 'Identity and access control', 'Vulnerability and risk reviews', 'Security awareness support', 'Policy and governance enablement'], 'cybersecurity-services', true);
    }

    public static function cloud($atts = []) {
        self::enqueue();
        return self::service_band('cloud-infrastructure', self::market($atts), 'Cloud & Infrastructure', 'Build scalable infrastructure that supports growth and continuity.', 'From cloud readiness to infrastructure optimisation, Tranter helps teams modernise without losing control of performance, security or cost.', ['Cloud migration and advisory', 'Server and network infrastructure', 'Backup and business continuity', 'Performance optimisation', 'Hybrid infrastructure support'], 'cloud-infrastructure');
    }

    public static function smart_solutions($atts = []) {
        self::enqueue();
        return self::service_band('smart-solutions', self::market($atts), 'Smart Solutions', 'Connect physical spaces, assets and operations with smarter visibility.', 'Tranter Smart Solutions supports intelligent buildings, smart monitoring, automation and connected systems for modern facilities and operations.', ['Smart building enablement', 'IoT monitoring and sensors', 'Energy and space intelligence', 'Automation workflows', 'Connected device support'], 'smart-solutions', true, true);
    }

    private static function service_band($section_key, $market, $fallback_title, $fallback_heading, $fallback_copy, $features, $service_slug, $reverse = false, $store_cta = false) {
        $s = self::section($section_key, $market, $fallback_title, $fallback_heading, $fallback_copy);
        $post = self::service_post($service_slug, $market);
        $heading = $post ? $post->post_title : $s['heading'];
        $copy = $post && $post->post_excerpt ? $post->post_excerpt : $s['copy'];
        $link = $post ? get_permalink($post) : '/contact/';
        ob_start(); ?>
        <section class="tes-section <?php echo $reverse ? 'tes-reverse' : ''; ?>" id="<?php echo esc_attr($section_key); ?>">
            <div class="tes-container tes-band-grid">
                <div class="tes-band-copy">
                    <span class="tes-eyebrow"><?php echo esc_html($s['title']); ?></span>
                    <h2><?php echo esc_html($heading); ?></h2>
                    <p><?php echo esc_html($copy); ?></p>
                    <ul class="tes-feature-list">
                        <?php foreach ($features as $feature): ?><li><?php echo esc_html($feature); ?></li><?php endforeach; ?>
                    </ul>
                    <div class="tes-actions tes-band-actions">
                        <a class="tes-btn tes-btn-primary" href="<?php echo esc_url($link); ?>">Learn More</a>
                        <?php if ($store_cta && $market === 'ng'): ?><a class="tes-btn tes-btn-light" href="https://shop.tranter-it.com/" target="_blank" rel="noopener">Visit Smart Solutions Store</a><?php endif; ?>
                    </div>
                </div>
                <div class="tes-band-visual">
                    <div class="tes-orbit"><span></span><span></span><span></span><strong><?php echo esc_html($fallback_title); ?></strong></div>
                </div>
            </div>
        </section>
        <?php return ob_get_clean();
    }

    public static function cta($atts = []) {
        self::enqueue();
        $market = self::market($atts);
        $s = self::section('services-cta', $market, 'Ready to Talk?', 'Let us recommend the right service path for your organisation.', 'Book a consultation with Tranter IT and turn your technology priorities into a clear, practical execution plan.');
        ob_start(); ?>
        <section class="tes-cta tes-full">
            <div class="tes-container">
                <span class="tes-eyebrow"><?php echo esc_html($s['title']); ?></span>
                <h2><?php echo esc_html($s['heading']); ?></h2>
                <p><?php echo esc_html($s['copy']); ?></p>
                <div class="tes-actions">
                    <a class="tes-btn tes-btn-primary" href="/contact/" data-te-open-demo>Book a Consultation</a>
                    <a class="tes-btn tes-btn-ghost" href="https://wa.me/2348183405221?text=Hello%20Tranter%20IT,%20I%20would%20like%20to%20speak%20to%20your%20team%20about%20your%20services.">Speak to Our Team</a>
                </div>
            </div>
        </section>
        <?php return ob_get_clean();
    }

    private static function section_header($title, $heading, $copy) {
        return '<header class="tes-section-header"><span class="tes-eyebrow">' . esc_html($title) . '</span><h2>' . esc_html($heading) . '</h2><p>' . esc_html($copy) . '</p></header>';
    }

    private static function icon($i) {
        $paths = [
            '<path d="M4 7h16M4 12h16M4 17h10"/><circle cx="18" cy="17" r="2"/>',
            '<path d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4z"/><path d="M9 12l2 2 4-5"/>',
            '<path d="M7 18a4 4 0 1 1 1-7 5 5 0 0 1 9 2 3 3 0 1 1 0 6H7z"/>',
            '<path d="M12 3v6M12 15v6M3 12h6M15 12h6"/><circle cx="12" cy="12" r="3"/>',
        ];
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $paths[($i - 1) % count($paths)] . '</svg>';
    }
}
