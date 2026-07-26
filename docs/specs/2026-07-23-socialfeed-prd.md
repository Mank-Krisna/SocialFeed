# PRD: SocialFeed — Social Media Mini

## Ringkasan

Aplikasi social media ala Facebook dibangun dengan Laravel + Blade + Livewire. Target pengguna: individu yang ingin berbagi status, terhubung dengan teman, dan berdiskusi dalam grup.

## Target Pengguna

- Pengguna umum yang ingin social media sederhana
- Cocok untuk circle pertemanan atau komunitas kecil

## Stack Teknologi

| Komponen | Pilihan |
|----------|---------|
| Backend | Laravel 11 |
| Frontend | Blade + Livewire 3 |
| Database | MySQL / PostgreSQL |
| Auth | Laravel Breeze (Blade stack) |
| File Upload | Laravel MediaLibrary |
| Realtime | Laravel Echo + Pusher (V3) |

## Fitur Lengkap

### V1 — MVP

- Register, login, logout
- Edit profil (bio, foto profil, foto cover)
- Buat status teks
- Feed global (semua postingan, urut terbaru)
- Like/unlike postingan
- Komentar di postingan (dengan nested reply)

### V2

- Upload gambar di postingan (multiple)
- Sistem teman (kirim, terima, tolak, unfriend)
- Notifikasi dalam app (teman baru, like, komentar)
- Filter feed berdasarkan teman

### V3

- Grup (buat grup publik/privat, join/leave, posting di grup, daftar anggota)
- Pencarian user & grup
- Notifikasi realtime via Pusher

## Database Tables

| Table | Kolom Utama |
|-------|-------------|
| `users` | id, name, username, email, password, bio, avatar, cover_photo, email_verified_at |
| `posts` | id, user_id, body, created_at, updated_at, deleted_at |
| `post_media` | id, post_id, file_path, type, created_at |
| `likes` | id, user_id, post_id, created_at |
| `comments` | id, user_id, post_id, parent_id, body, created_at |
| `friendships` | id, sender_id, receiver_id, status (pending/accepted/rejected), created_at |
| `groups` | id, name, slug, description, photo, type (public/private), user_id (creator), created_at |
| `group_user` | id, group_id, user_id, role (admin/member), created_at |
| `notifications` | id, user_id, type, data (json), read_at, created_at |

## Halaman & Routing

| URL | Halaman | Fitur |
|-----|---------|-------|
| `/` | Landing / Login | Login & register |
| `/register` | Register | Form daftar akun |
| `/feed` | Home feed | Semua postingan terbaru |
| `/profile/{user}` | Profil user | Profil, daftar post user, daftar teman |
| `/profile/edit` | Edit profil | Ganti bio, foto profil, cover |
| `/posts/{id}` | Detail post | Post + komentar |
| `/friends` | Teman | Daftar teman, permintaan teman |
| `/groups` | Grup | Daftar grup, buat grup |
| `/groups/{id}` | Detail grup | Post di grup, anggota grup |
| `/notifications` | Notifikasi | Daftar notifikasi |
| `/search` | Pencarian | Cari user & grup |

## Layout

3 kolom:

```
[Navbar — Logo | Pencarian | Notif | Profil]
                    ↓
[Sidebar kiri]  [Konten utama]  [Sidebar kanan]
   Navigasi        Feed/dll       Saran teman
```

## Iterasi Rilis

| Iterasi | Fitur | Estimasi |
|---------|-------|----------|
| V1 | Auth, profil, posting teks, feed global, like, komentar | 1-2 minggu |
| V2 | Upload gambar, sistem teman, notifikasi, filter feed | 1-2 minggu |
| V3 | Grup, pencarian, realtime notifikasi | 1-2 minggu |
