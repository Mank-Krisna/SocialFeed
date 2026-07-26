# Laporan Sesi Pengembangan — 26 Juli 2026

**Branch:** `ui/micro-interactions`
**Commits:** 9baf892 → fae4eff (10 commits)
**Tests:** 80/80 pass, 1 skipped
**Status:** Semua fitur utama berfungsi

---

## Ringkasan Pekerjaan

### 1. Bug Fix: Auth Double-Hashing
**Commit:** `eab7f2e`

**Masalah:** User model punya cast `'password' => 'hashed'` yang otomatis hash password. RegisterController juga pakai `Hash::make()` → password jadi double-hash, login gagal.

**Fix:** Hapus `Hash::make()` dari `RegisterController`. Password dikirim plain text, model cast handle hashing.

---

### 2. Bug Fix: Bookmarks & Hashtag Pages
**Commit:** `652c40e`

**Masalah:**
- BookmarksController pakai `Auth::user()->savedPosts()` tapi SavedPost model tidak punya relationships `likes()`, `comments()`, `reposts()` → error
- HashtagController pakai `where('tag')` tapi kolom DB nama `name` → query kosong

**Fix:**
- BookmarksController: query `Post` langsung via `whereHas('savedByUsers')`
- HashtagController: `where('tag')` → `where('name')`
- Views: `$tag->tag` → `$tag->name`

---

### 3. Bug Fix: Nav Search & Feed Button Overlap
**Commit:** `205cdce`

**Masalah:** Search bar di nav pakai `flex-1` tapi container terlalu lebar, overlap dengan tombol Feed.

**Fix:** Search bar fixed width `w-64 lg:w-72`, bukan `flex-1`.

---

### 4. Revert Design ke Original Blue Palette
**Commit:** `60dd660`

**Masalah:** Redesign indigo-gold tidak sesuai keinginan user.

**Fix:** Revert ke original:
- Primary: `#0058bc` (blue)
- Fonts: Inter + Sora
- Logo: original blue "S" dari `stitch_socialfeed_branding_design/screen.png`

---

### 5. Bug Fix: Nav Positioning & Mobile Layout
**Commit:** `cb5fc2e`

**Masalah:**
- Nav bar pakai `flex-1` di bagian kiri → search bar terpush
- Mobile drawer pakai hardcoded colors, bukan CSS variables
- Mobile bottom nav tidak ada padding bawah → konten tertutup

**Fix:**
- Hapus `flex-1` dari nav kiri
- Mobile drawer: hardcoded → CSS variables
- Main layout: tambah `pb-20 md:pb-4` untuk mobile bottom nav

---

### 6. Bug Fix: Parse Error di Navigation
**Commit:** `41279cb`

**Masalah:** Mobile drawer dan bottom nav tidak ada `@auth` wrapper → error kalau user belum login.

**Fix:** Bungkus dengan `@auth ... @endauth`.

---

### 7. Bug Fix: Messenger Page Error
**Commit:** `3dfc915`

**Masalah:** `GET /messages` error "Using $this when not in object context". ConversationController render `livewire.messenger` sebagai plain Blade, tapi view pakai `$this->conversations`, `wire:model`, `$wire`.

**Fix:** Buat `messages.blade.php` wrapper dengan `<livewire:messenger />` (sama kayak `feed.blade.php`). Route `/messages` pakai `Route::view` bukan controller.

---

### 8. Fitur: Notifikasi Dropdown
**Commit:** `9baf892`

**Masalah:** Notifikasi harusnya dropdown popover, bukan halaman terpisah.

**Fix:**
- Buat `NotificationsDropdown` Livewire component
- Compact dropdown: unread badge, mark as read, "Tandai semua dibaca", "Lihat Semua"
- Desktop: dropdown di nav bar
- Mobile: tetap page link (dropdown jelek di touch)

---

### 9. Cleanup: Hapus File Tidak Berguna
**Commit:** `fae4eff`

**24 files changed, 861 lines deleted:**

| File/Folder | Alasan |
|---|---|
| `resources/resources/` | Nested duplicate salah path |
| `stitch_ai_logo_generator.zip` + folder | Design artifact |
| `stitch_socialfeed_branding_design/` | Logo sudah di-copy |
| `screen.png`, `DESIGN.md` | Root clutter |
| `tools/` | Debug output |
| `Pagination_Diagnostics.md`, `skills-lock.json` | Debug artifacts |
| `.phpunit.result.cache`, `public/hot` | Build artifacts |
| `public/logo.png` | Duplikat `public/images/logo.png` |
| `app/Notifications/` (2 files) | WelcomeNotification, WeeklyDigestNotification never dispatched |
| `app/Events/` (3 files) | FriendRequestSent, FriendRequestAccepted, CommentPosted never dispatched |
| `dashboard.blade.php` | `/dashboard` redirect ke `/feed` |
| `search-float.js` | Never referenced |

**Fix tambahan:**
- UserFactory, DatabaseSeeder, LargeDataSetSeeder: `Hash::make('password')` → `'password'`
- BroadcastingTest: hapus 3 test yang refer deleted events

---

## Statistik Akhir

| Metrik | Nilai |
|---|---|
| Total commits | 16 |
| PHP files | ~85 |
| Blade templates | ~75 |
| Livewire components | 20 |
| Tests | 80/80 pass, 1 skipped |
| Routes | 25+ |
| Models | 17 |
| Controllers | 12 |
| Form Requests | 7 |
| Policies | 4 |
| Events | 2 (PostLiked, MessageSent) |

---

## Akun Testing

| Role | Email | Password | Username |
|---|---|---|---|
| **Admin** | `demo@socialfeed.com` | `password` | budis |
| User | `siti@example.com` | `password` | siti_r |
| User | `ahmad@example.com` | `password` | ahmad_f |
| User | `dian@example.com` | `password` | dian_p |
| User | `rudi@example.com` | `password` | rudi_h |
| User | `dewi@example.com` | `password` | dewi_l |

**Data seed:** 7 posts, 3 groups, 7 friendships, 2 conversations, 5 messages, 3 stories, 8 likes, 4 comments, 1 poll, 1 bookmark, 9 hashtags.

---

## Fitur yang Sudah Berfungsi

- [x] Login / Register / Logout
- [x] Feed dengan infinite scroll
- [x] Posting teks + upload media
- [x] Like, Comment (dengan reply)
- [x] Repost
- [x] Bookmark
- [x] Polling (create + vote)
- [x] Groups (public/private)
- [x] Friends (send/accept/reject)
- [x] Real-time messaging
- [x] Notifications (dropdown + page)
- [x] Hashtag explore
- [x] Search
- [x] User settings
- [x] Admin dashboard
- [x] Dark mode
- [x] Responsive (desktop + mobile)
- [x] PWA manifest + service worker
- [x] SEO meta tags

---

## Known Issues

- [ ] Real-time presence channel (online status)
- [ ] Video transcoding
- [ ] Push notifications (Web Push API)
- [ ] Admin report filter by status/date
