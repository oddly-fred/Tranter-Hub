<?php
if (!defined('ABSPATH')) exit;

class Tranter_Smart_Solutions_Page {
    public static function init() {
        add_shortcode('te_smart_solutions_page', [__CLASS__, 'page']);
        add_shortcode('te_smart_solution_page', [__CLASS__, 'page']);
    }

    private static function is_ng() {
        return !class_exists('Tranter_Market') || Tranter_Market::current() === 'ng';
    }

    private static function enqueue() {
        wp_enqueue_style('tranter-engine-public-font', 'https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap', [], null);
        wp_enqueue_style('tranter-engine-it-support-page', TRANTER_ENGINE_URL . 'assets/css/it-support-page.css', [], TRANTER_ENGINE_VERSION);
        wp_enqueue_style('tranter-engine-smart-solutions-page', TRANTER_ENGINE_URL . 'assets/css/smart-solutions-page.css', ['tranter-engine-it-support-page'], TRANTER_ENGINE_VERSION);
    }

    public static function page($atts = []) {
        if (!self::is_ng()) return '';
        self::enqueue();
        ob_start();
        echo '<div id="tranter-smart-solutions-page" class="tis-page">';
        echo self::hero();
        echo self::breadcrumb();
        echo self::capabilities();
        echo self::operations();
        echo self::model();
        echo self::solution_areas();
        echo self::metrics();
        echo self::resellers();
        echo self::faq();
        echo self::cta();
        echo self::schema();
        echo '</div>';
        return ob_get_clean();
    }

    private static function hero() { ob_start(); ?>
        <section class="tis-hero tis-full" aria-label="AI-ready Smart Solutions by Tranter IT">
            <div class="tis-container tis-hero-container">
                <div class="tis-hero-copy"><span class="tis-pill">Smart Solutions</span><h1>Intelligent Automation.<span>Built for Growth.</span></h1><p>Transform manual processes into intelligent digital workflows with smart automation, AI-assisted insights and connected systems that help teams operate faster, smarter and more efficiently.</p><div class="tis-actions"><a class="tis-btn tis-btn-primary" href="/wp/contact/" data-te-open-demo>Book a Demo</a><a class="tis-btn tis-btn-outline" href="https://api.whatsapp.com/send/?phone=2348183405221&text=Hello+Tranter+IT%2C+I+would+like+to+speak+to+your+team+about+Smart+Solutions.&type=phone_number&app_absent=0" target="_blank" rel="noopener">Speak to Our Team</a></div></div>
                <div class="tis-hero-panel"><span class="tis-floating-chip">AI automation layer</span><div class="tis-glass-card"><div class="tis-card-label"><span>Smart Operations</span><span class="tis-live"><i></i>Live Intelligence</span></div><div class="tis-mini-grid"><?php foreach (self::hero_cards() as $card): ?><article class="tis-mini-card"><div class="tis-mini-icon"><?php echo self::icon($card['type']); ?></div><strong><?php echo esc_html($card['title']); ?></strong><span><?php echo esc_html($card['copy']); ?></span></article><?php endforeach; ?></div><div class="tis-strategy-strip"><strong>Automation that improves execution.</strong><span>Not disconnected tools — intelligent systems designed to improve speed, accuracy and performance.</span></div></div></div>
            </div>
        </section>
    <?php return ob_get_clean(); }

    private static function breadcrumb() { return '<nav class="tis-breadcrumb" aria-label="Breadcrumb"><div class="tis-container"><a href="/wp/">Home</a><span>/</span><a href="/wp/what-we-do/">What We Do</a><span>/</span><strong>Smart Solutions</strong></div></nav>'; }

    private static function capabilities() {
        return self::card_section('smart-solution-capabilities', 'Smart Capabilities', 'Everything Your Business Needs to <span>Operate Smarter</span>', 'We design and implement intelligent business systems that streamline workflows, reduce manual effort and improve operational visibility.', [
            ['Business Process Automation','Automate repetitive tasks, approvals, reporting and handoffs so teams can focus on higher-value work.','support'],
            ['Intelligent Systems Design','Design digital systems that connect teams, data, workflows and business rules into one operating model.','web'],
            ['Workflow Optimisation','Simplify processes, remove bottlenecks and improve how work moves across your organisation.','security'],
            ['Operational Dashboards','Give leaders clear visibility into performance, service quality, tasks, trends and operational risk.','growth'],
            ['AI-Assisted Decision Support','Use AI-assisted insights to identify patterns, prioritise actions and support faster business decisions.','smart'],
            ['Integration & Reporting','Connect platforms, reduce data silos and generate real-time reports for better execution control.','bpo'],
        ]);
    }

    private static function operations() { ob_start(); ?>
        <section class="tis-section tis-soft" id="smart-operations"><div class="tis-container"><?php echo self::header('AI Smart Operations', 'From Manual Processes to <span>Intelligent Operations</span>', 'Our smart solutions model combines process design, automation and analytics so your operations become easier to run, measure and scale.'); ?><div class="tis-two"><article class="tis-card tis-copy-card"><h3>Automation that becomes a <span>competitive advantage</span></h3><p>Manual processes slow growth, increase errors and hide performance gaps. Smart operations use automation, connected data and intelligence to make work faster and more reliable.</p><p>Tranter helps organisations standardise workflows, digitise routine processes and create clearer accountability across teams, systems and stakeholders.</p><ul class="tis-list"><li>Reduce repetitive tasks and manual handoffs</li><li>Improve speed, visibility and customer experience</li><li>Standardise workflows and operational governance</li><li>Use AI-assisted analytics to prioritise what matters</li></ul></article><div class="tis-dashboard"><div class="tis-dashboard-top"><span></span><span></span><span></span><strong>AI Smart Operations Command Centre</strong><em>Monitoring Active</em></div><div class="tis-kpis"><div><strong>24/7</strong><span>Workflow coverage</span></div><div><strong>99.8%</strong><span>Process visibility</span></div><div><strong>AI</strong><span>Smart insights</span></div><div><strong>Secure</strong><span>Connected control</span></div></div><div class="tis-dashboard-main"><div class="tis-bars"><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div><div class="tis-orbit"><span></span><span></span><span></span><strong>Smart Intelligence</strong></div></div></div></div></div></section>
    <?php return ob_get_clean(); }

    private static function model() { return self::card_section('smart-solutions-model', 'Smart Solutions Model', 'A Managed Smart Solutions Model for <span>Growing Teams</span>', 'Whether you are digitising one department or transforming enterprise-wide operations, we help you move from scattered tools to connected systems.', [['Discover','We analyse your workflows, data movement, approval paths and operational bottlenecks.','bpo'],['Automate','We design smart workflows, dashboards and integrations that reduce friction and improve execution.','growth'],['Scale','We help expand successful automations across teams, locations and business functions.','smart']]); }

    private static function solution_areas() { return self::card_section('smart-solution-areas', 'Our Solutions', 'Smart Systems for <span>Operational Growth</span>', 'We support business transformation with smart solutions that improve visibility, control and speed across industries and operating models.', [['Digital Oil Fields','Streamline facility management and gain remote visibility across assets, teams and field operations.','web'],['Smart Homes','Secure homes and connected environments with simple, intelligent and responsive technology.','smart'],['Smart Cities','Connect and coordinate infrastructure, services and data for safer and more efficient environments.','bpo'],['Smart Manufacturing','Use operational data to improve production visibility, process performance and industrial efficiency.','growth'],['Smart Logistics','Monitor assets, improve route visibility and strengthen supply chain execution with connected systems.','support'],['Smart Agriculture','Use data and connected infrastructure to improve visibility, productivity and resource control.','security']]); }

    private static function metrics() { $metrics = [['99%','Process visibility across automated workflows'],['24/7','Automation-ready delivery model'],['350+','Expert ICT & smart solutions engineers'],['AI','Workflow intelligence and digital optimisation']]; ob_start(); ?><section class="tis-metrics tis-full"><div class="tis-container"><div class="tis-metrics-grid"><?php foreach ($metrics as $metric): ?><div class="tis-metric"><strong><?php echo esc_html($metric[0]); ?></strong><span><?php echo esc_html($metric[1]); ?></span></div><?php endforeach; ?></div></div></section><?php return ob_get_clean(); }

    private static function resellers() { ob_start(); ?><section class="tis-section tis-resellers" id="smart-resellers"><div class="tis-container"><?php echo self::header('Smart Solutions Resellers', 'Smart Technology Ecosystems for <span>Modern Enterprises</span>', 'We help organisations access smart platforms, automation ecosystems and connected technologies that support faster, more intelligent operations.'); ?><div class="tis-reseller-visual"><img src="https://hipterraafrica.com/wp-content/uploads/2026/02/IoT-Resellers-1024x304-1.png" alt="Smart Solutions Resellers technology solutions by Tranter IT" loading="lazy"></div></div></section><?php return ob_get_clean(); }

    private static function faq() { $faqs = [['What are Smart Solutions?','Smart Solutions are digital systems, automations, dashboards and integrations designed to make business processes faster, more visible and easier to scale.'],['Can you automate our existing workflows?','Yes. Tranter reviews current processes, identifies repetitive tasks and bottlenecks, then designs automation that fits the business model and existing technology environment.'],['How does AI support smart operations?','AI can help classify information, surface patterns, prioritise decisions, support reporting and improve visibility across workflows, service delivery and operational performance.'],['Can Smart Solutions integrate with our current tools?','Yes. Tranter designs solutions that can connect with existing platforms where possible, reducing data silos and improving operational continuity.'],['Which teams benefit from Smart Solutions?','Operations, finance, HR, customer service, field teams, management and leadership teams can benefit from workflow automation, digital reporting and process visibility.'],['How do we start?','Tranter begins with discovery to understand workflows, current systems, process gaps and business goals, then recommends the right smart solution roadmap.']]; ob_start(); ?><section class="tis-section tis-faq" id="faq"><div class="tis-container"><?php echo self::header('Frequently Asked Questions', 'Smart Solutions Questions <span>Answered</span>', 'Clear answers for organisations evaluating automation, business intelligence, workflow digitisation and AI-enabled operations.'); ?><div class="tis-faq-grid"><?php foreach ($faqs as $faq): ?><details><summary><?php echo esc_html($faq[0]); ?></summary><div class="answer"><?php echo esc_html($faq[1]); ?></div></details><?php endforeach; ?></div></div></section><?php return ob_get_clean(); }

    private static function cta() { ob_start(); ?><section class="tis-cta tis-full" id="book-a-demo"><div class="tis-container"><h2>Ready to Automate <span>Your Operations?</span></h2><p>Partner with Tranter to digitise workflows, connect systems and improve business performance with intelligent automation.</p><div class="tis-actions tis-cta-actions"><a class="tis-btn tis-btn-primary" href="/wp/contact/" data-te-open-demo>Book a Demo</a><a class="tis-btn tis-btn-outline" href="https://api.whatsapp.com/send/?phone=2348183405221&text=Hello+Tranter+IT%2C+I+would+like+to+speak+to+your+team+about+Smart+Solutions.&type=phone_number&app_absent=0" target="_blank" rel="noopener">Speak to Our Team</a></div><div class="tis-trust"><span>Faster execution</span><span>Connected workflows</span><span>AI-assisted insights</span></div></div></section><?php return ob_get_clean(); }

    private static function card_section($id, $pill, $title, $copy, $items) { ob_start(); ?><section class="tis-section" id="<?php echo esc_attr($id); ?>"><div class="tis-container"><?php echo self::header($pill, $title, $copy); ?><div class="tis-grid-3"><?php foreach ($items as $i => $item): ?><article class="tis-card tis-feature-card"><div class="tis-feature-num"><?php echo esc_html(str_pad($i + 1, 2, '0', STR_PAD_LEFT)); ?></div><div class="tis-feature-icon"><?php echo self::icon($item[2]); ?></div><h3><?php echo esc_html($item[0]); ?></h3><p><?php echo esc_html($item[1]); ?></p></article><?php endforeach; ?></div></div></section><?php return ob_get_clean(); }
    private static function header($pill, $title, $copy) { return '<header class="tis-header"><div class="tis-pill">' . esc_html($pill) . '</div><h2>' . wp_kses_post($title) . '</h2><div class="tis-divider"><span></span><i></i><span></span></div><p>' . esc_html($copy) . '</p></header>'; }
    private static function hero_cards() { return [['title'=>'Workflow Automation','copy'=>'Digitise repetitive work and reduce operational delays.','type'=>'support'],['title'=>'Connected Systems','copy'=>'Unify tools, data and processes across departments.','type'=>'security'],['title'=>'AI Insights','copy'=>'Surface patterns, decisions and opportunities faster.','type'=>'smart'],['title'=>'Digital Platforms','copy'=>'Build scalable platforms for modern enterprise teams.','type'=>'web']]; }
    private static function schema() { return '<script type="application/ld+json">{"@context":"https://schema.org","@type":"Service","name":"Smart Solutions","serviceType":"Smart Solutions and Business Automation Services","provider":{"@type":"Organization","name":"Tranter IT Infrastructure Services Limited","url":"https://hipterraafrica.com/"},"areaServed":"Nigeria","description":"Smart solutions services including workflow automation, intelligent systems design, dashboards, integrations, business process optimisation and AI-assisted operational visibility."}</script>'; }
    private static function icon($type) { $icons = ['support'=>'<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.8-3.8a6 6 0 0 1-8 8l-6.9 6.9a2.1 2.1 0 0 1-3-3l6.9-6.9a6 6 0 0 1 8-8z"/>','security'=>'<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/>','smart'=>'<path d="M12 2v4M12 18v4M4.9 4.9l2.8 2.8M16.3 16.3l2.8 2.8M2 12h4M18 12h4"/>','web'=>'<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>','bpo'=>'<path d="M21 16V8a2 2 0 0 0-1-1.7l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.7l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>','growth'=>'<path d="M4 17l6-6 4 4 6-8"/><path d="M20 7h-5M20 7v5"/>']; $path = $icons[$type] ?? $icons['smart']; return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $path . '</svg>'; }
}
