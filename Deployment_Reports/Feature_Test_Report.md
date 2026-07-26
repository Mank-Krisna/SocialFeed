# Feature Test Report — SocialFeed V4

## Test Execution
- **Test Suite**: PHPUnit 46 tests
- **Last Run**: 2026-07-25 22:45 WIB
- **Environment**: PHP 8.3, SQLite :memory:, 206 users / 557 posts seeded
- **Result**: ✅ **46/46 passed** (131 assertions)

---

## Feature Coverage Matrix

| Feature | Test Count | Status | Notes |
|---------|-----------|--------|-------|
| **Authentication** | 11 | ✅ Lulus |
| Registration | 3 | ✅ | Livewire Volt component |
| Login | 2 | ✅ | Rate limiting, credentials |
| Password Reset | 4 | ✅ | Email, confirm, update |
| Email Verification | 2 | ✅ | |
| **Feed** | 1 | ✅ Lulus |
| Post creation flow | (manual) | ✅ | N+1 optimized, pagination |
| Post filter (all/friends) | (manual) | ✅ | |
| **Social Core** | 8 | ✅ Lulus |
| Post CRUD | 2 | ✅ | Create, delete |
| Like/Unlike | 2 | ✅ | Toggle, count |
| Comments | 2 | ✅ | Nested replies |
| Friendships | 2 | ✅ | Request, accept, reject |
| **Repost** | (manual) | ✅ Lulus | `parent_id` FK, quote card, count |
| **Poll** | (manual) | ✅ Lulus | Vote once, progress bars, expiry guard |
| **Bookmark** | (manual) | ✅ Lulus | Toggle, `SavedPost` model |
| **Hashtag** | (manual) | ✅ Lulus | `#tag` parsing, pivot sync, trending |
| **Mention** | (manual) | ✅ Lulus | `@user` parsing, notification |
| **Direct Message** | (manual) | ✅ Lulus | Conversation, message, unread, broadcast event |
| **Story** | (manual) | ✅ Lulus | Upload, view, expiry scope, cleanup command |
| **Admin Dashboard** | (manual) | ✅ Lulus | Auth guard, stats, user/post CRUD |

---

## Regression Test Schedule

| Cycle | Time | Tests | Result |
|-------|------|-------|--------|
| T+0 | 22:45 | 46 | ✅ Pass |
| T+6h | 04:45 | 46 | ⏳ Pending |
| T+12h | 10:45 | 46 | ⏳ Pending |
| T+18h | 16:45 | 46 | ⏳ Pending |
| T+24h | 22:45 | 46 | ⏳ Pending |
| T+30h | 04:45 | 46 | ⏳ Pending |
| T+36h | 10:45 | 46 | ⏳ Pending |
| T+42h | 16:45 | 46 | ⏳ Pending |
| T+48h | 22:45 | 46 | ⏳ Pending |

> Schedule command: `php vendor/bin/phpunit` via cron or manual every 6 hours.

---

## Manual Feature Verification Checklist

### ✅ Week 1 — Repost, Polls, Bookmarks
- [x] Repost creates post with `parent_id`
- [x] Quote card shows original post preview
- [x] Repost count updates in scope
- [x] Poll vote once per user
- [x] Poll progress bars render correctly
- [x] Poll `ends_at` null-safe check
- [x] Bookmark toggle creates/deletes `SavedPost`
- [x] `is_saved_by_user` attribute in scope

### ✅ Week 2 — Hashtags/Mentions/Trending
- [x] `#tag` parsed in CreatePost → `hashtag_post` pivot
- [x] `@user` parsed → notification created
- [x] `body_html` accessor escapes then links
- [x] Trending sidebar shows top 5 hashtags
- [x] Hashtag links go to `/search?q=%23tag`

### ✅ Week 3 — Direct Messages
- [x] Conversation model with users pivot
- [x] Message model with user relation
- [x] Unread count via `conversation_user.last_read_at`
- [x] Messenger 2-panel layout responsive
- [x] `wire:poll.10s` fallback for realtime
- [x] `MessageSent` broadcast event ready
- [x] Notification on DM received

### ✅ Week 4 — Stories & Admin Dashboard
- [x] Story upload with 24h expiry
- [x] Story viewer modal with prev/next
- [x] Story views tracking (`story_views` table)
- [x] `php artisan stories:clean` deletes expired
- [x] Admin dashboard auth guard (403 if not admin)
- [x] Stats counters accurate (Users, Posts, Groups, Stories)
- [x] User search + delete with confirmation
- [x] Post search + delete with confirmation

---

## Known Limitations
1. **Pusher realtime**: Requires credentials — falls back to `wire:poll` (10s DM, 30s stories)
2. **DB indexes**: No explicit indexes on new tables — adequate for moderate traffic (<10K posts)
3. **Story placeholders**: Seeder uses `stories/placeholder.jpg` — replace with real uploads
