<?php
if (!defined('ABSPATH')) exit;

class Tranter_Shortcodes {
    public static function init() {
        $shortcodes = [
            'tranter_header'=>'header','tranter_footer'=>'footer','tranter_services'=>'services','tranter_service'=>'service','tranter_partners'=>'partners','tranter_insights'=>'insights',
            'te_header'=>'header','te_footer'=>'footer','te_services'=>'services','te_service'=>'service','te_partners'=>'partners','te_insights'=>'insights',
            'te_home'=>'home','te_home_hero'=>'home_hero','te_who_we_are'=>'who_we_are','te_what_we_do'=>'what_we_do','te_global_delivery'=>'global_delivery',
            'te_industries'=>'industries','te_how_we_work'=>'how_we_work','te_why_tranter'=>'why_tranter','te_metrics'=>'metrics','te_clients'=>'clients','te_faq'=>'faq','te_cta'=>'cta',
            'te_subscription'=>'subscription'
        ];
        foreach ($shortcodes as $tag=>$method) add_shortcode($tag, [__CLASS__, $method]);
    }

    private static function enqueue() {
        wp_enqueue_style('tranter-engine-public-font', 'https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap', [], null);
        wp_enqueue_style('tranter-engine-public', TRANTER_ENGINE_URL.'assets/css/public.css', [], TRANTER_ENGINE_VERSION);
        wp_enqueue_script('tranter-engine-public', TRANTER_ENGINE_URL.'assets/js/public.js', [], TRANTER_ENGINE_VERSION, true);
    }

    private static function market($atts = []) { return isset($atts['market']) ? sanitize_key($atts['market']) : Tranter_Market::current(); }

    public static function header($atts = []) {
        self::enqueue();
        $market = self::market($atts);
        $services = self::query_market_posts('tranter_service', $market, 12);
        $partners = self::query_market_posts('tranter_partner', $market, 6);
        ob_start(); ?>
        <header class="te-public-header trh-full">
            <a class="te-public-logo" href="<?php echo esc_url(home_url('/')); ?>">Tranter IT</a>
            <nav>
                <a href="/who-we-are/">Who We Are</a>
                <details><summary>What We Do</summary><div class="te-public-menu">
                    <?php foreach ($services as $s): ?><a href="<?php echo esc_url(get_permalink($s)); ?>"><?php echo esc_html($s->post_title); ?></a><?php endforeach; ?>
                    <?php foreach ($partners as $p): ?><a href="<?php echo esc_url(get_permalink($p)); ?>"><?php echo esc_html($p->post_title); ?></a><?php endforeach; ?>
                </div></details>
                <a href="/insights/">Insights</a>
                <?php if ($market === 'ng'): ?><a href="/event/">Events</a><?php endif; ?>
                <a href="/contact/">Contact</a>
            </nav>
            <div class="te-public-actions"><a href="<?php echo esc_url(add_query_arg('tranter_market', $market === 'ng' ? 'global' : 'ng')); ?>"><?php echo esc_html($market === 'ng' ? 'US/World' : 'Nigeria'); ?></a><a class="te-demo-link" href="/contact/" data-te-open-demo>Book a Demo</a></div>
        </header>
        <?php return ob_get_clean();
    }

    public static function footer($atts = []) {
        self::enqueue();
        ob_start(); ?>
        <footer class="te-public-footer trh-full"><div><strong>Tranter IT</strong><p>Enterprise technology services for Nigeria and US/World markets.</p></div><nav><a href="/contact/">Contact</a><a href="/privacy-policy/">Privacy Policy</a><a href="/insights/">Insights</a></nav></footer>
        <div class="te-demo-modal" data-te-demo-modal><div><button data-te-close-demo>&times;</button><iframe src="<?php echo esc_url(tranter_engine_cta_url('demo')); ?>"></iframe></div></div>
        <?php return ob_get_clean();
    }

    public static function home($atts = []) {
        self::enqueue();
        $market = self::market($atts);
        ob_start();
        echo '<div id="tranter-homepage-v2" class="te-tranter-home">';
        echo self::home_hero(['market'=>$market]);
        echo self::who_we_are(['market'=>$market]);
        echo self::what_we_do(['market'=>$market]);
        echo self::global_delivery(['market'=>$market]);
        echo self::industries(['market'=>$market]);
        echo self::how_we_work(['market'=>$market]);
        echo self::why_tranter(['market'=>$market]);
        echo self::metrics(['market'=>$market]);
        echo self::insights(['market'=>$market,'limit'=>3]);
        echo self::clients(['market'=>$market]);
        echo self::faq(['market'=>$market]);
        echo self::cta(['market'=>$market]);
        echo '</div>';
        return ob_get_clean();
    }

    public static function home_hero($atts = []) {
        self::enqueue();
        $market = self::market($atts);
        $post = self::get_section('home-hero', $market) ?: self::get_section('home', $market);
        $title = $post ? $post->post_title : 'Technology. At Scale. Without Borders.';
        $body = $post ? wp_strip_all_tags($post->post_content) : 'Driving business efficiency and digital transformation with secure infrastructure, managed support and smart workflow solutions built for enterprise growth.';
        ob_start(); ?>
        <section class="trh-hero trh-full" id="home" aria-label="Tranter IT hero section">
            <div class="trh-slider"><div class="trh-slide trh-active" style="background-image:url('https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&q=70&w=1400')"></div></div><div class="trh-overlay"></div><div class="trh-vignette"></div>
            <div class="trh-hero-inner"><div class="trh-hero-content trh-reveal"><div class="trh-eyebrow">Tranter IT Solutions</div><h1><span>Technology.</span><span class="outline">At Scale</span><span class="accent">Without Borders.</span></h1><p><?php echo esc_html($body); ?></p><div class="trh-hero-actions"><a class="trh-btn trh-btn-primary" href="/contact/" data-te-open-demo><span>Book a Demo</span></a><a class="trh-btn trh-btn-outline" href="#trh-services"><span>Explore Services</span></a></div></div></div>
        </section>
        <?php return ob_get_clean();
    }

    public static function who_we_are($atts = []) {
        self::enqueue(); $market = self::market($atts); $s = self::section_data('who-we-are',$market,'Delivering Secure and Scalable Technology Solutions','Who We Are','We partner with executive teams to solve complex operational and digital challenges through scalable technology, automation and managed services.');
        ob_start(); ?>
        <section class="trh-section" id="who-we-are"><div class="trh-container"><header class="trh-header trh-reveal"><div class="trh-pill"><?php echo esc_html($s['title']); ?></div><h2 class="trh-title"><?php echo esc_html($s['excerpt']); ?></h2><div class="trh-divider"><span></span><i></i><span></span></div></header><div class="trh-two"><article class="trh-card trh-copy-card trh-reveal"><h3>Strategic <span>Partner</span> for Digital Growth</h3><?php echo wpautop(esc_html($s['content'])); ?></article><div class="trh-image-stack trh-reveal"><img src="https://hipterraafrica.com/wp-content/uploads/2026/03/multiethnic-leaders-greeting-each-other-city-scaled.jpg" alt="Business leaders shaking hands" loading="lazy"><img class="small" src="https://hipterraafrica.com/wp-content/uploads/2026/03/digital-growth-shown-by-rising-arrows-tablet-screen-hand-scaled.jpg" alt="Digital growth dashboard" loading="lazy"></div></div></div></section>
        <?php return ob_get_clean();
    }

    public static function what_we_do($atts = []) {
        self::enqueue(); $market = self::market($atts); $s = self::section_data('what-we-do',$market,'Enhancing Businesses with Smart Workflow','What We Do','Enterprise technology capabilities for organisations that need reliable infrastructure, better workflows and stronger execution.');
        ob_start(); ?>
        <section class="trh-section" id="trh-services"><div class="trh-container"><header class="trh-header trh-reveal"><div class="trh-pill"><?php echo esc_html($s['title']); ?></div><h2 class="trh-title"><?php echo esc_html($s['excerpt']); ?></h2><div class="trh-divider"><span></span><i></i><span></span></div><p class="trh-subtitle"><?php echo esc_html($s['content']); ?></p></header><?php echo self::services_grid($market); ?></div></section>
        <?php return ob_get_clean();
    }

    public static function services($atts = []) { return self::what_we_do($atts); }

    private static function services_grid($market) {
        $posts = self::query_market_posts('tranter_service', $market, 20);
        ob_start(); echo '<div class="trh-grid-3">'; $i=1;
        foreach ($posts as $post) {
            echo '<article class="trh-card trh-service-card trh-reveal"><div class="num">'.esc_html(str_pad($i,2,'0',STR_PAD_LEFT)).'</div><div class="trh-icon">'.self::mini_icon($i).'</div><h3>'.esc_html($post->post_title).'</h3><p>'.esc_html($post->post_excerpt).'</p><a href="'.esc_url(get_permalink($post)).'" class="trh-learn">Learn more</a></article>'; $i++;
        }
        echo '</div>'; return ob_get_clean();
    }

    public static function global_delivery($atts = []) {
        self::enqueue(); $market = self::market($atts); $s = self::section_data('global-delivery',$market,'Global Delivery','Global. Distributed. Flexible.','This model enables us to serve clients across borders, scale capacity rapidly and deliver consistent service with data-led visibility across support, security and performance.');
        ob_start(); ?>
        <section class="trh-section" id="global-delivery"><div class="trh-container"><header class="trh-header trh-reveal"><h2 class="trh-title"><?php echo esc_html($s['excerpt']); ?></h2><div class="trh-divider"><span></span><i></i><span></span></div><p class="trh-subtitle"><?php echo esc_html($s['content']); ?></p></header><div class="trh-two"><article class="trh-card trh-copy-card trh-reveal"><ul class="trh-list"><li><span class="trh-check">✓</span>Remote delivery teams</li><li><span class="trh-check">✓</span>Regional operational presence</li><li><span class="trh-check">✓</span>On-site deployment where required</li><li><span class="trh-check">✓</span>AI-assisted monitoring and service intelligence</li></ul><p><?php echo esc_html($s['content']); ?></p></article><div class="trh-ai-dashboard trh-reveal"><div class="trh-ai-shell"><div class="trh-ai-top"><div class="trh-ai-window"><i></i><i></i><i></i></div><div class="trh-ai-title">Operations Command Centre</div><div class="trh-ai-live">Monitoring Active</div></div><div class="trh-ai-body"><div class="trh-ai-kpis"><div class="trh-ai-kpi"><strong>24/7</strong><span>Managed support</span></div><div class="trh-ai-kpi"><strong>99%</strong><span><em>SLA</em> visibility</span></div><div class="trh-ai-kpi"><strong>350+</strong><span>Expert engineers</span></div><div class="trh-ai-kpi"><strong>AI</strong><span>Service intelligence</span></div></div><div class="trh-ai-main"><div class="trh-ai-panel"><div class="trh-ai-head"><strong>Service Performance</strong><span class="trh-ai-chip green">Optimised</span></div><div class="trh-ai-bars"><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div></div><div class="trh-ai-panel"><div class="trh-ai-head"><strong>Coverage</strong><span class="trh-ai-chip">Ready</span></div><div class="trh-ai-regions"><div class="trh-ai-region"><span>Africa Operations</span><em>Online</em></div><div class="trh-ai-region"><span>Enterprise Support</span><em>Online</em></div><div class="trh-ai-region"><span>Security Alerts</span><em>Watched</em></div><div class="trh-ai-region"><span>Field Deployment</span><em>Ready</em></div></div></div></div></div></div></div></div></div></section>
        <?php return ob_get_clean();
    }

    public static function industries($atts = []) {
        self::enqueue(); $market = self::market($atts); $s = self::section_data('industries',$market,'Industries We Serve','Industries','We partner with organisations operating across complex, regulated and high-growth environments.');
        $industries = ['Government','Financial Services','Healthcare','Manufacturing','Education','Retail','Hospitality','Oil & Gas','SMEs','NGOs','Logistics','Real Estate & Property','Public Sector & Infrastructure'];
        ob_start(); ?>
        <section class="trh-section" id="sectors"><div class="trh-container"><header class="trh-header trh-reveal"><div class="trh-pill"><?php echo esc_html($s['title']); ?></div><h2 class="trh-title"><?php echo esc_html($s['excerpt']); ?></h2><div class="trh-divider"><span></span><i></i><span></span></div></header><div class="trh-two"><article class="trh-card trh-copy-card trh-reveal"><p><?php echo esc_html($s['content']); ?></p><ul class="trh-list"><?php foreach($industries as $industry) echo '<li><span class="trh-check">✓</span>'.esc_html($industry).'</li>'; ?></ul></article><div class="trh-image-stack trh-reveal"><img src="https://hipterraafrica.com/wp-content/uploads/2026/06/hands-with-support-gears-isolated-white-background-scaled.jpg" alt="Industries" loading="lazy"></div></div></div></section>
        <?php return ob_get_clean();
    }

    public static function how_we_work($atts = []) {
        self::enqueue(); $market = self::market($atts); $s = self::section_data('how-we-work',$market,'A Strategy-Led Engagement Model','How We Work','We operate as advisors and delivery partners, not transactional vendors.');
        $steps=[['Diagnose','We understand business objectives, operational realities and transformation priorities.'],['Architect','We design technology and operational systems aligned to strategic outcomes.'],['Deliver','We deploy through global teams using secure, governed and SLA-driven execution models.'],['Operate','We manage, optimise and continuously improve systems as long-term partners.']];
        ob_start(); ?>
        <section class="trh-section" id="how-we-work"><div class="trh-container"><header class="trh-header trh-reveal"><div class="trh-pill"><?php echo esc_html($s['title']); ?></div><h2 class="trh-title"><?php echo esc_html($s['excerpt']); ?></h2><div class="trh-divider"><span></span><i></i><span></span></div></header><div class="trh-work-grid"><div class="trh-work-side"><?php foreach(array_slice($steps,0,2) as $idx=>$st) echo self::work_card($st[0],$st[1],$idx+1); ?></div><article class="trh-card trh-work-center trh-reveal"><h3>A <span style="color:var(--green)">Strategy</span> Led Engagement Model</h3><div style="position:relative"><img src="https://hipterraafrica.com/wp-content/uploads/2026/02/How-we-work.jpeg" alt="Team strategy meeting" loading="lazy"><span class="trh-badge">Strategic Delivery</span></div><p><?php echo esc_html($s['content']); ?></p></article><div class="trh-work-side"><?php foreach(array_slice($steps,2) as $idx=>$st) echo self::work_card($st[0],$st[1],$idx+3); ?></div></div></div></section>
        <?php return ob_get_clean();
    }
    private static function work_card($title,$text,$n){ return '<article class="trh-card trh-work-card trh-reveal"><div class="trh-icon">'.self::mini_icon($n).'</div><h3>'.esc_html($title).'</h3><p>'.esc_html($text).'</p></article>'; }

    public static function why_tranter($atts = []) {
        self::enqueue(); $market = self::market($atts); $s = self::section_data('why-tranter',$market,'Built for Enterprise Performance','Why Tranter','Secure infrastructure, distributed operations and long-term support built for growth-focused organisations.');
        $items=[['Distributed operations','Global Delivery Model','We scale support across locations and time zones with managed execution discipline.'],['Security first','Secure Infrastructure','Security is embedded into infrastructure, service delivery and operational controls.'],['Continuous optimisation','Operational Excellence','We improve systems over time so performance keeps increasing after deployment.'],['Partnership mindset','Long-Term Support','We work as a strategic delivery partner, not a one-off technology vendor.'],['Growth ready','Scalable Engagement','We design solutions that scale with your business, maintaining consistent performance and client satisfaction.']];
        ob_start(); echo '<section class="trh-section" id="why-tranter"><div class="trh-container"><header class="trh-header trh-reveal"><div class="trh-pill">'.esc_html($s['title']).'</div><h2 class="trh-title">'.esc_html($s['excerpt']).'</h2><div class="trh-divider"><span></span><i></i><span></span></div></header><div class="trh-why-list">';
        foreach($items as $item) echo '<article class="trh-card trh-why-card trh-reveal"><img src="https://tranter-it.com/wp-content/uploads/2026/02/delivery_34-scaled.jpg" alt="'.esc_attr($item[1]).'" loading="lazy"><div><span class="trh-kicker"><i></i>'.esc_html($item[0]).'</span><h3>'.esc_html($item[1]).'</h3><p>'.esc_html($item[2]).'</p></div></article>';
        echo '</div></div></section>'; return ob_get_clean();
    }

    public static function metrics($atts = []) {
        self::enqueue();
        $metrics=[['99%','SLA completion across all support sites'],['350+','Expert ICT & smart solutions engineers'],['40+','Global OEM partners across the globe'],['60+','Channel partners around the world']];
        ob_start(); echo '<section class="trh-metrics trh-full"><div class="trh-container"><div class="trh-metrics-grid">'; foreach($metrics as $m) echo '<div class="trh-metric trh-reveal"><strong>'.esc_html($m[0]).'</strong><span>'.esc_html($m[1]).'</span></div>'; echo '</div></div></section>'; return ob_get_clean();
    }

    public static function clients($atts = []) {
        self::enqueue(); $market = self::market($atts); $s = self::section_data('clients',$market,'Trusted by organisations committed to performance.','Clients That Trust Us','Client trust section for logos, proof points and credibility copy.');
        $logos=['aero-logo.webp','air-peace-logo-png.webp','BOI.webp','DBN-logo.webp','FBNQuest_logo-removebg-preview.webp','fcmb.webp','fidelity-bank-logo.webp','fsdh-logo.webp','GTBank_logo.svg.webp','heritage-bank-removebg-preview.webp'];
        ob_start(); echo '<section class="trh-section" id="clients"><div class="trh-container"><header class="trh-header trh-reveal"><div class="trh-pill">'.esc_html($s['title']).'</div><h2 class="trh-title">'.esc_html($s['excerpt']).'</h2><div class="trh-divider"><span></span><i></i><span></span></div></header><div class="trh-clients-wrap trh-reveal"><div class="trh-client-track">';
        foreach(array_merge($logos,$logos) as $logo) echo '<div class="trh-logo-card"><img src="https://tranter-it.com/wp-content/uploads/2026/02/'.esc_attr($logo).'" alt="Client logo" loading="lazy"></div>';
        echo '</div></div></div></section>'; return ob_get_clean();
    }

    public static function faq($atts = []) {
        self::enqueue(); $market = self::market($atts); $faqs = self::query_market_posts('tranter_faq',$market,6);
        if (!$faqs) { $faqs = [
            (object)['post_title'=>'What does Tranter specialize in?','post_content'=>'Tranter specializes in IT support services, smart solutions, HR support, digital marketing, business process outsourcing, website development and cybersecurity.'],
            (object)['post_title'=>'Do you provide managed IT services?','post_content'=>'Yes. We provide managed IT services for reliable infrastructure, secure operations, service continuity and enterprise technology delivery.'],
            (object)['post_title'=>'Can Tranter support enterprise-level organisations?','post_content'=>'Yes. Our delivery model supports enterprise-scale requirements, regulated environments and high-growth organisations.'],
            (object)['post_title'=>'What industries do you serve?','post_content'=>'We serve government, finance, healthcare, manufacturing, education, retail, hospitality, oil and gas, SMEs, NGOs, logistics and real estate.'],
            (object)['post_title'=>'How do we start working with Tranter?','post_content'=>'We begin with a discovery conversation to understand your goals, then recommend the right service path for your market and business need.'],
            (object)['post_title'=>'What makes Tranter different?','post_content'=>'Tranter works as a strategic partner, combining secure technology delivery, operational insight and scalable execution.'],
        ]; }
        $section = self::section_data('faq',$market,'Answers to common questions about Tranter IT services.','Frequently Asked Questions','Helping you make informed decisions before speaking with our team.');
        ob_start(); echo '<section class="trh-section trh-faq" id="faq"><div class="trh-container"><header class="trh-header trh-reveal"><div class="trh-pill">'.esc_html($section['title']).'</div><h2 class="trh-title">'.esc_html($section['excerpt']).'</h2><p class="trh-subtitle">'.esc_html($section['content']).'</p><div class="trh-divider"><span></span><i></i><span></span></div></header><div class="trh-faq-grid trh-reveal">';
        foreach($faqs as $f) echo '<details><summary>'.esc_html($f->post_title).'</summary><div class="answer">'.wp_kses_post(wpautop($f->post_content)).'</div></details>';
        echo '</div></div></section>'; return ob_get_clean();
    }

    public static function cta($atts = []) {
        self::enqueue();
        $atts = shortcode_atts(['type'=>'consultation','market'=>Tranter_Market::current()], $atts);
        $key = $atts['type'] === 'consultation' ? 'cta-consultation' : sanitize_title($atts['type']);
        $s = self::section_data($key, $atts['market'], 'Ready to improve your technology operations?', 'Final Conversion CTA', 'Speak with Tranter IT about the right solution for your organisation, market and growth priorities.');
        $heading = $s['excerpt'] ?: 'Ready to improve your technology operations?';
        $copy = $s['content'] ?: 'Speak with Tranter IT about the right solution for your organisation, market and growth priorities.';
        ob_start(); ?>
        <section class="trh-cta trh-full" id="book-a-demo"><div class="trh-container trh-reveal"><h2><?php echo esc_html($heading); ?></h2><p><?php echo esc_html($copy); ?></p><div class="trh-cta-actions"><a class="trh-btn trh-btn-primary" href="/contact/" data-te-open-demo><span>Book a Demo</span></a><a class="trh-btn trh-btn-outline" href="https://wa.me/2348183405221?text=Hello%20Tranter%20IT,%20I%20would%20like%20to%20speak%20to%20your%20team."><span>Speak to Our Team</span></a></div><div class="trh-trust"><span><i></i>Secure delivery</span><span><i></i>Enterprise-ready systems</span><span><i></i>Long-term support</span></div></div></section>
        <?php return ob_get_clean();
    }

    public static function partners($atts = []) {
        self::enqueue(); $market = self::market($atts); $posts = self::query_market_posts('tranter_partner', $market, 20);
        ob_start(); echo '<div class="trh-grid-3">'; foreach($posts as $post) echo '<article class="trh-card trh-service-card trh-reveal"><h3>'.esc_html($post->post_title).'</h3><p>'.esc_html($post->post_excerpt).'</p><a class="trh-learn" href="'.esc_url(get_permalink($post)).'">Explore solution</a></article>'; echo '</div>'; return ob_get_clean();
    }

    public static function service($atts = []) {
        self::enqueue(); $atts=shortcode_atts(['slug'=>'','market'=>Tranter_Market::current()],$atts); $post=get_page_by_path(sanitize_title($atts['slug']),OBJECT,'tranter_service'); if(!$post||!tranter_engine_market_enabled($post->ID,$atts['market'])) return '';
        ob_start(); echo '<div id="tranter-homepage-v2"><section class="trh-section"><div class="trh-container"><header class="trh-header"><div class="trh-pill">'.esc_html(tranter_engine_market_label($atts['market'])).'</div><h1 class="trh-title">'.esc_html($post->post_title).'</h1><p class="trh-subtitle">'.esc_html($post->post_excerpt).'</p><div class="trh-divider"><span></span><i></i><span></span></div></header><article class="trh-card trh-copy-card">'.wp_kses_post(wpautop($post->post_content));
        if ($post->post_name === 'smart-solutions' && $atts['market'] === 'ng') {
            $store_url = get_post_meta($post->ID, '_tranter_store_cta_url', true) ?: 'https://shop.tranter-it.com/';
            $store_label = get_post_meta($post->ID, '_tranter_store_cta_label', true) ?: 'Visit Smart Solutions Store';
            echo '<p class="te-service-store-cta"><a class="trh-btn trh-btn-primary" href="'.esc_url($store_url).'" target="_blank" rel="noopener"><span>'.esc_html($store_label).'</span></a></p>';
        }
        echo '</article></div></section></div>'; return ob_get_clean();
    }

    public static function insights($atts = []) {
        self::enqueue();
        $atts = shortcode_atts(['market' => Tranter_Market::current(), 'limit' => 3], $atts);
        $market = sanitize_key($atts['market']);
        $limit = absint($atts['limit']);

        if (class_exists('Tranter_Knowledge_Hub')) {
            $posts = Tranter_Knowledge_Hub::query_insights($market, $limit);
        } else {
            $posts = get_posts(['post_type' => 'tranter_insight', 'posts_per_page' => $limit, 'post_status' => 'publish']);
        }

        ob_start(); ?>
        <section class="trh-section" id="insights"><div class="trh-container"><header class="trh-header trh-reveal"><div class="trh-pill">Knowledge Hub</div><h2 class="trh-title">Latest from the Knowledge Hub</h2><p class="trh-subtitle">Practical Tranter insights for technology leaders, public institutions and growth-focused organisations.</p><div class="trh-divider"><span></span><i></i><span></span></div></header><div class="trh-grid-3 te-home-kh-grid">
            <?php if ($posts): foreach ($posts as $post): 
                if (class_exists('Tranter_Knowledge_Hub')) { echo Tranter_Knowledge_Hub::card($post); continue; }
                $img = get_the_post_thumbnail_url($post->ID, 'medium') ?: 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&q=70&w=600';
                ?>
                <article class="trh-card trh-insight-card trh-reveal"><div class="trh-insight-img" style="background-image:url('<?php echo esc_url($img); ?>')"></div><div class="trh-insight-content"><div class="trh-meta"><span><?php echo esc_html(get_the_author_meta('display_name', $post->post_author)); ?></span> &bull; <span><?php echo get_the_date('', $post); ?></span></div><h3><?php echo esc_html($post->post_title); ?></h3><p><?php echo esc_html(wp_trim_words($post->post_content, 20)); ?></p><a href="<?php echo get_permalink($post); ?>" class="trh-learn">Read Insight</a></div></article>
            <?php endforeach; else: ?>
                <article class="trh-card te-kh-empty"><h3>No insights published yet.</h3><p>Add your first Insight from Tranter Hub to automatically populate this section.</p></article>
            <?php endif; ?>
        </div><div class="trh-actions trh-reveal"><a href="/knowledge-hub/" class="trh-btn trh-btn-outline"><span>View All Insights</span></a></div></div></section>
        <?php return ob_get_clean();
    }

    public static function subscription($atts = []) {
        wp_enqueue_style('tranter-engine-insights', TRANTER_ENGINE_URL.'assets/css/insights.css', [], TRANTER_ENGINE_VERSION);
        wp_enqueue_script('tranter-engine-insights', TRANTER_ENGINE_URL.'assets/js/insights.js', [], TRANTER_ENGINE_VERSION, true);
        ob_start(); ?>
        <section class="te-knowledge-hub">
            <h3>Stay Ahead with Enterprise Technology</h3>
            <p>Receive practical insights on cybersecurity, business technology and digital transformation.</p>
            <form class="te-subscribe-form">
                <input type="email" name="email" placeholder="Email Address" required>
                <input type="text" name="company" placeholder="Company Name" required>
                <input type="text" name="role" placeholder="Role (Optional)">
                <button type="submit" class="te-subscribe-btn">Subscribe</button>
            </form>
        </section>
        <?php return ob_get_clean();
    }

    private static function section_data($key,$market,$fallback_excerpt,$fallback_title,$fallback_content) {
        $p=self::get_section($key,$market);
        if(!$p) return ['title'=>$fallback_title,'excerpt'=>$fallback_excerpt,'content'=>$fallback_content];
        return ['title'=>$p->post_title,'excerpt'=>$p->post_excerpt ?: $fallback_excerpt,'content'=>wp_strip_all_tags($p->post_content) ?: $fallback_content];
    }

    private static function get_section($key,$market) {
        $posts=get_posts(['post_type'=>'tranter_section','numberposts'=>1,'post_status'=>'publish','meta_query'=>[['key'=>'_tranter_section_key','value'=>sanitize_title($key)]]]);
        if(!$posts) $posts=[get_page_by_path(sanitize_title($key),OBJECT,'tranter_section')];
        $p=$posts && $posts[0] ? $posts[0] : null;
        if($p && tranter_engine_market_enabled($p->ID,$market)) return $p;
        return null;
    }

    private static function query_market_posts($post_type,$market,$limit) {
        $posts=get_posts(['post_type'=>$post_type,'numberposts'=>$limit,'post_status'=>'publish','orderby'=>'menu_order title','order'=>'ASC']);
        return array_values(array_filter($posts,function($p) use ($market){ return tranter_engine_market_enabled($p->ID,$market); }));
    }

    private static function mini_icon($i) { return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M2 12h20"/><circle cx="12" cy="12" r="6"/></svg>'; }
}
