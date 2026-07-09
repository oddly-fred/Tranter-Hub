# Tranter Hub Header v3.0 Plugin

This WordPress plugin exposes the Tranter Hub Header v3.0 as an Elementor-friendly shortcode.

## Shortcode

```text
[tranter_hub_header]
```

Optional region override for testing:

```text
[tranter_hub_header region="ng"]
[tranter_hub_header region="global"]
[tranter_hub_header region="auto"]
```

Optional tracking configuration:

```text
[tranter_hub_header region="auto" geo_endpoint="/wp-json/tranter/v1/region" webhook="https://example.com/webhook" analytics="true"]
```

## Latest correction

The header now stays fixed at the top throughout page scrolling, while preserving the current UI styling. A dynamic spacer is included so page content does not hide underneath the fixed header.

## Latest transition correction

Header motion has been refined so the fixed top header transitions more smoothly during scroll and hover. The update improves easing, spacer movement, shell rounding, shadow, logo sizing and height changes without changing the approved visual design.

## Latest GeoIP correction

The header now mirrors the working Contact Us page GeoIP flow exactly: `?region=ng/nigeria/global/us` override first, then a fresh `https://ipapi.co/json/` lookup using `cache: no-store`. When `country_code` is `NG`, the Nigeria experience is shown. Every other country is Global. Lookup failure falls back directly to Global. Stale localStorage region caching has been removed from the live detection path.

## Latest routing correction

Header links have been updated for the Tranter Hub routing map:

- Who We Are -> `/who-we-are/`
- What We Do -> `/what-we-do/`
- View all services -> `/what-we-do/`
- Zoho Solutions -> `/zoho-solutions/`
- ManageEngine -> `/manage-engine/`
- Speak with our team -> `/contact/`
- Insights -> `/insights/`
- Contact -> `/contact/`
- Partners menu now uses a mega-menu style partner view with Zoho, ManageEngine and Sophos, plus a Zoho solution subgroup for CRM, Books, Desk and Workplace.

## Features

- GeoIP-aware Nigeria/Global navigation.
- Nigeria experience: all services and partners.
- Global experience: Zoho CRM, Desk, Books and Workplace only.
- Fixed top header with the current shrink-on-scroll/hover styling preserved.
- Smoother scroll/hover transition using improved easing and RAF-based scroll handling.
- Contact-page GeoIP logic lifted into the header engine.
- Updated Tranter Hub route mapping.
- Interactive mega menu, search panel, AI Assistant hook and mobile drawer.
- Analytics hooks for GA4/GTM, Microsoft Clarity, Meta Pixel, LinkedIn Insight Tag and custom webhook.

## Elementor usage

Add an Elementor Shortcode widget and paste:

```text
[tranter_hub_header]
```

## Notes

- The shortcode renders only once per page to avoid duplicate header IDs.
- Header HTML is stored in compressed chunks inside the installable preview ZIP.
- This is a review package. After approval, the same header can be refactored into separate production assets.
