# Tranter Events v1.1 Refinement

This update responds to the Events page review feedback.

## Updates included

- Removed the event impact dashboard.
- Replaced the dashboard with a leadership/proof panel that shows why Tranter is trusted for tech events.
- Updated the hero to feel more live with floating animated event badges, glow motion and a floating event card.
- Added an upcoming-event display card in the hero with event image, event type, venue, summary and CTA.
- Countdown timer now belongs to the upcoming event and is ready to be backend-fed.
- Featured event cards now include images.
- Updated frontend event logic to pick the nearest upcoming event from `/wp-json/tranter-hub/v1/events`.
- Preserved gallery, videos, CTA and REST hooks.

## Backend behavior roadmap

The Events admin module should allow a non-technical user to create and update event information. Recommended logic:

1. Admin creates an event under `Tranter Hub > Events`.
2. Event is stored as `tranter_event` with `status`: `upcoming`, `live`, or `past`.
3. Event includes title, type, image, venue, city, start date/time, summary and CTA URL.
4. Frontend fetches `/wp-json/tranter-hub/v1/events`.
5. The nearest future event powers the hero upcoming card and countdown.
6. Featured event cards are populated from the same endpoint.
7. When the event date passes or admin marks it as `past`, it moves into past events/gallery/video areas.

## Review ZIP

Generated in ChatGPT as:

```text
tranter-events-v1-1-review.zip
```
