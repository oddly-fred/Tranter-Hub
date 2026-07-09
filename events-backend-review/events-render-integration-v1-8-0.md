# Tranter Hub v1.8.0 Review — Events Render Integration

This review patch was built on top of the uploaded `tranter-hub-v1.7.3-preview(1).zip` plugin.

## Main goal

Connect the Events backend logic so the approved Events HTML page renders from the plugin and pulls event data from the backend, similar to how Insights is managed by non-technical users.

## What changed

- Added `templates/events-page.html` from the approved Events page HTML.
- Added `includes/events-page.php`.
- Added frontend shortcode:

```text
[tranter_hub_events]
```

Aliases:

```text
[te_events]
[te_events_page]
```

- Registered backend-fed Events REST routes:

```text
/wp-json/tranter-hub/v1/events
/wp-json/tranter-hub/v1/events/{slug}
/wp-json/tranter-hub/v1/event-gallery
/wp-json/tranter-hub/v1/event-videos
/wp-json/tranter-hub/v1/event-sponsors
/wp-json/tranter-hub/v1/event-speakers
/wp-json/tranter-hub/v1/event-registrations
```

- Updated the `tranter_event` post type meta fields for non-technical event input:
  - Event type
  - Status: auto, upcoming, live, past
  - Start date/time
  - End date/time
  - Venue
  - City
  - Hero/featured image URL
  - Gallery image URLs
  - Intro video URL
  - Recap video URL
  - Sponsor logos
  - Speakers
  - CTA label and URL
  - Registration URL
  - Market visibility
  - Partner focus
  - Lead owner/source

- Added lifecycle logic:
  - Future events render as upcoming.
  - Current events render as live.
  - Completed events render as past.

- Updated the Lean Admin Events module with usage guidance for `[tranter_hub_events]` and backend-fed media logic.
- Added the Events template to the Page Templates library.
- Updated the Events navigation route from `/event/` to `/events/`.

## Review ZIP

Generated in ChatGPT as:

```text
tranter-hub-v1.8.0-events-render-review.zip
```

## QA performed

- PHP syntax check completed across all plugin PHP files.
- ZIP archive validation completed successfully.
