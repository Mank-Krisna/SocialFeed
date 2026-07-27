# ADR 0002: SocialFeed V5 Architecture — Security, Moderation & Search

- **Status**: Approved
- **Date**: 2026-07-26
- **Authors**: SocialFeed Engineering Team

## Context
SocialFeed V5 menambahkan lapisan Keamanan (FormRequest, Policies, Rate Limiting), Moderasi Konten (Sistem Laporan), Broadcasting Event Real-time, Pencarian Terindeks (Laravel Scout), serta Manajemen Notifikasi Email & Queue.

## Decisions Made

1. **Authorization & Policies**:
   - `PostPolicy`, `CommentPolicy`, dan `GroupPolicy` menggunakan `before()` hook untuk mengecek `is_admin=true`.
   - Pemilik konten (`user_id === $owner->id`) diberikan hak `update` dan `delete`.

2. **Rate Limiting**:
   - Dikonfigurasikan di `bootstrap/app.php` menggunakan `RateLimiter::for()`:
     - `post-create`: 10/menit
     - `comment-create`: 30/menit
     - `like-toggle`: 60/menit
     - `friend-request`: 5/menit
     - `media-upload`: 5/menit

3. **Sistem ModerasiKonten (Reports)**:
   - Migration `2026_07_26_000002_create_reports_table.php` membuat struktur *polymorphic morphs* (`reportable_type`, `reportable_id`).
   - `ReportModal` Livewire component memfasilitasi pengguna untuk melaporkan postingan/komentar dengan rate limit & proteksi duplikasi.
   - Dashboard Admin memfasilitasi aksi `Resolve` (soft delete) dan `Dismiss`.

4. **Pencarian Terindeks (Laravel Scout)**:
   - Paket `laravel/scout` v11 dipasang.
   - Trait `Searchable` dan metode `toSearchableArray()` ditambahkan pada model `Post`, `User`, dan `Group`.
   - Menggunakan driver `database` untuk pengembangan lokal tanpa infrastruktur eksternal.

5. **Real-time Broadcasting**:
   - Event `PostLiked`, `CommentPosted`, `FriendRequestSent`, dan `FriendRequestAccepted` mengimplementasikan `ShouldBroadcast`.
   - Menyiarkan data ke *private channels* `user.{id}` yang divalidasi di `routes/channels.php`.

## Consequences
- Keamanan aplikasi meningkat drastis dengan pembatasan laju request (*rate limiting*) dan proteksi otorisasi bertingkat.
- Pengalaman pengguna lebih interaktif dengan notifikasi real-time via *broadcasting channels*.
- Pencarian data menjadi terstruktur dan siap dimigrasikan ke Meilisearch jika diperlukan di lingkungan produksi.
