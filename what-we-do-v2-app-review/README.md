# Tranter What We Do v2 App-like Review

This review package updates the What We Do page using the current Elementor/codebase upload as the base.

## Updates included

- Homepage-aligned app-like visual refinement layer.
- Uniform pill colour treatment across hero and inner section headers.
- Smoother transitions using the homepage-style easing pattern.
- Mobile-first typography and spacing retained and tightened.
- Smart Solutions card is hidden outside the Nigeria market.
- US/Global market also changes the 350+ metric wording to “Expert ICT & enterprise solutions engineers”.
- Smart Solutions grid layout reflows cleanly when hidden.
- CTA modal logic corrected to use `what_we_do` instead of `who_we_are`.
- Smart Solutions route changed from `/wp/smart-solutions/` to `/smart-solutions/`.

## Files in downloadable review ZIP

- `tranter-what-we-do-v2-app-like.html` — WordPress/Elementor HTML block review code.
- `elementor-what-we-do-v2-app-like.json` — Elementor import-style JSON based on the uploaded file.
- `README.md` — review notes.

## Market logic

The page reads market state from:

1. `data-tranter-market` on `html` or `body`
2. `tranter-market-global`, `tranter-market-us`, or `tranter-market-ng` class
3. `tranter_market` cookie

`ng` keeps Smart Solutions visible. `global`, `us`, and other non-NG markets hide Smart Solutions.
