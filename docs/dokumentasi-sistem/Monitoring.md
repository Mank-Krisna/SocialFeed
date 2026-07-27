# Monitoring — SocialFeed V4

## 1. Cleanup Story Otomatis

Stories memiliki masa hidup 24 jam. Mekanisme cleanup:

**Saat query:**
```php
// Story model scope
public function scopeActive($query)
{
    return $query->where('expires_at', '>', now());
}
```

Semua query story menggunakan scope `active()` sehingga story expired tidak pernah muncul ke user.

**Saat ini belum ada scheduler command untuk hapus fisik.** Direkomendasikan:
```php
// app/Console/Commands/CleanExpiredStories.php
$stories = Story::where('expires_at', '<=', now())->get();
foreach ($stories as $story) {
    Storage::delete($story->media_path);  // Hapus file
    $story->views()->delete();            // Hapus views
    $story->delete();                     // Hapus record
}
```
Lalu daftarkan di `routes/console.php`:
```php
Schedule::command('stories:clean')->daily();
```

## 2. Analitik Admin

Tersedia di `AdminDashboard` (Livewire component), `/admin` route.

**Statistik Saat Ini:**
| Metrik | Query | Sumber |
|--------|-------|--------|
| Total Users | `User::count()` | tabel `users` |
| Total Posts | `Post::count()` | tabel `posts` |
| Total Groups | `Group::count()` | tabel `groups` |
| Active Stories | `Story::active()->count()` | tabel `stories` |

**Metrik yang Direkomendasikan untuk Dikembangkan:**

| Metrik | Query | Keterangan |
|--------|-------|------------|
| DAU (Daily Active Users) | `User::whereHas('posts', fn(q) => q->whereDate('created_at', today()))->count()` | User yang posting hari ini |
| MAU (Monthly Active Users) | Count distinct user_id dari likes/comments/posts bulan ini | Bisa dari beberapa tabel |
| Total Likes | `Like::count()` | Tabel likes |
| Total Comments | `Comment::count()` | Tabel comments |
| Total Reposts | `Post::whereNotNull('parent_id')->count()` | Post dengan parent_id |
| Total Bookmarks | `SavedPost::count()` | Tabel saved_posts |
| Polls Active | `Poll::whereNull('ends_at')->orWhere('ends_at', '>', now())->count()` | Poll belum expired |
| Story Views | `StoryView::count()` | Tabel story_views |
| Messages Sent | `Message::count()` per periode | Tabel messages |
| Pendaftaran Baru | `User::whereDate('created_at', today())->count()` | Registrasi per hari |
| Grup Baru | `Group::whereDate('created_at', today())->count()` | Grup per hari |

## 3. Logging Error

**Laravel default logging** — semua error tercatat di `storage/logs/laravel.log`.

Channel yang digunakan (default):
- `stack` → single file + slack (opsional)
- `daily` → rotasi harian (retensi default 30 hari)

**Rekomendasi produksi:**
- Gunakan Sentry/Laravel Telescope untuk error tracking visual.
- Atau Flare (bagian dari Ignition) untuk debugging.

**Try-catch yang ada di kode:**
```php
// Messenger.php — broadcast gagal
try {
    broadcast(new MessageSent(...))->toOthers();
} catch (\Throwable $e) {
    // Pusher not configured — rely on wire:poll fallback
}
```

## 4. Monitoring Performa

**Rekomendasi untuk produksi:**

| Tools | Fungsi |
|-------|--------|
| Laravel Debugbar | Profiling query, memory, waktu render (dev) |
| Laravel Telescope | Query monitoring, job, mail, notifikasi, cache |
| Sentry | Error tracking + performance monitoring |
| MySQL Slow Query Log | Identifikasi query lambat |
| Pulse (Laravel) | Dashboard performa real-time: slow routes, job, cache |

**Optimasi yang sudah diterapkan:**
- Eager loading dengan `withFeedRelations()` di Post model.
- Pagination dengan `paginate()` bukan `get()`.
- `withCount()` untuk agregasi tanpa subquery.
- Scope query (`active()`) untuk filter database-side.
- Unique constraints untuk prevent duplicate index scans.

## 5. Ringkasan

| Area | Status | Catatan |
|------|--------|---------|
| Story Cleanup | Sebagian (query filter scope) | Perlu scheduler command |
| Statistik Admin | Dasar (users, posts, groups, stories) | Metrik lanjutan (DAU/MAU) belum ada |
| Error Logging | Ya (storage/logs) | Sentry/Telescope opsional |
| Query Profiling | Belum | Debugbar untuk dev |
| Performance Monitoring | Belum | Pulse/Sentry untuk produksi |

**Catatan:** SocialFeed V4 saat ini dalam tahap pengembangan. Infrastruktur monitoring produksi (Sentry, Pulse, scheduler job cleanup) perlu ditambahkan sebelum deployment ke production.
