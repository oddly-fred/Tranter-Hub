# Tranter Engine Campaigns v1.4.2

Campaigns are intentionally simple: paste the full campaign HTML and let Tranter Hub handle wrapping, GeoIP visibility and analytics.

## Workflow

1. Go to Tranter Hub > Campaigns > Add Campaign.
2. Add campaign title and slug.
3. Choose market visibility: Nigeria, Global or Both.
4. Choose layout wrapper: Full HTML only, HTML + Tranter header, HTML + Tranter footer, or both.
5. Paste full campaign HTML/widget code.
6. Publish/update.

The plugin automatically creates a WordPress landing page containing:

```text
[te_campaign id="campaign-slug"]
```

## Tracking

Tracking is automatic for:

- Page views
- Link clicks
- Button clicks
- Form submits
- WhatsApp links
- Phone links
- Email links
- Outbound links
- UTM fields

Optional labels can be added to campaign HTML:

```html
<a href="https://example.com" data-te-label="Register Button">Register</a>
<button data-te-track="conversion" data-te-label="Lead Form Submit">Submit</button>
```

## Shortcode

```text
[te_campaign id="campaign-slug"]
```

Legacy modular campaign shortcodes remain available for compatibility, but the recommended flow is now full HTML campaigns.


## v1.4.2 Admin UI
Campaigns now use a cleaner lightweight Campaign Builder UI inside the WordPress edit screen. The HTML remains the source of truth; Tranter Hub only provides publishing, layout wrapper options and analytics tracking.

## v1.4.3 Campaign Rendering Hotfix

- Auto-generated campaign pages render the campaign directly instead of showing the default theme page.
- Single campaign previews now render campaign HTML directly.
- Full HTML only mode outputs pasted full HTML documents with automatic tracking injected.
- Campaign generated pages now use the numeric campaign ID in the shortcode for reliable rendering.
- Campaign editor styling now loads on campaign edit screens and the HTML editor is full width.
- Empty, unpublished, unavailable and region-hidden campaigns now show helpful error messages instead of blank output.

## v1.4.4 Campaign Production Baseline

This update stabilizes the Campaign Engine before moving to the next sprint.

### Final campaign behaviour

- Campaigns remain full-HTML first.
- Campaign HTML may include its own header, footer, CTA buttons, forms, scripts and embedded widgets.
- The campaign landing page is auto-generated/updated whenever the campaign is saved.
- Generated campaign pages are marked as Tranter Campaign Pages in WordPress.
- Full HTML mode renders through Tranter Hub directly, preventing the normal theme title/header/footer from appearing.
- Tracking remains automatic for visits, links, buttons, forms, WhatsApp, phone, email and outbound links.
- The shortcode remains available for manual use, but generated pages are the preferred workflow.

### Recommended workflow

1. Create a Campaign.
2. Paste the complete HTML/widget code.
3. Select Full HTML only unless a Tranter wrapper is required.
4. Publish/update.
5. Open the generated landing page URL.
6. Review views/clicks/conversions in the Campaign Tracking box.
