<?php
if (!defined('ABSPATH')) exit;

class Tranter_Lean_Admin {
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'menu'], 999);
        add_action('admin_enqueue_scripts', [__CLASS__, 'assets']);
        add_action('admin_head', [__CLASS__, 'inline_styles']);
    }

    public static function menu() {
        global $submenu;
        remove_menu_page('tranter-engine');
        if (isset($submenu['tranter-engine'])) unset($submenu['tranter-engine']);
        add_menu_page('Tranter Hub', 'Tranter Hub', 'manage_options', 'tranter-engine', [__CLASS__, 'dashboard'], 'dashicons-chart-area', 3);
        if (isset($submenu['tranter-engine'][0][0])) $submenu['tranter-engine'][0][0] = 'Dashboard';
        foreach (self::items() as $key => $item) {
            if ($key === 'dashboard') continue;
            add_submenu_page('tranter-engine', $item[0], $item[0], 'manage_options', 'tranter-engine-' . $key, [__CLASS__, 'route']);
        }
    }

    public static function assets($hook) {
        $page = isset($_GET['page']) ? sanitize_key($_GET['page']) : '';
        if (strpos($hook, 'tranter-engine') === false && strpos($page, 'tranter-engine') !== 0) return;
        wp_enqueue_style('tranter-engine-mulish', 'https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap', [], null);
        wp_enqueue_style('tranter-engine-admin', TRANTER_ENGINE_URL . 'assets/css/admin.css', ['tranter-engine-mulish'], TRANTER_ENGINE_VERSION);
        wp_enqueue_script('tranter-engine-admin', TRANTER_ENGINE_URL . 'assets/js/admin.js', [], TRANTER_ENGINE_VERSION, true);
        $critical_css_path = TRANTER_ENGINE_PATH . 'assets/css/admin.css';
        if (file_exists($critical_css_path)) wp_add_inline_style('tranter-engine-admin', file_get_contents($critical_css_path));
    }

    private static function items() {
        return [
            'dashboard' => ['Dashboard','dashicons-dashboard'],
            'website' => ['Website Engine','dashicons-admin-site'],
            'page-templates' => ['Page Templates','dashicons-media-code'],
            'campaigns' => ['Campaigns','dashicons-megaphone'],
            'events' => ['Events','dashicons-calendar-alt'],
            'insights' => ['Insights','dashicons-welcome-write-blog'],
            'knowledge-base' => ['Knowledge Base','dashicons-book-alt'],
            'leads-analytics' => ['Leads & Analytics','dashicons-chart-bar'],
            'settings' => ['Settings','dashicons-admin-generic'],
        ];
    }

    private static function screen() {
        $page = isset($_GET['page']) ? sanitize_key($_GET['page']) : 'tranter-engine-dashboard';
        return str_replace('tranter-engine-', '', $page === 'tranter-engine' ? 'dashboard' : $page);
    }

    public static function dashboard() { self::route(); }

    public static function route() {
        $screen = self::screen();
        echo '<div class="te-app te-workspace te-lean-app">';
        self::header($screen);
        echo '<main class="te-main">';
        switch ($screen) {
            case 'website': self::website(); break;
            case 'page-templates': Tranter_Page_Templates::render(); break;
            case 'campaigns': self::campaigns(); break;
            case 'events': self::events(); break;
            case 'insights': self::insights(); break;
            case 'knowledge-base': self::knowledge_base(); break;
            case 'leads-analytics': self::analytics(); break;
            case 'settings': self::settings(); break;
            default: self::dashboard_content(); break;
        }
        echo '</main></div>';
    }

    private static function header($active) {
        $market = class_exists('Tranter_Market') ? tranter_engine_market_label(Tranter_Market::current()) : 'Nigeria';
        echo '<header class="te-app-header"><div class="te-brand-inline"><span class="te-brand-mark">TH</span><div><strong>Tranter Hub</strong><em>v'.esc_html(TRANTER_ENGINE_VERSION).' Sales Hub</em></div></div><nav class="te-workspace-nav">';
        foreach (self::items() as $key => $item) {
            $href = $key === 'dashboard' ? 'tranter-engine' : 'tranter-engine-' . $key;
            echo '<a class="'.esc_attr($active === $key ? 'is-active' : '').'" href="'.esc_url(admin_url('admin.php?page=' . $href)).'"><span class="dashicons '.esc_attr($item[1]).'"></span>'.esc_html($item[0]).'</a>';
        }
        echo '</nav><div class="te-header-actions"><button class="te-search" type="button">HTML + Editorial</button><span class="te-market-switch">'.esc_html($market).'</span></div></header>';
    }

    private static function dashboard_content() {
        $events = intval(wp_count_posts('tranter_event')->publish ?? 0);
        $insights = intval(wp_count_posts('tranter_insight')->publish ?? 0);
        $campaigns = intval(wp_count_posts('tranter_campaign')->publish ?? 0);
        $resources = intval(wp_count_posts('tranter_resource')->publish ?? 0);
        $templates = class_exists('Tranter_Page_Templates') ? count(Tranter_Page_Templates::templates()) : 0;
        echo '<section class="te-title-row te-modern-dashboard-hero"><div><span class="te-kicker">Mission Control</span><h1>Tranter Hub</h1><p>A lean command centre for the Tranter sales website, HTML page templates, campaigns, events, insights, knowledge base and lead intelligence.</p></div><a class="te-btn te-btn-primary" href="'.esc_url(admin_url('admin.php?page=tranter-engine-page-templates')).'">Open Page Templates</a></section>';
        echo '<section class="te-dashboard-grid te-dashboard-modern">';
        self::stat('Page Templates', $templates, 'Editable HTML codebases', 'dashicons-media-code');
        self::stat('Campaigns', $campaigns, 'Full HTML campaign pages', 'dashicons-megaphone');
        self::stat('Events', $events, 'Flexible inputs + optional HTML', 'dashicons-calendar-alt');
        self::stat('Insights', $insights, 'Advanced editorial content', 'dashicons-welcome-write-blog');
        self::stat('Knowledge Base', $resources, 'AI assistant source content', 'dashicons-book-alt');
        echo '</section><section class="te-grid te-grid-3">';
        self::module('Website Engine', 'Global header, footer, Book a Demo popup, WhatsApp, scripts and market logic.', 'Open Engine', 'website', 'dashicons-admin-site');
        self::module('Knowledge Base', 'Approved content for the footer AI assistant and visitor guidance.', 'Open Knowledge Base', 'knowledge-base', 'dashicons-book-alt');
        self::module('Leads & Analytics', 'Track page, campaign, event, insight and AI assistant performance.', 'View Analytics', 'leads-analytics', 'dashicons-chart-bar');
        echo '</section><section class="te-panel"><div class="te-panel-head"><h2>v1.7.3 Direction</h2><span>HTML + Editorial + AI Knowledge</span></div><ul class="te-clean-list"><li>Pages and campaigns use editable HTML/widget code.</li><li>Events support simple fields for non-technical staff plus optional HTML for developers.</li><li>Insights use an advanced editorial text editor, not pasted HTML.</li><li>Knowledge Base is the source of truth for the footer AI Assistant.</li><li>Tracking uses <code>data-te-event</code>, <code>data-te-service</code> and <code>data-te-source</code>.</li></ul></section>';
    }

    private static function website() {
        echo '<section class="te-title-row"><div><span class="te-kicker">Website Engine</span><h1>Website Engine</h1><p>Global infrastructure for the Tranter website: header, footer, Book a Demo, tracking standards, footer AI assistant, market logic and shared scripts.</p></div><a class="te-btn te-btn-primary" href="'.esc_url(admin_url('admin.php?page=tranter-engine-page-templates')).'">Open Page Templates</a></section>';
        echo '<section class="te-grid te-grid-4">';
        self::tile('Global Book a Demo', 'Use one shared popup form across all HTML templates.', 'data-tdp-open');
        self::tile('Footer AI Assistant', 'Connect visitor questions to approved Knowledge Base content.', 'Knowledge Base');
        self::tile('Tracking Attributes', 'Add sales and marketing intelligence to links and buttons.', 'data-te-event');
        self::tile('HTML Page Bodies', 'Build page bodies inside Elementor HTML widgets.', 'View + Copy HTML');
        echo '</section><section class="te-panel"><div class="te-panel-head"><h2>CTA Standard</h2><span>Use in every HTML codebase</span></div><pre class="te-code-block">&lt;a href=&quot;#&quot; data-tdp-open data-te-event=&quot;book_demo&quot; data-te-service=&quot;it_support&quot;&gt;Book a Demo&lt;/a&gt;\n\n&lt;a href=&quot;#&quot; data-tdp-open data-te-event=&quot;book_demo&quot; data-te-source=&quot;ai_assistant&quot;&gt;Book a Demo&lt;/a&gt;</pre></section>';
    }

    private static function campaigns() {
        echo '<section class="te-title-row"><div><span class="te-kicker">Campaign Engine</span><h1>Campaigns</h1><p>Campaigns remain the reference workflow: paste full HTML/widget code, publish, then track views, clicks and conversions.</p></div><a class="te-btn te-btn-primary" href="'.esc_url(admin_url('post-new.php?post_type=tranter_campaign')).'">Add Campaign</a></section>';
        self::list_posts('tranter_campaign', 'dashicons-megaphone');
    }

    private static function events() {
        echo '<section class="te-title-row"><div><span class="te-kicker">Event Engine</span><h1>Events</h1><p>Events support both non-technical event inputs and optional developer HTML codebases for full landing-page control.</p></div><a class="te-btn te-btn-primary" href="'.esc_url(admin_url('post-new.php?post_type=tranter_event')).'">Add Event</a></section>';
        echo '<section class="te-panel"><div class="te-panel-head"><h2>Event Input Direction</h2><span>Flexible input</span></div><p class="te-muted">Non-technical staff can add event title, short description, date, time, location, registration URL and featured image. Developers can optionally paste a full HTML/widget codebase for custom event pages. Registration tracking should use <code>data-te-event=&quot;event_register&quot;</code>.</p></section>';
        self::list_posts('tranter_event', 'dashicons-calendar-alt');
    }

    private static function insights() {
        echo '<section class="te-title-row"><div><span class="te-kicker">Editorial Engine</span><h1>Insights</h1><p>Insights are advanced editorial content, not HTML-first pages. Use the WordPress editor for rich text, featured image, categories, SEO, CTA controls and analytics.</p></div><a class="te-btn te-btn-primary" href="'.esc_url(admin_url('post-new.php?post_type=tranter_insight')).'">Add Insight</a></section>';
        echo '<section class="te-grid te-grid-3">';
        self::tile('Advanced Text Editor', 'Use rich text for thought leadership and articles.', 'Editorial');
        self::tile('CTA Controls', 'Book a Demo, WhatsApp and newsletter CTAs should be inserted by the module.', 'Tracked');
        self::tile('Content Metadata', 'Categories, tags, featured images, SEO and related service mapping.', 'Structured');
        echo '</section>';
        self::list_posts('tranter_insight', 'dashicons-welcome-write-blog');
    }

    private static function knowledge_base() {
        echo '<section class="te-title-row"><div><span class="te-kicker">AI Source of Truth</span><h1>Knowledge Base</h1><p>Approved content that powers the footer AI assistant, visitor guidance, service recommendations and tracked lead actions.</p></div><a class="te-btn te-btn-primary" href="'.esc_url(admin_url('post-new.php?post_type=tranter_resource')).'">Add Knowledge Item</a></section>';
        echo '<section class="te-grid te-grid-3">';
        self::tile('AI Assistant Source', 'Use approved documents, FAQs, service notes and resources to guide footer chat responses.', 'Approved content');
        self::tile('Lead Guidance', 'The AI assistant should recommend Book a Demo, WhatsApp, events, insights or service pages.', 'Tracked actions');
        self::tile('Content Governance', 'Keep sales answers consistent by managing what the AI is allowed to reference.', 'Single source');
        echo '</section>';
        self::list_posts('tranter_resource', 'dashicons-book-alt');
    }

    private static function analytics() {
        echo '<section class="te-title-row"><div><span class="te-kicker">Sales Intelligence</span><h1>Leads & Analytics</h1><p>Central reporting for page views, CTA clicks, Book a Demo opens, WhatsApp clicks, campaign conversions, event registrations, insight performance and AI assistant question trends.</p></div></section>';
        echo '<section class="te-grid te-grid-3">';
        self::tile('Demo Intent', 'Track all global Book a Demo opens.', 'data-tdp-open');
        self::tile('Service Interest', 'Map CTA clicks to pages, services and AI conversations.', 'data-te-service');
        self::tile('AI Assistant Demand', 'Track common questions and service intent from footer chat.', 'ai_assistant');
        echo '</section>';
    }

    private static function settings() {
        echo '<section class="te-title-row"><div><span class="te-kicker">Settings</span><h1>Settings</h1><p>Core website settings for global scripts, WhatsApp, Book a Demo, AI assistant and tracking.</p></div></section>';
        echo '<section class="te-panel"><div class="te-panel-head"><h2>Current phase</h2><span>v1.7.3</span></div><p class="te-muted">Settings controls will be tightened after the lean backend, template workflow and Knowledge Base are stable.</p></section>';
    }

    private static function list_posts($post_type, $icon) {
        $posts = get_posts(['post_type'=>$post_type, 'numberposts'=>12, 'post_status'=>['publish','draft','pending'], 'orderby'=>'date', 'order'=>'DESC']);
        if (!$posts) { echo '<section class="te-empty"><h3>No records yet.</h3><p>Add your first item to begin.</p></section>'; return; }
        echo '<section class="te-card-grid">';
        foreach ($posts as $post) {
            echo '<article class="te-content-card"><div class="te-content-top"><div class="te-content-icon"><span class="dashicons '.esc_attr($icon).'"></span></div><span>'.esc_html(ucfirst($post->post_status)).'</span></div><div class="te-card-body"><h3>'.esc_html($post->post_title).'</h3><p>'.esc_html(wp_trim_words($post->post_excerpt ?: wp_strip_all_tags($post->post_content), 18)).'</p></div><div class="te-card-actions"><a href="'.esc_url(get_edit_post_link($post->ID)).'">Edit</a><a href="'.esc_url(get_permalink($post->ID)).'" target="_blank">View</a></div></article>';
        }
        echo '</section>';
    }

    private static function stat($label, $value, $note, $icon) {
        echo '<article class="te-mini-stat"><div class="te-icon"><span class="dashicons '.esc_attr($icon).'"></span></div><strong>'.intval($value).'</strong><span>'.esc_html($label).'</span><p>'.esc_html($note).'</p></article>';
    }

    private static function module($title, $text, $button, $page, $icon) {
        echo '<article class="te-module"><span class="dashicons '.esc_attr($icon).'"></span><h3>'.esc_html($title).'</h3><p>'.esc_html($text).'</p><a href="'.esc_url(admin_url('admin.php?page=tranter-engine-'.$page)).'">'.esc_html($button).'</a></article>';
    }

    private static function tile($title, $text, $meta) {
        echo '<article class="te-website-tile"><h3>'.esc_html($title).'</h3><p>'.esc_html($text).'</p><code>'.esc_html($meta).'</code></article>';
    }

    public static function inline_styles() {
        $page = isset($_GET['page']) ? sanitize_key($_GET['page']) : '';
        if (strpos($page, 'tranter-engine') !== 0) return;
        echo '<style>.te-modern-dashboard-hero{background:radial-gradient(circle at 12% 20%,rgba(0,155,85,.16),transparent 32%),radial-gradient(circle at 92% 18%,rgba(137,18,25,.11),transparent 30%),linear-gradient(135deg,#fff,#f7faf9);border:1px solid rgba(0,27,20,.06);border-radius:28px;padding:32px;box-shadow:0 24px 70px rgba(0,0,0,.055)}.te-template-grid{display:grid;grid-template-columns:1fr;gap:22px;margin-top:22px}.te-template-card{background:#fff;border:1px solid rgba(0,27,20,.08);border-radius:26px;padding:24px;box-shadow:0 24px 70px rgba(0,0,0,.055)}.te-template-card-head{display:flex;align-items:flex-start;justify-content:space-between;gap:18px;margin-bottom:18px}.te-template-card-head span{display:inline-flex;padding:8px 12px;border-radius:999px;background:rgba(0,155,85,.09);color:#009b55;font-size:11px;font-weight:900;text-transform:uppercase;letter-spacing:.08em}.te-template-card-head h2{margin:12px 0 8px;font-size:24px;line-height:1.15;font-weight:900;color:#001b14}.te-template-card-head p{margin:0;max-width:760px;color:rgba(0,27,20,.64);font-weight:700;line-height:1.65}.te-template-card-head b{white-space:nowrap;padding:9px 12px;border-radius:999px;background:rgba(137,18,25,.08);color:#891219;font-size:12px}.te-template-actions{display:flex;gap:12px;flex-wrap:wrap;margin:14px 0}.te-template-code,textarea.te-template-code{display:none!important;width:100%!important;min-height:460px!important;margin-top:16px!important;padding:18px!important;border-radius:18px!important;border:1px solid rgba(0,27,20,.16)!important;background:#fff!important;color:#0a2419!important;font-family:Consolas,Monaco,monospace!important;font-size:12px!important;line-height:1.55!important;white-space:pre!important;overflow:auto!important;box-shadow:inset 0 0 0 1px rgba(0,155,85,.06)!important}.te-template-code.is-visible,textarea.te-template-code.is-visible{display:block!important}.te-template-code::selection{background:rgba(0,155,85,.22)!important;color:#001b14!important}#wpbody-content .te-template-code{color:#0a2419!important;background:#fff!important}.te-template-rule-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}.te-template-rule-grid article{padding:16px;border:1px solid rgba(0,27,20,.08);border-radius:18px;background:#fff}.te-template-rule-grid strong{display:block;margin-bottom:10px;color:#001b14}.te-template-rule-grid code{display:block;white-space:normal;line-height:1.45;background:#f7faf8;border-radius:12px;padding:10px;color:#891219}.te-btn.is-copied{background:#891219!important;border-color:#891219!important;color:#fff!important}.te-clean-list code{padding:2px 7px;border-radius:8px;background:rgba(0,155,85,.09);color:#009b55;font-weight:900}@media(max-width:960px){.te-template-rule-grid{grid-template-columns:1fr}.te-template-card-head{display:block}.te-template-card-head b{display:inline-flex;margin-top:12px}.te-template-code{min-height:360px}}</style>';
    }
}
