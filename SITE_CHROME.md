# Tranter Site Chrome

Tranter Hub now includes lightweight global header/footer shortcodes based on the supplied approved HTML snippets.

## Shortcodes

```text
[te_site_header]
[te_site_footer]
```

Aliases:

```text
[te_header]
[te_footer]
```

## Intended use

Use these on normal WordPress or Elementor Canvas pages:

```text
[te_site_header]
[te_home_hero]
...
[te_site_footer]
```

## GeoIP behaviour

The renderer calls `Tranter_Market::current()`. For non-Nigeria/global visitors, Nigeria/event-specific links are removed by default.

## Campaigns

Campaigns are intentionally not placed in the menu. Campaign pages remain direct landing pages and are accessed through campaign URLs, ads, emails, or targeted CTAs.
