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

## Features

- GeoIP-aware Nigeria/Global navigation.
- Nigeria experience: all services and partners.
- Global experience: Zoho CRM, Desk, Books and Workplace only.
- Fixed top header with the current shrink-on-scroll/hover styling preserved.
- Smoother scroll/hover transition using improved easing and RAF-based scroll handling.
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
