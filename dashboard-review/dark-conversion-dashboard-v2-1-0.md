# Tranter Hub v2.1.0 Review — Dark Conversion Dashboard

This review update builds on `tranter-hub-v2.0.0-content-forms-review.zip` and updates the Tranter Hub backend dashboard into a dark, calm, app-like command centre inspired by the provided dashboard reference.

## Main direction

The backend should feel like its own Tranter Hub product, not a default WordPress admin screen. Editors should feel calm, safe and confident when managing content.

## Dashboard UX updates

- Dark overall backend background with subtle Tranter green and wine accents.
- Smaller app-like cards.
- Glass-style panels and soft shadows.
- Calm wording and guided actions for non-technical staff.
- Dashboard renamed as a conversion command centre.
- Content health module for Campaigns, Events, Insights and Knowledge Base.

## Real conversion-focused data now shown

The dashboard now surfaces:

- Important clicks
- Demo clicks
- Total clicks
- Page views
- Campaign conversions
- WhatsApp clicks
- Outbound clicks
- Form submits
- Top entry points
- Exit points
- Latest tracked activity

## New global analytics tracker

Added:

```text
includes/dashboard-analytics.php
```

The tracker stores aggregate data in:

```text
te_hub_analytics_v1
```

Frontend tracking sends:

- page view
- click
- demo click
- important CTA click
- WhatsApp click
- outbound click
- form submit
- exit

## Existing campaign analytics preserved

Campaign-specific metrics are still read from:

```text
_te_campaign_views
_te_campaign_clicks
_te_campaign_conversions
_te_campaign_recent_events
```

The dashboard combines global interaction signals with existing campaign conversion data.

## Plugin version

```text
2.1.0-dashboard-review
```

## Review ZIP

Generated in ChatGPT as:

```text
tranter-hub-v2.1.0-dashboard-review.zip
```

## QA

- PHP syntax check completed across all plugin PHP files.
- ZIP archive validation completed successfully.
