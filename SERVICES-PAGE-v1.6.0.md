# Tranter Engine v1.6.0 - Services / Solutions Page Sprint

This sprint adds a sales-focused Services / Solutions page module without disturbing the existing homepage, insights, campaigns, header/footer, or Who We Are module.

## Primary full-page shortcode

```text
[te_services_page]
```

Alias:

```text
[te_solutions_page]
```

## Recommended WordPress page structure

```text
[te_site_header]
[te_services_page]
[te_site_footer]
```

## Modular shortcodes

```text
[te_services_hero]
[te_services_overview]
[te_solution_categories]
[te_managed_it_services]
[te_cybersecurity_services]
[te_cloud_infrastructure]
[te_smart_solutions]
[te_services_cta]
```

## Service areas covered

- Managed IT Services
- Cybersecurity Services
- Cloud & Infrastructure
- Smart Solutions

## Lead-generation intent

The page is structured to support a simple sales journey:

1. Explain Tranter's enterprise service ecosystem.
2. Segment visitors by solution need.
3. Present each service area with practical feature lists.
4. Push visitors toward consultation, WhatsApp enquiry, or service detail pages.

## Files added

- `includes/services-page.php`
- `assets/css/services-page.css`

## Files updated

- `includes/class-tranter-engine.php`
- `tranter-engine.php`

## Version

Plugin version bumped to `1.6.0`.
