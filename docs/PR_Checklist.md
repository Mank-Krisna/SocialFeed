## PR Review Checklist — Pagination & Infinite Scroll

### Scope
- [ ] Perubahan hanya pada pagination/infinite scroll, sentinel JS, dan index DB.

### Frontend
- [ ] Livewire `resetPage()` dipanggil saat filter berubah (AdminDashboard `switchTab`).
- [ ] Sentinel element `#feed-sentinel` berada di dalam feed container (`feed.blade.php`).
- [ ] `config('feed.per_page')` digunakan sebagai nilai perPage default di mount/loadMore.
- [ ] feed-sentinel.js: IntersectionObserver aktif + fallback scroll listener + tombol "Muat Lainnya".

### Backend
- [ ] Query pagination menggunakan `take(N+1)` pattern (Feed, GroupDetail) atau `paginate()` (AdminDashboard).
- [ ] Tidak ada N+1 — `withFeedRelations()` eager-loads user, media, group, parent, poll.
- [ ] Migration `2026_07_26_000001_add_feed_indexes.php` menambahkan index:
  - `posts(created_at, id)` — composite untuk feed order
  - `messages(conversation_id, created_at)` — chat listing
  - `stories(expires_at)` — active story query

### DB / Ops
- [ ] Migration safe untuk tabel kecil–sedang (<1M rows). Untuk tabel besar, gunakan `pt-online-schema-change`.
- [ ] `down()` migration tersedia dan teruji.

### Tests
- [ ] Unit/Feature `PaginationTest` (7 tests) — lulus.
- [ ] Full suite 66 tests, 164 assertions — lulus.
- [ ] E2E smoke: `tests/E2E/feed-pagination.spec.js` — 4 test cases (sentinel trigger, end marker, filter reset, fallback button).

### Manual QA (staging)
- [ ] Scroll feed sampai habis — end marker "Sudah semua" muncul.
- [ ] Klik "Postingan Teman" — feed reset, perPage kembali ke config.
- [ ] Uji di desktop & mobile viewport.
- [ ] PerPage change via env `FEED_PER_PAGE` — konsisten di semua komponen.

### Rollback
- [ ] `git revert <merge-commit>` atau `git reset --hard HEAD~1`.
- [ ] `php artisan migrate:rollback` untuk index migration.

### Sign-off
- [ ] Frontend ✅
- [ ] Backend ✅
- [ ] DB/Ops ✅

---

### Cara jalankan E2E
```bash
# Install Playwright (pertama kali)
npx playwright install chromium

# Jalankan test (pastikan server Laravel jalan di APP_URL)
$env:APP_URL="http://localhost:8000"
npx playwright test tests/E2E
```
