# Changelog

All notable changes to SocialFeed will be documented in this file.

## [1.0.1] — 2026-07-26

### Fixed
- Auth double-hashing: RegisterController no longer calls Hash::make() (model 'hashed' cast handles it)
- Bookmarks page: query Post directly via whereHas instead of SavedPost relationship
- Hashtag pages: column is `name` not `tag`
- Nav search overlap: fixed to fixed width w-64 lg:w-72
- Nav positioning: removed flex-1 from left section
- Mobile drawer: replaced hardcoded colors with CSS variables
- Mobile bottom nav: added pb-20 md:pb-4 padding
- Messenger page: converted to Livewire component wrapper
- Parse error: added @auth wrapper for mobile drawer and bottom nav

### Added
- NotificationsDropdown Livewire component (compact popover in nav)
- messages.blade.php wrapper for Livewire messenger component
- Session report: docs/Session_Report_2026-07-26.md

### Changed
- Notifications: desktop uses dropdown popover instead of full page
- UserFactory, DatabaseSeeder, LargeDataSetSeeder: plain text passwords (model cast handles hashing)

### Removed
- resources/resources/ (nested duplicate)
- stitch_ai_logo_generator.zip + folder
- stitch_socialfeed_branding_design/ (logo already copied)
- screen.png, DESIGN.md, Pagination_Diagnostics.md, skills-lock.json
- tools/ (debug/archive files)
- .phpunit.result.cache, public/hot, public/logo.png (duplicate)
- app/Notifications/ (WelcomeNotification, WeeklyDigestNotification - never dispatched)
- app/Events/ (FriendRequestSent, FriendRequestAccepted, CommentPosted - never dispatched)
- resources/views/dashboard.blade.php (unused)
- resources/js/search-float.js (never referenced)

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
