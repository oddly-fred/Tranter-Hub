# Tranter Hub v2.0.0 Review — Campaign + Insight Forms

This review update builds on `tranter-hub-v1.9.0-events-form-review.zip` and adds the same guided backend UI logic to Campaigns and Insights.

## Main goal

Campaigns and Insights should no longer rely on the default WordPress editor for non-technical staff. They now follow the same Tranter Hub form pattern introduced for Events.

## New guided forms

```text
Tranter Hub > Add Campaign
Tranter Hub > Add Insight
```

## Default editor bypass

The plugin now redirects default WordPress editor routes:

```text
post-new.php?post_type=tranter_campaign
post.php?post={campaign_id}&action=edit
post-new.php?post_type=tranter_insight
post.php?post={insight_id}&action=edit
```

to:

```text
admin.php?page=tranter-engine-campaign-form
admin.php?page=tranter-engine-campaign-form&campaign_id={campaign_id}
admin.php?page=tranter-engine-insight-form
admin.php?page=tranter-engine-insight-form&insight_id={insight_id}
```

A fallback link remains in each form using:

```text
te_default_editor=1
```

## Campaign form fields

- Campaign title
- Short summary
- Internal campaign brief
- Campaign type
- Campaign status
- Market visibility
- Primary goal
- Target audience
- Campaign offer
- Primary CTA label
- Primary CTA URL
- Hero/social image URL with media picker
- Expiry date
- Layout wrapper
- Campaign HTML/embed code
- Tracking on/off
- UTM campaign
- Lead owner
- Lead source
- Generated campaign shortcode

## Campaign actions

- Publish campaign
- Save draft
- Open generated campaign page
- Preview campaign
- Duplicate campaign
- Delete campaign
- Open default WordPress editor fallback

## Insight form fields

- Insight title
- Short excerpt
- Insight body
- Insight type
- Publishing status
- Market visibility
- Related service
- Featured image URL with media picker
- SEO title
- SEO description
- CTA label
- CTA URL
- Key takeaways
- Author/owner
- Read time
- Featured insight toggle
- Newsletter queue toggle

## Insight actions

- Publish insight
- Save draft
- Preview insight
- Duplicate insight
- Delete insight
- Open default WordPress editor fallback

## Existing frontend logic preserved

- Existing campaign HTML rendering remains available through `[te_campaign]`.
- Campaign tracking logic remains available.
- Generated campaign page sync is preserved in the custom campaign form.
- Existing Insights frontend/shortcode logic remains available.
- Events custom form remains unchanged.

## Review ZIP

Generated in ChatGPT as:

```text
tranter-hub-v2.0.0-content-forms-review.zip
```

## QA

- PHP syntax check completed across all plugin PHP files.
- ZIP archive validation completed successfully.
