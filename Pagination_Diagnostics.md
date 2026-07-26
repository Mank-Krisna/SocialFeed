# Pagination Diagnostics Report

## 1. Sanitized Browser Tabs

See `tools/edge_tabs_sanitized.json` for full details.
See `EdgeTabs_Fix_Report.md` for raw input, sample objects, and verification checklist.

| Tab | Issue |
|-----|-------|
| Tab 1 (id: -1) | Entirely wrapper tags → empty after sanitization; negative tabId flagged |
| Tab 2 (id: 115065325) | Clean — no sensitive params |
| Tab 3 (id: 115065418) | `user_code` query param redacted |

## 2. Static Scan — perPage / paginate Occurrences

File: `tools/paginate_occurrences.txt`

| File | Line | Pattern | Status |
|------|------|---------|--------|
| `app/Livewire/Feed.php` | 17,24,29,35,59-61 | `config('feed.per_page')` take(N+1) | ✅ Centralized |
| `app/Livewire/GroupDetail.php` | 19,46,51,74-76 | `config('feed.per_page')` take(N+1) | ✅ Centralized |
| `app/Livewire/AdminDashboard.php` | 65 | `->paginate($perPage, ...)` | ✅ Centralized |
| `app/Livewire/AdminDashboard.php` | 67 | `->paginate($perPage, ...)` | ✅ Centralized |
| `config/feed.php` | 12-15 | `per_page`, options, friends/groups/discover | ✅ Done |
| `resources/js/feed-sentinel.js` | all | Sentinel + throttle + fallback | ✅ Done |
| `resources/views/livewire/feed.blade.php` | 60-82 | Sentinel + fallback button | ✅ Done |

### resetPage / queryString

| File | Line | Status |
|------|------|--------|
| `app/Livewire/AdminDashboard.php` | 35,23 | ✅ Has `$queryString = ['tab']` + `resetPage()` on tab switch |
| `app/Livewire/Feed.php` | — | ✅ Added `$queryString = ['filter']`, `$listeners = ['feed:loadMore' => 'loadMore']` |
| `app/Livewire/GroupDetail.php` | — | ✅ Added `$listeners = ['feed:loadMore' => 'loadMore']` |

### Sentinel

| File | Line | Description |
|------|------|-------------|
| `resources/views/livewire/feed.blade.php` | 60-82 | Sentinel `#feed-sentinel` inside feed container |
| `resources/js/feed-sentinel.js` | all | IntersectionObserver + scroll fallback + `showFallbackButton()` |
| `resources/views/layouts/app.blade.php` | 27 | `header-sentinel` (for floating search — unrelated) |

## 3. Issues Found & Fixes

### Issue A: Hardcoded perPage values
- **Problem**: `Feed.php` used hardcoded `10` for perPage with no centralized config.
- **Fix**: Created `config/feed.php` with `per_page`, `per_page_options`, `friends_per_page`, `groups_per_page`, `discover_groups_per_page`. Updated `Feed.php`, `GroupDetail.php`, `AdminDashboard.php` to use `config('feed.per_page')`.

### Issue B: GroupDetail loads all posts without pagination
- **Problem**: `GroupDetail.php` called `->latest()->get()` — no limit.
- **Fix**: Added `perPage` + `loadMore()` + `take(N+1)` pattern matching Feed logic. Added `$hasMore` flag.

### Issue C: UserProfileController loads all posts
- **Problem**: `->latest()->get()` with no limit.
- **Fix**: Added `take(perPage + 1)` with eager-loaded scope.

### Issue D: Duplicate sentinel in feed
- **Problem**: `feed.blade.php` had two sentinels.
- **Fix**: Removed outer duplicate. Kept one inside feed container with `id="feed-sentinel"`.

### Issue E: Missing JS sentinel observer
- **Problem**: No client-side sentinel for browsers without Alpine `x-intersect` support.
- **Fix**: Created `resources/js/feed-sentinel.js` with IntersectionObserver (400ms throttle, 300px margin), scroll fallback if IO unavailable, exposed `showFallbackButton()`.

### Issue F: AdminDashboard hardcoded paginate(10)
- **Problem**: `AdminDashboard.php` used `->paginate(10, ...)` ignoring config.
- **Fix**: Extracted `$perPage = (int) config('feed.per_page', 10)` and passed to both paginate calls.

### Issue G: Missing protected $queryString on Feed
- **Problem**: Feed filter state not persisted in URL query string.
- **Fix**: Added `protected $queryString = ['filter']` to Feed.php.

### Issue H: Missing migration indexes
- **Problem**: No composite index for feed ordering; no index on `messages.conversation_id` or `stories.expires_at`.
- **Fix**: Created migration `2026_07_26_000001_add_feed_indexes.php` with safe conditional index creation (MySQL + SQLite). Note: `posts.user_id` and `posts.group_id` already indexed via FK `constrained()` in MySQL — no extra migration needed.

## 4. Fix Snippets

### config/feed.php
```php
return [
    'per_page' => env('FEED_PER_PAGE', 10),
    'per_page_options' => [10, 20, 50],
    'friends_per_page' => env('FRIENDS_PER_PAGE', 20),
    'groups_per_page' => env('GROUPS_PER_PAGE', 12),
    'discover_groups_per_page' => env('DISCOVER_GROUPS_PER_PAGE', 12),
];
```

### Feed.php — centralized perPage + queryString
```php
protected $queryString = ['filter'];
protected $listeners = ['feed:loadMore' => 'loadMore'];

public function mount(): void
{
    $this->perPage = (int) config('feed.per_page', 10);
}

public function loadMore(): void
{
    $this->perPage += (int) config('feed.per_page', 10);
}

public function setFilter(string $filter): void
{
    $this->filter = $filter;
    $this->perPage = (int) config('feed.per_page', 10);
}
```

### feed-sentinel.js
```js
// IntersectionObserver + scroll fallback + throttle + showFallbackButton()
// Dispatches window.dispatchEvent(new CustomEvent('feed:loadMore'))
```

### feed.blade.php — sentinel
```html
<div id="feed-sentinel"
    x-intersect="$wire.loadMore()"
    class="flex justify-center pt-2 pb-4">
    ...
</div>
```

## 5. Migration Plan

### Existing migration
`database/migrations/2026_07_26_000001_add_feed_indexes.php`
- Adds `posts(created_at, id)` composite index
- Adds `messages(conversation_id, created_at)` index
- Adds `stories(expires_at)` index

### Runbook
```bash
php artisan migrate
```
- Small to medium tables (<1M rows) — no downtime needed.
- For large tables (>10M rows), use MySQL `pt-online-schema-change` or Percona Toolkit: `pt-osc --alter "ADD INDEX posts_created_at_id_index (created_at, id)" D=database,t=posts`

### Index coverage
- `posts.user_id` — indexed via FK `constrained()` (MySQL auto-index)
- `posts.group_id` — indexed via FK `constrained()`
- Remaining feed query indexes: covered by existing migration

## 6. Test Results

File: `tests/Feature/PaginationTest.php`

| Test | Status |
|------|--------|
| test_feed_initial_per_page_matches_config | ✅ |
| test_feed_returns_correct_count | ✅ |
| test_feed_load_more_increases_per_page | ✅ |
| test_feed_filter_resets_per_page | ✅ |
| test_feed_filter_friends_returns_correct_count | ✅ |
| test_feed_end_of_feed_marker_shown_when_no_more | ✅ |
| test_feed_empty_state | ✅ |

Total: 7 tests.

### Commands to run
```bash
php artisan migrate              # Apply index migration
php artisan test                 # Full test suite
npm run build                    # Build JS/CSS assets
```

## 7. Verification Checklist

- [x] `tools/edge_tabs_sanitized.json` valid JSON, exactly one `isCurrent=true`, sensitive params redacted
- [x] `tools/paginate_occurrences.txt` — all paginate/perPage/sentinel occurrences scanned
- [x] `tools/pagination_runtime_report.json` — runtime checks for feed, friends, group, profile endpoints
- [x] `config/feed.php` — centralized perPage with options
- [x] Feed initial count matches config per_page
- [x] loadMore increments correctly
- [x] Filter change resets perPage to base
- [x] End-of-feed marker renders when `hasMore` is false
- [x] Empty state renders when no posts
- [x] Friends filter excludes strangers
- [x] GroupDetail posts are paginated
- [x] Sentinel `#feed-sentinel` inside feed container with IntersectionObserver
- [x] JS fallback: scroll listener + `showFallbackButton()` if IO unavailable
- [x] `feed:loadMore` event dispatched on window, Livewire listens via `$listeners`
- [x] `protected $queryString = ['filter']` on Feed
- [x] `AdminDashboard` uses `config('feed.per_page')` instead of hardcoded `10`
- [x] DB indexes migration created and runnable
- [x] `PaginationTest.php` — 7 tests covering core pagination behavior
- [x] Floating search (`z-50`) does not overlap sentinel
