<?php
if (!defined('ABSPATH')) exit;

class Tranter_Page_Templates {
    public static function init() {
        // Page Templates are rendered from Tranter_Lean_Admin.
    }

    public static function templates() {
        return [
            'homepage-v171' => [
                'title' => 'Homepage HTML Template',
                'version' => 'v1.7.1',
                'category' => 'Website Page',
                'description' => 'Editable homepage HTML for Elementor HTML widgets. Uses global Book a Demo and sales tracking attributes.',
                'file' => TRANTER_ENGINE_PATH . 'templates/page-homepage-v1.7.0.html',
                'usage' => 'Paste into an Elementor HTML widget. Use with the global Tranter site chrome/header so data-tdp-open triggers the shared Book a Demo popup.',
            ],
        ];
    }

    public static function render() {
        $templates = self::templates();
        echo '<section class="te-title-row"><div><span class="te-kicker">Editable HTML Library</span><h1>Page Templates</h1><p>View and copy developer-editable HTML codebases for Elementor HTML widgets. Page bodies are no longer locked into rigid shortcodes.</p></div></section>';
        echo '<section class="te-panel te-template-rules"><div class="te-panel-head"><h2>HTML Widget Standard</h2><span>v1.7.1</span></div><div class="te-template-rule-grid">';
        echo '<article><strong>Book a Demo</strong><code>&lt;a href=&quot;#&quot; data-tdp-open data-te-event=&quot;book_demo&quot; data-te-service=&quot;homepage&quot;&gt;Book a Demo&lt;/a&gt;</code></article>';
        echo '<article><strong>WhatsApp CTA</strong><code>data-te-event=&quot;whatsapp_click&quot; data-te-service=&quot;homepage&quot;</code></article>';
        echo '<article><strong>Tracking</strong><code>data-te-event, data-te-service, data-te-source</code></article>';
        echo '</div></section>';
        echo '<section class="te-template-grid">';
        foreach ($templates as $key => $template) {
            $code = file_exists($template['file']) ? file_get_contents($template['file']) : '<!-- Template file missing. -->';
            $textarea_id = 'te-template-code-' . sanitize_key($key);
            echo '<article class="te-template-card">';
            echo '<div class="te-template-card-head"><div><span>'.esc_html($template['category']).'</span><h2>'.esc_html($template['title']).'</h2><p>'.esc_html($template['description']).'</p></div><b>'.esc_html($template['version']).'</b></div>';
            echo '<div class="te-template-actions"><button type="button" class="te-btn te-btn-primary" data-te-copy-template data-target="#'.esc_attr($textarea_id).'">Copy HTML</button><button type="button" class="te-btn" data-te-toggle-template data-target="#'.esc_attr($textarea_id).'">View HTML</button></div>';
            echo '<p class="te-muted">'.esc_html($template['usage']).'</p>';
            echo '<textarea id="'.esc_attr($textarea_id).'" class="te-template-code" readonly rows="18">'.esc_textarea($code).'</textarea>';
            echo '</article>';
        }
        echo '</section>';
    }
}
