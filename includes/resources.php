<?php
if (!defined('ABSPATH')) exit;

class Tranter_Resources {
    private static function enqueue() {
        wp_enqueue_style('tranter-engine-public-font', 'https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap', [], null);
        wp_enqueue_style('tranter-engine-public', TRANTER_ENGINE_URL.'assets/css/public.css', [], TRANTER_ENGINE_VERSION);
        wp_enqueue_style('tranter-engine-knowledge-hub', TRANTER_ENGINE_URL.'assets/css/knowledge-hub.css', ['tranter-engine-public'], TRANTER_ENGINE_VERSION);
    }

    public static function init() {
        add_shortcode('te_resources', [__CLASS__, 'shortcode']);
        add_action('admin_post_nopriv_tranter_resource_download', [__CLASS__, 'handle_download']);
        add_action('admin_post_tranter_resource_download', [__CLASS__, 'handle_download']);
        add_action('add_meta_boxes', [__CLASS__, 'add_meta_boxes']);
        add_action('save_post_tranter_resource', [__CLASS__, 'save_meta']);
    }

    public static function add_meta_boxes() {
        add_meta_box('tranter_resource_file', 'Resource Download Settings', [__CLASS__, 'render_meta_box'], 'tranter_resource', 'side', 'high');
    }

    public static function render_meta_box($post) {
        wp_nonce_field('tranter_resource_save', 'tranter_resource_nonce');
        $file = get_post_meta($post->ID, '_tranter_resource_file_url', true);
        $gated = get_post_meta($post->ID, '_tranter_resource_gated', true);
        echo '<p><label>File URL</label><input type="url" name="tranter_resource_file_url" value="'.esc_attr($file).'" class="widefat" placeholder="https://..."></p>';
        echo '<p><label><input type="checkbox" name="tranter_resource_gated" value="1" '.checked($gated, '1', false).'> Gate this download behind lead capture</label></p>';
    }

    public static function save_meta($post_id) {
        if (!isset($_POST['tranter_resource_nonce']) || !wp_verify_nonce($_POST['tranter_resource_nonce'], 'tranter_resource_save')) return;
        update_post_meta($post_id, '_tranter_resource_file_url', esc_url_raw($_POST['tranter_resource_file_url'] ?? ''));
        update_post_meta($post_id, '_tranter_resource_gated', isset($_POST['tranter_resource_gated']) ? '1' : '0');
    }

    public static function shortcode($atts = []) {
        self::enqueue();
        $atts = shortcode_atts(['limit' => 6], $atts, 'te_resources');
        $resources = get_posts(['post_type' => 'tranter_resource', 'numberposts' => absint($atts['limit']), 'post_status' => 'publish']);
        ob_start();
        echo '<section class="te-resources-wrap"><div class="te-section-head"><span class="te-kicker">Resources</span><h2>Guides, checklists and downloads</h2><p>Useful sales and knowledge assets for Tranter audiences.</p></div><div class="te-resource-grid">';
        foreach ($resources as $resource) {
            $file = get_post_meta($resource->ID, '_tranter_resource_file_url', true);
            echo '<article class="te-resource-card"><h3>'.esc_html($resource->post_title).'</h3><p>'.esc_html(wp_trim_words($resource->post_excerpt ?: wp_strip_all_tags($resource->post_content), 22)).'</p>';
            if ($file) echo '<a href="'.esc_url(add_query_arg(['action'=>'tranter_resource_download','resource_id'=>$resource->ID], admin_url('admin-post.php'))).'">Download Resource</a>';
            echo '</article>';
        }
        echo '</div></section>';
        return ob_get_clean();
    }

    public static function handle_download() {
        $resource_id = absint($_GET['resource_id'] ?? 0);
        $file = $resource_id ? get_post_meta($resource_id, '_tranter_resource_file_url', true) : '';
        if (!$file) wp_die('Resource not available.');
        $downloads = (int) get_post_meta($resource_id, '_tranter_downloads', true);
        update_post_meta($resource_id, '_tranter_downloads', $downloads + 1);
        wp_safe_redirect($file);
        exit;
    }
}
