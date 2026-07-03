<?php
if (!defined('ABSPATH')) exit;

class Tranter_IT_Support_Page {
    public static function init() {
        add_shortcode('te_it_support_page', [__CLASS__, 'page']);
        add_shortcode('te_it_support_services_page', [__CLASS__, 'page']);
    }

    private static function enqueue() {
        wp_enqueue_style('tranter-engine-public-font', 'https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap', [], null);
        wp_enqueue_style('tranter-engine-it-support-page', TRANTER_ENGINE_URL . 'assets/css/it-support-page.css', [], TRANTER_ENGINE_VERSION);
    }

    public static function page($atts = []) {
        self::enqueue();
        ob_start();
        echo '<div id="tranter-it-support-page" class="tis-page">';
        echo self::hero();
        echo self::breadcrumb();
        echo self::capabilities();
        echo self::operations();
        echo self::support_model();
        echo self::metrics();
        echo self::resellers();
        echo self::faq();
        echo self::cta();
        echo self::schema();
        echo '</div>';
        return ob_get_clean();
    }

    private static function hero() { ob_start(); ?>
        <section class="tis-hero tis-full" aria-label="AI-ready IT Support Services by Tranter IT">
            <div class="tis-container tis-hero-container">
                <div class="tis-hero-copy">
                    <span class="tis-pill">IT Support Service</span>
                    <h1>AI-Ready IT Support.<span>Built for Uptime.</span></h1>
                    <p>Keep your people productive, your systems secure and your infrastructure performing with managed IT support that combines expert engineers, automation and AI-assisted service visibility.</p>
                    <div class="tis-actions"><a class="tis-btn tis-btn-primary" href="/wp/contact/" data-te-open-demo>Book a Demo</a><a class="tis-btn tis-btn-outline" href="https://api.whatsapp.com/send/?phone=2348183405221&text=Hello+Tranter+IT%2C+I+would+like+to+speak+to+your+team+about+IT+Support+Services.&type=phone_number&app_absent=0" target="_blank" rel="noopener">Speak to Our Team</a></div>
                </div>
                <div class="tis-hero-panel"><span class="tis-floating-chip">AI service desk layer</span><div class="tis-glass-card"><div class="tis-card-label"><span>Support Intelligence</span><span class="tis-live"><i></i>Live Coverage</span></div><div class="tis-mini-grid"><?php foreach (self::hero_cards() as $card): ?><article class="tis-mini-card"><div class="tis-mini-icon"><?php echo self::icon($card['type']); ?></div><strong><?php echo esc_html($card['title']); ?></strong><span><?php echo esc_html($card['copy']); ?></span></article><?php endforeach; ?></div><div class="tis-strategy-strip"><strong>Support that prevents downtime.</strong><span>Not just ticket closure — a managed support model that improves reliability, security and user productivity.</span></div></div></div>
            </div>
        </section>
    <?php return ob_get_clean(); }

    private static function breadcrumb() { return '<nav class="tis-breadcrumb" aria-label="Breadcrumb"><div class="tis-container"><a href="/wp/">Home</a><span>/</span><a href="/wp/what-we-do/">What We Do</a><span>/</span><strong>IT Support Services</strong></div></nav>'; }

    private static function capabilities() {
        $items = [
            ['Service Desk & User Support','Fast support for end users, productivity tools, access issues, incidents, escalations and day-to-day technical requests.','support'],
            ['Endpoint & Device Management','Device setup, maintenance, patch visibility, troubleshooting and support for distributed teams and workplace environments.','web'],
            ['Security-Aware Support','Support processes that consider endpoint risk, access control, account hygiene, incident escalation and secure operations.','security'],
            ['Infrastructure Monitoring','Visibility into system health, service availability, recurring issues and operational patterns that affect productivity.','growth'],
            ['AI-Assisted Ticket Intelligence','Smarter classification, prioritisation and reporting that helps support teams respond faster and leaders see patterns clearly.','smart'],
            ['SLA Governance & Reporting','Structured service performance tracking, SLA discipline, support reporting and insights for continuous improvement.','bpo'],
        ];
        return self::card_section('it-support-capabilities', 'Support Capabilities', 'Everything Your Teams Need to <span>Stay Productive</span>', 'We provide structured IT support for organisations that need stable systems, faster response times and stronger operational control.', $items);
    }

    private static function operations() { ob_start(); ?>
        <section class="tis-section tis-soft" id="ai-support-operations"><div class="tis-container"><?php echo self::header('AI Support Operations', 'From Reactive Support to <span>Predictive IT Operations</span>', 'Our managed support model combines people, process and technology intelligence so your IT environment becomes easier to manage and easier to trust.'); ?><div class="tis-two"><article class="tis-card tis-copy-card"><h3>Support that becomes a <span>business advantage</span></h3><p>Traditional support waits for issues to become tickets. Modern IT support uses visibility, automation and intelligence to prevent repeated problems and reduce operational friction.</p><p>Tranter helps organisations standardise support, improve service quality and create clearer accountability across users, devices, infrastructure and vendors.</p><ul class="tis-list"><li>Reduce recurring incidents and manual escalations</li><li>Improve uptime, response time and user satisfaction</li><li>Strengthen endpoint security and support governance</li><li>Use AI-assisted insights to prioritise what matters</li></ul></article><div class="tis-dashboard"><div class="tis-dashboard-top"><span></span><span></span><span></span><strong>AI IT Support Command Centre</strong><em>Monitoring Active</em></div><div class="tis-kpis"><div><strong>24/7</strong><span>Support coverage</span></div><div><strong>99.8%</strong><span>SLA visibility</span></div><div><strong>AI</strong><span>Ticket triage</span></div><div><strong>Secure</strong><span>Endpoint care</span></div></div><div class="tis-dashboard-main"><div class="tis-bars"><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div><div class="tis-orbit"><span></span><span></span><span></span><strong>Support Intelligence</strong></div></div></div></div></div></section>
    <?php return ob_get_clean(); }

    private static function support_model() {
        $items = [
            ['Stabilise','We improve support structure, clarify escalation paths and create visibility around your IT environment.','bpo'],
            ['Optimise','We use service data, recurring issue patterns and automation opportunities to reduce friction and improve delivery.','growth'],
            ['Scale','We support more users, more devices and more locations without weakening service quality or operational control.','smart'],
        ];
        return self::card_section('support-model', 'Support Model', 'A Managed Support Model for <span>Growing Teams</span>', 'Whether your users are in one office or distributed across regions, we help you maintain continuity, control and service quality.', $items);
    }

    private static function metrics() {
        $metrics = [['99%','SLA completion across support sites'],['24/7','Managed support coverage'],['350+','Expert ICT & smart solutions engineers'],['AI','Service visibility and ticket intelligence']];
        ob_start(); ?><section class="tis-metrics tis-full"><div class="tis-container"><div class="tis-metrics-grid"><?php foreach ($metrics as $metric): ?><div class="tis-metric"><strong><?php echo esc_html($metric[0]); ?></strong><span><?php echo esc_html($metric[1]); ?></span></div><?php endforeach; ?></div></div></section><?php return ob_get_clean();
    }

    private static function resellers() { ob_start(); ?>
        <section class="tis-section tis-resellers" id="ict-resellers"><div class="tis-container"><?php echo self::header('ICT Resellers', 'Trusted Technology Access for <span>Modern Enterprises</span>', 'We connect organisations with reliable ICT products, solutions and technology ecosystems that support smarter operations and long-term infrastructure growth.'); ?><div class="tis-reseller-visual"><img src="https://hipterraafrica.com/wp-content/uploads/2026/02/ICT-Resellers.png" alt="ICT Resellers technology solutions by Tranter IT" loading="lazy"></div></div></section>
    <?php return ob_get_clean(); }

    private static function faq() {
        $faqs = [
            ['What is included in Tranter IT Support Service?','Our IT support service can include user support, device support, infrastructure monitoring, endpoint visibility, incident management, escalation handling, reporting and SLA-driven service delivery.'],
            ['Do you support distributed or remote teams?','Yes. Our support model is designed for distributed teams, multiple sites and organisations that need remote support with on-site engagement where required.'],
            ['How does AI improve IT support?','AI-assisted support helps classify tickets, identify recurring issues, prioritise risk, surface service trends and give leadership better visibility into support performance.'],
            ['Can Tranter support our existing IT team?','Yes. We can complement your internal IT team by handling service desk operations, escalation support, monitoring, reporting or managed support coverage.'],
            ['Is cybersecurity part of your IT support?','Security is considered throughout our support process, including endpoint care, access-related issues, incident escalation, patch awareness and secure operations.'],
            ['How do we start?','We begin with a discovery conversation to understand your users, systems, current support gaps, service priorities and operating environment, then recommend the right support model.'],
        ];
        ob_start(); ?><section class="tis-section tis-faq" id="faq"><div class="tis-container"><?php echo self::header('Frequently Asked Questions', 'IT Support Questions <span>Answered</span>', 'Clear answers for organisations evaluating managed IT support, service desk operations and AI-ready technology support.'); ?><div class="tis-faq-grid"><?php foreach ($faqs as $faq): ?><details><summary><?php echo esc_html($faq[0]); ?></summary><div class="answer"><?php echo esc_html($faq[1]); ?></div></details><?php endforeach; ?></div></div></section><?php return ob_get_clean();
    }

    private static function cta() { ob_start(); ?>
        <section class="tis-cta tis-full" id="book-a-demo"><div class="tis-container"><h2>Ready to Modernise <span>Your IT Support?</span></h2><p>Partner with Tranter to improve uptime, response time, user productivity and technology resilience with AI-ready managed IT support.</p><div class="tis-actions tis-cta-actions"><a class="tis-btn tis-btn-primary" href="/wp/contact/" data-te-open-demo>Book a Demo</a><a class="tis-btn tis-btn-outline" href="https://api.whatsapp.com/send/?phone=2348183405221&text=Hello+Tranter+IT%2C+I+would+like+to+speak+to+your+team+about+IT+Support+Services.&type=phone_number&app_absent=0" target="_blank" rel="noopener">Speak to Our Team</a></div><div class="tis-trust"><span>Faster response</span><span>Secure operations</span><span>AI-assisted visibility</span></div></div></section>
    <?php return ob_get_clean(); }

    private static function card_section($id, $pill, $title, $copy, $items) { ob_start(); ?>
        <section class="tis-section" id="<?php echo esc_attr($id); ?>"><div class="tis-container"><?php echo self::header($pill, $title, $copy); ?><div class="tis-grid-3"><?php foreach ($items as $i => $item): ?><article class="tis-card tis-feature-card"><div class="tis-feature-num"><?php echo esc_html(str_pad($i + 1, 2, '0', STR_PAD_LEFT)); ?></div><div class="tis-feature-icon"><?php echo self::icon($item[2]); ?></div><h3><?php echo esc_html($item[0]); ?></h3><p><?php echo esc_html($item[1]); ?></p></article><?php endforeach; ?></div></div></section>
    <?php return ob_get_clean(); }

    private static function header($pill, $title, $copy) { return '<header class="tis-header"><div class="tis-pill">' . esc_html($pill) . '</div><h2>' . wp_kses_post($title) . '</h2><div class="tis-divider"><span></span><i></i><span></span></div><p>' . esc_html($copy) . '</p></header>'; }

    private static function hero_cards() { return [['title'=>'Managed Helpdesk','copy'=>'Responsive support for users, devices and daily operations.','type'=>'support'],['title'=>'Secure Endpoints','copy'=>'Device visibility, patch awareness and security-first support.','type'=>'security'],['title'=>'AI Triage','copy'=>'Faster ticket routing, prioritisation and service insights.','type'=>'smart'],['title'=>'Infrastructure Care','copy'=>'Support for networks, systems, applications and availability.','type'=>'web']]; }

    private static function schema() { return '<script type="application/ld+json">{"@context":"https://schema.org","@type":"Service","name":"IT Support Services","serviceType":"Managed IT Support Services","provider":{"@type":"Organization","name":"Tranter IT Infrastructure Services Limited","url":"https://hipterraafrica.com/"},"areaServed":"Global","description":"AI-ready managed IT support services including helpdesk support, endpoint support, infrastructure monitoring, service desk operations, SLA governance and secure support delivery."}</script>'; }

    private static function icon($type) {
        $icons = [
            'support' => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.8-3.8a6 6 0 0 1-8 8l-6.9 6.9a2.1 2.1 0 0 1-3-3l6.9-6.9a6 6 0 0 1 8-8z"/>',
            'security' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/>',
            'smart' => '<path d="M12 2v4M12 18v4M4.9 4.9l2.8 2.8M16.3 16.3l2.8 2.8M2 12h4M18 12h4"/>',
            'web' => '<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>',
            'bpo' => '<path d="M21 16V8a2 2 0 0 0-1-1.7l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.7l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>',
            'growth' => '<path d="M4 17l6-6 4 4 6-8"/><path d="M20 7h-5M20 7v5"/>',
        ];
        $path = $icons[$type] ?? $icons['support'];
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $path . '</svg>';
    }
}
