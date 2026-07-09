# Tranter Hub v1.9.0 Review — Custom Events Form

This review update builds on `tranter-hub-v1.8.0-events-render-review.zip` and adds a guided Tranter Hub Event Form so non-technical staff can manage Events without using the default WordPress editor.

## Main update

Added a custom admin form at:

```text
Tranter Hub > Add Event
```

The Events screen button now points to this guided form instead of:

```text
post-new.php?post_type=tranter_event
```

## Default editor bypass

The plugin now redirects:

```text
post-new.php?post_type=tranter_event
post.php?post={event_id}&action=edit
```

to:

```text
admin.php?page=tranter-engine-event-form
admin.php?page=tranter-engine-event-form&event_id={event_id}
```

A fallback link remains inside the form to open the default WordPress editor with:

```text
te_default_editor=1
```

## Form fields included

- Event title
- Short summary
- Full description
- Event type
- Manual status: auto, upcoming, live, past, draft
- Start date/time
- End date/time
- Display date
- Display time
- Hero / featured image URL with media picker
- Featured event toggle
- Venue
- City
- Short location label
- Google Map link
- Gallery image URLs
- Intro video URL
- Recap video URL
- CTA label
- CTA URL
- Registration URL
- Market visibility
- Partner focus
- Lead owner
- Lead source
- Sponsor logo URLs
- Speakers

## Event actions included

- Publish event
- Save draft
- Preview event
- Duplicate event
- Delete event / move to Trash
- Open default WordPress editor fallback

## Frontend logic preserved

The frontend Events shortcode remains:

```text
[tranter_hub_events]
```

The Events page still reads backend data through:

```text
/wp-json/tranter-hub/v1/events
/wp-json/tranter-hub/v1/event-gallery
/wp-json/tranter-hub/v1/event-videos
```

The nearest upcoming event powers the hero event card and countdown.

## Review ZIP

Generated in ChatGPT as:

```text
tranter-hub-v1.9.0-events-form-review.zip
```

## QA

- PHP syntax check completed across plugin PHP files.
- ZIP archive validation completed successfully.
