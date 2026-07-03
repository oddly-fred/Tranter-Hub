<?php
if (!defined('ABSPATH')) exit;

class Tranter_Insights {
    public static function init() {
        add_action('add_meta_boxes', [__CLASS__, 'add_meta_boxes']);
        add_action('save_post', [__CLASS__, 'save_post_meta']);
        add_action('admin_menu', [__CLASS__, 'add_admin_menu']);
        
        // Track views
        add_action('wp_head', [__CLASS__, 'track_view']);
    }

    public static function add_admin_menu() {
        add_menu_page(
            'Insights',
            'Insights',
            'edit_posts',
            'tranter-insights',
            [__CLASS__, 'render_admin_page'],
            'dashicons-media-document',
            6
        );
    }

    public static function render_admin_page() {
        ?>
        <div class="wrap te-admin-page">
            <h1>Insights Engine</h1>
            <p>Manage your enterprise insights and track performance.</p>
            
            <div class="te-insights-list">
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Market</th>
                            <th>Views</th>
                            <th>Subscribers</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $posts = get_posts(['post_type' => 'post', 'posts_per_page' => 20]);
                        foreach ($posts as $post) {
                            $market = get_post_meta($post->ID, '_tranter_market', true) ?: 'Both';
                            $views = get_post_meta($post->ID, '_tranter_views', true) ?: 0;
                            $subs = get_post_meta($post->ID, '_tranter_subscribers', true) ?: 0;
                            ?>
                            <tr>
                                <td><strong><?php echo esc_html($post->post_title); ?></strong></td>
                                <td><?php echo esc_html(ucfirst($market)); ?></td>
                                <td><?php echo esc_html($views); ?></td>
                                <td><?php echo esc_html($subs); ?></td>
                                <td>
                                    <a href="<?php echo get_edit_post_link($post->ID); ?>">Edit</a> | 
                                    <a href="<?php echo get_permalink($post->ID); ?>" target="_blank">View</a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }

    public static function add_meta_boxes() {
        add_meta_box(
            'tranter_insight_settings',
            'Insight Settings',
            [__CLASS__, 'render_meta_box'],
            'post',
            'side'
        );
    }

    public static function render_meta_box($post) {
        $market = get_post_meta($post->ID, '_tranter_market', true);
        $views = get_post_meta($post->ID, '_tranter_views', true) ?: 0;
        $clicks = get_post_meta($post->ID, '_tranter_cta_clicks', true) ?: 0;
        ?>
        <p>
            <label for="tranter_market">Target Market:</label><br>
            <select name="tranter_market" id="tranter_market" class="widefat">
                <option value="both" <?php selected($market, 'both'); ?>>Both (Global & Nigeria)</option>
                <option value="ng" <?php selected($market, 'ng'); ?>>Nigeria</option>
                <option value="global" <?php selected($market, 'global'); ?>>Global</option>
            </select>
        </p>
        <div class="te-stats-summary">
            <p><strong>Views:</strong> <?php echo esc_html($views); ?></p>
            <p><strong>CTA Clicks:</strong> <?php echo esc_html($clicks); ?></p>
        </div>
        <?php
    }

    public static function save_post_meta($post_id) {
        if (isset($_POST['tranter_market'])) {
            update_post_meta($post_id, '_tranter_market', sanitize_text_field($_POST['tranter_market']));
        }
    }

    public static function track_view() {
        if (is_single() && get_post_type() === 'post') {
            global $post;
            $views = (int) get_post_meta($post->ID, '_tranter_views', true);
            update_post_meta($post->ID, '_tranter_views', $views + 1);
        }
    }
}
