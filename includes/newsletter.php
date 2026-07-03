<?php
if (!defined('ABSPATH')) exit;

class Tranter_Newsletter {
    private static function enqueue() {
        wp_enqueue_style('tranter-engine-public-font', 'https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap', [], null);
        wp_enqueue_style('tranter-engine-public', TRANTER_ENGINE_URL.'assets/css/public.css', [], TRANTER_ENGINE_VERSION);
        wp_enqueue_style('tranter-engine-knowledge-hub', TRANTER_ENGINE_URL.'assets/css/knowledge-hub.css', ['tranter-engine-public'], TRANTER_ENGINE_VERSION);
    }

    public static function init() {
        add_shortcode('te_newsletter', [__CLASS__, 'shortcode']);
        add_action('admin_post_nopriv_tranter_newsletter_subscribe', [__CLASS__, 'handle_subscribe']);
        add_action('admin_post_tranter_newsletter_subscribe', [__CLASS__, 'handle_subscribe']);
    }

    public static function shortcode($atts = []) {
        self::enqueue();
        $atts = shortcode_atts([
            'title' => 'Get Tranter insights in your inbox',
            'subtitle' => 'Join our knowledge hub for practical updates on IT governance, cybersecurity, cloud, data and smart solutions.',
            'source' => 'newsletter_shortcode',
        ], $atts, 'te_newsletter');
        ob_start();
        ?>
        <section class="te-newsletter-box">
            <div>
                <span class="te-kicker">Knowledge Hub</span>
                <h2><?php echo esc_html($atts['title']); ?></h2>
                <p><?php echo esc_html($atts['subtitle']); ?></p>
            </div>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="te-newsletter-form">
                <input type="hidden" name="action" value="tranter_newsletter_subscribe">
                <input type="hidden" name="source" value="<?php echo esc_attr($atts['source']); ?>">
                <?php wp_nonce_field('tranter_newsletter_subscribe', 'tranter_newsletter_nonce'); ?>
                <div class="te-form-row">
                    <input type="text" name="first_name" placeholder="First name" required>
                    <input type="text" name="last_name" placeholder="Last name">
                </div>
                <input type="email" name="email" placeholder="Work email" required>
                <div class="te-form-row">
                    <input type="text" name="company" placeholder="Company">
                    <input type="text" name="job_title" placeholder="Job title">
                </div>
                <select name="interest">
                    <option value="">Primary interest</option>
                    <option value="Cybersecurity">Cybersecurity</option>
                    <option value="Digital Government">Digital Government</option>
                    <option value="Cloud">Cloud</option>
                    <option value="Data Analytics">Data Analytics</option>
                    <option value="Smart Solutions">Smart Solutions</option>
                    <option value="Events">Events</option>
                </select>
                <button type="submit">Subscribe</button>
            </form>
        </section>
        <?php
        return ob_get_clean();
    }

    public static function handle_subscribe() {
        if (!isset($_POST['tranter_newsletter_nonce']) || !wp_verify_nonce($_POST['tranter_newsletter_nonce'], 'tranter_newsletter_subscribe')) {
            wp_die('Invalid newsletter request.');
        }
        $email = sanitize_email($_POST['email'] ?? '');
        if (!$email || !is_email($email)) {
            wp_die('Please enter a valid email address.');
        }
        $existing = get_posts([
            'post_type' => 'tranter_subscriber',
            'post_status' => 'any',
            'numberposts' => 1,
            'meta_key' => '_tranter_subscriber_email',
            'meta_value' => $email,
        ]);
        $name = trim(sanitize_text_field(($_POST['first_name'] ?? '') . ' ' . ($_POST['last_name'] ?? '')));
        $post_id = $existing ? $existing[0]->ID : wp_insert_post([
            'post_type' => 'tranter_subscriber',
            'post_title' => $name ?: $email,
            'post_status' => 'publish',
        ]);
        if ($post_id && !is_wp_error($post_id)) {
            update_post_meta($post_id, '_tranter_subscriber_email', $email);
            update_post_meta($post_id, '_tranter_first_name', sanitize_text_field($_POST['first_name'] ?? ''));
            update_post_meta($post_id, '_tranter_last_name', sanitize_text_field($_POST['last_name'] ?? ''));
            update_post_meta($post_id, '_tranter_company', sanitize_text_field($_POST['company'] ?? ''));
            update_post_meta($post_id, '_tranter_job_title', sanitize_text_field($_POST['job_title'] ?? ''));
            update_post_meta($post_id, '_tranter_interest', sanitize_text_field($_POST['interest'] ?? ''));
            update_post_meta($post_id, '_tranter_source', sanitize_text_field($_POST['source'] ?? 'website'));
            update_post_meta($post_id, '_tranter_subscribed_at', current_time('mysql'));
        }
        $redirect = wp_get_referer() ?: home_url('/');
        wp_safe_redirect(add_query_arg('te_subscribed', '1', $redirect));
        exit;
    }
}
