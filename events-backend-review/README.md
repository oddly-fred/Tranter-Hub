# Tranter Hub Events Backend Review Plugin

This review package updates the backend direction for the Events page built in this chat. It provides the Events module logic needed for non-technical users to manage upcoming and past Tranter events in the same spirit as Insights.

## Review ZIP generated

```text
tranter-hub-events-backend-review.zip
```

## What the plugin review build adds

- `Tranter Hub > Events` admin section.
- `tranter_event` custom post type.
- Non-technical event fields for event date, venue, city, image, CTA, gallery images, videos, sponsors, speakers, partner focus and lead routing.
- Auto lifecycle logic:
  - future events display as `upcoming`
  - active events display as `live`
  - completed events display as `past`
- REST endpoints for frontend consumption.
- `[tranter_hub_events]` shortcode for the Events page.
- Backend-fed hero upcoming-event card.
- Countdown timer driven by the nearest upcoming event.
- Featured event cards with images.
- Past event gallery and hosted-event videos.
- View-more routes for gallery and videos.

## REST endpoints included

```text
/wp-json/tranter-hub/v1/events
/wp-json/tranter-hub/v1/events/{slug}
/wp-json/tranter-hub/v1/event-gallery
/wp-json/tranter-hub/v1/event-videos
/wp-json/tranter-hub/v1/event-sponsors
/wp-json/tranter-hub/v1/event-speakers
/wp-json/tranter-hub/v1/event-registrations
```

## Admin fields included

- title
- event type
- manual status: auto, upcoming, live, past
- start date/time
- end date/time
- venue
- city
- short summary
- hero/featured image URL
- gallery image URLs
- intro video URL
- recap video URL
- sponsor logo URLs
- speakers
- CTA label
- CTA URL
- market visibility: all, Nigeria, Global/US
- partner focus: general, Zoho, ManageEngine, Sophos, Smart Solutions
- lead owner
- lead source
- featured on Events page

## Frontend behavior

The shortcode uses the REST API to load event data. The nearest future event powers the hero card and countdown. Featured event cards are image-led. Gallery and videos are powered by their corresponding REST endpoints, with fallback content retained for review.

## Implementation note

This is a review plugin package. After approval, merge the Events CPT, meta fields, REST routes and shortcode into the main Tranter Hub plugin so Events sits beside Insights, Leads and other backend modules.
