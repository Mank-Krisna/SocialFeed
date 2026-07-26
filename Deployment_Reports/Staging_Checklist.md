# Staging Checklist — SocialFeed V4

## Verification Results

| # | Task | Status | Notes |
|---|------|--------|-------|
| 1 | **Cache & Build Assets** | ✅ | `npm run build` success (CSS 55KB, JS 96KB). `php artisan optimize:clear` — config, cache, views, routes cleared. |
| 2 | **Storage & Uploads** | ✅ | Symlink exists at `public/storage` → `storage/app/public`. Dirs: avatars, covers, posts (stories created on first upload). |
| 3 | **Environment Variables** | ✅ | `APP_URL=http://127.0.0.1:9000`. `APP_ENV=staging`. `APP_DEBUG=false`. `LOG_LEVEL=warning`. Pusher vars commented — activate on deploy. `BROADCAST_CONNECTION=log`. |
| 4 | **Database Migration + Seeder** | ✅ | `php artisan migrate:fresh --seed` — 21 migrations, no errors. `php artisan db:seed --class=LargeDataSetSeeder` — 206 users, 557 posts, 304 comments, 53 stories, 3855 likes, 26 polls, 140 bookmarks, 32 conversations, 124 messages. All in 10s. |
| 5 | **Logs & Error Check** | ✅ | Log cleared. 3 bugs found & fixed (see Error_Report.md). No active errors. |
| 6 | **PHPUnit Tests** | ✅ | 46/46 pass, 131 assertions. Regression tested with full dataset. |
| 7 | **UX — Empty States** | ✅ | Feed empty state: icon + illustration card + CTA button. All filters covered. End-of-feed marker. |
| 8 | **UX — Infinite Scroll** | ✅ | `x-intersect` directive on sentinel element. Auto-loads next page. Fallback "Muat Lainnya" button visible. SVG spinner during load. |
| 9 | **UX — Toast Notifications** | ✅ | 8 components dispatch `notify` event consistently: BookmarkButton, RepostButton, PostItem (delete), Messenger, AdminDashboard (delete user/post), StorySection (upload). Auto-dismiss 3.5s. Support success/error/info. |
| 10 | **Performance** | ✅ | Feed: 8 queries optimized (N+1 prevented). Pagination: `perPage+1` trick. 10s seed time for 500+ posts. |

---

## Pre-Production Launch Checklist

### Environment
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Set `LOG_LEVEL=error`
- [ ] Configure `APP_URL` with production domain
- [ ] Set `SESSION_DRIVER` → `redis` or `database`
- [ ] Set `CACHE_STORE` → `redis` or `database`

### Pusher Realtime
- [ ] Fill `PUSHER_APP_ID`, `PUSHER_APP_KEY`, `PUSHER_APP_SECRET` in `.env`
- [ ] Uncomment `VITE_PUSHER_APP_KEY` / `VITE_PUSHER_APP_CLUSTER`
- [ ] Set `BROADCAST_CONNECTION=pusher`
- [ ] Set `QUEUE_CONNECTION` → `database` or `redis`

### Database
- [ ] Switch from SQLite to MySQL/PostgreSQL
- [ ] Run `php artisan migrate:fresh --seed`
- [ ] Add indexes: `posts.parent_id`, `messages.conversation_id`, `stories.expires_at`

### Caching
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Run `php artisan optimize`

### Storage
- [ ] Verify `php artisan storage:link`
- [ ] Configure CDN for media files (Cloudflare R2 / S3)

### Monitoring
- [ ] Install Sentry: `composer require sentry/sentry-laravel`
- [ ] Set `SENTRY_LARAVEL_DSN` in `.env`
- [ ] Verify `php artisan schedule:run` for story cleanup

---

## Sign-Off

| Role | Name | Date | Signature |
|------|------|------|-----------|
| QA Lead | — | — | — |
| DevOps | — | — | — |
| PM | — | — | — |
