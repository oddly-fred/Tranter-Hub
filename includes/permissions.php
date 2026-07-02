<?php
if (!defined('ABSPATH')) exit;

class Tranter_Permissions {
    public static function init() {}

    public static function capability() {
        return 'manage_options';
    }
}
