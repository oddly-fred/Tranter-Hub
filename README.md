# Tranter Engine

Sales-focused shortcode content engine for the Tranter IT website.

## Homepage shortcodes

[te_home_hero]
[te_who_we_are]
[te_services]
[te_global_delivery]
[te_industries]
[te_how_we_work]
[te_why_tranter]
[te_metrics]
[te_insights]
[te_clients]
[te_faq]
[te_cta]

## v1.1.0 updates
- Smoother section gradient blending at section joins.
- Conversion CTA headline updated away from "Consultation CTA".
- Backend section editor added under Tranter Engine > Website Sections.
- Section fields: pill/title, H2 headline, paragraph, market visibility.
- Existing shortcode-by-section structure preserved.


## v1.2.1 sprint update

Built on top of the approved Tranter Engine v1.2.0 foundation.

- Preserves Mission Control dashboard and Website Workspace.
- Adds Newsletter lead capture shortcode: [te_newsletter].
- Adds Subscriber storage and admin workspace.
- Adds Resources/download module shortcode: [te_resources].
- Adds Resource download tracking and gated-resource metadata fields.
- Keeps existing sections, services, partners, CTAs, market rules and homepage shortcode flow intact.


## v1.2.2 hotfix

- Fixed Add New Insight admin URL returning `Invalid post type` on some WordPress installs.
- Registers Tranter custom post types at priority 0 on `init` with a safety-net registration path.
- Keeps the approved Mission Control / Website Workspace experience intact.

## v1.3.0 Knowledge Hub Sprint

This release builds the Knowledge Hub directly on top of the approved Tranter Engine foundation.

### New public shortcodes

- `[te_knowledge_hub]` - full Knowledge Hub page with hero, search, tag filters, featured insight, latest insights, featured resource, newsletter and CTA.
- `[te_latest_insights limit="3"]` - compact latest insights grid for landing pages.
- `[te_newsletter]` - newsletter lead capture form.
- `[te_resources]` - resource/download grid.

### Automatic page

On activation, the plugin creates `/knowledge-hub/` with `[te_knowledge_hub]` if the page does not already exist.

### Insight enhancements

Each Tranter Insight now supports Knowledge Hub settings:

- Industry
- Solution
- CTA Type
- Related Resource
- Newsletter toggle
- Homepage priority
- SEO Title
- Meta Description

### Frontend improvements

- Dynamic homepage Knowledge Hub section now pulls from Tranter Insights.
- Single Insight pages render article meta, related resource, newsletter, related insights and final CTA.
- Why Tranter card paragraph spacing has been normalized.


## v1.3.2
- Polished Insights page typography to match the homepage scale.
- Reduced oversized hero and CTA headings.
- Improved paragraph sizing, line-height, card text hierarchy, and mobile-first responsiveness.

## v1.3.3 - Premium Form Controls Hotfix
- Fixes dropdown option visibility in the Insights newsletter form.
- Replaces generic browser-blue focus styling with Tranter green focus states.
- Applies reusable input/select/textarea/button focus logic for current and future frontend modules.


## v1.4.0 Campaigns Engine

- Added Campaign custom post type.
- Added Campaigns admin workspace.
- Added full HTML/widget embed support.
- Added campaign shortcodes.
- Added visit, click and conversion tracking.
- Added UTM capture foundation.
- Added premium mobile-first campaign frontend styles.

See `CAMPAIGNS.md` for usage.


## v1.4.1 Campaign simplification

- Campaigns now use a full HTML-first workflow.
- CTA label/URL fields are no longer required for HTML campaigns.
- Campaign admin fields are grouped into spacious premium cards.
- Landing pages are automatically generated on campaign save/update.
- Automatic tracking captures views, clicks, form submits, WhatsApp, phone, email and outbound links.
- Optional Tranter header/footer wrappers can be selected per campaign.


## v1.4.2 Campaign Builder UI
- Refreshed Add/Edit Campaign screen with lightweight premium card layout.
- Campaign settings are grouped into Overview, Layout, Tracking and Landing Page cards.
- Full-width Campaign Content editor for pasted HTML/widget code.
- CTA URL/label fields remain removed; campaign HTML owns its own CTA and flow.
- Automatic tracking and auto landing page generation retained.


## v1.4.4

- Campaign Engine production baseline.
- Generated campaign pages are marked and synced automatically.
- Full HTML mode keeps theme header/footer/title out of the visitor experience.
- Campaign page metadata is prepared for Elementor Canvas fallback while the frontend is still rendered by Tranter Hub.
- Added next sprint plan for About / Who We Are page shortcodes.


## v1.4.5 - Global Header & Footer Chrome

Adds lightweight site chrome shortcodes based on the supplied Tranter header and footer HTML.

Shortcodes:

- `[te_site_header]` or `[te_header]`
- `[te_site_footer]` or `[te_footer]`

Notes:

- Campaigns are not added to the site menu. Campaigns remain standalone/direct landing pages.
- GeoIP market logic is respected through `Tranter_Market::current()`.
- Global visitors do not see Nigeria/event-specific links by default.
- The supplied header demo popup and footer chat widget are preserved.

Recommended page structure:

```text
[te_site_header]
[page section shortcodes]
[te_site_footer]
```

## v1.4.6
- Consolidated Website guidance.
- Added Page Guides and Shortcodes Reference in backend.
- Renamed Website Sections menu to Section Manager.
- Added global header/footer shortcodes to recommended page structures.


## v1.5.2 Who We Are design correction

- Added Company / Who We Are module.
- Added `[te_about]` shortcode using the approved supplied frontend template.
- Recommended page stack: `[te_site_header]`, `[te_about]`, `[te_site_footer]`.
- Preserves the extensive v1.4.6 plugin base.


## v1.5.2 update
- Refined Why Tranter paragraph typography for better readability and visual balance.


## v1.5.3 Who We Are Why Tranter typography match
- Matched the Who We Are Why Tranter section typography to the homepage Why Tranter section.
- Standardized heading size, paragraph size, line-height, weight, color and spacing.
- Preserved the approved Who We Are layout and shortcode stack.
