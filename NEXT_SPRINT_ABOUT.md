# Next Sprint: v1.5.0 — About / Who We Are Page

The Campaign Engine is now stable enough to serve as a production campaign foundation. The next sprint should complete the public website foundation by adding shortcode-driven About page sections.

## Objective

Build a polished, mobile-first Who We Are page using the same design language as the homepage and Insights page.

## Proposed shortcodes

```text
[te_about_hero]
[te_company_story]
[te_mission_vision]
[te_core_values]
[te_leadership]
[te_about_cta]
```

## Backend requirements

The first version should remain simple and lightweight:

- Use Tranter Hub content/sections where possible.
- Avoid building a page builder.
- Provide reusable shortcodes that editors can place on normal WordPress pages.
- Keep the styling consistent with existing homepage and Insights components.

## Content areas

### About Hero
- Badge
- Heading
- Intro paragraph
- Primary CTA
- Optional image/background

### Company Story
- Main narrative
- Supporting highlight card
- Optional metric badges

### Mission & Vision
- Mission card
- Vision card

### Core Values
- 4 to 6 value cards

### Leadership
- Leadership/management cards
- Name, role, image, short bio

### About CTA
- Closing conversion section
- CTA buttons

## Design standard

- Mobile-first
- Consistent typography with homepage
- Same card radius, shadows and deep-green/red gradient language
- No Elementor dependency beyond page composition
