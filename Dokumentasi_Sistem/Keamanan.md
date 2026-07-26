# Keamanan — SocialFeed V4

## 1. Validasi Input

Setiap Livewire component melakukan validasi di method sebelum eksekusi.

| Component | Validasi |
|-----------|----------|
| `CreatePost` | `body: nullable|string|max:500`, `mediaFiles: array|max:10`, `mediaFiles.*: file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,webm,mkv|max:102400` |
| `StorySection` | `mediaFile: required|image|max:10240`, `caption: nullable|string|max:200` |
| `Messenger` | `body: required|string|max:2000` |
| `CommentSection` | Validasi body di component |

Laravel otomatis memfilter XSS via Blade escaping `{{ }}`. Body post tidak di-render sebagai HTML mentah—dibersihkan sebelum ditampilkan.

## 2. ACL (Access Control)

Pemeriksaan akses manual di method atau mount:

**Admin Dashboard:**
```php
if (!Auth::user()?->isAdmin()) abort(403);
```

**CreatePost (grup private):**
```php
$group = Group::find($this->groupId);
if (!$group || !$group->isMember(Auth::user())) {
    $this->addError('groupId', 'Anda tidak dapat memposting ke grup ini.');
}
```

**Feed (filter grup private):**
```php
$query->whereNull('group_id')
  ->orWhereHas('group', function ($q) {
      $q->where('type', 'public')
        ->orWhereIn('id', $myGroupIds);
  });
```

**Messenger (akses percakapan):**
```php
$conv = Conversation::find($this->activeConversationId);
if (!$conv || !$conv->users()->where('user_id', Auth::id())->exists()) return;
```

Polanya: cek kepemilikan/keanggotaan **setiap kali** data diakses, bukan hanya di mount.

## 3. Proteksi CSRF

Laravel menangani CSRF secara otomatis:

- Setiap sesi punya token CSRF unik.
- Semua POST livewire request menyertakan `X-CSRF-TOKEN` header.
- Livewire 3 secara implisit mengirim token via `csrf_token()` di layout.
- `Same-Site: Lax` di cookie session mencegah CSRF dari origin lain.

## 4. Hashing Password

Di `User.php`:
```php
protected function casts(): array
{
    return [
        'password' => 'hashed',
    ];
}
```

Cast `'hashed'` = Laravel otomatis bcrypt password saat assign. Password tidak pernah disimpan plain text. Default cost factor bcrypt = 10-12.

## 5. Validasi Upload File

**Livewire temporary upload:**
```
max:102400 (100MB)
```

**CreatePost validation:**
```php
'mediaFiles.*' => 'file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,webm,mkv|max:102400'
```

**Story upload:**
```php
'mediaFile' => 'required|image|max:10240'
```

**Lapisan keamanan:**
1. `mimes:` membatasi ekstensi file yang diizinkan.
2. `max:` membatasi ukuran file (100MB post, 10MB story).
3. Livewire menyimpan upload ke temporary disk sebelum validasi.
4. Hanya file valid yang dipindahkan ke storage final.
5. Deteksi tipe melalui MIME + ekstensi di `PostMedia::detectType()`.

## 6. Optimasi Query

- **Eager loading** via `withFeedRelations()` mencegah N+1 di feed.
- **`withCount()`** untuk like/comment count tanpa subquery.
- **Paginate dengan `pageName`** untuk paginasi independen per komponen.
- **Scope** `active()` di Story untuk filter row expired.
- **`whereIn()` friend IDs** untuk filter feed friends.
- **`latest()`** di post, message, notification queries untuk data terbaru.

## 7. Proteksi Tambahan

| Aspek | Mekanisme |
|-------|-----------|
| XSS | Blade `{{ }}` auto-escape |
| SQL Injection | Eloquent ORM (parameter binding) |
| Mass Assignment | `$fillable` di tiap model |
| Session Fixation | `session()->regenerate()` setelah login |
| Soft Delete | `deleted_at` — data tidak hilang permanen |
| Rate Limit | Login form via Livewire throttle |
| Self-Notifikasi Skip | `if ($post->user_id === $sender->id) return;` |
| Admin Self-Delete | `if ($user->id !== Auth::id())` |

## Ringkasan

**"Apakah database aman?"** — Ya. Foreign keys menjaga integritas data. Soft delete mencegah kehilangan permanen. Constraints UNIQUE cegah duplikasi. Eloquent binding cegah SQL injection.
