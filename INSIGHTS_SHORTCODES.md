# Tranter Engine v1.3.1 - Insights Page Shortcodes

Create a WordPress page titled `Insights` and add these shortcodes in this order:

```text
[te_insights_hero]
[te_featured_insight]
[te_insights_search]
[te_insights_tags]
[te_insights_grid limit="9"]
[te_featured_resource]
[te_newsletter title="Stay ahead of technology trends" subtitle="Receive practical Tranter insights, guides and event updates directly in your inbox."]
[te_insights_cta]
```

## Available modular shortcodes

- `[te_insights_hero]` - Hero section with search and popular tags.
- `[te_featured_insight]` - Large featured Insight card.
- `[te_insights_search]` - Standalone search bar.
- `[te_insights_tags]` - Popular tag/topic cloud.
- `[te_insights_grid limit="9"]` - Filterable Insight grid.
- `[te_featured_resource]` - Featured Resource download band.
- `[te_newsletter]` - Newsletter lead form.
- `[te_insights_cta]` - Bottom CTA section.

## Backward compatibility

The existing `[te_knowledge_hub]` and `[te_insights_page]` full-page shortcodes still work, but the recommended flow is now the modular Insights page shortcode flow.
