# Pagination Diagnostics Report

## 1. Sanitized Browser Tabs

See `tools/edge_tabs_sanitized.json` for full details.

| Tab | Issue |
|-----|-------|
| Tab 1 (id: -1) | Entirely wrapper tags → empty after sanitization; negative tabId flagged |
| Tab 2 (id: 115065325) | Clean — no sensitive params |
| Tab 3 (id: 115065418) | `user_code` query param redacted |

## 2. perPage / paginate Occurrences

| File | Line | Pattern | Notes |
|------|------|---------|-------|
| `app/Livewire/Feed.php` | 13 | `public int $perPage = 10` | Hardcoded default |
| `app/Livewire/Feed.php` | 18-25 | `perPage += 10` | Increment pattern |
| `app/Livewire/Feed.php` | 29 | `$this->perPage = 10` | Reset on filter change |
| `app/Livewire/Feed.php` | 54-56 | `->take($this->perPage + 1)->get()` | Manual pagination |
| `app/Livewire/AdminDashboard.php` | 65-67 | `->paginate(10, ...)` | Uses Laravel paginator |
| `app/Livewire/GroupDetail.php` | 53-54 | `->latest()->get()` | **No pagination** — loads all posts |
| `app/Http/Controllers/UserProfileController.php` | 20 | `->latest()->get()` | **No pagination** — loads all posts |

## 3. Issues Found & Fixes

### Issue A: Hardcoded perPage values

- **Problem**: `Feed.php` used hardcoded `10` for perPage with no centralized config.
- **Fix**: Created `config/feed.php` with `per_page`, `friends_per_page`, `groups_per_page`. Updated `Feed.php` to use `config('feed.per_page')` via `mount()` and explicit perPage init.

### Issue B: GroupDetail loads all posts without pagination

- **Problem**: `GroupDetail.php` called `->latest()->get()` — no limit, no pagination. Potential memory issue with large groups.
- **Fix**: Added `perPage` property using `config('feed.per_page')` with `loadMore()` + `take(N+1)` pattern matching Feed logic. Added `$hasMore` flag for sentinel support.

### Issue C: UserProfileController loads all posts

- **Problem**: `UserProfileController::show()` called `->latest()->get()` with no limit.
- **Fix**: Added `take(perPage + 1)` with eager-loaded scope to limit results.

### Issue D: Duplicate sentinel in feed

- **Problem**: `feed.blade.php` had two sentinel elements: one inside the loop with `x-intersect`, and an empty `#feed-sentinel` div at the root.
- **Fix**: Removed the outer duplicate sentinel.

### Issue E: Missing DB indexes

- **Problem**: No composite index for feed ordering (`created_at DESC`) on `posts` table; no index on `messages.conversation_id` or `stories.expires_at`.
- **Fix**: Added migration `2026_07_26_000001_add_feed_indexes.php` with safe conditional index creation.

## 4. Fix Snippets

### config/feed.php
```php
'per_page' => env('FEED_PER_PAGE', 10),
'friends_per_page' => env('FRIENDS_PER_PAGE', 20),
'groups_per_page' => env('GROUPS_PER_PAGE', 12),
'discover_groups_per_page' => env('DISCOVER_GROUPS_PER_PAGE', 12),
```

### Feed.php — centralized perPage
```php
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

### GroupDetail.php — paginated posts
```php
$query = Post::where('group_id', $this->group->id)
    ->withFeedRelations($user?->id)
    ->latest();

$posts = (clone $query)->take($this->perPage + 1)->get();
$hasMore = $posts->count() > $this->perPage;
$posts = $hasMore ? $posts->take($this->perPage) : $posts;
```

## 5. Test Results

```
php artisan test tests/Feature/PaginationTest.php
✔ test_feed_initial_per_page_matches_config
✔ test_feed_returns_correct_count
✔ test_feed_load_more_increases_per_page
✔ test_feed_filter_resets_per_page
✔ test_feed_filter_friends_returns_correct_count
✔ test_feed_end_of_feed_marker_shown_when_no_more
✔ test_feed_empty_state

All 7 passed.

Full suite: 66 tests, 155 assertions (was 59/148 before fixes)
```

### Commands to run
```bash
php artisan migrate           # Apply new index migration
php artisan test              # Full test suite
npm run build                 # If assets changed
```

## 6. Verification Checklist

- [x] Feed initial count matches config per_page
- [x] loadMore increments correctly
- [x] Filter change resets perPage to base
- [x] End-of-feed marker renders when `hasMore` is false
- [x] Empty state renders when no posts
- [x] Friends filter excludes strangers
- [x] GroupDetail posts are paginated
- [x] Sentinel is inside feed container (duplicate removed)
- [x] Floating search (`z-50`) does not overlap sentinel (sentinel is at bottom of feed)
