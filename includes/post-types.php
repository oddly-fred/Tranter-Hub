<?php
if (!defined('ABSPATH')) exit;

class Tranter_Post_Types {
    public static function init() {
        add_action('init', [__CLASS__, 'register'], 0);
        if (did_action('init') && !post_type_exists('tranter_insight')) {
            self::register();
        }
        add_action('add_meta_boxes', [__CLASS__, 'meta_boxes']);
        add_action('save_post', [__CLASS__, 'save_meta']);
    }

    public static function register() {
        $types = [
            'tranter_service' => ['Services', 'Service', 'dashicons-admin-tools'],
            'tranter_partner' => ['Partner Solutions', 'Partner Solution', 'dashicons-star-filled'],
            'tranter_product' => ['Partner Products', 'Partner Product', 'dashicons-products'],
            'tranter_insight' => ['Insights', 'Insight', 'dashicons-welcome-learn-more'],
            'tranter_event' => ['Events', 'Event', 'dashicons-calendar-alt'],
            'tranter_campaign' => ['Campaigns', 'Campaign', 'dashicons-megaphone'],
            'tranter_resource' => ['Resources', 'Resource', 'dashicons-download'],
            'tranter_subscriber' => ['Subscribers', 'Subscriber', 'dashicons-email-alt'],
            'tranter_cta' => ['CTAs', 'CTA', 'dashicons-megaphone'],
            'tranter_faq' => ['FAQs', 'FAQ', 'dashicons-editor-help'],
            'tranter_section' => ['Website Sections', 'Website Section', 'dashicons-layout'],
        ];
        foreach ($types as $slug => $data) {
            register_post_type($slug, [
                'labels' => [
                    'name' => $data[0],
                    'singular_name' => $data[1],
                    'add_new_item' => 'Add New ' . $data[1],
                    'edit_item' => 'Edit ' . $data[1],
                    'new_item' => 'New ' . $data[1],
                    'view_item' => 'View ' . $data[1],
                    'search_items' => 'Search ' . $data[0],
                    'not_found' => 'No ' . strtolower($data[0]) . ' found',
                ],
                'public' => in_array($slug, ['tranter_insight','tranter_event','tranter_section','tranter_resource','tranter_campaign'], true),
                'show_ui' => true,
                'show_in_menu' => false,
                'supports' => $slug === 'tranter_subscriber' ? ['title'] : ['title', 'editor', 'thumbnail', 'excerpt'],
                'has_archive' => false,
                'rewrite' => ['slug' => str_replace('tranter_', '', $slug)],
                'show_in_rest' => true,
            ]);
        }
    }

    public static function meta_boxes() {
        foreach (['tranter_service','tranter_partner','tranter_product','tranter_insight','tranter_event','tranter_campaign','tranter_resource','tranter_cta','tranter_faq','tranter_section'] as $type) {
            add_meta_box('tranter_market_meta', 'Tranter Engine Settings', [__CLASS__, 'render_meta'], $type, 'side', 'high');
        }
    }

    public static function render_meta($post) {
        wp_nonce_field('tranter_engine_save_meta', 'tranter_engine_nonce');
        $markets = get_post_meta($post->ID, '_tranter_markets', true);
        if (!is_array($markets)) $markets = ['ng','global'];
        $featured = get_post_meta($post->ID, '_tranter_featured', true);
        echo '<p><strong>Market Availability</strong></p>';
        foreach (tranter_engine_markets() as $key => $label) {
            echo '<label style="display:block;margin:6px 0"><input type="checkbox" name="tranter_markets[]" value="'.esc_attr($key).'" '.checked(in_array($key,$markets,true), true, false).'> '.esc_html($label).'</label>';
        }
        echo '<hr><label><input type="checkbox" name="tranter_featured" value="1" '.checked($featured, '1', false).'> Featured</label>';
        if ($post->post_type === 'tranter_service') {
            echo '<hr><p><strong>Service Order</strong></p><input type="number" name="tranter_menu_order" value="'.esc_attr($post->menu_order). '" min="0" style="width:100%">';
            if ($post->post_name === 'smart-solutions') {
                $store_label = get_post_meta($post->ID, '_tranter_store_cta_label', true) ?: 'Visit Smart Solutions Store';
                $store_url = get_post_meta($post->ID, '_tranter_store_cta_url', true) ?: 'https://shop.tranter-it.com/';
                echo '<hr><p><strong>Smart Solutions Store CTA</strong></p>';
                echo '<label>CTA Label</label><input type="text" name="tranter_store_cta_label" value="'.esc_attr($store_label).'" style="width:100%;margin:4px 0 8px">';
                echo '<label>Store URL</label><input type="url" name="tranter_store_cta_url" value="'.esc_attr($store_url).'" style="width:100%;margin-top:4px">';
                echo '<p style="color:#667">Shows on Nigeria Smart Solutions page only.</p>';
            }
        }
        if ($post->post_type === 'tranter_section') {
            $section_key = get_post_meta($post->ID, '_tranter_section_key', true);
            echo '<hr><p><strong>Section Key</strong></p><input type="text" name="tranter_section_key" value="'.esc_attr($section_key ?: $post->post_name).'" style="width:100%" placeholder="who_we_are">';
            echo '<p style="color:#667">Used by shortcodes like [te_who_we_are].</p>';
        }
    }

    public static function save_meta($post_id) {
        if (!isset($_POST['tranter_engine_nonce']) || !wp_verify_nonce($_POST['tranter_engine_nonce'], 'tranter_engine_save_meta')) return;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        $markets = isset($_POST['tranter_markets']) ? array_values(array_intersect(array_map('sanitize_key', $_POST['tranter_markets']), ['ng','global'])) : [];
        update_post_meta($post_id, '_tranter_markets', $markets);
        update_post_meta($post_id, '_tranter_featured', isset($_POST['tranter_featured']) ? '1' : '0');
        if (get_post_type($post_id) === 'tranter_service') {
            if (isset($_POST['tranter_menu_order'])) {
                wp_update_post(['ID' => $post_id, 'menu_order' => intval($_POST['tranter_menu_order'])]);
            }
            if (isset($_POST['tranter_store_cta_label'])) {
                update_post_meta($post_id, '_tranter_store_cta_label', sanitize_text_field(wp_unslash($_POST['tranter_store_cta_label'])));
            }
            if (isset($_POST['tranter_store_cta_url'])) {
                update_post_meta($post_id, '_tranter_store_cta_url', esc_url_raw(wp_unslash($_POST['tranter_store_cta_url'])));
            }
        }
        if (get_post_type($post_id) === 'tranter_section' && isset($_POST['tranter_section_key'])) {
            update_post_meta($post_id, '_tranter_section_key', sanitize_title(str_replace('_','-', wp_unslash($_POST['tranter_section_key']))));
        }
    }
}
