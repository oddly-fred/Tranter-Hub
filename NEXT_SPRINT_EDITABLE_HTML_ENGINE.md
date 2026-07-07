# Tranter Engine v1.7.0 Direction: Editable HTML Engine

## Strategic decision

Tranter Engine should stop expanding public website pages as rigid PHP shortcode renderers. The website is a sales-focused, analytical and AI-driven IT enterprise website, so the page-building workflow must be flexible for developer editing, fast link changes, image replacement, embedded scripts and campaign-style testing.

The new standard is:

```text
Tranter Engine = website intelligence, publishing support, global CTA logic, analytics and reusable infrastructure.
HTML widgets/codebase = editable page, campaign and event body content.
Insights = advanced editorial content editor, not HTML-first.
Footer AI Assistant = frontend chat assistant connected to the Tranter knowledge base.
```

Campaigns are the reference implementation for codebase-driven landing experiences. The campaign feature already uses pasted HTML as the source of truth while Tranter Engine provides wrapping, publishing support and analytics. Pages and events should follow the same philosophy.

Insights are different. Insights should remain an advanced text/editorial module for articles, thought leadership and content marketing.

The AI Assistant should not be treated as a separate admin content builder at this stage. Tranter already has a footer chat assistant direction, and that assistant should connect to the Tranter knowledge base to answer visitor questions, guide users to services, and support lead generation.

## Scope moving forward

Campaigns, events and website pages should support a full HTML/widget-code workflow.

Insights should support an advanced text editor workflow.

The AI Assistant should be a frontend footer/chat experience connected to approved knowledge base content, not a replacement for the Page Templates, Events, Campaigns or Insights workflows.

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

Events should be flexible, not HTML-only.

Event pages should support non-technical inputs for:

- Featured image.
- Event title.
- Short description.
- Full description.
- Date and time.
- Venue/location.
- Registration URL.
- Speakers or hosts.
- Event status.

Events should also support optional pasted HTML/widget code for developer-built event landing pages, including:

- Event hero.
- Registration CTA.
- Agenda.
- Speakers.
- Partners/sponsors.
- Venue or virtual access.
- Resources.
- Post-event recap.
- Scripts and embeds.

Tranter Engine should provide event publishing support, structured event inputs, optional HTML override, wrapper settings and analytics.

### Insights

Insights should not be HTML-first.

Insights should use an advanced text editor/editorial workflow for:

- Article title.
- Rich text body.
- Featured image.
- Excerpt.
- Categories and tags.
- Related service.
- Author/editorial metadata.
- SEO fields.
- Featured insight toggle.
- Newsletter CTA.
- Book a Demo CTA.
- WhatsApp CTA.
- Recommended resources.

Insights should support analytics, but the content input should remain editorial instead of pasted HTML code.

### Footer AI Assistant

The AI Assistant should live as a frontend/footer chat assistant and connect to the Tranter knowledge base.

Its role should be to:

- Answer visitor questions using approved Tranter knowledge base content.
- Guide visitors to the right service, campaign, event, insight or contact path.
- Support lead generation by suggesting Book a Demo, WhatsApp or registration actions.
- Use analytics to track common questions, service interest and conversion intent.
- Avoid becoming a separate admin page builder or content editing workflow.

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

For Insights, the editor UI should insert CTAs automatically rather than requiring the writer to paste HTML manually.

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

Insight CTAs should be generated by the Insights editor/module with analytics attributes such as:

```html
<a href="#" data-tdp-open data-te-event="book_demo" data-te-source="insight" data-te-topic="cybersecurity">Book a Demo</a>
```

### AI Assistant CTA

The footer AI Assistant should be able to recommend tracked actions such as:

```html
<a href="#" data-tdp-open data-te-event="book_demo" data-te-source="ai_assistant" data-te-service="it_support">Book a Demo</a>
```

## Analytics value

This structure should help the sales and marketing team understand:

- Which pages generate demo intent.
- Which services attract the most interest.
- Which campaigns generate clicks and conversions.
- Which events drive registration and post-event leads.
- Which insights support lead generation.
- Which AI Assistant questions indicate service demand.
- Which CTAs perform best.
- Which sections or pages need improvement.

## Proposed lean backend UI

Tranter Engine should be cleaned into the following core areas:

```text
Tranter Hub
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
- AI Assistant question trends.
- Recent lead-intent activity.

### Website Engine

Handle global infrastructure:

- Header/footer logic.
- Footer AI Assistant connected to the knowledge base.
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

- View template.
- Copy HTML button.
- Required images.
- Required links.
- Tracking notes.
- Version notes.

### Campaigns

Keep the existing campaign-style full HTML logic and refine analytics.

### Events

Use a hybrid event workflow:

- Standard event inputs for non-technical staff.
- Optional full HTML/widget code for developer-built event pages.
- Event metadata.
- Featured image.
- Date, time and location.
- Registration URL.
- Market visibility.
- Wrapper option.
- Auto-generated event page where useful.
- Registration and CTA analytics.

### Insights

Keep insights as an advanced editorial content marketing engine, not an HTML-first builder.

Insights should focus on:

- Rich text editing.
- Featured image.
- Categories/tags.
- Related service mapping.
- CTA selection.
- Newsletter lead generation.
- SEO support.
- Insight performance analytics.

### Leads & Analytics

Central reporting for:

- Page views.
- CTA clicks.
- Book a Demo opens.
- WhatsApp clicks.
- Campaign conversions.
- Event registrations/clicks.
- Insight CTA performance.
- AI Assistant question trends.
- Service interest.

## Implementation order

1. Clean backend UI.
2. Standardize global Book a Demo and tracking attributes.
3. Add editable Page Templates area.
4. Refine campaign analytics using the same tracking standard.
5. Build Events as a hybrid structured input + optional HTML engine.
6. Upgrade Insights with an advanced editorial editor and CTA controls.
7. Connect the footer AI Assistant to the Tranter knowledge base.
8. Build Leads & Analytics dashboard.

## Rule moving forward

Do not build new public page bodies as rigid PHP shortcode renderers unless there is a strong reason.

Preferred pattern for Pages and Campaigns:

```text
Editable HTML/widget code + Tranter Engine global logic + analytics tracking.
```

Preferred pattern for Events:

```text
Structured event inputs + optional HTML/widget code + registration analytics.
```

Preferred pattern for Insights:

```text
Advanced text editor + content metadata + automated CTA/analytics controls.
```

Preferred pattern for AI Assistant:

```text
Footer chat assistant + approved knowledge base + tracked lead actions.
```
