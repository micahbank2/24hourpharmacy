---
phase: 02
slug: city-page-template-compliance-pages
status: draft
nyquist_compliant: false
wave_0_complete: false
created: 2026-03-29
---

# Phase 02 — Validation Strategy

> Per-phase validation contract for feedback sampling during execution.

---

## Test Infrastructure

| Property | Value |
|----------|-------|
| **Framework** | Manual PHP validation (no unit test framework — WordPress templates verified via grep + file inspection) |
| **Config file** | none — PHP templates validated by acceptance criteria grep checks |
| **Quick run command** | `grep -c 'do_shortcode' wordpress/theme/single-city.php` |
| **Full suite command** | `bash -c 'for f in single-city.php front-page.php single-pharmacy.php archive-state.php; do echo "=== $f ==="; grep -cE "do_shortcode|ad-zone|get_header|get_footer" wordpress/theme/$f; done'` |
| **Estimated runtime** | ~2 seconds |

---

## Sampling Rate

- **After every task commit:** Run quick grep checks on modified template files
- **After every plan wave:** Run full suite command across all 4 templates
- **Before `/gsd:verify-work`:** Full suite must show all expected patterns present
- **Max feedback latency:** 2 seconds

---

## Per-Task Verification Map

| Task ID | Plan | Wave | Requirement | Test Type | Automated Command | File Exists | Status |
|---------|------|------|-------------|-----------|-------------------|-------------|--------|
| 02-01-01 | 01 | 1 | INFRA-07 | grep | `grep -c 'ad-zone' wordpress/theme/single-city.php` | ❌ W0 | ⬜ pending |
| 02-01-02 | 01 | 1 | INFRA-08 | grep | `grep -c 'do_shortcode' wordpress/theme/single-city.php` | ❌ W0 | ⬜ pending |
| 02-01-03 | 01 | 1 | INFRA-09 | file | `test -f wordpress/theme/front-page.php` | ❌ W0 | ⬜ pending |
| 02-01-04 | 01 | 1 | CONTENT-01 | file+wc | `wc -w docs/compliance/disclaimer.md` | ❌ W0 | ⬜ pending |

*Status: ⬜ pending · ✅ green · ❌ red · ⚠️ flaky*

---

## Wave 0 Requirements

- Existing infrastructure covers all phase requirements — PHP templates validated via grep pattern matching and file existence checks. No test framework needed.

---

## Manual-Only Verifications

| Behavior | Requirement | Why Manual | Test Instructions |
|----------|-------------|------------|-------------------|
| Templates render correctly in browser | INFRA-07 | Requires WordPress + Kadence active theme on Hostinger | Deploy zip, visit /city/test-city/, verify visual layout |
| Compliance pages accessible at correct URLs | CONTENT-01 | Requires WP admin page creation | Create pages in WP admin, verify 200 response |
| Disclaimer not double-firing | INFRA-08 | Requires live WordPress with content filter active | View page source, count disclaimer div occurrences |

---

## Validation Sign-Off

- [ ] All tasks have `<automated>` verify or Wave 0 dependencies
- [ ] Sampling continuity: no 3 consecutive tasks without automated verify
- [ ] Wave 0 covers all MISSING references
- [ ] No watch-mode flags
- [ ] Feedback latency < 2s
- [ ] `nyquist_compliant: true` set in frontmatter

**Approval:** pending
