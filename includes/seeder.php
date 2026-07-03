<?php
if (!defined('ABSPATH')) exit;

class Tranter_Seeder {
    public static function seed() {
        if (get_option('tranter_engine_seeded_beta')) return;
        $services = [
            ['IT Support Services','it-support-services','Reliable infrastructure support, uptime management and service continuity across operating environments.',['ng','global']],
            ['Smart Solutions','smart-solutions','Workflow automation and intelligent systems that reduce manual effort and improve decisions.',['ng']],
            ['HR Support Services','hr-support-services','Technology-enabled workforce operations for distributed teams and enterprise environments.',['ng','global']],
            ['Digital Marketing & Brand','digital-marketing-brand','Enterprise-grade digital presence aligned to growth, credibility and customer acquisition.',['ng','global']],
            ['Business Process Outsourcing','business-process-outsourcing','Managed operational systems that turn support functions into scalable execution models.',['ng','global']],
            ['Website Development & Optimisation','website-development-optimisation','High-performance web platforms built as commercial and operational infrastructure.',['ng','global']],
            ['Cybersecurity','cybersecurity','Security-first operations that protect infrastructure, data and business continuity.',['ng','global']],
        ];
        foreach ($services as $idx => $s) self::create('tranter_service', $s[0], $s[1], $s[2], $s[3], $idx + 1);

        $partners = [
            ['Zoho Solutions','zoho-solutions','CRM, finance, support and workplace automation implemented and supported by Tranter IT.',['ng','global']],
            ['ManageEngine','manageengine','IT operations, monitoring, service desk and endpoint management solutions.',['ng']],
            ['Sophos','sophos','Cybersecurity protection for endpoints, networks and managed detection.',['ng']],
        ];
        foreach ($partners as $idx => $p) self::create('tranter_partner', $p[0], $p[1], $p[2], $p[3], $idx + 1);

        $products = [
            ['Zoho CRM','zoho-crm','Sales CRM for lead, pipeline and customer relationship management.',['ng','global']],
            ['Zoho Books','zoho-books','Cloud accounting and finance operations for growing businesses.',['ng','global']],
            ['Zoho Desk','zoho-desk','Customer support and ticketing platform for service teams.',['ng','global']],
            ['Zoho Workplace','zoho-workplace','Business email, collaboration and workplace productivity suite.',['ng','global']],
            ['Zoho People','zoho-people','HR management and workforce operations platform.',['ng']],
            ['Zoho Projects','zoho-projects','Project management and delivery collaboration.',['ng']],
            ['Zoho Analytics','zoho-analytics','Business intelligence and reporting dashboards.',['ng']],
            ['ManageEngine Endpoint Central','manageengine-endpoint-central','Unified endpoint management for enterprise IT teams.',['ng']],
            ['Sophos Firewall','sophos-firewall','Network protection and firewall security for organisations.',['ng']],
        ];
        foreach ($products as $idx => $p) self::create('tranter_product', $p[0], $p[1], $p[2], $p[3], $idx + 1);

        self::create('tranter_cta','Book Consultation','book-consultation','Start a strategic conversation with Tranter IT.',['ng','global']);
        self::create('tranter_cta','Request Website Audit','request-website-audit','Get a practical assessment of your website and conversion opportunities.',['ng','global']);
        self::create('tranter_faq','How do we start?','how-do-we-start','We begin with a discovery conversation to understand your organisation, goals and current technology environment.',['ng','global']);
        self::ensure_sections();
        update_option('tranter_engine_seeded_beta', 1);
    }


    public static function ensure_sections() {
        $sections = self::section_defaults();
        foreach ($sections as $section) {
            self::create_section($section[0], $section[1], $section[2], $section[3], $section[4], $section[5]);
        }
        update_option('tranter_engine_sections_seeded_104', 1);
    }

    public static function migrate_106() {
        $services = [
            ['IT Support Services','it-support-services','Reliable infrastructure support, uptime management and service continuity across operating environments.',['ng','global'],1],
            ['Smart Solutions','smart-solutions','Workflow automation and intelligent systems that reduce manual effort and improve decisions.',['ng'],2],
            ['HR Support Services','hr-support-services','Technology-enabled workforce operations for distributed teams and enterprise environments.',['ng','global'],3],
            ['Digital Marketing & Brand','digital-marketing-brand','Enterprise-grade digital presence aligned to growth, credibility and customer acquisition.',['ng','global'],4],
            ['Business Process Outsourcing','business-process-outsourcing','Managed operational systems that turn support functions into scalable execution models.',['ng','global'],5],
            ['Website Development & Optimisation','website-development-optimisation','High-performance web platforms built as commercial and operational infrastructure.',['ng','global'],6],
            ['Cybersecurity','cybersecurity','Security-first operations that protect infrastructure, data and business continuity.',['ng','global'],7],
        ];
        foreach ($services as $s) {
            $id = self::create('tranter_service', $s[0], $s[1], $s[2], $s[3], $s[4]);
            if ($id) {
                wp_update_post(['ID' => $id, 'post_title' => $s[0], 'post_excerpt' => $s[2], 'post_content' => $s[2], 'menu_order' => $s[4]]);
                update_post_meta($id, '_tranter_markets', $s[3]);
                if ($s[1] === 'smart-solutions') {
                    update_post_meta($id, '_tranter_store_cta_label', 'Visit Smart Solutions Store');
                    update_post_meta($id, '_tranter_store_cta_url', 'https://shop.tranter-it.com/');
                    update_post_meta($id, '_tranter_store_cta_market', 'ng');
                }
            }
        }
        update_option('tranter_engine_migrated_106', 1);
    }



    public static function section_defaults() {
        return [
            ['Home', 'home', 'Sales-focused technology platform for modern business.', 'Explore services, partner solutions, insights and conversion paths built for Nigeria and US/World audiences.', ['ng','global'], 1],
            ['Who We Are', 'who-we-are', 'Delivering Secure and Scalable Technology Solutions', 'Tranter IT helps organisations improve performance, resilience and operational control through reliable digital systems and expert support.', ['ng','global'], 2],
            ['What We Do', 'what-we-do', 'Enhancing Businesses with Smart Workflow', 'Enterprise technology capabilities for organisations that need reliable infrastructure, better workflows and stronger execution.', ['ng','global'], 3],
            ['Clients That Trust Us', 'clients', 'Trusted by organisations committed to performance.', 'Client trust section for logos, proof points and credibility copy.', ['ng','global'], 4],
            ['Industries', 'industries', 'Industries We Serve', 'We support complex, regulated and high-growth sectors with dependable technology services.', ['ng','global'], 5],
            ['Consultation CTA', 'cta-consultation', 'Start a Strategic Conversation', 'Transform operations, strengthen resilience and accelerate growth with enterprise-grade technology solutions tailored to your organisation.', ['ng','global'], 6],
            ['Home Hero', 'home-hero', 'Technology. At Scale. Without Borders.', 'Driving business efficiency and digital transformation with secure infrastructure, managed support and smart workflow solutions built for enterprise growth.', ['ng','global'], 7],
            ['Global Delivery', 'global-delivery', 'Global. Distributed. Flexible.', 'AI-assisted delivery visibility for secure, scalable operations across markets, teams and time zones.', ['ng','global'], 8],
            ['How We Work', 'how-we-work', 'A Strategy-Led Engagement Model', 'We operate as advisors and delivery partners, not transactional vendors.', ['ng','global'], 9],
            ['Why Tranter', 'why-tranter', 'Built for Enterprise Performance', 'Secure infrastructure, distributed operations and long-term support built for growth-focused organisations.', ['ng','global'], 10],
            ['Frequently Asked Questions', 'faq', 'Answers to common questions about Tranter IT services.', 'Helping you make informed decisions before speaking with our team.', ['ng','global'], 11],
        ];
    }

    public static function migrate_109() {
        if (get_option('tranter_engine_migrated_109')) return;
        foreach (self::section_defaults() as $section) {
            $id = self::create_section($section[0], $section[1], $section[2], $section[3], $section[4], $section[5]);
            if ($id) {
                wp_update_post([
                    'ID' => $id,
                    'post_title' => $section[0],
                    'post_excerpt' => $section[2],
                    'post_content' => $section[3],
                    'menu_order' => $section[5],
                ]);
                update_post_meta($id, '_tranter_markets', $section[4]);
                update_post_meta($id, '_tranter_section_key', $section[1]);
            }
        }
        $faqs = [
            ['What does Tranter specialize in?', 'what-does-tranter-specialize-in', 'Tranter specializes in IT support services, smart solutions, HR support, digital marketing, business process outsourcing, website development and cybersecurity.'],
            ['Do you provide managed IT services?', 'do-you-provide-managed-it-services', 'Yes. We provide managed IT services designed to support reliable infrastructure, secure operations, service continuity and scalable enterprise technology delivery.'],
            ['Can Tranter support enterprise-level organisations?', 'can-tranter-support-enterprise-level-organisations', 'Yes. Our systems, delivery model and operational approach are designed for enterprise-scale requirements, regulated environments and high-growth organisations.'],
            ['What industries do you serve?', 'what-industries-do-you-serve', 'We serve government, financial services, healthcare, manufacturing, education, retail, hospitality, oil and gas, SMEs, NGOs, logistics, real estate and infrastructure-led organisations.'],
            ['How do we start working with Tranter?', 'how-do-we-start', 'We begin with a discovery conversation to understand your organisation, goals and current technology environment, then recommend the best service path.'],
            ['What makes Tranter different?', 'what-makes-tranter-different', 'Tranter works as a strategic partner, not just a vendor. We combine secure technology delivery, operational insight and scalable execution to improve performance.'],
        ];
        foreach ($faqs as $idx => $faq) {
            $existing = get_page_by_path($faq[1], OBJECT, 'tranter_faq');
            if ($existing) {
                wp_update_post(['ID' => $existing->ID, 'post_title' => $faq[0], 'post_excerpt' => $faq[2], 'post_content' => $faq[2], 'menu_order' => $idx + 1, 'post_status' => 'publish']);
                update_post_meta($existing->ID, '_tranter_markets', ['ng','global']);
            } else {
                self::create('tranter_faq', $faq[0], $faq[1], $faq[2], ['ng','global'], $idx + 1);
            }
        }
        update_option('tranter_engine_migrated_109', 1);
    }



    public static function migrate_110() {
        if (get_option('tranter_engine_migrated_110')) return;
        $updates = [
            'cta-consultation' => [
                'Final Conversion CTA',
                'Ready to improve your technology operations?',
                'Speak with Tranter IT about the right solution for your organisation, market and growth priorities.',
            ],
            'clients' => [
                'Clients That Trust Us',
                'Trusted by organisations committed to performance.',
                'Proof points, client logos and credibility signals that help visitors trust Tranter IT before they speak with Sales.',
            ],
            'faq' => [
                'Frequently Asked Questions',
                'Answers to common questions about Tranter IT services.',
                'Helpful answers for decision makers evaluating Tranter IT services before speaking with our team.',
            ],
        ];
        foreach ($updates as $key => $data) {
            $post = get_page_by_path($key, OBJECT, 'tranter_section');
            if ($post) {
                wp_update_post([
                    'ID' => $post->ID,
                    'post_title' => $data[0],
                    'post_excerpt' => $data[1],
                    'post_content' => $data[2],
                    'post_status' => 'publish',
                ]);
                update_post_meta($post->ID, '_tranter_section_key', $key);
                update_post_meta($post->ID, '_tranter_markets', ['ng','global']);
            }
        }
        update_option('tranter_engine_migrated_110', 1);
    }

    private static function create_section($title, $key, $excerpt, $content, $markets, $order = 0) {
        $existing = get_page_by_path($key, OBJECT, 'tranter_section');
        if ($existing) {
            update_post_meta($existing->ID, '_tranter_section_key', $key);
            return $existing->ID;
        }
        $id = wp_insert_post([
            'post_type' => 'tranter_section',
            'post_title' => $title,
            'post_name' => $key,
            'post_excerpt' => $excerpt,
            'post_content' => $content,
            'post_status' => 'publish',
            'menu_order' => $order,
        ]);
        if ($id && !is_wp_error($id)) {
            update_post_meta($id, '_tranter_markets', $markets);
            update_post_meta($id, '_tranter_featured', '1');
            update_post_meta($id, '_tranter_section_key', $key);
        }
        return $id;
    }

    private static function create($type, $title, $slug, $excerpt, $markets, $order = 0) {
        $existing = get_page_by_path($slug, OBJECT, $type);
        if ($existing) {
            wp_update_post(['ID' => $existing->ID, 'menu_order' => intval($order)]);
            update_post_meta($existing->ID, '_tranter_markets', $markets);
            return $existing->ID;
        }
        $id = wp_insert_post([
            'post_type' => $type,
            'post_title' => $title,
            'post_name' => $slug,
            'post_excerpt' => $excerpt,
            'post_content' => $excerpt,
            'post_status' => 'publish',
            'menu_order' => intval($order),
        ]);
        if ($id && !is_wp_error($id)) {
            update_post_meta($id, '_tranter_markets', $markets);
            update_post_meta($id, '_tranter_featured', '1');
        }
        return $id;
    }
}
