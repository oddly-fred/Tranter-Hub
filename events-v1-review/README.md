# Tranter Events v1 Review

This review package introduces a homepage-aligned Events page for Tranter Hub.

## Included in the review build

- App-like homepage visual style using Mulish, Tranter green and wine accents.
- Hero section positioning Tranter as a sponsor, host and leader in technology events.
- Countdown timer for the next featured event.
- Featured events cards for ITGOV, Zoho sessions and ManageEngine workshops.
- Past-event gallery section for event images.
- Hosted-event video showcase section for short event intros, trailers and recaps.
- Conversion CTA for sponsorship, co-hosting and event partnerships.
- Frontend hooks for backend-fed event data.

## Backend path roadmap for Tranter Hub plugin

Recommended admin module:

```text
Tranter Hub > Events
```

Recommended post type:

```text
tranter_event
```

Recommended REST paths:

```text
/wp-json/tranter-hub/v1/events
/wp-json/tranter-hub/v1/events/{slug}
/wp-json/tranter-hub/v1/event-gallery
/wp-json/tranter-hub/v1/event-videos
/wp-json/tranter-hub/v1/event-sponsors
/wp-json/tranter-hub/v1/event-speakers
/wp-json/tranter-hub/v1/event-registrations
```

Recommended event fields:

- title
- slug
- event_type
- status: upcoming, live, past
- start_datetime
- end_datetime
- venue
- city
- summary
- description
- hero_image
- gallery_images
- intro_video_url
- recap_video_url
- sponsor_logos
- speaker_list
- registration_url
- cta_label
- cta_url
- market_visibility: ng, global, all
- partner_focus: zoho, manageengine, sophos, smart-solutions, general
- lead_owner
- lead_source

## Frontend data attributes included

The page shell includes:

```html
data-events-endpoint="/wp-json/tranter-hub/v1/events"
data-gallery-endpoint="/wp-json/tranter-hub/v1/event-gallery"
data-video-endpoint="/wp-json/tranter-hub/v1/event-videos"
```

The first pass hydrates featured event cards from `/wp-json/tranter-hub/v1/events` when available, while keeping fallback content visible for preview.

## Suggested implementation roadmap

1. Add Events admin module under Tranter Hub plugin.
2. Register `tranter_event` custom post type.
3. Add event metadata fields for date, venue, media, speakers, sponsors and CTA.
4. Add REST endpoints for events, gallery and videos.
5. Connect frontend sections to REST output.
6. Add lead capture routing to CRM or Tranter Hub Leads module.
7. Add analytics events for countdown, gallery clicks, video plays, CTA clicks and registration clicks.
8. Add Elementor shortcode: `[tranter_hub_events]`.

## Review ZIP

Generated in ChatGPT as:

```text
tranter-events-v1-review.zip
```
