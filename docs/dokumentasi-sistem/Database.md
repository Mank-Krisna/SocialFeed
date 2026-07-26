# Database — SocialFeed V4

## Ringkasan

19 tabel bisnis + 8 tabel sistem Laravel. Semua migrasi di `database/migrations/`. InnoDB, foreign keys, unique constraints.

## Diagram ERD Sederhana

```
users ──┬── posts ──────┬── post_media
        │               ├── likes
        │               ├── comments ── parent_id (self-ref)
        │               ├── polls ────── poll_options ──── poll_votes
        │               ├── saved_posts
        │               ├── hashtag_post ──── hashtags
        │               └── parent_id (self-ref: repost)
        │
        ├── friendships ── sender_id / receiver_id → users
        ├── notifications ── sender_id → users
        ├── groups ──────── group_user ──── users
        ├── stories ─────── story_views ─── users
        └── conversations ── conversation_user ── users
                            └── messages
```

## Daftar Tabel

### 1. `users`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | Auto-increment |
| name | varchar(255) | Nama lengkap |
| username | varchar(255) UNIQUE | Username unik |
| email | varchar(255) UNIQUE | Email unik |
| email_verified_at | timestamp nullable | Verifikasi email |
| password | varchar(255) | Bcrypt hash (via `'hashed'` cast) |
| is_admin | boolean default false | Status admin |
| bio | text nullable | Bio profil |
| avatar | varchar(255) nullable | Path foto profil |
| cover_photo | varchar(255) nullable | Path sampul |
| remember_token | varchar(100) nullable | Token "remember me" |
| timestamps | created_at, updated_at | Waktu buat/ubah |

**Relasi:** hasMany posts, likes, comments, notifications, stories, savedPosts. belongsToMany groups, conversations.

---

### 2. `posts`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| user_id | bigint FK → users.id | Pembuat post |
| group_id | bigint FK → groups.id nullable | Grup tempat post |
| parent_id | bigint FK → posts.id nullable | Post asli (untuk repost) |
| body | text | Isi post (text) |
| deleted_at | timestamp nullable | Soft delete |
| timestamps | | |

**Relasi:** belongsTo user, group. hasMany media, likes, comments, reposts (parent_id). belongsToMany hashtags. hasOne poll. hasMany savedByUsers.

**Constraint:** foreign key `user_id` → `users.id` ON DELETE CASCADE. foreign key `group_id` → `groups.id` ON DELETE SET NULL. foreign key `parent_id` → `posts.id` ON DELETE CASCADE.

---

### 3. `post_media`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| post_id | bigint FK → posts.id | Post pemilik |
| file_path | varchar(255) | Path relatif file |
| type | varchar(20) | 'image' atau 'video' |
| timestamps | | |

**Relasi:** belongsTo post.
**Constraint:** foreign key `post_id` → `posts.id` ON DELETE CASCADE.

---

### 4. `likes`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| user_id | bigint FK → users.id | Pemberi like |
| post_id | bigint FK → posts.id | Post di-like |
| timestamps | | |

**Constraint:** UNIQUE(user_id, post_id). foreign key → users, posts ON DELETE CASCADE.

---

### 5. `comments`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| user_id | bigint FK → users.id | Pengomentar |
| post_id | bigint FK → posts.id | Post dikomentari |
| parent_id | bigint FK → comments.id nullable | Balasan ke komentar lain |
| body | text | Isi komentar |
| timestamps | | |

**Relasi:** belongsTo user, post. belongsTo parent (self-ref). hasMany replies.
**Constraint:** foreign keys → users, posts, comments (self) ON DELETE CASCADE.

---

### 6. `friendships`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| sender_id | bigint FK → users.id | Pengirim permintaan |
| receiver_id | bigint FK → users.id | Penerima |
| status | enum('pending','accepted','rejected') | Status pertemanan |
| timestamps | | |

**Constraint:** UNIQUE(sender_id, receiver_id). foreign key → users ON DELETE CASCADE.

---

### 7. `notifications`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| user_id | bigint FK → users.id | Penerima notifikasi |
| sender_id | bigint FK → users.id | Pelaku aksi |
| type | varchar(50) | like, comment, mention, friend_request, friend_accepted, repost, message |
| data | json | Payload: message + link |
| read_at | timestamp nullable | Waktu dibaca |
| timestamps | | |

**Relasi:** belongsTo user (user_id & sender_id).

---

### 8. `groups`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| user_id | bigint FK → users.id | Pembuat grup |
| name | varchar(255) | Nama grup |
| slug | varchar(255) UNIQUE | Slug untuk URL |
| description | text nullable | Deskripsi |
| photo | varchar(255) nullable | Foto grup |
| type | enum('public','private') | Visibilitas |
| timestamps | | |

**Relasi:** belongsTo creator (user_id). belongsToMany members. hasMany posts.

---

### 9. `group_user` (pivot)

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| group_id | bigint FK → groups.id | |
| user_id | bigint FK → users.id | |
| role | enum('admin','member') | Peran di grup |
| timestamps | | |

**Constraint:** UNIQUE(group_id, user_id).

---

### 10. `polls`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| post_id | bigint FK → posts.id UNIQUE | Post terkait |
| question | varchar(300) | Pertanyaan poll |
| ends_at | timestamp nullable | Waktu berakhir |
| timestamps | | |

**Relasi:** belongsTo post. hasMany options.
**Constraint:** foreign key → posts ON DELETE CASCADE.

---

### 11. `poll_options`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| poll_id | bigint FK → polls.id | Induk poll |
| label | varchar(200) | Teks opsi |
| timestamps | | |

**Relasi:** belongsTo poll. hasMany votes.

---

### 12. `poll_votes`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| poll_option_id | bigint FK → poll_options.id | Opsi dipilih |
| user_id | bigint FK → users.id | Pemilih |
| timestamps | | |

**Constraint:** UNIQUE(poll_option_id, user_id). one vote per opsi per user.

---

### 13. `saved_posts` (bookmarks)

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| user_id | bigint FK → users.id | |
| post_id | bigint FK → posts.id | |
| timestamps | | |

**Constraint:** UNIQUE(user_id, post_id).

---

### 14. `hashtags`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| name | varchar(100) UNIQUE | Nama hashtag (lowercase) |
| timestamps | | |

---

### 15. `hashtag_post` (pivot)

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| hashtag_id | bigint FK → hashtags.id | |
| post_id | bigint FK → posts.id | |
| timestamps | | |

**Constraint:** UNIQUE(hashtag_id, post_id).

---

### 16. `conversations`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| type | varchar(20) default 'private' | Tipe percakapan |
| timestamps | | |

---

### 17. `conversation_user` (pivot)

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| conversation_id | bigint FK → conversations.id | |
| user_id | bigint FK → users.id | |
| last_read_at | timestamp nullable | Waktu baca terakhir |
| timestamps | | |

**Constraint:** UNIQUE(conversation_id, user_id).

---

### 18. `messages`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| conversation_id | bigint FK → conversations.id | |
| user_id | bigint FK → users.id | Pengirim |
| body | text | Isi pesan |
| timestamps | | |

**Relasi:** belongsTo conversation, user.

---

### 19. `stories`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| user_id | bigint FK → users.id | Pembuat story |
| media_path | varchar(255) | Path file media |
| caption | varchar(200) nullable | Teks cerita |
| expires_at | datetime | Expired (created + 24 jam) |
| timestamps | | |

**Relasi:** belongsTo user. hasMany views.
**Scope aktif:** `where('expires_at', '>', now())`.

---

### 20. `story_views`

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| story_id | bigint FK → stories.id | |
| user_id | bigint FK → users.id | Penonton |
| timestamps | | |

**Constraint:** UNIQUE(story_id, user_id). satu view per user per story.

---

### Tabel Sistem Laravel

| Tabel | Fungsi |
|-------|--------|
| `cache` | Cache key-value |
| `cache_locks` | Lock untuk atomic cache |
| `jobs` | Antrian job |
| `job_batches` | Batch job tracking |
| `failed_jobs` | Job gagal |
| `password_reset_tokens` | Token reset password |
| `sessions` | Session user (driver database) |
| `migrations` | Riwayat migrasi |

---

## Indeks & Optimasi

- **Unique constraints** mencegah duplikasi: likes, friendships, saved_posts, poll_votes, hashtag_post, conversation_user, story_views.
- **Foreign keys** dengan `ON DELETE CASCADE` memastikan integritas referensial saat data induk dihapus.
- **Soft delete** di `posts` memungkinkan pemulihan data.
- **Scope `active()`** di Story memfilter baris expired tanpa load semua data.
- **Query `withFeedRelations()`** di Post model mengoptimasi eager loading relasi feed.
