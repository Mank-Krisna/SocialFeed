# SocialFeed — Architecture Guide

Panduan lengkap struktur file, folder, dan alur kerja project SocialFeed.
Target: developer baru yang baru pertama kali melihat codebase ini.

---

## 1. Root Directory Overview

### File Root

| File | Fungsi | Kapan Dipakai | Catatan |
|------|--------|---------------|---------|
| `.env` | Konfigurasi environment lokal | Saat development | Jangan di-commit |
| `.env.example` | Template environment | Setup project baru | Commit ke repo |
| `.env.production.example` | Template production | Deploy ke server | Beda dengan .env.example |
| `composer.json` | Dependency PHP | `composer install` | Requires PHP ^8.3, Laravel ^13.8, Livewire ^3.6 |
| `package.json` | Dependency JS/Node | `npm install` | Vite, Tailwind, Alpine.js |
| `vite.config.js` | Konfigurasi build tool | `npm run dev` / `npm run build` | Entry: app.css + app.js |
| `tailwind.config.js` | Design system & tema | Build CSS | Custom palette #0058bc |
| `postcss.config.js` | CSS processing pipeline | Build CSS | Tailwind + Autoprefixer |
| `phpunit.xml` | Konfigurasi testing | `php artisan test` | SQLite in-memory |
| `artisan` | CLI Laravel | `php artisan ...` | Command line interface |
| `deploy.sh` | Script deploy VPS | Deployment manual | Jalankan setelah git pull |
| `Dockerfile` | Container image | `docker build` | PHP 8.3-FPM + Nginx |
| `docker-compose.yml` | Container orchestration | `docker-compose up` | App + Redis + Meilisearch |
| `AGENTS.md` | Aturan AI agents | Development | Livewire rules, upload rules |
| `CONTEXT.md` | Domain knowledge | Development | Glossary: Post, Group, dll |
| `CHANGELOG.md` | Riwayat perubahan | Release | Keep a Changelog format |
| `README.md` | Dokumentasi umum | Onboarding | Install guide |
| `.gitignore` | File diabaikan Git | Setiap commit | node_modules, vendor, .env |
| `.editorconfig` | Standar format file | Editing | Consistent indentation |
| `.npmrc` | Konfigurasi npm | `npm install` | Registry settings |
| `.phpunit.result.cache` | PHPUnit cache | Test runs | Auto-generated, don't commit |

### Folder Root

| Folder | Fungsi | Isi Utama |
|--------|--------|-----------|
| `app/` | Core aplikasi PHP | Controllers, Models, Livewire, Policies, Services |
| `bootstrap/` | Bootstrapping Laravel | app.php, providers.php, cache/ |
| `config/` | Konfigurasi Laravel | database.php, feed.php, livewire.php |
| `database/` | Database layer | Migrations, factories, seeders |
| `docker/` | Config Docker | nginx.conf, supervisord.conf |
| `docs/` | Dokumentasi project | ADR, reports, guides |
| `design/` | Desain assets | socialfigma figma exports, logo |
| `public/` | Document root web server | index.php, assets, uploads |
| `resources/` | Frontend assets | Views (Blade), CSS, JS |
| `routes/` | Routing definitions | web.php, api.php, channels.php |
| `storage/` | Writable storage | Logs, cache, sessions, uploads |
| `tests/` | Automated tests | Feature tests, Unit tests |
| `vendor/` | Dependency PHP | Auto-generated Composer |
| `node_modules/` | Dependency JS | Auto-generated npm |
| `.github/` | GitHub Actions CI/CD | workflows/deploy.yml |
| `.scratch/` | Issue tracker lokal | Markdown files |

---

## 2. App Directory Deep Dive (`app/`)

### `app/Http/Controllers/`

Controller klasik menangani request via route. Setiap method mengarah ke view atau response.

| Controller | Route | Fungsi |
|-----------|-------|--------|
| `BookmarksController` | `GET /bookmarks` | Tampilkan bookmarked posts |
| `ConversationController` | `GET /messages/{id}`, `POST /messages/{id}/send` | Detail & kirim pesan |
| `GroupController` | `GET /groups/{slug}` | Detail grup |
| `HashtagController` | `GET /explore`, `GET /tags/{tag}` | Explore & detail hashtag |
| `MessageController` | `POST /messages/start` | Mulai percakapan baru |
| `NotificationController` | `GET /notifications`, `POST /read-all`, `DELETE /{id}` | Notifikasi |
| `PostController` | `GET /posts/{id}` | Detail post |
| `SettingsController` | `GET/PUT/DELETE /settings` | Pengaturan akun |
| `UserProfileController` | `GET /profile/{user}` | Profil publik |
| `Auth/LoginController` | `GET/POST /login` | Login |
| `Auth/RegisterController` | `GET/POST /register` | Register |
| `Auth/VerifyEmailController` | `GET /verify-email` | Verifikasi email |

**Alur:** `Route → Controller → View/Response`

### `app/Livewire/`

Livewire 3 adalah reactive component yang re-render partial tanpa full page reload. Setiap component punya 1 PHP class + 1 Blade view.

| Component | Route/Trigger | View | Fungsi |
|-----------|--------------|------|--------|
| `Feed` | `GET /feed` (full-page) | `livewire/feed.blade.php` | Feed utama, infinite scroll |
| `CreatePost` | Inline di Feed | `livewire/create-post.blade.php` | Form posting baru |
| `PostItem` | Inline di Feed | `livewire/post-item.blade.php` | Satu post (like, comment, repost) |
| `CommentSection` | Inline di PostItem | `livewire/comment-section.blade.php` | Komentar & reply |
| `BookmarkButton` | Inline di PostItem | `livewire/bookmark-button.blade.php` | Toggle bookmark |
| `RepostButton` | Inline di PostItem | `livewire/repost-button.blade.php` | Toggle repost |
| `PollDisplay` | Inline di PostItem | `livewire/poll-display.blade.php` | Tampilkan & vote poll |
| `StorySection` | Inline di Feed | `livewire/story-section.blade.php` | Cerita (stories) |
| `SearchCenter` | `GET /search` (full-page) | `livewire/search-center.blade.php` | Pencarian |
| `FriendsManager` | `GET /friends` (full-page) | `livewire/friends-manager.blade.php` | Kelola teman |
| `GroupsManager` | `GET /groups` (full-page) | `livewire/groups-manager.blade.php` | Daftar grup |
| `GroupDetail` | `GET /groups/{slug}` | `livewire/group-detail.blade.php` | Detail grup |
| `Messenger` | `GET /messages` (full-page) | `livewire/messenger.blade.php` | Chat/messaging |
| `NotificationsCenter` | `GET /notifications` | `livewire/notifications-center.blade.php` | Halaman notifikasi |
| `NotificationsDropdown` | Inline di Navigation | `livewire/notifications-dropdown.blade.php` | Dropdown notifikasi |
| `AdminDashboard` | `GET /admin` (full-page) | `livewire/admin-dashboard.blade.php` | Panel admin |
| `SettingsPage` | `GET /settings` | `livewire/settings-page.blade.php` | Pengaturan |
| `Layout/Navigation` | Inline di layout | `livewire/layout/navigation.blade.php` | Navbar desktop + mobile |
| `Profile/UpdatePasswordForm` | Inline di profile | `livewire/profile/update-password-form.blade.php` | Update password |
| `Profile/UpdateProfileInformationForm` | Inline di profile | `livewire/profile/update-profile-information-form.blade.php` | Update profil |
| `Profile/DeleteUserForm` | Inline di profile | `livewire/profile/delete-user-form.blade.php` | Hapus akun |

**Alur Livewire:**
```
User klik/submit → Livewire wire:click/wire:submit
→ PHP method dipanggil → state berubah
→ View re-render → DOM updated (partial)
```

### `app/Models/`

| Model | Tabel | Relasi Utama | $fillable | Catatan |
|-------|-------|-------------|-----------|---------|
| `User` | `users` | posts, likes, comments, notifications, groups, conversations, stories, savedPosts | name, username, email, password, bio, avatar, cover_photo | `'password' => 'hashed'` cast |
| `Post` | `posts` | user, group, media, likes, comments, parent (repost), poll, hashtags, savedByUsers | user_id, group_id, parent_id, body | SoftDeletes, Searchable |
| `Comment` | `comments` | user, post, parent (nested) | user_id, post_id, parent_id, body | — |
| `Like` | `likes` | user, post | user_id, post_id | — |
| `Group` | `groups` | creator, members, posts | user_id, name, slug, description, photo, type | Searchable |
| `Friendship` | `friendships` | sender, receiver | sender_id, receiver_id, status | status: pending/accepted |
| `Conversation` | `conversations` | users, messages | type | Pivot: last_read_at |
| `Message` | `messages` | conversation, user | conversation_id, user_id, body | — |
| `Notification` | `notifications` | user, sender | user_id, sender_id, type, data, read_at | data: JSON |
| `Hashtag` | `hashtags` | posts (pivot) | name | Many-to-Many dengan Post |
| `SavedPost` | `saved_posts` | user, post | user_id, post_id | — |
| `Poll` | `polls` | post, options | post_id, question, ends_at | — |
| `PollOption` | `poll_options` | poll, votes | poll_id, label | — |
| `PollVote` | `poll_votes` | option | poll_option_id, user_id | — |
| `Story` | `stories` | user, views | user_id, media_path, caption, expires_at | — |
| `PostMedia` | `post_media` | post | post_id, path, type | — |
| `Report` | `reports` | reporter, resolver, reportable (morph) | user_id, reportable_type, reportable_id, reason, status | Polymorphic |

### `app/Policies/`

| Policy | Model | Method | Siapa Bisa |
|--------|-------|--------|-----------|
| `PostPolicy` | Post | view, update, delete | Owner post |
| `GroupPolicy` | Group | view, update, delete | Owner/admin grup |
| `CommentPolicy` | Comment | update, delete | Owner komentar |
| `AdminPolicy` | — | admin | User `is_admin = true` |

### `app/Services/`

| Service | Fungsi |
|---------|--------|
| `NotificationService` | Buat notifikasi (like, comment, friend, repost) |
| `UsernameGenerator` | Generate username unik dari nama user |

### `app/Events/`

| Event | Kapan Dispatch | Broadcast Ke |
|-------|---------------|-------------|
| `PostLiked` | User like post | Channel user post owner |
| `MessageSent` | User kirim pesan | Channel conversation |

### `app/Providers/`

| Provider | Fungsi |
|----------|--------|
| `AppServiceProvider` | Register services, boot logic (rate limiters, model observe) |

**Note:** Laravel 13 uses `bootstrap/app.php` for routing, middleware, and rate limiters — no separate RouteServiceProvider needed. `bootstrap/providers.php` lists all providers (currently only AppServiceProvider).

---

## 3. Config Directory (`config/`)

| File | Fungsi | Key Penting |
|------|--------|-------------|
| `app.php` | Konfigurasi aplikasi | name, env, debug, url |
| `auth.php` | Autentikasi | guards, providers |
| `broadcasting.php` | Broadcasting config | connections (pusher) |
| `cache.php` | Caching | stores (file/redis) |
| `database.php` | Database | connections (sqlite/mysql) |
| `feed.php` | **Custom config** | per_page, max_media_mb, rate limits |
| `filesystems.php` | File storage | disks (local/public/s3) |
| `livewire.php` | Livewire config | class_map, update_url |
| `logging.php` | Logging | channels (stack, daily) |
| `mail.php` | Mail config | drivers, from address |
| `queue.php` | Queue worker | connections (redis/sync) |
| `session.php` | Session | driver (file/redis), lifetime |
| `services.php` | Third-party | pusher, meilisearch |

**`config/feed.php`** — Custom config SocialFeed:
```php
'per_page' => env('FEED_PER_PAGE', 10),
'max_media_mb' => env('MAX_MEDIA_MB', 100),
'rate_post_per_minute' => env('RATE_POST_PER_MINUTE', 10),
```

---

## 4. Database Directory (`database/`)

### Migrations (23 files)

| Migration | Tabel | Kolom Penting |
|-----------|-------|--------------|
| `create_users_table` | users | name, username, email, password, bio, avatar, is_admin |
| `create_cache_table` | cache | key, value |
| `create_jobs_table` | jobs | queue, payload |
| `create_posts_table` | posts | user_id, body, parent_id, group_id, soft_deletes |
| `create_likes_table` | likes | user_id, post_id |
| `create_comments_table` | comments | user_id, post_id, parent_id, body |
| `create_post_images_table` | post_media | post_id, path, type |
| `create_friendships_table` | friendships | sender_id, receiver_id, status |
| `create_app_notifications_table` | notifications | user_id, sender_id, type, data, read_at |
| `align_post_media_and_notifications` | (alter tables) | — |
| `align_friendships_table` | (alter tables) | — |
| `add_soft_deletes_to_posts` | posts | deleted_at |
| `create_groups_table` | groups | user_id, name, slug, description, type |
| `create_group_user_table` | group_user | user_id, group_id, role |
| `add_group_id_to_posts` | posts | group_id |
| `add_repost_polls_bookmarks` | polls, poll_options, poll_votes, saved_posts | — |
| `create_hashtags_table` | hashtags, hashtag_post | name, pivot |
| `create_conversations_table` | conversations, conversation_user | type, last_read_at |
| `create_stories_table` | stories | user_id, media_path, caption, expires_at |
| `create_story_views_table` | story_views | user_id, story_id |
| `add_is_admin_to_users` | users | is_admin |
| `add_feed_indexes` | (indexes) | Performance indexes |
| `create_reports_table` | reports | Polymorphic reportable |

### Factories

| Factory | Model | Kegunaan |
|---------|-------|----------|
| `UserFactory` | User | Testing & seeding |
| `PostFactory` | Post | Testing & seeding |

### Seeders

| Seeder | Kegunaan | Command |
|--------|----------|---------|
| `DatabaseSeeder` | Seed 6 akun dummy + data lengkap | `php artisan db:seed` |
| `LargeDataSetSeeder` | Seed 200 users, 500 posts | `php artisan db:seed --class=LargeDataSetSeeder` |

---

## 5. Routes Directory (`routes/`)

### `routes/web.php` — Semua Route

| Method | URI | Target | Name | Middleware |
|--------|-----|--------|------|-----------|
| GET | `/` | welcome view | — | — |
| GET | `/feed` | feed view (Livewire) | `feed` | auth |
| GET | `/dashboard` | redirect ke /feed | `dashboard` | — |
| GET | `/profile/edit` | profile view | `profile.edit` | auth |
| GET | `/profile/{user?}` | UserProfileController@show | `profile.show` | auth |
| GET | `/posts/{post}` | PostController@show | `posts.show` | auth |
| GET | `/groups` | groups view (Livewire) | `groups` | auth |
| GET | `/groups/{group:slug}` | GroupController@show | `groups.show` | auth |
| GET | `/search` | search view (Livewire) | `search` | auth |
| GET | `/friends` | friends view (Livewire) | `friends` | auth |
| GET | `/admin` | admin view (Livewire) | `admin` | auth, can:admin |
| GET | `/messages` | messages view (Livewire) | `messages` | auth |
| GET | `/messages/{conversation}` | ConversationController@show | `messages.show` | auth |
| POST | `/messages/{conversation}/send` | ConversationController@send | `messages.send` | auth |
| POST | `/messages/start` | MessageController@store | `messages.store` | auth |
| GET | `/notifications` | NotificationController@index | `notifications` | auth |
| POST | `/notifications/read-all` | NotificationController@markAllRead | `notifications.markAllRead` | auth |
| DELETE | `/notifications/{id}` | NotificationController@destroy | `notifications.destroy` | auth |
| GET | `/bookmarks` | BookmarksController@index | `bookmarks` | auth |
| GET | `/explore` | HashtagController@index | `hashtags.index` | auth |
| GET | `/tags/{tag}` | HashtagController@show | `hashtags.show` | auth |
| GET | `/settings` | SettingsController@index | `settings` | auth |
| PUT | `/settings` | SettingsController@update | `settings.update` | auth |
| PUT | `/settings/password` | SettingsController@updatePassword | `settings.password` | auth |
| DELETE | `/settings` | SettingsController@destroy | `settings.destroy` | auth |

### `routes/auth.php`

Route autentikasi (login, register, password reset, email verification).

### `routes/channels.php`

Broadcasting channels:
- `user.{id}` — Private channel notifikasi per user
- `conversation.{id}` — Private channel percakapan (hanya participant)

---

## 6. Resources Directory (`resources/`)

### `resources/views/` — Struktur Blade

```
views/
├── layouts/
│   ├── app.blade.php          ← Master layout (auth pages)
│   └── guest.blade.php        ← Master layout (guest pages)
├── components/                 ← Reusable Blade components
│   ├── dropdown.blade.php
│   ├── search.blade.php
│   ├── skeleton-post.blade.php
│   └── ... (18 components)
├── livewire/                   ← Livewire component views
│   ├── feed.blade.php
│   ├── post-item.blade.php
│   ├── layout/navigation.blade.php
│   ├── profile/                ← Profile sub-components
│   └── ... (20+ views)
├── auth/                       ← Authentication views
│   ├── login.blade.php
│   ├── register.blade.php
│   └── ...
├── admin.blade.php             ← Admin panel wrapper
├── feed.blade.php              ← Feed wrapper (<livewire:feed />)
├── friends.blade.php           ← Friends wrapper
├── groups.blade.php            ← Groups wrapper
├── messages.blade.php          ← Messages wrapper
├── notifications.blade.php     ← Notifications page
├── profile.blade.php           ← Profile edit wrapper
├── search.blade.php            ← Search wrapper
├── welcome.blade.php           ← Landing page
└── posts/show.blade.php        ← Post detail
```

**Pattern:** Wrapper view = `<x-app-layout><livewire:xxx /></x-app-layout>`

### `resources/css/app.css`

Tailwind directives + custom CSS:
- CSS variables (`:root` untuk light, `.dark` untuk dark mode)
- Animations: `fade-in-up`, `scale-bounce`, `shimmer`
- Micro-interactions: `btn-press`, `avatar-hover`, `link-underline`, `input-glow`, `icon-nudge`
- Component styles: `card-elevation`, `skeleton-shimmer`, `pulse-bar`

### `resources/js/app.js`

Entry point JS:
```javascript
import '@aejkatappaja/phantom-ui';  // UI library
import Echo from 'laravel-echo';     // Real-time
import Pusher from 'pusher-js';      // WebSocket
import './feed-sentinel';            // Infinite scroll
import intersect from '@alpinejs/intersect';  // Alpine plugin
```

- **feed-sentinel.js** — IntersectionObserver untuk infinite scroll
- **Echo + Pusher** — Real-time broadcasting (jika configured)

---

## 7. Public Directory (`public/`)

| File | Fungsi |
|------|--------|
| `index.php` | Front controller (entry point semua request) |
| `.htaccess` | Rewrite rules Apache |
| `robots.txt` | SEO crawler instructions |
| `favicon.ico` | Icon browser |
| `manifest.json` | PWA manifest |
| `sw.js` | Service Worker (cache-first assets) |
| `images/logo.png` | Logo SocialFeed |
| `build/` | Output Vite (auto-generated) |

**Upload flow:**
```
User upload → Livewire WithFileUploads
→ storage/app/public/avatars/ atau posts/
→ php artisan storage:link → public/storage/ symlink
→ asset('storage/avatars/xxx.jpg') → bisa diakses
```

---

## 8. Tests Directory (`tests/`)

### Feature Tests (10 files)

| Test | Yang Di-test |
|------|-------------|
| `BroadcastingTest` | Event broadcasting |
| `PaginationTest` | Feed pagination config |
| `PostMediaUploadTest` | File upload |
| `PostPolicyTest` | Post authorization |
| `ProfileTest` | Profile update |
| `ReportTest` | Report system |
| `SearchTest` | Search functionality |
| `SocialFeedCoreTest` | Core features |
| `SocialFeedV2Test` | V2 features |
| `SocialFeedV3Test` | V3 features |

### Unit Tests (1 file)

| Test | Yang Di-test |
|------|-------------|
| `UsernameGeneratorTest` | Username generation |

### Menjalankan Test

```bash
php artisan test                          # Semua test
php artisan test --filter=PaginationTest  # Specific test
php artisan test --tests=Auth             # Specific folder
```

---

## 9. Docker & Deployment

### `Dockerfile` — Multi-stage Build

```
Stage 1: Node → npm install, npm run build (assets)
Stage 2: PHP-FPM → composer install, runtime
Stage 3: Nginx → web server
```

### `docker-compose.yml` — Services

| Service | Image | Port | Fungsi |
|---------|-------|------|--------|
| `app` | Custom PHP-FPM | — | Laravel app |
| `nginx` | Nginx | 80 | Reverse proxy |
| `redis` | Redis | 6379 | Cache & session |
| `meilisearch` | Meilisearch | 7700 | Search engine |

### `deploy.sh` — VPS Deployment

```bash
git pull origin main
composer install --optimize-autoloader --no-dev
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
sudo systemctl restart php-fpm
```

---

## 10. Alur Kerja End-to-End

### A. Registration & Login

```
Browser → GET /register → RegisterController@showRegistrationForm
→ render auth/register.blade.php

User submit → POST /register → RegisterController@register
→ validate → User::create() (password auto-hashed by model cast)
→ login → session created → redirect ke /feed
```

### B. Create Post

```
User klik "Posting" → Livewire CreatePost component
→ wire:submit="create" → validate (StorePostRequest)
→ Post::create(['user_id' => ..., 'body' => ...])
→ Handle media upload → storage/app/public/posts/
→ PostMedia::create() → sync hashtags
→ broadcast PostLiked event → re-render feed
→ user lihat post baru di feed
```

### C. Infinite Scroll Feed

```
User scroll ke bawah → feed-sentinel.js IntersectionObserver
→ sentinel element visible → dispatch 'loadMore' event
→ Livewire Feed::loadMore()
→ Post::withFeedRelations()->paginate(per_page)
→ append posts ke view → re-render partial
```

### D. Real-time Messaging

```
User kirim pesan → Livewire Messenger::sendMessage()
→ validate → Message::create()
→ broadcast MessageSent event → toOthers()
→ Pusher → Laravel Echo (JS) → append message
→ otherUser notification created
```

### E. Notification System

```
Event (like/comment/friend) → NotificationService::postLiked()
→ Notification::create() → broadcast via Echo
→ NotificationsDropdown nhận event → $refresh
→ badge count update → user klik → mark as read
```

### F. Dark Mode

```
User klik toggle → Alpine.js x-on:click
→ localStorage.setItem('dark', !dark)
→ document.documentElement.classList.toggle('dark')
→ CSS variables berubah → Tailwind dark: prefix aktif
```

---

## 11. Request Lifecycle (Laravel)

```
1. Browser → HTTP Request → public/index.php
2. require __DIR__.'/../vendor/autoload.php'
3. require __DIR__.'/../bootstrap/app.php'
4. Laravel Kernel handle request
5. Middleware pipeline:
   - TrustProxies
   - HandleCors
   - PreventRequestsDuringMaintenance
   - ValidatePostSize
   - TrimStrings
   - ConvertEmptyStringsToNull
   - StartSession
   - ShareErrorsFromSession
   - CSRF Protection
   - Authenticate (if auth middleware)
6. Route matching → routes/web.php
7. Controller / Livewire execution
8. Database query (Eloquent ORM)
9. View rendering (Blade templates)
10. Response → HTTP 200 + HTML
11. Terminable middleware (logging, etc.)
```

---

## 12. Asset Pipeline (Vite)

```
resources/css/app.css ─────┐
resources/js/app.js ───────┼─→ Vite build → public/build/
resources/js/feed-sentinel.js ┘    ├── manifest.json
                                   ├── app-Du8NRZix.js
                                   └── app-h2l5brla.css
```

**Di Blade:**
```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

**Vite Plugin** baca manifest → inject `<script>` dan `<link>` tags otomatis.

---

## 13. Database Schema Overview

```
users ──┬──< posts ──┬──< post_media
        │            ├──< comments (parent_id → nested)
        │            ├──< likes
        │            ├──< saved_posts (bookmarks)
        │            ├──< hashtags (many-to-many via hashtag_post)
        │            └──< polls ──< poll_options ──< poll_votes
        │
        ├──< friendships (sender_id, receiver_id, status)
        ├──< notifications (user_id, sender_id, type, data)
        ├──< stories ──< story_views
        ├──< group_user (user_id, group_id, role) ──> groups
        └──< conversations (via conversation_user pivot)
                └──< messages
```

**Relasi:**
- **One-to-Many:** User→Posts, Post→Comments, Post→Likes, Conversation→Messages
- **Many-to-Many:** User↔Groups (group_user), Post↔Hashtags (hashtag_post), User↔Conversations (conversation_user)
- **Polymorphic:** Report→reportable (Post, Comment, atau User)
- **Self-referencing:** Post→parent (repost), Comment→parent (nested reply)

---

## 14. Security Layer

| Layer | Implementasi | File |
|-------|-------------|------|
| Authentication | Laravel Auth + custom controllers | `Auth/LoginController`, `Auth/RegisterController` |
| Authorization | Policies + `@can` directive | `app/Policies/` |
| CSRF | `@csrf` token per form | Blade templates |
| XSS | `{{ }}` escaped output, `e()` helper | `Post::getBodyHtmlAttribute()` |
| File Upload | Mime type + size validation | `StorePostRequest`, `WithFileUploads` |
| Rate Limiting | Throttle middleware | `bootstrap/app.php` (5 rate limiters) |
| SQL Injection | Eloquent parameter binding | All Model queries |
| Password | Auto-hashed via model cast | `User::$casts = ['password' => 'hashed']` |
| Admin | `can:admin` policy middleware | `routes/web.php` admin route |

---

## 15. Design System

### Warna

| Token | Light | Dark | Fungsi |
|-------|-------|------|--------|
| `--accent` | `#0058bc` | `#0058bc` | Primary blue (constant) |
| `--bg-page` | `#f9f9fd` | `#0f1117` | Background page |
| `--card-bg` | `#ffffff` | `#1a1c1f` | Background card |
| `--text-primary` | `#1a1c1f` | `#e2e2e6` | Teks utama |
| `--text-secondary` | `#727785` | `#9ca3af` | Teks sekunder |

### Typography

- **Body:** Inter (system-ui fallback)
- **Display:** Sora (heading, logo)
- **Icons:** Material Symbols Outlined

### Micro-interactions

- `btn-press` — Scale 0.97 on active
- `avatar-hover` — Scale 1.05 + shadow on hover
- `link-underline` — Animated underline on hover
- `input-glow` — Accent glow on focus
- `icon-nudge` — TranslateX(2px) on hover
- `animate-fade-in-up` — Feed post entrance
- `animate-like-bounce` — Like button bounce
