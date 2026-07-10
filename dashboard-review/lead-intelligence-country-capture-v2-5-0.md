# Tranter Hub v2.5.0 Review — Lead Intelligence and Country Attribution

This review update builds on `tranter-hub-v2.4.0-knowledge-corpus-review.zip`.

## Changes

### Country attribution
- Added visitor-country aggregation to Leads & Analytics.
- Country is detected from trusted server/CDN headers when available:
  - `CF-IPCountry`
  - `CloudFront-Viewer-Country`
  - `X-Country-Code`
  - `GEOIP_COUNTRY_CODE`
- Added a `Visitor countries` dashboard panel.
- Lead signals now carry the detected country.

### Lead contact capture
- On voluntary form submission, the tracker looks for common fields for:
  - name
  - email
  - phone
  - company/organisation
- Available contact details are attached to the lead signal and displayed for sales follow-up.
- Anonymous visitors cannot be identified by email unless they submit a form or explicitly provide their details.

### Analytics layout fix
- Fixed overflowing rows in `Pages being interacted with`.
- Added constrained panel overflow and controlled horizontal scrolling for long tables.
- Prevented child rows from extending outside the parent panel.

## Operational note

For country data to populate accurately, enable Cloudflare IP Geolocation or ensure the web host supplies a supported GeoIP country header. Without that, the dashboard will show `Unknown`.

## Review ZIP

`tranter-hub-v2.5.0-lead-intelligence-review.zip`

## QA

- Updated PHP files passed syntax validation.
- ZIP archive passed integrity validation.
