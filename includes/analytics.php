<?php
if (!defined('ABSPATH')) exit;

class Tranter_Analytics {
    public static function get_kpis() {
        $total_views = 0;
        $total_subs = 0;
        
        $posts = get_posts(['post_type' => 'post', 'posts_per_page' => -1]);
        $published_count = count($posts);
        $draft_count = count(get_posts(['post_type' => 'post', 'post_status' => 'draft', 'posts_per_page' => -1]));
        
        $top_post = null;
        $max_views = -1;

        foreach ($posts as $post) {
            $views = (int) get_post_meta($post->ID, '_tranter_views', true);
            $total_views += $views;
            $total_subs += (int) get_post_meta($post->ID, '_tranter_subscribers', true);
            
            if ($views > $max_views) {
                $max_views = $views;
                $top_post = $post->post_title;
            }
        }

        return [
            'published' => $published_count,
            'drafts' => $draft_count,
            'total_views' => $total_views,
            'subscribers' => $total_subs,
            'top_insight' => $top_post ?: 'None'
        ];
    }
}
