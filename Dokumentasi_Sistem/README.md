# Dokumentasi Sistem — SocialFeed V4

Indeks dokumentasi arsitektur aplikasi SocialFeed V4.

| File | Isi |
|------|-----|
| [Database.md](Database.md) | Struktur tabel, relasi, diagram ERD, indeks, constraints |
| [Keamanan.md](Keamanan.md) | Validasi input, ACL, CSRF, hashing password, upload security, query optimization |
| [PenyimpananFile.md](PenyimpananFile.md) | Lokasi storage, symlink, cloud opsional, path di database |
| [AlurAplikasi.md](AlurAplikasi.md) | Langkah user tiap fitur: post, repost, poll, bookmark, hashtag, mention, DM, story, moderasi |
| [Monitoring.md](Monitoring.md) | Cleanup story, analitik admin, logging error, performa |

## Ringkasan

- **Stack:** Laravel 11 + Livewire 3 + MySQL + Tailwind CSS
- **Arsitektur:** SPA-like (Livewire `wire:navigate`), rendering di server, update real-time via polling/broadcast
- **Auth:** Laravel Breeze + session-based (database driver)
- **Storage Lokal:** `storage/app/public/` → symlink `public/storage/`
- **Database:** 19 tabel bisnis + 8 tabel sistem Laravel
- **Keamanan:** CSRF otomatis (Laravel), validasi input di tiap Livewire component, password bcrypt via cast `hashed`, ACL manual di controller/component
