<?php
if (!defined('ABSPATH')) exit;

function tranter_engine_market_label($market) {
    return $market === 'ng' ? 'Nigeria' : 'US/World';
}

function tranter_engine_markets() {
    return [
        'ng' => 'Nigeria',
        'global' => 'US/World',
    ];
}

function tranter_engine_get_meta($post_id, $key, $default = '') {
    $value = get_post_meta($post_id, $key, true);
    return $value === '' || $value === null ? $default : $value;
}

function tranter_engine_market_enabled($post_id, $market = null) {
    $market = $market ?: Tranter_Market::current();
    $enabled = get_post_meta($post_id, '_tranter_markets', true);
    if (!is_array($enabled)) $enabled = ['ng', 'global'];
    return in_array($market, $enabled, true);
}

function tranter_engine_cta_url($type = 'demo') {
    $settings = get_option('tranter_engine_settings', []);
    if ($type === 'whatsapp') return $settings['whatsapp_url'] ?? 'https://api.whatsapp.com/send/?phone=2348183405221&text=Hello+Tranter+IT%2C+I%20would%20like%20to%20speak%20to%20your%20team.&type=phone_number&app_absent=0';
    return $settings['zoho_form_url'] ?? 'https://forms.zohopublic.com/zohopeople1029/form/TranterWebsiteLeadGeneration/formperma/_8k-vw9IXCAlYqvhSXT4XNVEqH7wHI2pNwq0szW17h0';
}

function tranter_engine_svg_icon($name) {
    $icons = [
        'service' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19V5"/><path d="M4 19h16"/><path d="M8 16v-5"/><path d="M12 16V8"/><path d="M16 16v-7"/></svg>',
        'partner' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3 7h7l-5.5 4.4L18 21l-6-4-6 4 1.5-7.6L2 9h7z"/></svg>',
        'insight' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M4 4.5A2.5 2.5 0 0 1 6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5z"/></svg>',
        'event' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v4"/><path d="M16 2v4"/><path d="M3 10h18"/><path d="M5 4h14v18H5z"/></svg>',
        'cta' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg>',
        'faq' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9.1 9a3 3 0 1 1 5.8 1c0 2-3 2-3 4"/><path d="M12 17h.01"/><circle cx="12" cy="12" r="10"/></svg>',
        'section' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 9h18"/><path d="M8 14h8"/></svg>',
    ];
    return $icons[$name] ?? $icons['service'];
}

function tranter_engine_format_number($num) {
    if ($num >= 1000000) return round($num / 1000000, 1) . 'M';
    if ($num >= 1000) return round($num / 1000, 1) . 'K';
    return $num;
}
