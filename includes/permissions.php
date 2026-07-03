<?php
if (!defined('ABSPATH')) exit;

class Tranter_Permissions {
    public static function init() {
        // This will be expanded in future sprints to handle specific role capabilities
        // For now, we use standard WP capabilities but define the logic here
    }

    public static function can_manage_insights($user = null) {
        return current_user_can('edit_posts');
    }

    public static function can_view_analytics($user = null) {
        return current_user_can('manage_options');
    }
}
