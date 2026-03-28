---
phase: 1
slug: wordpress-plugin-foundation
status: draft
nyquist_compliant: false
wave_0_complete: false
created: 2026-03-28
---

# Phase 1 — Validation Strategy

> Per-phase validation contract for feedback sampling during execution.

---

## Test Infrastructure

| Property | Value |
|----------|-------|
| **Framework** | Manual only — no automated test framework (no CLI server access; deploy is zip + WP admin upload) |
| **Config file** | none — manual verification via browser + WP admin |
| **Quick run command** | Visit site in browser, check admin dashboard |
| **Full suite command** | Full manual checklist (see Per-Task Verification Map below) |
| **Estimated runtime** | ~10 minutes per full pass |

---

## Sampling Rate

- **After every task commit:** Open WP admin, confirm no PHP errors, confirm feature visible
- **After every plan wave:** Run full manual checklist below
- **Before `/gsd:verify-work`:** All manual checks must pass
- **Max feedback latency:** ~10 minutes (manual browser check)

---

## Per-Task Verification Map

| Task ID | Plan | Wave | Requirement | Test Type | Automated Command | File Exists | Status |
|---------|------|------|-------------|-----------|-------------------|-------------|--------|
| 1-01-01 | 01 | 1 | INFRA-01 | manual | Visit `/city/test-city/` → 200 not 404 | ✅ | ⬜ pending |
| 1-01-02 | 01 | 1 | INFRA-02 | manual | WP admin → "24Hr Pharmacy Tools" settings page visible | ✅ | ⬜ pending |
| 1-01-03 | 01 | 1 | INFRA-03 | manual | View source on city post → valid JSON-LD in `<head>` | ✅ | ⬜ pending |
| 1-01-04 | 01 | 1 | INFRA-04 | manual | `[medical_disclaimer]` shortcode renders text on test page | ✅ | ⬜ pending |
| 1-01-05 | 01 | 1 | INFRA-05 | manual | `[affiliate_disclosure]` shortcode renders text on test page | ✅ | ⬜ pending |

*Status: ⬜ pending · ✅ green · ❌ red · ⚠️ flaky*

---

## Wave 0 Requirements

No automated test framework to install. This phase is PHP-only deployed to a shared Hostinger server — no CLI access. All verification is manual via browser and WP admin.

*Existing infrastructure covers all phase requirements — manual verification only.*

---

## Manual-Only Verifications

| Behavior | Requirement | Why Manual | Test Instructions |
|----------|-------------|------------|-------------------|
| CPT City URL returns 200 | INFRA-01 | No server CLI; can't curl from local | Visit `/city/new-york/` in browser after activating plugin, confirm 200 not 404 |
| Admin settings page exists | INFRA-02 | WP admin only | WP Admin → Settings → "24Hr Pharmacy Tools" or "Pharmacy Tools" menu item appears |
| JSON-LD in page head | INFRA-03 | Browser/view-source | View source on any city post, search for `application/ld+json`, validate via Google Rich Results Test |
| Medical disclaimer shortcode | INFRA-04 | Live site render | Add `[medical_disclaimer]` to a test page, confirm disclaimer text visible on frontend |
| Affiliate disclosure shortcode | INFRA-05 | Live site render | Add `[affiliate_disclosure]` to a test page, confirm disclosure text visible on frontend |
| wp_localize_script passes config | INFRA-05 | Browser DevTools | Open DevTools console on page with widget shortcode, confirm `pharmacyTools` JS object present |
| No PHP errors after activation | All | WP debug log | Enable WP_DEBUG, activate plugin, confirm no errors in debug.log |

---

## Validation Sign-Off

- [ ] All tasks verified manually in browser post-deploy
- [ ] WP_DEBUG enabled during testing, no errors in debug.log
- [ ] JSON-LD validated via Google Rich Results Test
- [ ] CPT rewrite rules flushed on activation (tested by deactivate/reactivate cycle)
- [ ] Settings page saves and retrieves option values correctly
- [ ] Shortcodes return HTML strings (not echo) — output appears inline with content
- [ ] `nyquist_compliant: true` set in frontmatter when all checks pass

**Approval:** pending
