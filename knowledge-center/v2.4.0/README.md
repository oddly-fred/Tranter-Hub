# Tranter Hub v2.4.0 — AI Knowledge Corpus

This review release adds an AI-first knowledge corpus and backend input structure. It is not a public page builder.

## What changed

- Renamed the backend module to **Knowledge Corpus**.
- Added a guided non-technical **Add Knowledge Record** form.
- Added record types for company wiki, services, products, industries, FAQs, comparisons, objections, glossary, policies, events and case studies.
- Added governance fields: version, review date, review owner, source confidence, AI confidence, change log and AI-indexable flag.
- Added starter Tranter, Zoho, ManageEngine and Sophos knowledge records.
- Added Markdown and JSON corpus files.
- Added an AI retrieval endpoint:

```text
/wp-json/tranter-hub/v1/knowledge-search?q=endpoint+management
```

- Retrieval ranking considers source confidence, freshness, AI confidence and query relevance.
- Starter records seed on activation only when no published knowledge records already exist.

## Backend input structure

Each knowledge record supports:

- Title
- Concise summary
- Detailed knowledge
- Content type
- Product family
- Product
- Audience
- Industries
- Market
- Problems solved
- Benefits
- Features
- Use cases
- FAQs
- Related products
- Related Tranter services
- Source confidence
- Official source URL
- Review owner
- Last reviewed date
- Version
- AI confidence
- Change log
- CTA
- AI-indexable status

## Review ZIP

```text
tranter-hub-v2.4.0-knowledge-corpus-review.zip
```

## QA

- All PHP files passed syntax validation.
- Corpus JSON passed validation.
- ZIP archive passed integrity validation.
