# Phase 2: City Page Template + Compliance Pages - Discussion Log

> **Audit trail only.** Do not use as input to planning, research, or execution agents.
> Decisions are captured in CONTEXT.md — this log preserves the alternatives considered.

**Date:** 2026-03-29
**Phase:** 02-city-page-template-compliance-pages
**Areas discussed:** City page layout, Homepage hero, Affiliate CTA style, Compliance depth

---

## City Page Layout

| Option | Description | Selected |
|--------|-------------|----------|
| Pharmacy finder widget | Hero H1 + pharmacy finder widget front and center — tool first, content second | ✓ |
| Content intro first | H1 + 2-3 sentence city intro paragraph, then widget below | |
| Search + stats combo | H1 + quick pharmacy count/stats bar + finder widget | |

**User's choice:** Pharmacy finder widget (Recommended)
**Notes:** Widget placeholder renders immediately; real widget swaps in Phase 3.

| Option | Description | Selected |
|--------|-------------|----------|
| Content → Discount → CTAs → Disclaimer | Natural reading flow, 500+ word content block first | ✓ |
| CTAs → Content → Discount → Disclaimer | Capitalize on intent early with CTAs after widget | |
| Content → CTAs interleaved → Disclaimer | Weave CTAs between H2 sections | |

**User's choice:** Content → Discount → CTAs → Disclaimer
**Notes:** Natural reading flow selected.

| Option | Description | Selected |
|--------|-------------|----------|
| Full-width, no sidebar | Single column, stacked sections, simpler on mobile | ✓ |
| Sidebar on desktop, collapses on mobile | Two-column with ad zone + discount card in sidebar | |
| You decide | Claude picks based on Kadence grid + mobile patterns | |

**User's choice:** Full-width, no sidebar
**Notes:** 70%+ traffic is mobile — single column simplest.

| Option | Description | Selected |
|--------|-------------|----------|
| Header + 2 in-content + footer | Header zone above H1, one after widget, one after content, footer at bottom | |
| Header + 1 in-content + footer | Minimal — less intrusive | |
| You decide | Claude places ad zones for Mediavine best practices | ✓ |

**User's choice:** You decide (Claude's discretion)
**Notes:** Claude will optimize ad zone placement.

---

## Homepage Hero

| Option | Description | Selected |
|--------|-------------|----------|
| Search entry point | Big H1, search/city input, CTA button. Gets visitors to city page fast | ✓ |
| City directory grid | Grid of top city cards with pharmacy counts | |
| Content hub | Featured articles + city links, magazine feel | |

**User's choice:** Search entry point (Recommended)

| Option | Description | Selected |
|--------|-------------|----------|
| Popular cities + how it works | City cards → 3-step How It Works → featured articles | ✓ |
| City directory only | Full city grid by state/region | |
| You decide | Claude structures for SEO + conversion | |

**User's choice:** Popular cities + how it works (Recommended)

| Option | Description | Selected |
|--------|-------------|----------|
| Simple city redirect | Text input with autocomplete → redirects to /city/{slug}/ | ✓ |
| Full pharmacy finder embed | Embed React widget on homepage | |

**User's choice:** Simple city redirect (Recommended)
**Notes:** Lightweight, no API calls on homepage.

| Option | Description | Selected |
|--------|-------------|----------|
| Simple city list with counts | Alphabetical city links with pharmacy counts | ✓ |
| Map + city cards | State map with pins + city cards | |
| You decide | Claude picks pre-widget approach | |

**User's choice:** Simple city list with counts (Recommended)
**Notes:** archive-state.php — clean, fast, SEO-friendly.

| Option | Description | Selected |
|--------|-------------|----------|
| Basic info card + nearby cities | Name, address, hours, phone, map placeholder, nearby city links | ✓ |
| Rich detail page | Full detail with hours table, services, reviews placeholder | |
| You decide | Claude builds what makes sense pre-Phase 4 | |

**User's choice:** Basic info card + nearby cities (Recommended)
**Notes:** single-pharmacy.php — minimal until real data in Phase 4.

---

## Affiliate CTA Style

| Option | Description | Selected |
|--------|-------------|----------|
| Card blocks | Styled card with icon, short copy, button linking to /go/ URLs. 2-3 stacked | ✓ |
| Inline text links | Woven into content paragraphs, subtler | |
| Button bar | Horizontal row of branded buttons | |

**User's choice:** Card blocks (Recommended)

| Option | Description | Selected |
|--------|-------------|----------|
| One CTA section after content | Single group of 2-3 cards between content and disclaimer | ✓ |
| Two spots: after widget + after content | More aggressive, higher conversion | |
| You decide | Claude places for best conversion | |

**User's choice:** One CTA section after content (Recommended)
**Notes:** Clean, not pushy. FTC disclosure sits right above.

---

## Compliance Depth

| Option | Description | Selected |
|--------|-------------|----------|
| Detailed and specific | 500-800 words each, site-specific (GA4, Clarity, CookieYes, affiliate cookies) | ✓ |
| Standard boilerplate | Generator + templates, faster but generic | |
| Minimal placeholders | Brief real content, expand later | |

**User's choice:** Detailed and specific (Recommended)
**Notes:** YMYL — Google scrutinizes thin legal pages.

| Option | Description | Selected |
|--------|-------------|----------|
| Brand-only | About 24HourPharmacy.com as a resource, no personal name | ✓ |
| Named founder | Mention by name for E-E-A-T | |
| Hybrid | Brand-first with "Founded by" line | |

**User's choice:** Brand-only (Recommended)
**Notes:** Easier to scale, sell, or bring on contributors.

| Option | Description | Selected |
|--------|-------------|----------|
| WordPress pages | Content in database, editable via WP admin. Text written as repo files | ✓ |
| Theme templates | Hardcode in PHP files, version-controlled | |
| You decide | Claude picks for deployment workflow | |

**User's choice:** WordPress pages (Recommended)
**Notes:** Content written as text files in repo, pasted into WP admin on deploy.

---

## Claude's Discretion

- Ad zone placement on city pages
- Heading hierarchy within content sections
- Homepage city autocomplete implementation
- Pharmacy detail page section ordering
- CSS styling for affiliate CTA cards

## Deferred Ideas

None — discussion stayed within phase scope.
