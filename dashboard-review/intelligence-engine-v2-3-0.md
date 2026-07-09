# Tranter Hub v2.3.0 Review — Intelligence Engine

This review update builds on `tranter-hub-v2.2.0-leads-analytics-review.zip` and fixes the core issue raised during testing: analytics should begin updating when a visitor browses the public website from phone or desktop.

## Main update

The analytics tracker is now loaded globally on frontend pages, not only when selected Tranter shortcodes are rendered. This means page views, clicks and referral signals can begin populating once the plugin is active and public pages are visited.

## Live tracking updates

Added/updated:

- Global frontend tracker on `wp_enqueue_scripts`.
- `navigator.sendBeacon()` delivery with `fetch(... keepalive)` fallback.
- Session ID and visitor ID storage.
- UTM capture:
  - `utm_source`
  - `utm_medium`
  - `utm_campaign`
  - `utm_content`
  - `utm_term`
- Referrer capture from `document.referrer`.
- Device, browser and operating system capture.
- Scroll-depth tracking at 25%, 50%, 75% and 100%.
- Time-on-page and exit-page tracking.
- Download tracking for PDF, DOC, XLS, PPT and ZIP files.
- Search interaction tracking.

## Sales and marketing signals tracked

- Page views
- Demo clicks
- Important CTA clicks
- WhatsApp clicks
- Form submissions
- Outbound clicks
- Downloads / datasheets
- Searches
- Scroll depth
- Exit pages
- Returning visitor/session signals

## Dashboard update

Leads & Analytics now polls live data every 8 seconds from:

```text
admin-ajax.php?action=te_hub_live_data
```

KPI cards now update without needing a full dashboard reload.

## Lead scoring

High-intent sales signals now receive simple scores:

- Form submit: 95
- Demo click: 90
- WhatsApp click: 85
- Important CTA: 70
- Download: 55

Each lead signal records:

- action type
- label
- page path
- referral source
- medium
- campaign
- device
- browser
- operating system
- session ID
- timestamp

## Referral intelligence

The tracker prioritises UTM values first, then falls back to browser referrer. Examples:

- Google Organic
- LinkedIn
- Facebook
- Instagram
- X / Twitter
- WhatsApp
- Email
- QR Code
- Direct
- Partner domains

## Review ZIP

Generated in ChatGPT as:

```text
tranter-hub-v2.3.0-intelligence-engine-review.zip
```

## QA

- PHP syntax check completed across all plugin PHP files.
- ZIP archive validation completed successfully.

## Important testing note

After installing this version, analytics will start collecting from new visits going forward. Visits that happened before the tracker was active cannot be backfilled.