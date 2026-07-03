<?php
if (!defined('ABSPATH')) exit;

class Tranter_Knowledge_Hub {
    public static function init() {
        add_shortcode('te_knowledge_hub', [__CLASS__, 'shortcode']); // Backward compatibility
        add_shortcode('te_insights_page', [__CLASS__, 'shortcode']);
        add_shortcode('te_knowledge_hub_preview', [__CLASS__, 'shortcode']);

        // Modular Insights page section shortcodes for WordPress pages / Elementor / Gutenberg.
        add_shortcode('te_insights_hero', [__CLASS__, 'insights_hero_shortcode']);
        add_shortcode('te_featured_insight', [__CLASS__, 'featured_insight_shortcode']);
        add_shortcode('te_insights_search', [__CLASS__, 'insights_search_shortcode']);
        add_shortcode('te_insights_tags', [__CLASS__, 'insights_tags_shortcode']);
        add_shortcode('te_insights_grid', [__CLASS__, 'insights_grid_shortcode']);
        add_shortcode('te_featured_resource', [__CLASS__, 'featured_resource_shortcode']);
        add_shortcode('te_insights_cta', [__CLASS__, 'insights_cta_shortcode']);
        add_shortcode('te_latest_insights', [__CLASS__, 'latest_shortcode']);
        add_action('add_meta_boxes', [__CLASS__, 'add_meta_boxes']);
        add_action('save_post_tranter_insight', [__CLASS__, 'save_meta']);
        add_filter('the_content', [__CLASS__, 'single_insight_content'], 20);
        add_filter('document_title_parts', [__CLASS__, 'document_title']);
        add_action('wp_head', [__CLASS__, 'seo_meta'], 2);
    }

    public static function activation_setup() {
        self::create_page();
    }

    public static function create_page() {
        $existing = get_page_by_path('insights');
        if (!$existing) {
            wp_insert_post([
                'post_type' => 'page',
                'post_title' => 'Insights',
                'post_name' => 'insights',
                'post_status' => 'publish',
                'post_content' => "[te_insights_hero]\n\n[te_featured_insight]\n\n[te_insights_search]\n\n[te_insights_tags]\n\n[te_insights_grid limit=\"9\"]\n\n[te_featured_resource]\n\n[te_newsletter title=\"Stay ahead of technology trends\" subtitle=\"Receive practical Tranter insights, guides and event updates directly in your inbox.\"]\n\n[te_insights_cta]",
            ]);
        }

        // Backward compatibility for older test installs. Do not overwrite designed pages.
        $legacy = get_page_by_path('knowledge-hub');
        if (!$legacy) {
            wp_insert_post([
                'post_type' => 'page',
                'post_title' => 'Knowledge Hub',
                'post_name' => 'knowledge-hub',
                'post_status' => 'draft',
                'post_content' => '[te_knowledge_hub]',
            ]);
        }
    }

    public static function enqueue() {
        wp_enqueue_style('tranter-engine-public-font', 'https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800;900&display=swap', [], null);
        wp_enqueue_style('tranter-engine-public', TRANTER_ENGINE_URL.'assets/css/public.css', [], TRANTER_ENGINE_VERSION);
        wp_enqueue_style('tranter-engine-knowledge-hub', TRANTER_ENGINE_URL.'assets/css/knowledge-hub.css', ['tranter-engine-public'], TRANTER_ENGINE_VERSION);
    }

    public static function add_meta_boxes() {
        add_meta_box('tranter_kh_settings', 'Insights Settings', [__CLASS__, 'render_meta_box'], 'tranter_insight', 'normal', 'high');
    }

    public static function render_meta_box($post) {
        wp_nonce_field('tranter_kh_save', 'tranter_kh_nonce');
        $industry = get_post_meta($post->ID, '_tranter_industry', true);
        $solution = get_post_meta($post->ID, '_tranter_solution', true);
        $seo_title = get_post_meta($post->ID, '_tranter_seo_title', true);
        $meta_description = get_post_meta($post->ID, '_tranter_meta_description', true);
        $cta_type = get_post_meta($post->ID, '_tranter_cta_type', true) ?: 'consultation';
        $show_newsletter = get_post_meta($post->ID, '_tranter_show_newsletter', true);
        $show_homepage = get_post_meta($post->ID, '_tranter_show_homepage', true);
        $resource_id = absint(get_post_meta($post->ID, '_tranter_related_resource', true));
        $resources = get_posts(['post_type'=>'tranter_resource','numberposts'=>100,'post_status'=>'publish','orderby'=>'title','order'=>'ASC']);
        ?>
        <div class="te-kh-admin-grid">
            <p><label><strong>Industry</strong></label><input type="text" name="tranter_industry" value="<?php echo esc_attr($industry); ?>" class="widefat" placeholder="Digital Government, Finance, Oil & Gas"></p>
            <p><label><strong>Solution</strong></label><input type="text" name="tranter_solution" value="<?php echo esc_attr($solution); ?>" class="widefat" placeholder="Cybersecurity, IT Governance, Cloud"></p>
            <p><label><strong>CTA Type</strong></label><select name="tranter_cta_type" class="widefat">
                <option value="consultation" <?php selected($cta_type, 'consultation'); ?>>Book Consultation</option>
                <option value="sales" <?php selected($cta_type, 'sales'); ?>>Contact Sales</option>
                <option value="demo" <?php selected($cta_type, 'demo'); ?>>Book Demo</option>
                <option value="itgov" <?php selected($cta_type, 'itgov'); ?>>ITGOV/Event Registration</option>
            </select></p>
            <p><label><strong>Related Resource</strong></label><select name="tranter_related_resource" class="widefat"><option value="0">None</option>
                <?php foreach ($resources as $resource): ?><option value="<?php echo esc_attr($resource->ID); ?>" <?php selected($resource_id, $resource->ID); ?>><?php echo esc_html($resource->post_title); ?></option><?php endforeach; ?>
            </select></p>
            <p><label><input type="checkbox" name="tranter_show_newsletter" value="1" <?php checked($show_newsletter, '1'); ?>> Show newsletter CTA after this article</label></p>
            <p><label><input type="checkbox" name="tranter_show_homepage" value="1" <?php checked($show_homepage, '1'); ?>> Prioritise on homepage Insights section</label></p>
            <p><label><strong>SEO Title</strong></label><input type="text" name="tranter_seo_title" value="<?php echo esc_attr($seo_title); ?>" class="widefat"></p>
            <p><label><strong>Meta Description</strong></label><textarea name="tranter_meta_description" class="widefat" rows="3"><?php echo esc_textarea($meta_description); ?></textarea></p>
        </div>
        <?php
    }

    public static function save_meta($post_id) {
        if (!isset($_POST['tranter_kh_nonce']) || !wp_verify_nonce($_POST['tranter_kh_nonce'], 'tranter_kh_save')) return;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        update_post_meta($post_id, '_tranter_industry', sanitize_text_field(wp_unslash($_POST['tranter_industry'] ?? '')));
        update_post_meta($post_id, '_tranter_solution', sanitize_text_field(wp_unslash($_POST['tranter_solution'] ?? '')));
        update_post_meta($post_id, '_tranter_cta_type', sanitize_key($_POST['tranter_cta_type'] ?? 'consultation'));
        update_post_meta($post_id, '_tranter_related_resource', absint($_POST['tranter_related_resource'] ?? 0));
        update_post_meta($post_id, '_tranter_show_newsletter', isset($_POST['tranter_show_newsletter']) ? '1' : '0');
        update_post_meta($post_id, '_tranter_show_homepage', isset($_POST['tranter_show_homepage']) ? '1' : '0');
        update_post_meta($post_id, '_tranter_seo_title', sanitize_text_field(wp_unslash($_POST['tranter_seo_title'] ?? '')));
        update_post_meta($post_id, '_tranter_meta_description', sanitize_textarea_field(wp_unslash($_POST['tranter_meta_description'] ?? '')));
    }

    public static function shortcode($atts = []) {
        self::enqueue();
        $atts = shortcode_atts(['market'=>Tranter_Market::current(), 'limit'=>9], $atts, 'te_knowledge_hub');
        $market = sanitize_key($atts['market']);
        $search = sanitize_text_field($_GET['kh_search'] ?? '');
        $tag = sanitize_text_field($_GET['kh_tag'] ?? '');
        $industry = sanitize_text_field($_GET['kh_industry'] ?? '');
        $featured = self::get_featured($market);
        $posts = self::query_insights($market, absint($atts['limit']), $search, $tag, $industry, $featured ? [$featured->ID] : []);
        $tags = self::popular_tags();
        $industries = self::popular_meta('_tranter_industry');
        ob_start(); ?>
        <div id="tranter-knowledge-hub" class="te-kh-page te-insights-page">
            <section class="te-kh-hero trh-full">
                <div class="trh-container">
                    <span class="trh-pill">Insights</span>
                    <h1>Expert insights for modern, secure and scalable organisations.</h1>
                    <p>Perspectives, guides and practical thinking to help organisations improve IT governance, cybersecurity, cloud, data and smart solution delivery.</p>
                    <form class="te-kh-search" method="get">
                        <input type="search" name="kh_search" value="<?php echo esc_attr($search); ?>" placeholder="Search insights...">
                        <button type="submit">Search</button>
                    </form>
                    <?php if ($tags): ?><div class="te-kh-chip-row"><?php foreach (array_slice($tags,0,8) as $t): ?><a href="<?php echo esc_url(add_query_arg('kh_tag', $t)); ?>"><?php echo esc_html($t); ?></a><?php endforeach; ?></div><?php endif; ?>
                </div>
            </section>
            <?php if ($featured): echo self::featured_markup($featured); endif; ?>
            <section class="trh-section te-kh-listing"><div class="trh-container">
                <header class="trh-header"><div class="trh-pill">Latest Insights</div><h2 class="trh-title">Explore Tranter perspectives</h2><div class="trh-divider"><span></span><i></i><span></span></div></header>
                <form class="te-kh-filters" method="get">
                    <input type="search" name="kh_search" value="<?php echo esc_attr($search); ?>" placeholder="Keyword">
                    <select name="kh_tag"><option value="">All tags</option><?php foreach ($tags as $t): ?><option value="<?php echo esc_attr($t); ?>" <?php selected($tag, $t); ?>><?php echo esc_html($t); ?></option><?php endforeach; ?></select>
                    <select name="kh_industry"><option value="">All industries</option><?php foreach ($industries as $i): ?><option value="<?php echo esc_attr($i); ?>" <?php selected($industry, $i); ?>><?php echo esc_html($i); ?></option><?php endforeach; ?></select>
                    <button type="submit">Apply</button>
                </form>
                <div class="te-kh-grid">
                    <?php if ($posts): foreach ($posts as $post): echo self::card($post); endforeach; else: ?>
                        <article class="trh-card te-kh-empty"><h3>No insights found yet.</h3><p>Add a new Insight from Tranter Hub, or clear the current filters.</p></article>
                    <?php endif; ?>
                </div>
            </div></section>
            <?php echo self::resource_band(); ?>
            <?php echo do_shortcode('[te_newsletter title="Stay ahead of technology trends" subtitle="Receive practical Tranter insights, guides and event updates directly in your inbox." source="insights_page"]'); ?>
            <?php echo self::cta_band(); ?>
        </div>
        <?php return ob_get_clean();
    }

    public static function latest_shortcode($atts = []) {
        self::enqueue();
        $atts = shortcode_atts(['market'=>Tranter_Market::current(), 'limit'=>3], $atts, 'te_latest_insights');
        $posts = self::query_insights(sanitize_key($atts['market']), absint($atts['limit']));
        ob_start(); echo '<div class="te-kh-grid te-kh-grid-compact">'; foreach ($posts as $post) echo self::card($post); echo '</div>'; return ob_get_clean();
    }


    /**
     * Modular Insights page shortcodes.
     * These allow editors to build the Insights page section by section in WordPress pages,
     * Elementor, Gutenberg or any builder that supports shortcodes.
     */
    public static function insights_hero_shortcode($atts = []) {
        self::enqueue();
        $atts = shortcode_atts([
            'title' => 'Expert insights for modern, secure and scalable organisations.',
            'subtitle' => 'Perspectives, guides and practical thinking to help organisations improve IT governance, cybersecurity, cloud, data and smart solution delivery.',
            'show_search' => 'yes',
            'show_tags' => 'yes',
        ], $atts, 'te_insights_hero');
        $tags = self::popular_tags();
        ob_start(); ?>
        <section class="te-kh-hero te-insights-hero trh-full">
            <div class="trh-container">
                <span class="trh-pill">Insights</span>
                <h1><?php echo esc_html($atts['title']); ?></h1>
                <p><?php echo esc_html($atts['subtitle']); ?></p>
                <?php if ($atts['show_search'] !== 'no'): ?>
                    <form class="te-kh-search" method="get" action="<?php echo esc_url(get_permalink()); ?>">
                        <input type="search" name="kh_search" value="<?php echo esc_attr(sanitize_text_field($_GET['kh_search'] ?? '')); ?>" placeholder="Search insights...">
                        <button type="submit">Search</button>
                    </form>
                <?php endif; ?>
                <?php if ($atts['show_tags'] !== 'no' && $tags): ?>
                    <div class="te-kh-chip-row"><?php foreach (array_slice($tags,0,8) as $t): ?><a href="<?php echo esc_url(add_query_arg('kh_tag', $t)); ?>"><?php echo esc_html($t); ?></a><?php endforeach; ?></div>
                <?php endif; ?>
            </div>
        </section>
        <?php return ob_get_clean();
    }

    public static function featured_insight_shortcode($atts = []) {
        self::enqueue();
        $atts = shortcode_atts(['market'=>Tranter_Market::current(), 'fallback'=>'latest'], $atts, 'te_featured_insight');
        $market = sanitize_key($atts['market']);
        $featured = self::get_featured($market);
        if (!$featured && $atts['fallback'] !== 'no') {
            $latest = self::query_insights($market, 1);
            $featured = $latest ? $latest[0] : null;
        }
        return $featured ? self::featured_markup($featured) : '<section class="trh-section"><div class="trh-container"><article class="trh-card te-kh-empty"><h3>No featured insight yet.</h3><p>Publish an Insight and mark it as featured or prioritised for the homepage.</p></article></div></section>';
    }

    public static function insights_search_shortcode($atts = []) {
        self::enqueue();
        $atts = shortcode_atts(['placeholder'=>'Search insights...', 'button'=>'Search'], $atts, 'te_insights_search');
        ob_start(); ?>
        <section class="trh-section te-insights-search-section"><div class="trh-container">
            <form class="te-kh-search te-insights-standalone-search" method="get">
                <input type="search" name="kh_search" value="<?php echo esc_attr(sanitize_text_field($_GET['kh_search'] ?? '')); ?>" placeholder="<?php echo esc_attr($atts['placeholder']); ?>">
                <button type="submit"><?php echo esc_html($atts['button']); ?></button>
            </form>
        </div></section>
        <?php return ob_get_clean();
    }

    public static function insights_tags_shortcode($atts = []) {
        self::enqueue();
        $atts = shortcode_atts(['limit'=>12], $atts, 'te_insights_tags');
        $tags = self::popular_tags();
        if (!$tags) return '';
        ob_start(); ?>
        <section class="trh-section te-insights-tags-section"><div class="trh-container">
            <header class="trh-header"><div class="trh-pill">Browse Topics</div><h2 class="trh-title">Explore insights by focus area</h2><div class="trh-divider"><span></span><i></i><span></span></div></header>
            <div class="te-kh-chip-row te-insights-tag-cloud"><?php foreach (array_slice($tags, 0, absint($atts['limit'])) as $t): ?><a href="<?php echo esc_url(add_query_arg('kh_tag', $t)); ?>"><?php echo esc_html($t); ?></a><?php endforeach; ?></div>
        </div></section>
        <?php return ob_get_clean();
    }

    public static function insights_grid_shortcode($atts = []) {
        self::enqueue();
        $atts = shortcode_atts(['market'=>Tranter_Market::current(), 'limit'=>9, 'show_filters'=>'yes', 'title'=>'Latest Insights', 'subtitle'=>'Explore Tranter perspectives'], $atts, 'te_insights_grid');
        $market = sanitize_key($atts['market']);
        $search = sanitize_text_field($_GET['kh_search'] ?? '');
        $tag = sanitize_text_field($_GET['kh_tag'] ?? '');
        $industry = sanitize_text_field($_GET['kh_industry'] ?? '');
        $posts = self::query_insights($market, absint($atts['limit']), $search, $tag, $industry);
        $tags = self::popular_tags();
        $industries = self::popular_meta('_tranter_industry');
        ob_start(); ?>
        <section class="trh-section te-kh-listing te-insights-grid-section"><div class="trh-container">
            <header class="trh-header"><div class="trh-pill"><?php echo esc_html($atts['title']); ?></div><h2 class="trh-title"><?php echo esc_html($atts['subtitle']); ?></h2><div class="trh-divider"><span></span><i></i><span></span></div></header>
            <?php if ($atts['show_filters'] !== 'no'): ?>
                <form class="te-kh-filters" method="get">
                    <input type="search" name="kh_search" value="<?php echo esc_attr($search); ?>" placeholder="Keyword">
                    <select name="kh_tag"><option value="">All tags</option><?php foreach ($tags as $t): ?><option value="<?php echo esc_attr($t); ?>" <?php selected($tag, $t); ?>><?php echo esc_html($t); ?></option><?php endforeach; ?></select>
                    <select name="kh_industry"><option value="">All industries</option><?php foreach ($industries as $i): ?><option value="<?php echo esc_attr($i); ?>" <?php selected($industry, $i); ?>><?php echo esc_html($i); ?></option><?php endforeach; ?></select>
                    <button type="submit">Apply</button>
                </form>
            <?php endif; ?>
            <div class="te-kh-grid">
                <?php if ($posts): foreach ($posts as $post): echo self::card($post); endforeach; else: ?>
                    <article class="trh-card te-kh-empty"><h3>No insights found yet.</h3><p>Add a new Insight from Tranter Hub, or clear the current filters.</p></article>
                <?php endif; ?>
            </div>
        </div></section>
        <?php return ob_get_clean();
    }

    public static function featured_resource_shortcode($atts = []) {
        self::enqueue();
        return self::resource_band();
    }

    public static function insights_cta_shortcode($atts = []) {
        self::enqueue();
        $atts = shortcode_atts(['title'=>'Ready to turn insight into action?'], $atts, 'te_insights_cta');
        return self::cta_band($atts['title']);
    }

    public static function get_featured($market) {
        $posts = get_posts(['post_type'=>'tranter_insight','numberposts'=>1,'post_status'=>'publish','meta_query'=>self::market_meta($market, [['_tranter_featured','1'], ['_tranter_show_homepage','1']]), 'orderby'=>'date', 'order'=>'DESC']);
        if (!$posts) $posts = get_posts(['post_type'=>'tranter_insight','numberposts'=>1,'post_status'=>'publish','meta_query'=>self::market_meta($market, [['_tranter_featured','1']]), 'orderby'=>'date', 'order'=>'DESC']);
        return $posts ? $posts[0] : null;
    }

    public static function query_insights($market, $limit = 6, $search = '', $tag = '', $industry = '', $exclude = []) {
        $args = ['post_type'=>'tranter_insight','posts_per_page'=>$limit,'post_status'=>'publish','orderby'=>'date','order'=>'DESC','post__not_in'=>$exclude,'meta_query'=>self::market_meta($market)];
        if ($search) $args['s'] = $search;
        if ($tag) $args['tag'] = sanitize_title($tag);
        if ($industry) $args['meta_query'][] = ['key'=>'_tranter_industry','value'=>$industry,'compare'=>'='];
        $posts = get_posts($args);
        if (!$posts && !$search && !$tag && !$industry) {
            // Backward compatible fallback for older installs that used WordPress posts as insights.
            $fallback = ['post_type'=>'post','posts_per_page'=>$limit,'post_status'=>'publish','orderby'=>'date','order'=>'DESC'];
            $posts = get_posts($fallback);
        }
        return $posts;
    }

    private static function market_meta($market, $extra = []) {
        $query = ['relation'=>'AND', ['relation'=>'OR', ['key'=>'_tranter_markets','value'=>$market,'compare'=>'LIKE'], ['key'=>'_tranter_markets','compare'=>'NOT EXISTS']]];
        foreach ($extra as $e) $query[] = ['key'=>$e[0], 'value'=>$e[1], 'compare'=>'='];
        return $query;
    }

    public static function card($post) {
        $img = get_the_post_thumbnail_url($post->ID, 'medium_large') ?: 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&q=70&w=700';
        $industry = get_post_meta($post->ID, '_tranter_industry', true);
        $reading = self::reading_time($post);
        $excerpt = $post->post_excerpt ?: wp_trim_words(wp_strip_all_tags($post->post_content), 22);
        ob_start(); ?>
        <article class="trh-card te-kh-card">
            <a class="te-kh-card-img" href="<?php echo esc_url(get_permalink($post)); ?>" style="background-image:url('<?php echo esc_url($img); ?>')"></a>
            <div class="te-kh-card-body">
                <div class="te-kh-meta"><span><?php echo esc_html($industry ?: 'Insight'); ?></span><span><?php echo esc_html($reading); ?> min read</span></div>
                <h3><a href="<?php echo esc_url(get_permalink($post)); ?>"><?php echo esc_html($post->post_title); ?></a></h3>
                <p><?php echo esc_html($excerpt); ?></p>
                <a class="trh-learn" href="<?php echo esc_url(get_permalink($post)); ?>">Read Insight</a>
            </div>
        </article>
        <?php return ob_get_clean();
    }

    private static function featured_markup($post) {
        $img = get_the_post_thumbnail_url($post->ID, 'large') ?: 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&q=70&w=1200';
        $industry = get_post_meta($post->ID, '_tranter_industry', true) ?: 'Featured Insight';
        ob_start(); ?>
        <section class="trh-section te-kh-featured"><div class="trh-container"><div class="trh-card te-kh-featured-card">
            <div class="te-kh-featured-img" style="background-image:url('<?php echo esc_url($img); ?>')"></div>
            <div class="te-kh-featured-copy"><span class="trh-pill"><?php echo esc_html($industry); ?></span><h2><?php echo esc_html($post->post_title); ?></h2><p><?php echo esc_html($post->post_excerpt ?: wp_trim_words(wp_strip_all_tags($post->post_content), 30)); ?></p><a class="trh-btn trh-btn-primary" href="<?php echo esc_url(get_permalink($post)); ?>"><span>Read Featured Insight</span></a></div>
        </div></div></section>
        <?php return ob_get_clean();
    }

    public static function single_insight_content($content) {
        if (!is_singular('tranter_insight') || !in_the_loop() || !is_main_query()) return $content;
        self::enqueue();
        global $post;
        $resource = self::related_resource_markup($post->ID);
        $newsletter = get_post_meta($post->ID, '_tranter_show_newsletter', true) === '1' ? do_shortcode('[te_newsletter title="Get more Tranter insights" subtitle="Subscribe for practical guides, research and event updates." source="single_insight"]') : '';
        $related = self::related_markup($post->ID);
        $cta = self::cta_band('Let us help you turn insight into action.');
        ob_start(); ?>
        <article class="te-kh-single">
            <header class="te-kh-single-hero trh-full"><div class="trh-container"><span class="trh-pill"><?php echo esc_html(get_post_meta($post->ID, '_tranter_industry', true) ?: 'Insight'); ?></span><h1><?php echo esc_html(get_the_title($post)); ?></h1><p><?php echo esc_html(get_the_excerpt($post)); ?></p><div class="te-kh-single-meta"><span><?php echo esc_html(get_the_author_meta('display_name', $post->post_author)); ?></span><span><?php echo esc_html(get_the_date('', $post)); ?></span><span><?php echo esc_html(self::reading_time($post)); ?> min read</span></div></div></header>
            <div class="trh-container te-kh-single-wrap"><div class="te-kh-article-body"><?php echo $content; ?></div><?php echo $resource; ?><?php echo $newsletter; ?><?php echo $related; ?><?php echo $cta; ?></div>
        </article>
        <?php return ob_get_clean();
    }

    public static function related_resource_markup($post_id) {
        $resource_id = absint(get_post_meta($post_id, '_tranter_related_resource', true));
        if (!$resource_id) return '';
        $resource = get_post($resource_id);
        if (!$resource || $resource->post_type !== 'tranter_resource') return '';
        $file = get_post_meta($resource_id, '_tranter_resource_file_url', true);
        ob_start(); ?>
        <section class="te-kh-download-card trh-card"><div><span class="trh-pill">Related Resource</span><h2><?php echo esc_html($resource->post_title); ?></h2><p><?php echo esc_html($resource->post_excerpt ?: wp_trim_words(wp_strip_all_tags($resource->post_content), 24)); ?></p></div><?php if ($file): ?><a class="trh-btn trh-btn-primary" href="<?php echo esc_url(add_query_arg(['action'=>'tranter_resource_download','resource_id'=>$resource_id], admin_url('admin-post.php'))); ?>"><span>Download Resource</span></a><?php endif; ?></section>
        <?php return ob_get_clean();
    }

    public static function related_markup($post_id) {
        $current = get_post($post_id);
        $market = Tranter_Market::current();
        $posts = self::query_insights($market, 3, '', '', get_post_meta($post_id, '_tranter_industry', true), [$post_id]);
        if (!$posts) return '';
        ob_start(); echo '<section class="te-kh-related"><header class="trh-header"><div class="trh-pill">Related Insights</div><h2 class="trh-title">Continue reading</h2></header><div class="te-kh-grid">'; foreach ($posts as $post) echo self::card($post); echo '</div></section>'; return ob_get_clean();
    }

    public static function resource_band() {
        $resources = get_posts(['post_type'=>'tranter_resource','numberposts'=>1,'post_status'=>'publish','meta_key'=>'_tranter_featured','meta_value'=>'1']);
        if (!$resources) $resources = get_posts(['post_type'=>'tranter_resource','numberposts'=>1,'post_status'=>'publish']);
        if (!$resources) return '';
        $resource = $resources[0];
        $file = get_post_meta($resource->ID, '_tranter_resource_file_url', true);
        ob_start(); ?>
        <section class="trh-section te-kh-resource-band"><div class="trh-container"><div class="trh-card te-kh-download-card"><div><span class="trh-pill">Featured Resource</span><h2><?php echo esc_html($resource->post_title); ?></h2><p><?php echo esc_html($resource->post_excerpt ?: wp_trim_words(wp_strip_all_tags($resource->post_content), 24)); ?></p></div><?php if ($file): ?><a class="trh-btn trh-btn-primary" href="<?php echo esc_url(add_query_arg(['action'=>'tranter_resource_download','resource_id'=>$resource->ID], admin_url('admin-post.php'))); ?>"><span>Download Guide</span></a><?php endif; ?></div></div></section>
        <?php return ob_get_clean();
    }

    public static function cta_band($title = 'Ready to transform your organisation?') {
        ob_start(); ?><section class="te-kh-final-cta trh-full"><div class="trh-container"><h2><?php echo esc_html($title); ?></h2><p>Speak with Tranter IT about the right solution for your organisation, market and growth priorities.</p><div class="trh-cta-actions"><a class="trh-btn trh-btn-primary" href="/contact/" data-te-open-demo><span>Book Consultation</span></a><a class="trh-btn trh-btn-outline" href="https://wa.me/2348183405221?text=Hello%20Tranter%20IT,%20I%20would%20like%20to%20speak%20to%20your%20team."><span>Speak to Our Team</span></a></div></div></section><?php return ob_get_clean();
    }

    public static function popular_tags() {
        $tags = get_tags(['hide_empty'=>false, 'orderby'=>'count', 'order'=>'DESC']);
        return array_map(function($t){ return $t->name; }, $tags ?: []);
    }

    public static function popular_meta($key) {
        global $wpdb;
        $rows = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT meta_value FROM {$wpdb->postmeta} WHERE meta_key=%s AND meta_value<>'' ORDER BY meta_value ASC LIMIT 30", $key));
        return array_values(array_filter(array_map('sanitize_text_field', $rows ?: [])));
    }

    public static function reading_time($post) {
        $words = str_word_count(wp_strip_all_tags($post->post_content));
        return max(1, (int) ceil($words / 220));
    }

    public static function document_title($parts) {
        if (is_singular('tranter_insight')) {
            $seo = get_post_meta(get_the_ID(), '_tranter_seo_title', true);
            if ($seo) $parts['title'] = $seo;
        }
        return $parts;
    }

    public static function seo_meta() {
        if (!is_singular('tranter_insight')) return;
        $desc = get_post_meta(get_the_ID(), '_tranter_meta_description', true);
        if (!$desc) $desc = get_the_excerpt();
        if ($desc) echo '<meta name="description" content="'.esc_attr(wp_strip_all_tags($desc)).'">' . "\n";
    }
}
