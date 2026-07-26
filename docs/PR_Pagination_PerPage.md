# PR: Centralize Feed Pagination + Fixes

**Branch:** `fix/pagination-perpage` → `master`

## Changes

### Config & Livewire
- `config/feed.php` — centralized `per_page`, `per_page_options`, friends/groups/discover per page
- `app/Livewire/Feed.php` — added `$queryString = ['filter']`, `$listeners = ['feed:loadMore' => 'loadMore']`, uses `config('feed.per_page')`
- `app/Livewire/GroupDetail.php` — added `$listeners`, changed from unbounded `->get()` to `take(N+1)` pagination
- `app/Livewire/AdminDashboard.php` — replaced hardcoded `paginate(10)` with `config('feed.per_page')`
- `app/Http/Controllers/UserProfileController.php` — added `take(perPage+1)` limit

### Infinite Scroll & Sentinel
- `resources/js/feed-sentinel.js` — IntersectionObserver (400ms throttle, 300px margin) + scroll fallback + `showFallbackButton()`, dispatches `feed:loadMore`
- `resources/views/livewire/feed.blade.php` — added `id="feed-sentinel"` to sentinel, kept duplicate removal

### Tools & Reports
- `tools/edge_tabs_sanitized.json` — sanitized browser tabs (wrapper tags stripped, sensitive params redacted)
- `tools/paginate_occurrences.txt` — static scan results (20 paginate, 1 resetPage, 11 sentinel)
- `tools/paginate_priorities.txt` — P0-P2 ranked fix list
- `tools/pagination_runtime_report.json` — runtime endpoint verification
- `EdgeTabs_Fix_Report.md` — full sanitization report
- `Pagination_Diagnostics.md` — comprehensive diagnostics + QA checklist

### Database
- `database/migrations/2026_07_26_000001_add_feed_indexes.php` — composite index on `posts(created_at, id)`, `messages(conversation_id, created_at)`, `stories(expires_at)`

### Tests
- `tests/Feature/PaginationTest.php` — 7 tests covering initial perPage, loadMore, filter reset, friends filter, end-of-feed, empty state

## Verification
- Build: ✅ `npm run build` passed (29 modules)
- Tests: ✅ 66/66 passed, 164 assertions
- PaginationTest: ✅ 7/7 passed

## Checklist
- [x] Centralized perPage config
- [x] Livewire components use config, have queryString/listeners
- [x] Sentinel inside feed container with IntersectionObserver + fallback
- [x] DB indexes migration runnable
- [x] Tools/reports committed
- [x] Full test suite green
