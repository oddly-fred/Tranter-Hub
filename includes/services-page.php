<?php
if (!defined('ABSPATH')) exit;

class Tranter_Services_Page {
    public static function init() {
        $shortcodes = [
            'te_what_we_do_page' => 'page',
            'te_services_page' => 'page',
            'te_solutions_page' => 'page',
            'te_services_hero' => 'hero',
            'te_what_we_do_hero' => 'hero',
            'te_services_overview' => 'overview',
            'te_solution_categories' => 'services_grid',
            'te_services_grid' => 'services_grid',
            'te_service_intelligence' => 'intelligence',
            'te_services_metrics' => 'metrics',
            'te_services_faq' => 'faq',
            'te_services_cta' => 'cta',
        ];

        foreach ($shortcodes as $tag => $method) add_shortcode($tag, [__CLASS__, $method]);
    }

    private static function enqueue() {
        wp_enqueue_style('tranter-engine-public-font', 'https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap', [], null);
        wp_enqueue_style('tranter-engine-services-page', TRANTER_ENGINE_URL . 'assets/css/services-page.css', [], TRANTER_ENGINE_VERSION);
    }

    public static function page($atts = []) {
        self::enqueue();
        ob_start();
        echo '<div id="tranter-services-page" class="twd-page">';
        echo self::hero($atts);
        echo self::services_grid($atts);
        echo self::intelligence($atts);
        echo self::metrics($atts);
        echo self::faq($atts);
        echo self::cta($atts);
        echo '</div>';
        return ob_get_clean();
    }

    public static function hero($atts = []) {
        self::enqueue();
        ob_start(); ?>
        <section class="twd-hero twd-full" id="what-we-do-hero">
            <div class="twd-hero-bg"></div>
            <div class="twd-container twd-hero-container">
                <div class="twd-hero-copy">
                    <span class="twd-pill">What We Do</span>
                    <h1>Business Technology.<span>Built to Scale.</span></h1>
                    <p>We help organisations run smarter, safer and faster through managed IT support, cybersecurity, automation, digital systems and AI-assisted service visibility.</p>
                    <div class="twd-actions">
                        <a class="twd-btn twd-btn-primary" href="#our-services">Explore Services</a>
                        <a class="twd-btn twd-btn-outline" href="https://api.whatsapp.com/send/?phone=2348183405221&text=Hello+Tranter+IT%2C+I+would+like+to+speak+to+your+team.&type=phone_number&app_absent=0" target="_blank" rel="noopener">Speak to Our Team</a>
                    </div>
                </div>
                <div class="twd-hero-panel">
                    <span class="twd-floating-chip">AI-assisted delivery</span>
                    <div class="twd-glass-card">
                        <div class="twd-card-label"><span>Service Intelligence</span><span class="twd-live"><i></i>Live Operations</span></div>
                        <div class="twd-mini-grid">
                            <?php foreach (self::hero_cards() as $card): ?>
                                <article class="twd-mini-card"><div class="twd-mini-icon"><?php echo self::icon($card[3]); ?></div><strong><?php echo esc_html($card[0]); ?></strong><span><?php echo esc_html($card[1]); ?></span></article>
                            <?php endforeach; ?>
                        </div>
                        <div class="twd-strategy-strip"><strong>We design operational systems.</strong><span>Not disconnected services — integrated capabilities that move your business forward.</span></div>
                    </div>
                </div>
            </div>
        </section>
        <?php return ob_get_clean();
    }

    public static function overview($atts = []) { return self::services_grid($atts); }

    public static function services_grid($atts = []) {
        self::enqueue();
        $services = [
            ['IT Support Services','Reliable infrastructure support, uptime management and service continuity across operating environments.','support'],
            ['Smart Solutions','Workflow automation and intelligent systems that reduce manual effort and improve decisions.','smart'],
            ['HR Support Services','Technology-enabled workforce operations for distributed teams and enterprise environments.','hr'],
            ['Digital Marketing & Brand','Enterprise-grade digital presence aligned to growth, credibility and customer acquisition.','brand'],
            ['Business Process Outsourcing','Managed operational systems that turn support functions into scalable execution models.','bpo'],
            ['Website Dev & Optimisation','High-performance web platforms built as commercial and operational infrastructure.','web'],
            ['Cybersecurity','Security-first operations that protect infrastructure, data and business continuity.','security'],
        ];
        ob_start(); ?>
        <section class="twd-section" id="our-services">
            <div class="twd-container">
                <?php echo self::header('Our Services', 'Technology Solutions Built for <span>Modern Business</span>', 'We focus on the services that directly improve performance, resilience, customer experience and operational control.'); ?>
                <div class="twd-services-grid">
                    <?php foreach ($services as $i => $service): ?>
                        <article class="twd-service-card <?php echo $service[2] === 'security' ? 'cyber' : ''; ?>">
                            <div class="twd-service-num"><?php echo esc_html(str_pad($i + 1, 2, '0', STR_PAD_LEFT)); ?></div>
                            <div class="twd-service-icon"><?php echo self::icon($service[2]); ?></div>
                            <h3><?php echo esc_html($service[0]); ?></h3>
                            <p><?php echo esc_html($service[1]); ?></p>
                            <a class="twd-learn" href="#book-a-demo">Learn more</a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php return ob_get_clean();
    }

    public static function intelligence($atts = []) {
        self::enqueue();
        ob_start(); ?>
        <section class="twd-section twd-soft" id="service-intelligence">
            <div class="twd-container">
                <?php echo self::header('Service Intelligence', 'Smarter Delivery with <span>AI-Assisted Visibility</span>', 'We combine skilled delivery teams with technology intelligence so leaders can see performance, risks and opportunities faster.'); ?>
                <div class="twd-two">
                    <article class="twd-card twd-copy-card">
                        <h3>From service delivery to <span>operational advantage</span></h3>
                        <p>Tranter helps organisations move from reactive support to proactive operations through managed services, automated workflows, service analytics and secure infrastructure oversight.</p>
                        <p>The result is a technology environment that is easier to manage, easier to scale and easier to trust.</p>
                        <ul class="twd-list"><li>AI-assisted monitoring and service intelligence</li><li>Secure infrastructure and endpoint visibility</li><li>Workflow automation for faster execution</li><li>Managed delivery teams with SLA discipline</li></ul>
                    </article>
                    <div class="twd-dashboard">
                        <div class="twd-dashboard-top"><span></span><span></span><span></span><strong>AI Service Operations Dashboard</strong><em>Monitoring Active</em></div>
                        <div class="twd-kpis"><div><strong>24/7</strong><span>Managed IT support</span></div><div><strong>99.8%</strong><span>SLA visibility</span></div><div><strong>AI</strong><span>Service intelligence</span></div><div><strong>Secure</strong><span>Operational control</span></div></div>
                        <div class="twd-dashboard-main"><div class="twd-bars"><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div><div class="twd-orbit"><span></span><span></span><span></span><strong>Operational Intelligence</strong></div></div>
                    </div>
                </div>
            </div>
        </section>
        <?php return ob_get_clean();
    }

    public static function metrics($atts = []) {
        self::enqueue();
        $metrics = [['99%','SLA completion across support sites'],['350+','Expert ICT & smart solutions engineers'],['40+','Global OEM partners across the globe'],['60+','Channel partners around the world']];
        ob_start(); ?>
        <section class="twd-metrics twd-full"><div class="twd-container"><div class="twd-metrics-grid"><?php foreach ($metrics as $m): ?><div class="twd-metric"><strong><?php echo esc_html($m[0]); ?></strong><span><?php echo esc_html($m[1]); ?></span></div><?php endforeach; ?></div></div></section>
        <?php return ob_get_clean();
    }

    public static function faq($atts = []) {
        self::enqueue();
        $faqs = [
            ['What does Tranter specialize in?','Tranter specializes in IT support services, smart workflow solutions, cybersecurity, website optimisation, HR support, digital marketing and business process outsourcing.'],
            ['Do you provide managed IT services?','Yes. We provide managed IT services designed to support reliable infrastructure, secure operations, service continuity and scalable enterprise technology delivery.'],
            ['Does Tranter support enterprise-level organisations?','Yes. Our delivery model supports enterprise-scale requirements, regulated environments, distributed teams and high-growth organisations.'],
            ['Can Tranter help with cybersecurity?','Yes. We help organisations strengthen infrastructure, protect data, reduce operational risk and embed security into day-to-day technology operations.'],
            ['How does Tranter deliver projects?','We begin with discovery, design the right operating model, deploy through managed execution and continuously optimise for performance and resilience.'],
            ['What makes Tranter different?','Tranter works as a strategic partner, combining secure technology delivery, operational insight, AI-assisted visibility and scalable execution to improve business performance.'],
        ];
        ob_start(); ?>
        <section class="twd-section twd-faq" id="faq"><div class="twd-container"><?php echo self::header('Frequently Asked Questions', 'Helping You Make <span>Informed Decisions</span>', 'Clear answers for organisations evaluating secure, scalable and intelligent technology services.'); ?><div class="twd-faq-grid"><?php foreach ($faqs as $faq): ?><details><summary><?php echo esc_html($faq[0]); ?></summary><div class="answer"><?php echo esc_html($faq[1]); ?></div></details><?php endforeach; ?></div></div></section>
        <?php return ob_get_clean();
    }

    public static function cta($atts = []) {
        self::enqueue();
        ob_start(); ?>
        <section class="twd-cta twd-full" id="book-a-demo"><div class="twd-container"><h2>Ready to Transform <span>Your Operations?</span></h2><p>Partner with Tranter to deploy secure, scalable and intelligent technology solutions tailored to your organisation.</p><div class="twd-actions twd-cta-actions"><a class="twd-btn twd-btn-primary" href="/wp/contact/" data-te-open-demo>Book a Demo</a><a class="twd-btn twd-btn-outline" href="https://api.whatsapp.com/send/?phone=2348183405221&text=Hello+Tranter+IT%2C+I+would+like+to+speak+to+your+team.&type=phone_number&app_absent=0" target="_blank" rel="noopener">Speak to Our Team</a></div><div class="twd-trust"><span>Secure delivery</span><span>Enterprise-ready systems</span><span>Long-term support</span></div></div></section>
        <?php return ob_get_clean();
    }

    private static function header($pill, $title, $copy) { return '<header class="twd-header"><div class="twd-pill">' . esc_html($pill) . '</div><h2>' . wp_kses_post($title) . '</h2><div class="twd-divider"><span></span><i></i><span></span></div><p>' . esc_html($copy) . '</p></header>'; }

    private static function hero_cards() { return [['Managed IT','Reliable support, uptime and service continuity.','','support'],['Cybersecurity','Security-first operations for business resilience.','','security'],['Automation','Smarter workflows that reduce manual effort.','','smart'],['Digital Systems','Scalable platforms for modern enterprises.','','web']]; }

    private static function icon($type) {
        $icons = [
            'support' => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.8-3.8a6 6 0 0 1-8 8l-6.9 6.9a2.1 2.1 0 0 1-3-3l6.9-6.9a6 6 0 0 1 8-8z"/>',
            'security' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/>',
            'smart' => '<path d="M12 2v4M12 18v4M4.9 4.9l2.8 2.8M16.3 16.3l2.8 2.8M2 12h4M18 12h4"/>',
            'hr' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>',
            'brand' => '<path d="M3 11l18-5-5 18-4-8-9-5z"/>',
            'bpo' => '<path d="M21 16V8a2 2 0 0 0-1-1.7l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.7l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>',
            'web' => '<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>',
        ];
        $path = $icons[$type] ?? $icons['support'];
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $path . '</svg>';
    }
}
