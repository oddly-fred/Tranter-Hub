# Tranter Engine v1.7.0 Direction: Editable HTML Engine

## Strategic decision

Tranter Engine should stop expanding public website pages as rigid PHP shortcode renderers. The website is a sales-focused, analytical and AI-driven IT enterprise website, so the page-building workflow must be flexible for developer editing, fast link changes, image replacement, embedded scripts and campaign-style testing.

The new standard is:

```text
Tranter Engine = website intelligence, publishing support, global CTA logic, analytics and reusable infrastructure.
HTML widgets/codebase = editable page, campaign and event body content.
```

Campaigns are the reference implementation. The campaign feature already uses pasted HTML as the source of truth while Tranter Engine provides wrapping, publishing support and analytics. Pages and events should follow the same philosophy.

## Scope moving forward

Campaigns, events and website pages should all support a full HTML/widget-code workflow.

### Campaigns

Campaigns remain full-HTML first:

- Paste full campaign HTML/widget code.
- Select wrapper behaviour where needed.
- Publish/update.
- Track page views, links, buttons, forms, WhatsApp, phone, email, outbound links and conversion labels.

### Pages

Service pages and strategic website pages should move away from page-body shortcodes such as service-rendering modules.

Recommended approach:

- Keep only infrastructure shortcodes where useful, mainly header and footer.
- Page body should be pasted as editable HTML in Elementor HTML widgets or a future Tranter page-template editor.
- Each page should use a scoped wrapper, for example:

```html
<div id="tranter-it-support-page">...</div>
<div id="tranter-digital-brand-page">...</div>
```

### Events

Events should also be codebase-driven like campaigns.

Event pages should support pasted HTML/widget code for:

- Event hero.
- Registration CTA.
- Agenda.
- Speakers.
- Partners/sponsors.
- Venue or virtual access.
- Resources.
- Post-event recap.
- Scripts and embeds.

Tranter Engine should provide event publishing support, wrapper settings and analytics, not force event layouts through rigid shortcode templates.

## Global Book a Demo standard

Every Book a Demo button in every future HTML codebase must use the global header popup logic.

Standard markup:

```html
<a href="#" data-tdp-open data-te-event="book_demo" data-te-service="it_support">Book a Demo</a>
```

or:

```html
<button type="button" data-tdp-open data-te-event="book_demo" data-te-service="digital_marketing">Book a Demo</button>
```

The page HTML should not include duplicate modal scripts for the demo form. The popup logic must be lifted from the global header/site chrome and reused everywhere.

## Tracking attributes standard

HTML codebases should support lightweight tracking attributes for sales and marketing analytics.

### Book a Demo

```html
<a href="#" data-tdp-open data-te-event="book_demo" data-te-service="it_support">Book a Demo</a>
```

### WhatsApp CTA

```html
<a href="https://api.whatsapp.com/send/?phone=2348183405221" data-te-event="whatsapp_click" data-te-service="smart_solutions">Speak to Our Team</a>
```

### Campaign registration

```html
<a href="#register" data-te-event="campaign_register" data-te-campaign="itgov_2026">Register Now</a>
```

### Event registration

```html
<a href="#register" data-te-event="event_register" data-te-event-name="itgov_2026">Register for Event</a>
```

### Insight CTA

```html
<a href="#" data-tdp-open data-te-event="book_demo" data-te-source="insight" data-te-topic="cybersecurity">Book a Demo</a>
```

## Analytics value

This structure should help the sales and marketing team understand:

- Which pages generate demo intent.
- Which services attract the most interest.
- Which campaigns generate clicks and conversions.
- Which events drive registration and post-event leads.
- Which insights support lead generation.
- Which CTAs perform best.
- Which sections or pages need improvement.

## Proposed lean backend UI

Tranter Engine should be cleaned into the following core areas:

```text
Tranter Engine
├── Dashboard
├── Website Engine
├── Page Templates
├── Campaigns
├── Events
├── Insights
├── Leads & Analytics
└── Settings
```

### Dashboard

Show a lean business overview:

- Demo clicks.
- WhatsApp clicks.
- Top service pages.
- Top campaigns.
- Top insights.
- Event performance.
- Recent lead-intent activity.

### Website Engine

Handle global infrastructure:

- Header/footer logic.
- Global Book a Demo popup.
- WhatsApp number.
- Global scripts.
- Market/GeoIP behaviour where needed.
- Tracking settings.

### Page Templates

Developer-friendly template library:

- IT Support Services HTML.
- Smart Solutions HTML.
- HR Support Services HTML.
- Digital Marketing & Brand Development HTML.
- Cybersecurity HTML.
- BPO HTML.
- Website Development HTML.

Each template should include:

- Copy HTML button.
- View/edit template code.
- Required images.
- Required links.
- Tracking notes.
- Version notes.

### Campaigns

Keep the existing campaign-style logic and refine analytics.

### Events

Replicate campaign logic where necessary:

- Pasted full HTML/widget code.
- Event metadata.
- Market visibility.
- Wrapper option.
- Auto-generated event page where useful.
- Registration and CTA analytics.

### Insights

Keep insights as the content marketing engine and connect insight CTAs to analytics.

### Leads & Analytics

Central reporting for:

- Page views.
- CTA clicks.
- Book a Demo opens.
- WhatsApp clicks.
- Campaign conversions.
- Event registrations/clicks.
- Insight CTA performance.
- Service interest.

## Implementation order

1. Clean backend UI.
2. Standardize global Book a Demo and tracking attributes.
3. Add editable Page Templates area.
4. Refine campaign analytics using the same tracking standard.
5. Build Events as campaign-style HTML engine.
6. Connect Insights to the same analytics layer.
7. Build Leads & Analytics dashboard.

## Rule moving forward

Do not build new public page bodies as rigid PHP shortcode renderers unless there is a strong reason.

Preferred pattern:

```text
Editable HTML/widget code + Tranter Engine global logic + analytics tracking.
```
