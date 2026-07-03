<?php
if (!defined('ABSPATH')) exit;

class Tranter_Admin_App {
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'menu']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'assets']);
        add_action('admin_post_tranter_engine_save_settings', [__CLASS__, 'save_settings']);
        add_action('admin_post_tranter_engine_save_section', [__CLASS__, 'save_section']);
    }

    public static function menu() {
        add_menu_page('Tranter Engine', 'Tranter Engine', 'manage_options', 'tranter-engine', [__CLASS__, 'render'], 'dashicons-chart-area', 3);
        foreach ([
            'dashboard' => 'Dashboard',
            'market' => 'Market Manager',
            'website' => 'Website',
            'company' => 'Who We Are',
            'services' => 'Services',
            'partners' => 'Partner Solutions',
            'products' => 'Partner Products',
            'insights' => 'Insights',
            'events' => 'Events',
            'resources' => 'Resources',
            'subscribers' => 'Subscribers',
            'ctas' => 'CTAs',
            'faqs' => 'FAQs',
            'ai' => 'AI Assistant',
            'seo' => 'SEO',
            'analytics' => 'Analytics',
            'settings' => 'Settings'
        ] as $key => $label) {
            add_submenu_page('tranter-engine', $label, $label, 'manage_options', 'tranter-engine-'.$key, [__CLASS__, 'render']);
        }
        remove_submenu_page('tranter-engine', 'tranter-engine');
    }

    public static function assets($hook) {
        $page = isset($_GET['page']) ? sanitize_key($_GET['page']) : '';
        $screen = function_exists('get_current_screen') ? get_current_screen() : null;
        $post_type = $screen && !empty($screen->post_type) ? $screen->post_type : '';
        $is_te_post_type = in_array($post_type, ['tranter_campaign','tranter_insight','tranter_resource','tranter_event','tranter_service','tranter_partner','tranter_product','tranter_cta','tranter_faq'], true);
        if (strpos($hook, 'tranter-engine') === false && strpos($page, 'tranter-engine') === false && !$is_te_post_type) return;
        wp_enqueue_style('tranter-engine-mulish', 'https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap', [], null);
        wp_enqueue_style('tranter-engine-admin', TRANTER_ENGINE_URL.'assets/css/admin.css', ['tranter-engine-mulish'], TRANTER_ENGINE_VERSION);
        wp_enqueue_script('tranter-engine-admin', TRANTER_ENGINE_URL.'assets/js/admin.js', [], TRANTER_ENGINE_VERSION, true);

        // Fallback critical CSS. Some WordPress hosts/cache plugins delay or strip plugin CSS on custom admin pages.
        $critical_css_path = TRANTER_ENGINE_PATH . 'assets/css/admin.css';
        if (file_exists($critical_css_path)) {
            wp_add_inline_style('tranter-engine-admin', file_get_contents($critical_css_path));
        }
    }

    public static function current_screen_key() {
        $page = isset($_GET['page']) ? sanitize_key($_GET['page']) : 'tranter-engine-dashboard';
        return str_replace('tranter-engine-', '', $page === 'tranter-engine' ? 'dashboard' : $page);
    }

    public static function render() {
        $screen = self::current_screen_key();
        echo '<div class="te-app te-workspace">';
        self::app_header($screen);
        echo '<main class="te-main">';
        switch ($screen) {
            case 'market': self::market_manager(); break;
            case 'website': self::website_workspace(); break;
            case 'company': self::company_workspace(); break;
            case 'sections': if (isset($_GET['section_id'])) self::section_editor(absint($_GET['section_id'])); else self::website_sections(); break;
            case 'services': self::content_cards('tranter_service', 'Technology Solutions', 'Manage the seven core Tranter services.', 'service'); break;
            case 'partners': self::content_cards('tranter_partner', 'Partner Solutions', 'Manage Zoho, ManageEngine and Sophos.', 'partner'); break;
            case 'products': self::content_cards('tranter_product', 'Partner Products', 'Control market-specific product visibility.', 'partner'); break;
            case 'insights': self::content_cards('tranter_insight', 'Insights', 'Publish NG and US/World thought leadership.', 'insight'); break;
            case 'events': self::content_cards('tranter_event', 'Events', 'Nigeria-facing events and webinars.', 'event'); break;
            case 'resources': self::resources_workspace(); break;
            case 'subscribers': self::subscribers_workspace(); break;
            case 'ctas': self::content_cards('tranter_cta', 'CTAs', 'Manage conversion prompts and action buttons.', 'cta'); break;
            case 'faqs': self::content_cards('tranter_faq', 'FAQs', 'Reusable questions for pages and AI.', 'faq'); break;
            case 'ai': self::ai_workspace(); break;
            case 'seo': self::seo_workspace(); break;
            case 'analytics': self::analytics_workspace(); break;
            case 'settings': self::settings(); break;
            default: self::dashboard(); break;
        }
        echo '</main></div>';
    }

    private static function app_header($active) {
        $nav = [
            'dashboard' => ['Dashboard','dashicons-dashboard'],
            'website' => ['Website','dashicons-admin-site'],
            'company' => ['Who We Are','dashicons-building'],
            'services' => ['Content','dashicons-screenoptions'],
            'resources' => ['Resources','dashicons-download'],
            'ctas' => ['Marketing','dashicons-megaphone'],
            'ai' => ['AI','dashicons-format-chat'],
            'analytics' => ['Analytics','dashicons-chart-bar'],
            'settings' => ['Settings','dashicons-admin-generic'],
        ];
        $market = tranter_engine_market_label(Tranter_Market::current());
        echo '<header class="te-app-header">';
        echo '<div class="te-brand-inline"><span class="te-brand-mark">TE</span><div><strong>Tranter Engine</strong><em>v1.1.0 beta</em></div></div>';
        echo '<nav class="te-workspace-nav">';
        foreach ($nav as $key => $item) {
            $is_active = $active === $key || ($key === 'website' && in_array($active, ['website'], true)) || ($key === 'services' && in_array($active, ['services','partners','products','insights','events','faqs'], true)) || ($key === 'ctas' && in_array($active, ['ctas','seo'], true));
            echo '<a class="'.($is_active ? 'is-active' : '').'" href="'.esc_url(admin_url('admin.php?page=tranter-engine-'.$key)).'"><span class="dashicons '.esc_attr($item[1]).'"></span>'.esc_html($item[0]).'</a>';
        }
        echo '</nav>';
        echo '<div class="te-header-actions"><button class="te-search" type="button">⌘K Search</button><a class="te-market-switch" href="'.esc_url(admin_url('admin.php?page=tranter-engine-market')).'">'.esc_html($market).'</a></div>';
        echo '</header>';
    }

    private static function dashboard() {
        $counts = self::counts();
        echo '<section class="te-title-row"><div><span class="te-kicker">Mission Control</span><h1>Dashboard</h1><p>Track content, markets, leads and website readiness from one workspace.</p></div><a class="te-btn te-btn-primary" href="'.esc_url(admin_url('admin.php?page=tranter-engine-services')).'">Manage Content</a></section>';

        echo '<section class="te-dashboard-grid">';
        self::health_card();
        self::market_status_card();
        self::mini_stat('Services', $counts['services'], 'Published core services', 'service');
        self::mini_stat('Insights', $counts['insights'], 'Published articles', 'insight');
        self::mini_stat('Events', $counts['events'], 'Upcoming and past events', 'event');
        self::mini_stat('Partners', $counts['partners'], 'Active partner solutions', 'partner');
        echo '</section>';

        echo '<section class="te-grid te-grid-2">';
        self::quick_actions();
        self::activity_panel();
        echo '</section>';

        echo '<section class="te-grid te-grid-3">';
        self::module_card('Website', 'Homepage, header, footer and navigation.', 'Open Website', 'website', 'dashicons-admin-site');
        self::module_card('AI Assistant', 'Connect endpoint and prepare market-aware answers.', 'Open AI', 'ai', 'dashicons-format-chat');
        self::module_card('Analytics', 'Business snapshots for services, CTAs and leads.', 'View Analytics', 'analytics', 'dashicons-chart-bar');
        echo '</section>';
    }

    private static function counts() {
        return [
            'services' => intval(wp_count_posts('tranter_service')->publish ?? 0),
            'partners' => intval(wp_count_posts('tranter_partner')->publish ?? 0),
            'products' => intval(wp_count_posts('tranter_product')->publish ?? 0),
            'insights' => intval(wp_count_posts('tranter_insight')->publish ?? 0),
            'events' => intval(wp_count_posts('tranter_event')->publish ?? 0),
        ];
    }

    private static function health_card() {
        echo '<article class="te-health-card"><div><span class="te-card-label">Website Health</span><strong>Ready</strong><p>Admin shell, markets, shortcodes and seed content are active.</p></div><div class="te-ring">92%</div></article>';
    }

    private static function market_status_card() {
        echo '<article class="te-status-card"><span class="te-card-label">Markets</span><div class="te-status-list"><b>Nigeria</b><span class="is-good">Active</span></div><div class="te-status-list"><b>US/World</b><span class="is-good">Active</span></div><a href="'.esc_url(admin_url('admin.php?page=tranter-engine-market')).'">Review market rules →</a></article>';
    }

    private static function mini_stat($label, $value, $note, $icon) {
        echo '<article class="te-mini-stat"><div class="te-icon">'.tranter_engine_svg_icon($icon).'</div><strong>'.intval($value).'</strong><span>'.esc_html($label).'</span><p>'.esc_html($note).'</p></article>';
    }

    private static function quick_actions() {
        $actions = [
            ['New Insight', 'post-new.php?post_type=tranter_insight'],
            ['New Event', 'post-new.php?post_type=tranter_event'],
            ['New CTA', 'post-new.php?post_type=tranter_cta'],
            ['Sections', 'admin.php?page=tranter-engine-sections'],
            ['Settings', 'admin.php?page=tranter-engine-settings'],
        ];
        echo '<article class="te-panel"><div class="te-panel-head"><h2>Quick Actions</h2><span>3 clicks or fewer</span></div><div class="te-action-grid">';
        foreach ($actions as $a) echo '<a class="te-action" href="'.esc_url(admin_url($a[1])).'">+'.esc_html($a[0]).'</a>';
        echo '</div></article>';
    }

    private static function activity_panel() {
        echo '<article class="te-panel"><div class="te-panel-head"><h2>Activity</h2><span>Latest system state</span></div><ul class="te-timeline"><li><b>Workspace updated</b><span>Dashboard 2.0 active</span></li><li><b>Market rules set</b><span>Smart Solutions hidden for US/World</span></li><li><b>Partner rules set</b><span>Zoho only for US/World</span></li></ul></article>';
    }

    private static function module_card($title, $text, $button, $page, $icon) {
        echo '<article class="te-module"><span class="dashicons '.esc_attr($icon).'"></span><h3>'.esc_html($title).'</h3><p>'.esc_html($text).'</p><a href="'.esc_url(admin_url('admin.php?page=tranter-engine-'.$page)).'">'.esc_html($button).'</a></article>';
    }

    private static function website_workspace() {
        echo '<section class="te-title-row"><div><span class="te-kicker">Website Workspace</span><h1>Website</h1><p>Use WordPress pages for layout, then paste the recommended Tranter shortcodes in the right order.</p></div><a class="te-btn te-btn-primary" href="'.esc_url(admin_url('admin.php?page=tranter-engine-sections')).'">Open Section Manager</a></section>';

        echo '<section class="te-grid te-grid-4">';
        self::website_tile('Page Guides', 'Recommended shortcode structures for each public page.', 'Homepage, Insights, Company, Services', 'website');
        self::website_tile('Shortcodes', 'Current shortcode catalogue grouped by module.', '[te_site_header]', 'website');
        self::website_tile('Section Manager', 'Edit reusable section content without touching page code.', '[te_who_we_are]', 'sections');
        self::website_tile('Header & Footer', 'Global header and footer shortcodes with GeoIP-aware logic.', '[te_site_header]', 'website');
        echo '</section>';

        self::page_guides_panel();
        self::shortcode_reference_panel();
    }

    private static function page_guides_panel() {
        $guides = [
            'Homepage' => "[te_site_header]\n\n[te_home_hero]\n[te_who_we_are]\n[te_services]\n[te_global_delivery]\n[te_industries]\n[te_how_we_work]\n[te_why_tranter]\n[te_metrics]\n[te_latest_insights]\n[te_clients]\n[te_faq]\n[te_cta]\n\n[te_site_footer]",
            'Insights' => "[te_site_header]\n\n[te_insights_hero]\n[te_featured_insight]\n[te_insights_search]\n[te_insights_tags]\n[te_insights_grid limit=\"9\"]\n[te_featured_resource]\n[te_newsletter title=\"Stay ahead of technology trends\" subtitle=\"Receive practical Tranter insights, guides and event updates directly in your inbox.\"]\n[te_insights_cta]\n\n[te_site_footer]",
            'Company / Who We Are' => "[te_site_header]\n\n[te_about]\n\n[te_site_footer]",
            'What We Do / Services' => "[te_site_header]\n\n[te_services_hero]\n[te_services_grid]\n[te_partner_solutions]\n[te_industries]\n[te_services_cta]\n\n[te_site_footer]",
            'Resources' => "[te_site_header]\n\n[te_resources_hero]\n[te_featured_resource]\n[te_resources]\n[te_newsletter]\n[te_resources_cta]\n\n[te_site_footer]",
            'Campaigns' => "Campaign pages are generated automatically from the Campaign Builder.\n\nUse the campaign layout settings to choose Full HTML only, Header, Footer, or Header + Footer.\n\nManual fallback:\n[te_campaign id=\"campaign-slug\"]",
        ];
        echo '<section class="te-panel"><div class="te-panel-head"><h2>Page Guides</h2><span>Recommended page structures</span></div><div class="te-guide-grid">';
        foreach ($guides as $title => $code) {
            echo '<article class="te-guide-card"><h3>'.esc_html($title).'</h3><pre class="te-code-block">'.esc_html($code).'</pre></article>';
        }
        echo '</div></section>';
    }

    private static function shortcode_reference_panel() {
        $groups = [
            'Global Site Chrome' => [
                ['[te_site_header]', 'Global GeoIP-aware header', 'Available'],
                ['[te_site_footer]', 'Global footer, contact links and chat widget', 'Available'],
                ['[te_header]', 'Alias for [te_site_header]', 'Available'],
                ['[te_footer]', 'Alias for [te_site_footer]', 'Available'],
            ],
            'Homepage' => [
                ['[te_home_hero]', 'Homepage hero section', 'Available'],
                ['[te_who_we_are]', 'Homepage company intro section', 'Available'],
                ['[te_services]', 'Homepage service cards', 'Available'],
                ['[te_global_delivery]', 'Global delivery section', 'Available'],
                ['[te_industries]', 'Industry cards section', 'Available'],
                ['[te_how_we_work]', 'Process section', 'Available'],
                ['[te_why_tranter]', 'Why Tranter section', 'Available'],
                ['[te_metrics]', 'Metrics/statistics section', 'Available'],
                ['[te_latest_insights]', 'Latest insights cards', 'Available'],
                ['[te_clients]', 'Client/logo section', 'Available'],
                ['[te_faq]', 'FAQ section', 'Available'],
                ['[te_cta]', 'Homepage CTA section', 'Available'],
            ],
            'Insights' => [
                ['[te_insights_hero]', 'Insights page hero', 'Available'],
                ['[te_featured_insight]', 'Featured insight card', 'Available'],
                ['[te_insights_search]', 'Insights search block', 'Available'],
                ['[te_insights_tags]', 'Insight tag filter chips', 'Available'],
                ['[te_insights_grid limit="9"]', 'Insight listing grid', 'Available'],
                ['[te_featured_resource]', 'Featured resource block', 'Available'],
                ['[te_newsletter]', 'Newsletter lead capture', 'Available'],
                ['[te_insights_cta]', 'Insights CTA block', 'Available'],
                ['[te_knowledge_hub]', 'Legacy full Knowledge Hub shortcode', 'Deprecated'],
            ],
            'Campaigns' => [
                ['[te_campaign id="campaign-slug"]', 'Render a campaign manually if needed', 'Available'],
                ['[te_campaign_hero id="campaign-slug"]', 'Legacy campaign hero section', 'Available'],
                ['[te_campaign_html id="campaign-slug"]', 'Legacy campaign HTML section', 'Available'],
                ['[te_campaign_cta id="campaign-slug"]', 'Legacy campaign CTA section', 'Available'],
                ['[te_campaign_footer]', 'Legacy campaign footer section', 'Available'],
            ],
            'Resources' => [
                ['[te_resources]', 'Resource listing/download cards', 'Available'],
                ['[te_resources_hero]', 'Resources page hero', 'Planned'],
                ['[te_resources_cta]', 'Resources CTA section', 'Planned'],
            ],
            'Company / About' => [
                ['[te_about]', 'Full approved Who We Are page', 'Available'],
                ['[te_about_hero]', 'Company page hero alias', 'Available'],
                ['[te_company_story]', 'Company story section', 'Planned'],
                ['[te_mission_vision]', 'Mission and vision section', 'Planned'],
                ['[te_core_values]', 'Core values cards', 'Planned'],
                ['[te_leadership]', 'Leadership cards', 'Planned'],
                ['[te_company_stats]', 'Company metrics', 'Planned'],
                ['[te_about_cta]', 'Company CTA section alias', 'Available'],
            ],
            'Services / What We Do' => [
                ['[te_services_hero]', 'Services page hero', 'Planned'],
                ['[te_services_grid]', 'Full services grid', 'Planned'],
                ['[te_partner_solutions]', 'Partner solutions section', 'Planned'],
                ['[te_services_cta]', 'Services CTA section', 'Planned'],
            ],
        ];
        echo '<section class="te-panel"><div class="te-panel-head"><h2>Shortcodes Reference</h2><span>Current catalogue and status</span></div>';
        foreach ($groups as $group => $items) {
            echo '<div class="te-shortcode-group"><h3>'.esc_html($group).'</h3><div class="te-shortcode-table">';
            foreach ($items as $item) {
                $status_class = strtolower($item[2]);
                echo '<div class="te-shortcode-row"><code>'.esc_html($item[0]).'</code><span>'.esc_html($item[1]).'</span><b class="te-status-'.esc_attr($status_class).'">'.esc_html($item[2]).'</b></div>';
            }
            echo '</div></div>';
        }
        echo '</section>';
    }

    private static function website_tile($title, $text, $meta, $page = 'website') {
        echo '<article class="te-website-tile"><h3>'.esc_html($title).'</h3><p>'.esc_html($text).'</p><code>'.esc_html($meta).'</code><a class="te-tile-link" href="'.esc_url(admin_url('admin.php?page=tranter-engine-'.$page)).'">Open →</a></article>';
    }

    private static function website_sections() {
        $sections = get_posts(['post_type'=>'tranter_section', 'numberposts'=>-1, 'post_status'=>['publish','draft','pending'], 'orderby'=>'menu_order title', 'order'=>'ASC']);
        echo '<section class="te-title-row"><div><span class="te-kicker">Website Sections</span><h1>Sections</h1><p>Edit shortcode content without touching page code.</p></div><a class="te-btn te-btn-primary" href="'.esc_url(admin_url('post-new.php?post_type=tranter_section')).'">Add Section</a></section>';
        echo '<nav class="te-tabs"><a class="is-active" href="'.esc_url(admin_url('admin.php?page=tranter-engine-sections')).'">Sections</a><a href="'.esc_url(admin_url('admin.php?page=tranter-engine-services')).'">Services</a><a href="'.esc_url(admin_url('admin.php?page=tranter-engine-partners')).'">Partners</a><a href="'.esc_url(admin_url('admin.php?page=tranter-engine-ctas')).'">CTAs</a></nav>';
        echo '<section class="te-panel"><div class="te-panel-head"><h2>Homepage shortcodes</h2><span>Use section by section</span></div><div class="te-shortcode-grid"><code>[te_home_hero]</code><code>[te_who_we_are]</code><code>[te_services]</code><code>[te_global_delivery]</code><code>[te_industries]</code><code>[te_how_we_work]</code><code>[te_why_tranter]</code><code>[te_metrics]</code><code>[te_latest_insights]</code><code>[te_clients]</code><code>[te_faq]</code><code>[te_cta]</code></div></section>';
        echo '<section class="te-panel"><div class="te-panel-head"><h2>Insights page shortcodes</h2><span>Create a page named Insights and add these in order</span></div><pre class="te-code-block">[te_insights_hero]\n[te_featured_insight]\n[te_insights_search]\n[te_insights_tags]\n[te_insights_grid limit="9"]\n[te_featured_resource]\n[te_newsletter title="Stay ahead of technology trends" subtitle="Receive practical Tranter insights, guides and event updates directly in your inbox."]\n[te_insights_cta]</pre></section>';
        if (!$sections) { echo '<section class="te-empty"><h3>No sections yet.</h3><p>Reactivate the plugin to seed starter website sections.</p></section>'; return; }
        echo '<section class="te-card-grid">';
        foreach ($sections as $post) {
            $markets = get_post_meta($post->ID, '_tranter_markets', true); if (!is_array($markets)) $markets = ['ng','global'];
            $key = get_post_meta($post->ID, '_tranter_section_key', true) ?: $post->post_name;
            echo '<article class="te-content-card"><div class="te-content-top"><div class="te-content-icon">'.tranter_engine_svg_icon('section').'</div><span>'.esc_html(ucfirst($post->post_status)).'</span></div><div class="te-card-body"><h3>'.esc_html($post->post_title).'</h3><p>'.esc_html(wp_trim_words($post->post_excerpt ?: wp_strip_all_tags($post->post_content), 18)).'</p><code class="te-inline-code">[te_'.esc_html(str_replace('-', '_', $key)).']</code><div class="te-badges">';
            foreach ($markets as $m) echo '<span>'.esc_html(tranter_engine_market_label($m)).'</span>';
            echo '</div></div><div class="te-card-actions"><a href="'.esc_url(admin_url('admin.php?page=tranter-engine-sections&section_id='.$post->ID)).'">Edit</a><a href="'.esc_url(get_permalink($post->ID)).'" target="_blank">View</a></div></article>';
        }
        echo '</section>';
    }



    private static function section_editor($section_id) {
        $post = get_post($section_id);
        if (!$post || $post->post_type !== 'tranter_section') {
            echo '<section class="te-empty"><h3>Section not found.</h3><p>Return to Website Sections and choose a valid section.</p></section>';
            return;
        }
        $markets = get_post_meta($post->ID, '_tranter_markets', true);
        if (!is_array($markets)) $markets = ['ng','global'];
        $key = get_post_meta($post->ID, '_tranter_section_key', true) ?: $post->post_name;
        $shortcode = '[te_'.str_replace('-', '_', $key).']';
        echo '<section class="te-title-row"><div><span class="te-kicker">Edit Website Section</span><h1>'.esc_html($post->post_title).'</h1><p>Update this shortcode section without touching page code.</p></div><a class="te-btn" href="'.esc_url(admin_url('admin.php?page=tranter-engine-sections')).'">Back to Sections</a></section>';
        echo '<form class="te-panel te-form te-section-editor" method="post" action="'.esc_url(admin_url('admin-post.php')).'">';
        wp_nonce_field('tranter_engine_save_section');
        echo '<input type="hidden" name="action" value="tranter_engine_save_section">';
        echo '<input type="hidden" name="section_id" value="'.esc_attr($post->ID).'">';
        echo '<div class="te-panel-head"><h2>Section Content</h2><span><code>'.esc_html($shortcode).'</code></span></div>';
        echo '<label>Section pill / title<input type="text" name="section_title" value="'.esc_attr($post->post_title).'" placeholder="Example: Who We Are"></label>';
        echo '<label>Supporting H2 headline<input type="text" name="section_excerpt" value="'.esc_attr($post->post_excerpt).'" placeholder="Example: Delivering Secure and Scalable Technology Solutions"></label>';
        echo '<label>Supporting paragraph<textarea name="section_content" rows="7" placeholder="Write the paragraph or supporting copy for this section.">'.esc_textarea($post->post_content).'</textarea></label>';
        echo '<fieldset class="te-market-checks"><legend>Market visibility</legend>';
        echo '<label><input type="checkbox" name="markets[]" value="ng" '.checked(in_array('ng', $markets, true), true, false).'> Nigeria</label>';
        echo '<label><input type="checkbox" name="markets[]" value="global" '.checked(in_array('global', $markets, true), true, false).'> US/World</label>';
        echo '</fieldset>';
        echo '<div class="te-form-actions"><button class="te-btn te-btn-primary" type="submit">Save Section</button><a class="te-btn" href="'.esc_url(admin_url('admin.php?page=tranter-engine-sections')).'">Cancel</a></div>';
        echo '</form>';
    }


    private static function company_workspace() {
        echo '<section class="te-title-row"><div><span class="te-kicker">Company Module</span><h1>Who We Are</h1><p>Use the approved supplied Who We Are frontend exactly as designed, with Tranter site header and footer around it.</p></div><a class="te-btn te-btn-primary" href="'.esc_url(home_url('/who-we-are/')).'" target="_blank">Preview Page</a></section>';
        echo '<section class="te-panel"><div class="te-panel-head"><h2>Recommended Page Setup</h2><span>Use this exact shortcode stack</span></div><pre class="te-code-block">[te_site_header]\n\n[te_about]\n\n[te_site_footer]</pre><p class="te-muted">The [te_about] shortcode outputs the approved supplied HTML, CSS, video modal, leadership carousel, FAQ schema and demo form modal.</p></section>';
        echo '<section class="te-panel"><div class="te-panel-head"><h2>Available Company Shortcodes</h2><span>v1.5.1</span></div><div class="te-shortcode-table">';
        $refs = class_exists('Tranter_Company') ? Tranter_Company::shortcode_reference() : [];
        foreach ($refs as $code => $desc) {
            echo '<div class="te-shortcode-row"><code>'.esc_html($code).'</code><span>'.esc_html($desc).'</span><b class="te-status-available">Available</b></div>';
        }
        echo '</div></section>';
        echo '<section class="te-panel"><div class="te-panel-head"><h2>Important</h2><span>Design fidelity</span></div><p>The previous generated version used simplified modular cards. This workspace now points to the supplied code template so the frontend matches the approved design instead of a recreated layout.</p></section>';
    }

    private static function market_manager() {
        echo '<section class="te-title-row"><div><span class="te-kicker">Market Manager</span><h1>Markets</h1><p>Control what Nigeria and US/World visitors see.</p></div></section>';
        echo '<section class="te-grid te-grid-2">';
        self::market_card('Nigeria', 'ng', ['All 7 core services', 'Zoho, ManageEngine, Sophos', 'Events enabled', 'NG insights and CTAs']);
        self::market_card('US/World', 'global', ['Smart Solutions hidden from services/navigation', 'Zoho only', 'No ManageEngine/Sophos', 'No Events in navigation', 'US/World insights and campaigns']);
        echo '</section>';
    }

    private static function market_card($label, $key, $rules) {
        echo '<article class="te-market-card"><div class="te-market-head"><span>'.esc_html($label).'</span><strong>'.esc_html($key).'</strong></div><ul class="te-clean-list">';
        foreach ($rules as $rule) echo '<li>'.esc_html($rule).'</li>';
        echo '</ul><a class="te-btn" href="'.esc_url(add_query_arg('tranter_market',$key,home_url('/'))).'" target="_blank">Preview '.esc_html($label).'</a></article>';
    }

    private static function content_cards($post_type, $title, $subtitle, $icon) {
        $posts = get_posts(['post_type'=>$post_type, 'numberposts'=>-1, 'post_status'=>['publish','draft','pending']]);
        echo '<section class="te-title-row"><div><span class="te-kicker">Content Workspace</span><h1>'.esc_html($title).'</h1><p>'.esc_html($subtitle).'</p></div><a class="te-btn te-btn-primary" href="'.esc_url(admin_url('post-new.php?post_type='.$post_type)).'">Add New</a></section>';
        self::content_tabs($post_type);
        if (!$posts) { echo '<section class="te-empty"><h3>No records yet.</h3><p>Add your first item or reactivate the plugin to seed starter content.</p></section>'; return; }
        echo '<section class="te-card-grid">';
        foreach ($posts as $post) {
            $markets = get_post_meta($post->ID, '_tranter_markets', true); if (!is_array($markets)) $markets = ['ng','global'];
            echo '<article class="te-content-card"><div class="te-content-top"><div class="te-content-icon">'.tranter_engine_svg_icon($icon).'</div><span>'.esc_html(ucfirst($post->post_status)).'</span></div><div class="te-card-body"><h3>'.esc_html($post->post_title).'</h3><p>'.esc_html(wp_trim_words($post->post_excerpt ?: wp_strip_all_tags($post->post_content), 18)).'</p><div class="te-badges">';
            foreach ($markets as $m) echo '<span>'.esc_html(tranter_engine_market_label($m)).'</span>';
            echo '</div></div><div class="te-card-actions"><a href="'.esc_url(admin_url('admin.php?page=tranter-engine-sections&section_id='.$post->ID)).'">Edit</a><a href="'.esc_url(get_permalink($post->ID)).'" target="_blank">View</a></div></article>';
        }
        echo '</section>';
    }

    private static function content_tabs($current_post_type) {
        $tabs = [
            'tranter_service' => ['Technology Solutions','services'],
            'tranter_partner' => ['Partner Solutions','partners'],
            'tranter_product' => ['Partner Products','products'],
            'tranter_insight' => ['Insights','insights'],
            'tranter_event' => ['Events','events'],
            'tranter_resource' => ['Resources','resources'],
            'tranter_faq' => ['FAQs','faqs'],
        ];
        echo '<nav class="te-tabs">';
        foreach ($tabs as $type => $tab) echo '<a class="'.($type === $current_post_type ? 'is-active' : '').'" href="'.esc_url(admin_url('admin.php?page=tranter-engine-'.$tab[1])).'">'.esc_html($tab[0]).'</a>';
        echo '</nav>';
    }


    private static function resources_workspace() {
        $resources = get_posts(['post_type'=>'tranter_resource', 'numberposts'=>-1, 'post_status'=>['publish','draft','pending']]);
        echo '<section class="te-title-row"><div><span class="te-kicker">Lead Resources</span><h1>Resources</h1><p>Attach whitepapers, guides, checklists and case studies to campaigns and insights.</p></div><a class="te-btn te-btn-primary" href="'.esc_url(admin_url('post-new.php?post_type=tranter_resource')).'">Add Resource</a></section>';
        echo '<section class="te-panel"><div class="te-panel-head"><h2>Resource shortcode</h2><span>Use on landing pages</span></div><pre class="te-code-block">[te_resources]</pre></section>';
        if (!$resources) { echo '<section class="te-empty"><h3>No resources yet.</h3><p>Add your first downloadable guide or checklist.</p></section>'; return; }
        echo '<section class="te-card-grid">';
        foreach ($resources as $post) {
            $downloads = (int) get_post_meta($post->ID, '_tranter_downloads', true);
            $gated = get_post_meta($post->ID, '_tranter_resource_gated', true) === '1' ? 'Gated' : 'Open';
            echo '<article class="te-content-card"><div class="te-content-top"><div class="te-content-icon">'.tranter_engine_svg_icon('insight').'</div><span>'.esc_html($gated).'</span></div><div class="te-card-body"><h3>'.esc_html($post->post_title).'</h3><p>'.esc_html(wp_trim_words($post->post_excerpt ?: wp_strip_all_tags($post->post_content), 18)).'</p><div class="te-badges"><span>'.esc_html($downloads).' downloads</span></div></div><div class="te-card-actions"><a href="'.esc_url(get_edit_post_link($post->ID)).'">Edit</a><a href="'.esc_url(get_permalink($post->ID)).'" target="_blank">View</a></div></article>';
        }
        echo '</section>';
    }

    private static function subscribers_workspace() {
        $subscribers = get_posts(['post_type'=>'tranter_subscriber', 'numberposts'=>100, 'post_status'=>'publish', 'orderby'=>'date', 'order'=>'DESC']);
        echo '<section class="te-title-row"><div><span class="te-kicker">Newsletter Leads</span><h1>Subscribers</h1><p>Review leads captured from newsletter and resource campaigns.</p></div></section>';
        echo '<section class="te-panel"><div class="te-panel-head"><h2>Newsletter shortcode</h2><span>Lead capture</span></div><pre class="te-code-block">[te_newsletter]</pre></section>';
        echo '<section class="te-panel"><div class="te-panel-head"><h2>Recent Subscribers</h2><span>'.esc_html(count($subscribers)).' shown</span></div><table class="widefat striped"><thead><tr><th>Name</th><th>Email</th><th>Company</th><th>Interest</th><th>Source</th><th>Date</th></tr></thead><tbody>';
        if (!$subscribers) echo '<tr><td colspan="6">No subscribers yet.</td></tr>';
        foreach ($subscribers as $sub) {
            echo '<tr><td>'.esc_html($sub->post_title).'</td><td>'.esc_html(get_post_meta($sub->ID, '_tranter_subscriber_email', true)).'</td><td>'.esc_html(get_post_meta($sub->ID, '_tranter_company', true)).'</td><td>'.esc_html(get_post_meta($sub->ID, '_tranter_interest', true)).'</td><td>'.esc_html(get_post_meta($sub->ID, '_tranter_source', true)).'</td><td>'.esc_html(get_post_meta($sub->ID, '_tranter_subscribed_at', true)).'</td></tr>';
        }
        echo '</tbody></table></section>';
    }

    private static function ai_workspace() {
        echo '<section class="te-title-row"><div><span class="te-kicker">AI Workspace</span><h1>AI Assistant</h1><p>Prepare the website assistant for real-time visitor support and lead qualification.</p></div></section>';
        echo '<section class="te-grid te-grid-3">';
        self::module_card('Connection', 'Set a secure backend endpoint before going live.', 'Open Settings', 'settings', 'dashicons-admin-links');
        self::module_card('Knowledge', 'Services, partners, FAQs, insights and market rules.', 'Review Content', 'services', 'dashicons-book');
        self::module_card('Lead Capture', 'Route qualified conversations into Zoho Forms/CRM.', 'Review CTAs', 'ctas', 'dashicons-megaphone');
        echo '</section>';
    }

    private static function seo_workspace() { self::placeholder('SEO Workspace', 'Manage market-aware SEO defaults, schema, OpenGraph and content health checks.'); }
    private static function analytics_workspace() {
        require_once TRANTER_ENGINE_PATH . 'includes/analytics.php';
        $kpis = Tranter_Analytics::get_kpis();
        echo '<section class="te-title-row"><div><span class="te-kicker">Business Intelligence</span><h1>Analytics</h1><p>Measure service performance, market trends and lead quality.</p></div></section>';
        ?>
        <section class="te-dashboard-grid">
            <article class="te-mini-stat">
                <strong><?php echo esc_html($kpis['published']); ?></strong>
                <span>Published Insights</span>
                <p>Live on website</p>
            </article>
            <article class="te-mini-stat">
                <strong><?php echo esc_html($kpis['drafts']); ?></strong>
                <span>Draft Insights</span>
                <p>Work in progress</p>
            </article>
            <article class="te-mini-stat">
                <strong><?php echo tranter_engine_format_number($kpis['total_views']); ?></strong>
                <span>Total Views</span>
                <p>Across all insights</p>
            </article>
            <article class="te-mini-stat">
                <strong><?php echo esc_html($kpis['subscribers']); ?></strong>
                <span>Subscribers</span>
                <p>Knowledge Hub growth</p>
            </article>
        </section>
        <section class="te-panel">
            <div class="te-panel-head"><h2>Top Performing Insight</h2></div>
            <div style="padding: 20px;">
                <h3><?php echo esc_html($kpis['top_insight']); ?></h3>
                <p>This insight has the highest engagement this month.</p>
            </div>
        </section>
        <?php
    }

    private static function placeholder($title, $message) {
        echo '<section class="te-title-row"><div><span class="te-kicker">Planned module</span><h1>'.esc_html($title).'</h1><p>'.esc_html($message).'</p></div></section>';
        echo '<section class="te-panel"><h2>Next integration</h2><p>'.esc_html($message).'</p></section>';
    }

    private static function settings() {
        $settings = get_option('tranter_engine_settings', []);
        echo '<section class="te-title-row"><div><span class="te-kicker">Settings</span><h1>Integrations</h1><p>Connect forms, WhatsApp and AI services.</p></div></section>';
        echo '<form class="te-panel te-form" method="post" action="'.esc_url(admin_url('admin-post.php')).'">';
        wp_nonce_field('tranter_engine_save_settings');
        echo '<input type="hidden" name="action" value="tranter_engine_save_settings">';
        echo '<label>Zoho Form URL<input type="url" name="zoho_form_url" value="'.esc_attr($settings['zoho_form_url'] ?? tranter_engine_cta_url('demo')).'"></label>';
        echo '<label>WhatsApp URL<input type="url" name="whatsapp_url" value="'.esc_attr($settings['whatsapp_url'] ?? tranter_engine_cta_url('whatsapp')).'"></label>';
        echo '<label>AI Chat API Endpoint<input type="url" name="ai_endpoint" placeholder="/wp-json/tranter-ai/v1/chat" value="'.esc_attr($settings['ai_endpoint'] ?? '').'"></label>';
        echo '<button class="te-btn te-btn-primary" type="submit">Save Settings</button></form>';
    }



    public static function save_section() {
        check_admin_referer('tranter_engine_save_section');
        $section_id = absint($_POST['section_id'] ?? 0);
        if (!$section_id || !current_user_can('manage_options')) {
            wp_die('You do not have permission to save this section.');
        }
        $post = get_post($section_id);
        if (!$post || $post->post_type !== 'tranter_section') {
            wp_die('Invalid section.');
        }
        $title = sanitize_text_field($_POST['section_title'] ?? '');
        $excerpt = sanitize_text_field($_POST['section_excerpt'] ?? '');
        $content = wp_kses_post($_POST['section_content'] ?? '');
        $markets = isset($_POST['markets']) && is_array($_POST['markets']) ? array_values(array_intersect(array_map('sanitize_key', $_POST['markets']), ['ng','global'])) : [];
        if (!$markets) $markets = ['ng','global'];
        wp_update_post([
            'ID' => $section_id,
            'post_title' => $title ?: $post->post_title,
            'post_excerpt' => $excerpt,
            'post_content' => $content,
            'post_status' => 'publish',
        ]);
        update_post_meta($section_id, '_tranter_markets', $markets);
        wp_redirect(admin_url('admin.php?page=tranter-engine-sections&section_id='.$section_id.'&updated=1'));
        exit;
    }

    public static function save_settings() {
        check_admin_referer('tranter_engine_save_settings');
        update_option('tranter_engine_settings', [
            'zoho_form_url' => esc_url_raw($_POST['zoho_form_url'] ?? ''),
            'whatsapp_url' => esc_url_raw($_POST['whatsapp_url'] ?? ''),
            'ai_endpoint' => esc_url_raw($_POST['ai_endpoint'] ?? ''),
        ]);
        wp_redirect(admin_url('admin.php?page=tranter-engine-settings&updated=1'));
        exit;
    }
}
