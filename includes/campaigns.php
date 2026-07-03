<?php
if (!defined('ABSPATH')) exit;

class Tranter_Campaigns {
    public static function init() {
        add_action('admin_menu', [__CLASS__, 'admin_menu'], 25);
        add_action('add_meta_boxes', [__CLASS__, 'meta_boxes']);
        add_action('save_post_tranter_campaign', [__CLASS__, 'save_campaign_meta']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'public_assets']);
        add_action('wp_ajax_te_campaign_track', [__CLASS__, 'track']);
        add_action('wp_ajax_nopriv_te_campaign_track', [__CLASS__, 'track']);
        add_action('template_redirect', [__CLASS__, 'maybe_render_campaign_page'], 1);
        add_filter('display_post_states', [__CLASS__, 'campaign_page_state'], 10, 2);

        add_shortcode('te_campaign', [__CLASS__, 'campaign_shortcode']);
        add_shortcode('te_campaign_hero', [__CLASS__, 'campaign_hero_shortcode']);
        add_shortcode('te_campaign_html', [__CLASS__, 'campaign_html_shortcode']);
        add_shortcode('te_campaign_cta', [__CLASS__, 'campaign_cta_shortcode']);
        add_shortcode('te_campaign_footer', [__CLASS__, 'campaign_footer_shortcode']);
    }

    public static function admin_menu() {
        add_submenu_page('tranter-engine', 'Campaigns', 'Campaigns', 'manage_options', 'tranter-engine-campaigns', [__CLASS__, 'admin_workspace']);
    }

    public static function meta_boxes() {
        add_meta_box('te_campaign_settings', 'Campaign Settings', [__CLASS__, 'render_settings_box'], 'tranter_campaign', 'normal', 'high');
        add_meta_box('te_campaign_html', 'Campaign HTML / Embed Code', [__CLASS__, 'render_html_box'], 'tranter_campaign', 'normal', 'default');
        add_meta_box('te_campaign_tracking', 'Campaign Tracking', [__CLASS__, 'render_tracking_box'], 'tranter_campaign', 'side', 'default');
    }

    public static function render_settings_box($post) {
        wp_nonce_field('te_campaign_save', 'te_campaign_nonce');
        $type = get_post_meta($post->ID, '_te_campaign_type', true) ?: 'landing';
        $layout = get_post_meta($post->ID, '_te_campaign_layout', true) ?: 'html_only';
        $market = get_post_meta($post->ID, '_te_campaign_market', true) ?: 'both';
        $goal = get_post_meta($post->ID, '_te_campaign_goal', true) ?: 'Lead capture';
        $expiry = get_post_meta($post->ID, '_te_campaign_expiry', true);
        $tracking = get_post_meta($post->ID, '_te_campaign_tracking', true);
        if ($tracking === '') $tracking = '1';
        $page_id = absint(get_post_meta($post->ID, '_te_campaign_page_id', true));
        $page_url = $page_id ? get_permalink($page_id) : '';
        $slug_shortcode = $post->post_name ? $post->post_name : $post->ID;

        echo '<div class="te-campaign-builder-lite">';
        echo '<div class="te-cb-intro"><span>Campaign Builder</span><h3>Full HTML campaigns with automatic tracking</h3><p>Paste the campaign design below. The HTML can include its own header, footer, CTA buttons, forms and third-party widgets. Tranter Hub keeps the setup simple and tracks the important actions automatically.</p></div>';

        echo '<div class="te-cb-grid">';
        echo '<section class="te-cb-card te-cb-card-main"><div class="te-cb-card-title"><span>01</span><div><h4>Overview</h4><p>Define the campaign type, audience and goal.</p></div></div><div class="te-cb-fields">';
        self::select_field('Campaign Type', 'te_campaign_type', $type, [
            'landing' => 'Landing Page', 'lead_magnet' => 'Lead Magnet', 'webinar' => 'Webinar', 'conference' => 'Conference', 'product_launch' => 'Product Launch', 'promotion' => 'Promotion', 'custom_html' => 'Custom HTML'
        ]);
        self::select_field('Market', 'te_campaign_market', $market, [
            'both' => 'Nigeria + Global', 'ng' => 'Nigeria only', 'global' => 'Global only'
        ]);
        echo '<label><strong>Primary Goal</strong><input type="text" name="te_campaign_goal" value="'.esc_attr($goal).'" placeholder="Lead capture, event registration, download"></label>';
        echo '<label><strong>Expiry Date</strong><input type="date" name="te_campaign_expiry" value="'.esc_attr($expiry).'"></label>';
        echo '</div></section>';

        echo '<section class="te-cb-card"><div class="te-cb-card-title"><span>02</span><div><h4>Layout Wrapper</h4><p>Choose whether Tranter Hub should add a site wrapper around your HTML.</p></div></div>';
        echo '<div class="te-layout-options">';
        $layouts = [
            'html_only' => ['Full HTML only', 'Your HTML controls the entire page.'],
            'with_header' => ['HTML + Tranter header', 'Adds a simple Tranter header above the HTML.'],
            'with_footer' => ['HTML + Tranter footer', 'Adds a simple Tranter footer below the HTML.'],
            'with_header_footer' => ['Header & footer', 'Adds both Tranter header and footer.'],
        ];
        foreach ($layouts as $key => $data) {
            echo '<label class="te-layout-option '.($layout === $key ? 'is-selected' : '').'"><input type="radio" name="te_campaign_layout" value="'.esc_attr($key).'" '.checked($layout, $key, false).'><span><strong>'.esc_html($data[0]).'</strong><small>'.esc_html($data[1]).'</small></span></label>';
        }
        echo '</div></section>';

        echo '<section class="te-cb-card"><div class="te-cb-card-title"><span>03</span><div><h4>Tracking</h4><p>Automatically monitor visits and interactions inside the campaign HTML.</p></div></div>';
        echo '<label class="te-premium-toggle"><input type="checkbox" name="te_campaign_tracking" value="1" '.checked($tracking, '1', false).'><span></span><div><strong>Enable automatic tracking</strong><small>Tracks visits, clicks, buttons, forms, WhatsApp, phone, email and outbound links.</small></div></label>';
        echo '</section>';

        echo '<section class="te-cb-card te-cb-card-page"><div class="te-cb-card-title"><span>04</span><div><h4>Landing Page</h4><p>Tranter Hub creates a WordPress page and inserts the campaign shortcode automatically.</p></div></div>';
        if ($page_url) {
            echo '<div class="te-cb-url"><strong>'.esc_html($page_url).'</strong><div><a class="button button-primary" href="'.esc_url($page_url).'" target="_blank" rel="noopener">Open page</a><a class="button" href="'.esc_url(get_edit_post_link($page_id)).'">Edit page</a></div></div>';
        } else {
            echo '<div class="te-cb-url is-pending"><strong>Landing page will be generated after publish/update.</strong><span>Save this campaign to create the public page.</span></div>';
        }
        echo '<details class="te-cb-advanced"><summary>Advanced shortcode</summary><code>[te_campaign id=&quot;'.esc_html($slug_shortcode).'&quot;]</code><small>Generated campaign pages render directly through Tranter Hub, so visitors do not see the WordPress theme header, page title or footer in Full HTML mode.</small></details>';
        echo '</section>';
        echo '</div>';
        echo '</div>';
    }

    private static function select_field($label, $name, $value, $options) {
        echo '<label><strong>'.esc_html($label).'</strong><select name="'.esc_attr($name).'">';
        foreach ($options as $key => $option_label) {
            echo '<option value="'.esc_attr($key).'" '.selected($value, $key, false).'>'.esc_html($option_label).'</option>';
        }
        echo '</select></label>';
    }

    public static function render_html_box($post) {
        $html = get_post_meta($post->ID, '_te_campaign_html', true);
        echo '<div class="te-campaign-content-builder">';
        echo '<div class="te-campaign-content-head"><div><span>Campaign Content</span><h3>Paste your full HTML or widget code</h3><p>The campaign HTML can include its own layout, CTA buttons, forms, scripts, event widgets and footer. Tranter Hub only wraps it for publishing and tracking.</p></div><div class="te-campaign-content-note"><strong>Auto tracking</strong><small>Links, buttons and forms are tracked automatically.</small></div></div>';
        echo '<textarea name="te_campaign_html" rows="28" spellcheck="false" placeholder="Paste your full HTML campaign code here...">'.esc_textarea($html).'</textarea>';
        echo '<div class="te-campaign-help"><strong>Optional labels:</strong><span>For cleaner analytics, add <code>data-te-label=&quot;Register Button&quot;</code> or <code>data-te-track=&quot;conversion&quot;</code>. This is optional; unlabelled links are still tracked.</span></div>';
        echo '</div>';
    }

    public static function render_tracking_box($post) {
        $views = absint(get_post_meta($post->ID, '_te_campaign_views', true));
        $clicks = absint(get_post_meta($post->ID, '_te_campaign_clicks', true));
        $conversions = absint(get_post_meta($post->ID, '_te_campaign_conversions', true));
        echo '<p><strong>Views:</strong> '.esc_html($views).'</p>';
        echo '<p><strong>Clicks:</strong> '.esc_html($clicks).'</p>';
        echo '<p><strong>Conversions:</strong> '.esc_html($conversions).'</p>';
        echo '<p class="description">Tracking begins once the campaign shortcode is rendered on a page.</p>';
    }

    public static function save_campaign_meta($post_id) {
        if (!isset($_POST['te_campaign_nonce']) || !wp_verify_nonce($_POST['te_campaign_nonce'], 'te_campaign_save')) return;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;

        $fields = ['te_campaign_type','te_campaign_layout','te_campaign_market','te_campaign_goal','te_campaign_expiry'];
        foreach ($fields as $field) {
            if (!isset($_POST[$field])) continue;
            $value = sanitize_text_field(wp_unslash($_POST[$field]));
            update_post_meta($post_id, '_' . $field, $value);
        }
        update_post_meta($post_id, '_te_campaign_tracking', isset($_POST['te_campaign_tracking']) ? '1' : '0');
        if (isset($_POST['te_campaign_html'])) {
            // Campaign HTML is intentionally stored as trusted admin-supplied HTML so third-party widgets/scripts can work.
            update_post_meta($post_id, '_te_campaign_html', wp_unslash($_POST['te_campaign_html']));
        }
        self::ensure_landing_page($post_id);
    }

    private static function ensure_landing_page($campaign_id) {
        $campaign = get_post($campaign_id);
        if (!$campaign || $campaign->post_type !== 'tranter_campaign' || $campaign->post_status === 'auto-draft') return;
        $slug = $campaign->post_name ?: sanitize_title($campaign->post_title);
        if (!$slug) return;
        $page_id = absint(get_post_meta($campaign_id, '_te_campaign_page_id', true));
        $content = '[te_campaign id="' . absint($campaign_id) . '"]';
        $page_data = [
            'post_title' => $campaign->post_title,
            'post_name' => $slug,
            'post_content' => $content,
            'post_status' => $campaign->post_status === 'publish' ? 'publish' : 'draft',
            'post_type' => 'page',
            'comment_status' => 'closed',
            'ping_status' => 'closed',
        ];
        if ($page_id && get_post($page_id)) {
            $page_data['ID'] = $page_id;
            wp_update_post($page_data);
        } else {
            $existing = get_page_by_path($slug, OBJECT, 'page');
            if ($existing) {
                $page_id = $existing->ID;
                $page_data['ID'] = $page_id;
                wp_update_post($page_data);
            } else {
                $page_id = wp_insert_post($page_data);
            }
            if ($page_id && !is_wp_error($page_id)) update_post_meta($campaign_id, '_te_campaign_page_id', $page_id);
        }
        if ($page_id && !is_wp_error($page_id)) {
            update_post_meta($page_id, '_te_campaign_source_id', $campaign_id);
            update_post_meta($page_id, '_te_generated_campaign_page', '1');
            update_post_meta($page_id, '_te_campaign_layout', get_post_meta($campaign_id, '_te_campaign_layout', true) ?: 'html_only');
            // If Elementor Canvas exists on the site, use it as a friendly editor fallback.
            // The frontend still renders through template_redirect, so Full HTML pages stay theme-free.
            update_post_meta($page_id, '_wp_page_template', 'elementor_canvas');
            update_post_meta($campaign_id, '_te_campaign_last_page_sync', current_time('mysql'));
        }
    }

    public static function campaign_page_state($states, $post) {
        if ($post instanceof WP_Post && $post->post_type === 'page' && get_post_meta($post->ID, '_te_generated_campaign_page', true)) {
            $states['te_campaign_page'] = 'Tranter Campaign Page';
        }
        return $states;
    }


    public static function maybe_render_campaign_page() {
        if (is_admin() || wp_doing_ajax()) return;
        $campaign_id = 0;
        $queried = get_queried_object();
        if ($queried instanceof WP_Post) {
            if ($queried->post_type === 'tranter_campaign') {
                $campaign_id = $queried->ID;
            } elseif ($queried->post_type === 'page') {
                $campaign_id = absint(get_post_meta($queried->ID, '_te_campaign_source_id', true));
            }
        }
        if (!$campaign_id) return;
        $campaign = get_post($campaign_id);
        if (!$campaign || $campaign->post_type !== 'tranter_campaign') {
            status_header(404);
            self::render_campaign_error('Campaign not found.');
            exit;
        }
        if ($campaign->post_status !== 'publish' && !current_user_can('edit_post', $campaign_id)) {
            status_header(404);
            self::render_campaign_error('Campaign unpublished.');
            exit;
        }
        if (!self::campaign_visible_for_market($campaign_id)) {
            status_header(404);
            self::render_campaign_error('This campaign is not available for your region.');
            exit;
        }
        self::render_campaign_document($campaign);
        exit;
    }

    private static function render_campaign_error($message) {
        echo '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Campaign</title><style>body{margin:0;font-family:Inter,Arial,sans-serif;background:#f6faf8;color:#102617;display:grid;place-items:center;min-height:100vh}.box{max-width:620px;background:#fff;border:1px solid rgba(9,75,55,.12);border-radius:24px;padding:34px;box-shadow:0 20px 60px rgba(0,0,0,.08)}h1{margin:0 0 10px;font-size:28px}p{margin:0;color:#61716a;line-height:1.6}</style></head><body><div class="box"><h1>Campaign unavailable</h1><p>'.esc_html($message).'</p></div></body></html>';
    }

    private static function render_campaign_document($campaign) {
        $layout = get_post_meta($campaign->ID, '_te_campaign_layout', true) ?: 'html_only';
        $tracking = get_post_meta($campaign->ID, '_te_campaign_tracking', true);
        if ($tracking === '') $tracking = '1';
        $html = get_post_meta($campaign->ID, '_te_campaign_html', true);
        if (trim((string)$html) === '') {
            self::render_campaign_error('No HTML has been added to this campaign yet.');
            return;
        }
        if ($layout === 'html_only') {
            echo self::prepare_standalone_campaign_html($html, $campaign, $tracking);
            return;
        }
        get_header();
        echo '<div class="te-campaign te-campaign-layout-'.esc_attr($layout).'" data-campaign-id="'.esc_attr($campaign->ID).'" data-campaign-slug="'.esc_attr($campaign->post_name).'" data-tracking="'.esc_attr($tracking).'">';
        if (in_array($layout, ['with_header', 'with_header_footer'], true)) echo self::campaign_minimal_header($campaign);
        echo '<main class="te-campaign-body te-campaign-html-mode"><div class="te-campaign-embed">'.self::render_trusted_html(self::extract_body_content($html)).'</div></main>';
        if (in_array($layout, ['with_footer', 'with_header_footer'], true)) echo self::campaign_footer_shortcode(['id' => $campaign->ID]);
        echo '</div>';
        echo self::inline_tracking_script($campaign->ID, $campaign->post_name, $tracking);
        get_footer();
    }

    private static function prepare_standalone_campaign_html($html, $campaign, $tracking = '1') {
        $tracking_script = self::inline_tracking_script($campaign->ID, $campaign->post_name, $tracking);
        $open = '<div class="te-campaign" data-campaign-id="'.esc_attr($campaign->ID).'" data-campaign-slug="'.esc_attr($campaign->post_name).'" data-tracking="'.esc_attr($tracking).'">';
        $close = '</div>';
        if (preg_match('/<body\b[^>]*>/i', $html, $m, PREG_OFFSET_CAPTURE)) {
            $pos = $m[0][1] + strlen($m[0][0]);
            $html = substr($html, 0, $pos) . $open . substr($html, $pos);
            if (preg_match('/<\/body>/i', $html, $bm, PREG_OFFSET_CAPTURE)) {
                $bpos = $bm[0][1];
                $html = substr($html, 0, $bpos) . $close . $tracking_script . substr($html, $bpos);
            } else {
                $html .= $close . $tracking_script;
            }
            return $html;
        }
        return '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>'.esc_html(get_the_title($campaign)).'</title></head><body>'.$open.self::render_trusted_html($html).$close.$tracking_script.'</body></html>';
    }

    private static function extract_body_content($html) {
        if (preg_match('/<body\b[^>]*>(.*?)<\/body>/is', $html, $m)) return $m[1];
        return $html;
    }

    private static function inline_tracking_script($campaign_id, $slug = '', $tracking = '1') {
        if ($tracking === '0') return '';
        $ajax = admin_url('admin-ajax.php');
        $nonce = wp_create_nonce('te_campaign_track');
        return '<script>(function(){function s(p){if(!p.campaignId)return;var d=new FormData();d.append("action","te_campaign_track");d.append("nonce","'.esc_js($nonce).'");Object.keys(p).forEach(function(k){d.append(k,p[k]||"")});if(navigator.sendBeacon){navigator.sendBeacon("'.esc_js($ajax). '",d);return}fetch("'.esc_js($ajax). '",{method:"POST",body:d,credentials:"same-origin"}).catch(function(){})}function u(n){return new URLSearchParams(location.search).get(n)||""}function l(e){return((e&& (e.getAttribute("data-te-label")||e.getAttribute("aria-label")||e.innerText||e.value||e.name||e.id||e.tagName))||"interaction").trim().replace(/\\s+/g," ").slice(0,140)}function t(e){var x=e.getAttribute("data-te-track");if(x)return x==="conversion"?"conversion":"click";var h=e.href||"";if(/^https?:\\/\\/(wa\\.me|api\\.whatsapp\\.com)/i.test(h))return"whatsapp";if(/^tel:/i.test(h))return"phone";if(/^mailto:/i.test(h))return"email";if(h&&e.hostname&&e.hostname!==location.hostname)return"outbound";return"click"}document.addEventListener("DOMContentLoaded",function(){var c=document.querySelector(".te-campaign[data-campaign-id=\\"'.esc_js($campaign_id).'\\"]")||document.querySelector(".te-campaign[data-campaign-id]");if(!c||c.getAttribute("data-tracking")==="0")return;var id=c.getAttribute("data-campaign-id");s({campaignId:id,event:"view",utmSource:u("utm_source"),utmMedium:u("utm_medium"),utmCampaign:u("utm_campaign"),utmContent:u("utm_content"),utmTerm:u("utm_term")});c.addEventListener("click",function(ev){var target=ev.target.closest("a,button,[role=button],[data-te-track],input[type=submit],input[type=button]");if(!target)return;s({campaignId:id,event:t(target),label:l(target),url:target.href||"",utmSource:u("utm_source"),utmMedium:u("utm_medium"),utmCampaign:u("utm_campaign"),utmContent:u("utm_content"),utmTerm:u("utm_term")})},true);c.addEventListener("submit",function(ev){var f=ev.target;if(!f||!f.matches("form"))return;s({campaignId:id,event:"conversion",label:l(f)||"form submit",url:f.action||location.href,utmSource:u("utm_source"),utmMedium:u("utm_medium"),utmCampaign:u("utm_campaign"),utmContent:u("utm_content"),utmTerm:u("utm_term")})},true)})})();</script>';
    }

    public static function public_assets() {
        wp_register_style('tranter-engine-campaigns', TRANTER_ENGINE_URL.'assets/css/campaigns.css', ['tranter-engine-public'], TRANTER_ENGINE_VERSION);
        wp_register_script('tranter-engine-campaigns', TRANTER_ENGINE_URL.'assets/js/campaigns.js', [], TRANTER_ENGINE_VERSION, true);
        wp_localize_script('tranter-engine-campaigns', 'TranterCampaigns', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('te_campaign_track'),
        ]);
    }

    public static function campaign_shortcode($atts = []) {
        $atts = shortcode_atts(['id' => '', 'layout' => ''], $atts);
        $campaign = self::get_campaign($atts['id']);
        if (!$campaign || !self::campaign_visible_for_market($campaign->ID)) return '';
        wp_enqueue_style('tranter-engine-campaigns');
        wp_enqueue_script('tranter-engine-campaigns');
        $layout = $atts['layout'] ?: (get_post_meta($campaign->ID, '_te_campaign_layout', true) ?: 'html_only');
        $tracking = get_post_meta($campaign->ID, '_te_campaign_tracking', true);
        if ($tracking === '') $tracking = '1';
        $html = get_post_meta($campaign->ID, '_te_campaign_html', true);
        if (!$html) $html = wpautop($campaign->post_content);
        ob_start();
        echo '<div class="te-campaign te-campaign-layout-'.esc_attr($layout).'" data-campaign-id="'.esc_attr($campaign->ID).'" data-campaign-slug="'.esc_attr($campaign->post_name).'" data-tracking="'.esc_attr($tracking).'">';
        if (in_array($layout, ['with_header', 'with_header_footer'], true)) echo self::campaign_minimal_header($campaign);
        echo '<main class="te-campaign-body te-campaign-html-mode">';
        echo '<div class="te-campaign-embed">'.self::render_trusted_html(self::extract_body_content($html)).'</div>';
        echo '</main>';
        if (in_array($layout, ['with_footer', 'with_header_footer'], true)) echo self::campaign_footer_shortcode(['id' => $campaign->ID]);
        echo '</div>';
        return ob_get_clean();
    }

    private static function campaign_visible_for_market($campaign_id) {
        $campaign_market = get_post_meta($campaign_id, '_te_campaign_market', true) ?: 'both';
        if ($campaign_market === 'both') return true;
        return $campaign_market === Tranter_Market::current();
    }

    private static function campaign_minimal_header($campaign) {
        return '<header class="te-campaign-minimal-header"><a href="'.esc_url(home_url('/')).'" class="te-campaign-brand">Tranter IT</a><span>'.esc_html($campaign->post_title).'</span></header>';
    }

    private static function render_trusted_html($html) {
        // Trusted campaign HTML is intentionally rendered so pasted widgets/scripts can operate.
        return do_shortcode($html);
    }

    public static function campaign_hero_shortcode($atts = []) {
        $atts = shortcode_atts(['id' => ''], $atts);
        $campaign = self::get_campaign($atts['id']);
        if (!$campaign) return '';
        wp_enqueue_style('tranter-engine-campaigns');
        $type = get_post_meta($campaign->ID, '_te_campaign_type', true) ?: 'Campaign';
        $goal = get_post_meta($campaign->ID, '_te_campaign_goal', true) ?: 'Focused conversion experience';
        $img = get_the_post_thumbnail_url($campaign->ID, 'large');
        $style = $img ? ' style="background-image:linear-gradient(135deg,rgba(3,41,30,.92),rgba(10,83,60,.82)),url('.esc_url($img).')"' : '';
        return '<section class="te-campaign-hero"'.$style.'><div class="te-campaign-container"><span class="te-campaign-pill">'.esc_html(str_replace('_',' ', strtoupper($type))).'</span><h1>'.esc_html($campaign->post_title).'</h1><p>'.esc_html($campaign->post_excerpt ?: $goal).'</p></div></section>';
    }

    public static function campaign_html_shortcode($atts = []) {
        $campaign = self::get_campaign(shortcode_atts(['id'=>''], $atts)['id']);
        if (!$campaign) return '';
        wp_enqueue_style('tranter-engine-campaigns');
        wp_enqueue_script('tranter-engine-campaigns');
        $html = get_post_meta($campaign->ID, '_te_campaign_html', true);
        return '<div class="te-campaign te-campaign-embed" data-campaign-id="'.esc_attr($campaign->ID).'">'.self::render_trusted_html($html).'</div>';
    }

    public static function campaign_cta_shortcode($atts = []) {
        $campaign = self::get_campaign(shortcode_atts(['id'=>''], $atts)['id']);
        if (!$campaign) return '';
        wp_enqueue_style('tranter-engine-campaigns');
        $label = get_post_meta($campaign->ID, '_te_campaign_cta_label', true) ?: 'Take the Next Step';
        $url = get_post_meta($campaign->ID, '_te_campaign_cta_url', true) ?: '#';
        return '<section class="te-campaign-cta"><div><span>Ready to continue?</span><h2>'.esc_html($label).'</h2></div><a href="'.esc_url($url).'" class="te-campaign-btn" data-te-track="cta" data-te-label="'.esc_attr($label).'">'.esc_html($label).'</a></section>';
    }

    public static function campaign_footer_shortcode($atts = []) {
        wp_enqueue_style('tranter-engine-campaigns');
        return '<footer class="te-campaign-footer"><p>© '.esc_html(date('Y')).' Tranter IT Infrastructure Services Limited. All rights reserved.</p></footer>';
    }

    private static function get_campaign($id) {
        if (!$id) return null;
        if (is_numeric($id)) {
            $post = get_post(absint($id));
            return ($post && $post->post_type === 'tranter_campaign') ? $post : null;
        }
        return get_page_by_path(sanitize_title($id), OBJECT, 'tranter_campaign');
    }

    public static function track() {
        check_ajax_referer('te_campaign_track', 'nonce');
        $campaign_id = absint($_POST['campaignId'] ?? 0);
        $event = sanitize_key($_POST['event'] ?? 'view');
        if (!$campaign_id || get_post_type($campaign_id) !== 'tranter_campaign') wp_send_json_error(['message' => 'Invalid campaign']);
        $meta_key = $event === 'click' ? '_te_campaign_clicks' : ($event === 'conversion' ? '_te_campaign_conversions' : '_te_campaign_views');
        $count = absint(get_post_meta($campaign_id, $meta_key, true));
        update_post_meta($campaign_id, $meta_key, $count + 1);
        $recent = get_post_meta($campaign_id, '_te_campaign_recent_events', true);
        if (!is_array($recent)) $recent = [];
        array_unshift($recent, [
            'event' => $event,
            'label' => sanitize_text_field(wp_unslash($_POST['label'] ?? '')),
            'url' => esc_url_raw(wp_unslash($_POST['url'] ?? '')),
            'market' => Tranter_Market::current(),
            'source' => sanitize_text_field(wp_unslash($_POST['utmSource'] ?? '')),
            'time' => current_time('mysql'),
        ]);
        update_post_meta($campaign_id, '_te_campaign_recent_events', array_slice($recent, 0, 50));
        wp_send_json_success(['count' => $count + 1]);
    }

    public static function admin_workspace() {
        $campaigns = get_posts(['post_type'=>'tranter_campaign','numberposts'=>20,'post_status'=>['publish','draft','pending'],'orderby'=>'date','order'=>'DESC']);
        echo '<div class="te-app te-workspace"><main class="te-main">';
        echo '<section class="te-title-row"><div><span class="te-kicker">Campaign Engine</span><h1>Campaigns</h1><p>Create full-HTML campaign pages with automatic visit and click tracking.</p></div><a class="te-btn te-btn-primary" href="'.esc_url(admin_url('post-new.php?post_type=tranter_campaign')).'">Add Campaign</a></section>';
        echo '<section class="te-panel"><div class="te-panel-head"><h2>Campaign shortcodes</h2><span>Use on Elementor/Gutenberg pages</span></div><pre class="te-code-block">[te_campaign id="campaign-slug"]</pre></section>';
        echo '<section class="te-card-grid">';
        foreach ($campaigns as $campaign) {
            $views = absint(get_post_meta($campaign->ID, '_te_campaign_views', true));
            $clicks = absint(get_post_meta($campaign->ID, '_te_campaign_clicks', true));
            $layout = get_post_meta($campaign->ID, '_te_campaign_layout', true) ?: 'blank';
            echo '<article class="te-content-card"><div class="te-content-top"><div class="te-content-icon">'.tranter_engine_svg_icon('cta').'</div><span>'.esc_html(ucfirst($campaign->post_status)).'</span></div><div class="te-card-body"><h3>'.esc_html($campaign->post_title).'</h3><p>'.esc_html(wp_trim_words($campaign->post_excerpt ?: wp_strip_all_tags($campaign->post_content), 18)).'</p><code class="te-inline-code">[te_campaign id=&quot;'.esc_html($campaign->post_name).'&quot;]</code><div class="te-badges"><span>'.esc_html($layout).'</span><span>'.esc_html($views).' views</span><span>'.esc_html($clicks).' clicks</span></div></div><div class="te-card-actions"><a href="'.esc_url(get_edit_post_link($campaign->ID)).'">Edit</a><a href="'.esc_url(get_permalink($campaign->ID)).'" target="_blank">View</a></div></article>';
        }
        if (!$campaigns) echo '<article class="te-empty"><h3>No campaigns yet.</h3><p>Create your first campaign, paste HTML/widget code, then use its shortcode on any page.</p></article>';
        echo '</section></main></div>';
    }
}
