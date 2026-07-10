# Tranter Hub Knowledge Center — Research & Structure Roadmap v1

## Objective

Build a structured, searchable, sales-supporting Knowledge Center using publicly available official vendor resources as the starting point.

The Knowledge Center should not simply copy vendor websites. It should convert official product information into concise, original Tranter IT summaries, practical use cases, downloadable-resource references, FAQs, related services and conversion CTAs.

## Initial source policy

Use these source priorities:

1. Official Tranter IT content
2. Official Zoho product pages and documentation
3. Official ManageEngine product pages and documentation
4. Official Sophos product pages and documentation
5. Official product brochures, datasheets, help centres and release notes
6. Public case studies and webinars from the vendors

Do not republish full vendor documents or copy long passages. Store source links and produce original summaries for Tranter Hub.

## Recommended Knowledge Center taxonomy

### Resource types

- Product Overview
- Product Guide
- Datasheet Link
- FAQ
- How-to Guide
- Comparison
- Case Study
- Video
- Webinar
- Event Resource
- White Paper
- Checklist
- Troubleshooting Guide

### Product families

#### Zoho

- Zoho CRM
- Zoho Books
- Zoho Desk
- Zoho Workplace
- Zoho People
- Zoho Projects
- Zoho Analytics
- Zoho Campaigns
- Zoho One

#### ManageEngine

- Endpoint Central
- ADManager Plus
- ADAudit Plus
- Log360
- ServiceDesk Plus
- Applications Manager
- OpManager
- PAM360
- Password Manager Pro
- AssetExplorer

#### Sophos

- Sophos Firewall
- Sophos Endpoint
- Sophos MDR
- Sophos Email
- Sophos Mobile
- Sophos ZTNA

#### Tranter solutions

- IT Support Services
- Smart Solutions
- HR Support Services
- Digital Marketing & Brand
- Business Process Outsourcing
- Website Development & Optimisation
- Cybersecurity

### Industry categories

- Government
- Financial Services
- Healthcare
- Education
- Oil & Gas
- Manufacturing
- Retail
- SMEs
- Professional Services

## Standard resource schema

Each Knowledge Center resource should support:

- Title
- Resource type
- Product family
- Product
- Industry
- Audience
- Short summary
- Main body
- Key benefits
- Key features
- Best-fit use cases
- Common challenges solved
- FAQs
- Related products
- Related services
- Official source URL
- Datasheet URL
- Video URL
- CTA label
- CTA URL
- Market visibility
- SEO title
- SEO description
- Last reviewed date
- Review owner
- AI-indexable toggle

## Initial research batch

Start with these products because they align most closely with current Tranter pages and sales priorities:

1. Zoho CRM
2. Zoho Books
3. Zoho Desk
4. Zoho Workplace
5. ManageEngine Endpoint Central
6. ManageEngine ADManager Plus
7. ManageEngine ADAudit Plus
8. ManageEngine Log360
9. Sophos Firewall
10. Sophos Endpoint

For each product, create:

- One concise product overview
- Five FAQs
- Three best-fit use cases
- One implementation guide outline
- One official datasheet/resource link record
- Related Tranter services
- Book-a-demo CTA

## Backend roadmap

Recommended admin structure:

```text
Tranter Hub
├── Knowledge Center
│   ├── All Resources
│   ├── Add Resource
│   ├── Categories
│   ├── Products
│   ├── Industries
│   ├── FAQs
│   ├── Downloads
│   ├── Videos
│   ├── Search Analytics
│   └── Settings
```

Recommended post type:

```text
tranter_knowledge
```

Recommended shortcode:

```text
[tranter_hub_knowledge_center]
```

Recommended REST routes:

```text
/wp-json/tranter-hub/v1/knowledge
/wp-json/tranter-hub/v1/knowledge/{slug}
/wp-json/tranter-hub/v1/knowledge-search
/wp-json/tranter-hub/v1/knowledge-categories
/wp-json/tranter-hub/v1/knowledge-products
/wp-json/tranter-hub/v1/knowledge-downloads
/wp-json/tranter-hub/v1/knowledge-videos
/wp-json/tranter-hub/v1/knowledge-search-event
```

## Search and analytics roadmap

Track:

- Search terms
- No-result searches
- Resource views
- Datasheet downloads
- Video plays
- CTA clicks
- Demo clicks
- Related-resource clicks
- Product interest
- Industry interest
- Entry page
- Exit page

## Implementation phases

### Phase 1 — Structure

- Register Knowledge Center content model
- Add custom non-technical resource form
- Add categories, products and industries
- Add resource listing and search UI

### Phase 2 — Research import

- Research official vendor resources
- Create initial 10 product records
- Add FAQs, source links and CTAs

### Phase 3 — Sales enablement

- Add related products and services
- Add demo and contact CTAs
- Connect analytics and lead scoring

### Phase 4 — AI readiness

- Add AI-indexable content flag
- Add structured content export endpoint
- Add semantic-search-ready fields
- Add source attribution and last-reviewed controls

## Editorial rules

- Write original Tranter IT summaries.
- Do not copy vendor wording extensively.
- Link to official vendor documents rather than redistributing restricted files.
- Mark pricing, availability and feature information with a review date.
- Keep product summaries short, practical and sales-oriented.
- Always include a Tranter implementation/support angle.
