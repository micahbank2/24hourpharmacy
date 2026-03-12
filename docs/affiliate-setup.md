# Affiliate Program Setup Guide

This document covers every affiliate and monetization program for 24hourpharmacy.com, in priority order. Apply to these as soon as the site has enough content to be approved.

**Important:** Never hardcode affiliate tracking codes in theme or plugin files. All affiliate IDs are stored in the WordPress options table via the plugin settings page (`class-settings.php`). Code references them via `get_option()`.

---

## 1. CJ Affiliate (Commission Junction)

**Signup:** https://signup.cj.com
**Why first:** CJ is the gateway to the highest-value pharmacy and health advertisers.

### Programs to apply for inside CJ:

| Advertiser | Commission | Type | Notes |
|---|---|---|---|
| Walgreens | 2% per sale | Revenue share | Largest 24hr pharmacy chain. Apply immediately. |
| Rite Aid | 5.6% per sale | Revenue share | Strong conversion on pharmacy products. |
| CVS | Varies | Revenue share | Apply once approved on CJ platform. |
| eHealthInsurance | $10-$75 | CPA (per lead/signup) | Health insurance leads. High value. |

### Setup steps:
1. Create a CJ publisher account at signup.cj.com
2. Add 24hourpharmacy.com as a website property
3. Wait for platform approval (1-3 business days)
4. Search for and apply to each advertiser listed above
5. Once approved, grab your deep link URLs from the CJ dashboard
6. Store tracking parameters in WordPress options (never in code)
7. Use the `[affiliate_link program="walgreens"]` shortcode to insert links

---

## 2. LowerMyRx

**Signup:** https://affiliate.lowermyrx.com
**Commission:** Up to $4 per filled prescription
**Why high priority:** Per-prescription payouts add up fast on a pharmacy site.

### Setup steps:
1. Apply at affiliate.lowermyrx.com
2. Get your unique affiliate ID and card branding assets
3. Store affiliate ID in WordPress options
4. Embed their discount card widget or build a custom one using their API
5. Place on every city page and the savings hub page

---

## 3. National Drug Card

**Signup:** https://ndcaffiliate.com
**Commission:** $2-$2.50 per filled prescription
**Why:** Secondary discount card program. Stack with LowerMyRx for A/B testing.

### Setup steps:
1. Apply at ndcaffiliate.com
2. Get affiliate ID and card assets
3. Store affiliate ID in WordPress options
4. Can run alongside LowerMyRx — test which converts better per city/page

---

## 4. Impact (impact.com)

**Signup:** https://impact.com (apply as a publisher/partner)
**Why:** Access to telehealth programs (GoodRx, Hims, Hers, Ro, etc.)

### Programs to apply for inside Impact:

| Advertiser | Commission | Type |
|---|---|---|
| Telehealth platforms | Varies | CPA per signup |
| Health & wellness brands | Varies | Revenue share / CPA |

### Setup steps:
1. Create an Impact publisher account
2. Add 24hourpharmacy.com as a property
3. Browse and apply to telehealth and health advertisers
4. Store tracking links in WordPress options
5. Use on relevant pages (city pages with telehealth mentions, savings hub)

---

## 5. Google AdSense

**Signup:** https://adsense.google.com
**Requirement:** Apply after 10+ pages of quality content are live
**Why:** Baseline display ad revenue while building to premium ad network thresholds.

### Setup steps:
1. Ensure at least 10 quality pages are published (500+ words each)
2. Apply at adsense.google.com
3. Add the AdSense verification snippet to the site `<head>` via functions.php
4. Once approved, use auto ads initially
5. Store the `ADSENSE_PUB_ID` in `.env` and reference via WordPress options
6. Place ads in the pre-built ad zones: `.ad-zone-header`, `.ad-zone-sidebar`, `.ad-zone-in-content`, `.ad-zone-footer`
7. **Replace with Mediavine or Raptive once traffic thresholds are met**

---

## 6. Secondary Discount Card Programs

Apply to these after the primary programs are running. Use for A/B testing, geo-targeting, or as fallback options.

| Program | Signup | Commission |
|---|---|---|
| EzRx | Contact via website | Per filled prescription |
| SaveonMeds | Contact via website | Per filled prescription |
| RxGo | Contact via website | Per filled prescription |
| USARx | Contact via website | Per filled prescription |
| AffordableMeds | Contact via website | Per filled prescription |

### Strategy:
- Test different card programs on different city pages
- Track conversion rates per program per city
- Rotate to highest-converting program over time
- Store all affiliate IDs in WordPress options table

---

## 7. Premium Display Ad Networks

These replace Google AdSense once traffic thresholds are reached. Significantly higher RPMs.

### Mediavine
- **Requirement:** 50,000 sessions per month
- **Signup:** https://www.mediavine.com/apply
- **RPM:** $15-$30+ (vs $2-$8 for AdSense)
- Uses the same `.ad-zone-*` classes already built into the theme

### Raptive (formerly AdThrive)
- **Requirement:** 25,000 page views per month (lower threshold)
- **Signup:** https://www.raptive.com
- **RPM:** $15-$25+
- Alternative to Mediavine; apply to both, go with whoever approves first

### Migration path:
1. Start with AdSense at launch
2. Apply to Raptive at 25K monthly views
3. Apply to Mediavine at 50K monthly sessions
4. Remove AdSense code when premium network goes live

---

## FTC Compliance Requirements

**This is legally required.** Every page with affiliate links must have a clear disclosure.

### What the FTC requires:
- Disclosure must be **clear and conspicuous** — not buried in a footer
- Must appear **before or near** the first affiliate link on the page
- Must be in **plain language** a typical reader can understand
- Must disclose the **financial relationship** (that you earn money from recommendations)

### Implementation:

1. **Site-wide disclosure banner** — Add a short disclosure at the top of every page with affiliate content:
   > "This site may earn a commission when you use our links to pharmacies and savings programs. This doesn't affect our recommendations or the prices you pay."

2. **Per-page disclosure** — Include near the top of any page with affiliate links:
   > "Disclosure: 24hourpharmacy.com participates in affiliate programs. We may earn a commission when you fill a prescription or make a purchase through links on this page, at no additional cost to you."

3. **Dedicated disclosure page** — Create a `/disclosure` page with full details about all affiliate relationships. Link to it from the footer on every page.

4. **Savings/discount card pages** — Must clearly state:
   > "The discount cards featured on this page are provided by our affiliate partners. We may earn a commission when you use these cards to fill a prescription."

### Technical implementation:
- The disclosure is rendered by `class-shortcodes.php` via the `[affiliate_disclosure]` shortcode
- Every template that may contain affiliate links includes this shortcode
- The disclosure text is stored in WordPress options so it can be updated without code changes
- CSS class `.affiliate-disclosure` for consistent styling

### Do NOT:
- Hide disclosures in expandable/collapsible sections
- Use vague language like "sponsored" without explaining the financial relationship
- Place disclosures only in the footer where users may never see them
- Forget disclosures on pages generated programmatically (city pages)
