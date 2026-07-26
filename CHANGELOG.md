# Changelog

All notable changes to SocialFeed will be documented in this file.

## [1.0.0] — 2026-07-26

### Added
- Feed with infinite scroll, pagination, per-page config
- Post creation with media upload (images/video)
- Polls (create + vote)
- Repost functionality
- Comments system with replies
- Groups (public/private) with admin/member roles
- Friends system (send/accept/reject requests)
- Real-time messaging with ConversationController
- Notifications center with mark-all-read
- Hashtag explore page
- Bookmarks (save posts for later)
- User settings (profile, privacy, password, account deletion)
- Admin dashboard with report management (resolve/dismiss)
- Polymorphic report system
- Search with Laravel Scout
- PWA manifest + service worker
- SEO meta tags + Open Graph + Twitter Card
- Dark mode toggle
- Micro-interactions (card hover, button press, avatar hover)
- Responsive navigation (desktop, mobile drawer, bottom nav)
- Unread badge counts (messages + notifications)
- Rate limiting on all write endpoints
- Form request validation (7 request classes)
- Authorization policies (Post, Group, Comment, Admin)
- Broadcasting events (PostLiked, CommentPosted, FriendRequest, MessageSent)

### Security
- Admin routes protected with `can:admin` policy
- All file uploads validated (mime type + size)
- XSS protection via `e()` escaping in body_html
- CSRF protection active on all forms
- Upload size limits enforced

### Performance
- N+1 queries eliminated in Feed, Bookmarks, Messenger
- Eager loading with `withFeedRelations()` scope
- Config + route + view caching
- Redis queue + cache support

### Infrastructure
- Dockerfile (PHP 8.3-FPM + Nginx + Node)
- docker-compose.yml (app + Redis + Meilisearch)
- GitHub Actions CI/CD workflow
- deploy.sh VPS deployment script

### Testing
- 88 PHPUnit tests (Feature)
- 4 Playwright E2E tests
- Broadcasting, Policy, Report, Search tests
