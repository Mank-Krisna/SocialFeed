# Penyimpanan File — SocialFeed V4

## 1. Lokasi Penyimpanan (Lokal)

Semua file upload disimpan di disk `public` Laravel:

```
storage/
└── app/
    └── public/
        ├── posts/       ← File media postingan (gambar/video)
        ├── stories/     ← File story (gambar)
        ├── avatars/     ← Foto profil user
        ├── covers/      ← Foto sampul user
        └── groups/      ← Foto grup
```

Setiap path di database hanya menyimpan **path relatif** terhadap disk `public`:

| Tabel | Kolom | Contoh Path |
|-------|-------|-------------|
| `post_media` | `file_path` | `posts/abc123.jpg` |
| `stories` | `media_path` | `stories/def456.jpg` |
| `users` | `avatar` | `avatars/ghi789.jpg` |
| `users` | `cover_photo` | `covers/jkl012.jpg` |
| `groups` | `photo` | `groups/mno345.jpg` |

## 2. Symlink

`config/filesystems.php`:
```php
'links' => [
    public_path('storage') => storage_path('app/public'),
],
```

Setelah `php artisan storage:link`, file dapat diakses via URL:
```
http://localhost/storage/posts/abc123.jpg
```

Model `PostMedia` mengakses via:
```php
public function getUrlAttribute(): string
{
    return Storage::url($this->file_path);
    // => /storage/posts/abc123.jpg
}
```

Model `User`:
```php
public function getAvatarUrlAttribute(): string
{
    if ($this->avatar) {
        return Storage::url($this->avatar);
    }
    return 'https://ui-avatars.com/api/?name=...';
}
```

## 3. Opsi Cloud Storage

Konfigurasi S3 sudah tersedia di `config/filesystems.php`, tinggal atur `.env`:

**.env:**
```
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your-key
AWS_SECRET_ACCESS_KEY=your-secret
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=socialfeed-uploads
AWS_URL=https://socialfeed-uploads.s3.amazonaws.com
AWS_ENDPOINT=https://sgp1.digitaloceanspaces.com   # Untuk DO Spaces
```

Untuk migrasi ke cloud, cukup ubah disk dari `public` ke `s3` di kode upload:
```php
$path = $file->store('posts', 's3');
// Daripada: $file->store('posts', 'public');
```

`Storage::url()` akan otomatis mengembalikan URL S3 jika disk s3 aktif.

## 4. Upload Flow (CreatePost)

1. User pilih file di form → Livewire `WithFileUploads` handle.
2. File masuk **temporary storage** Livewire untuk validasi.
3. Setelah validasi lolos, file dipindahkan ke `storage/app/public/posts/`.
4. Record `PostMedia` dibuat dengan `file_path` path relatif.
5. Frontend render via `$media->url` (accessor `getUrlAttribute`).

## 5. Upload Story

1. File divalidasi: `required|image|max:10240` (10MB).
2. Disimpan ke `storage/app/public/stories/` via `$file->store('stories', 'public')`.
3. Record `Story` dibuat dengan `media_path` path relatif.
4. Story otomatis expired dalam 24 jam (data + file).

## Ringkasan

**"Dimana file upload disimpan?"** — Di `storage/app/public/` (lokal), disymlink ke `public/storage/`. Path relatif disimpan di database tanpa URL absolut, memudahkan migrasi ke cloud (S3/DO Spaces) tanpa update data lama.

## Diagram Alur Upload

```
User → Form Upload
  → Livewire WithFileUploads
    → Temporary Storage (validasi)
      → Validasi lolos?
        → Ya: store('posts', 'public') → storage/app/public/posts/
          → Simpan path relatif ke DB (post_media.file_path)
          → Tampilkan via Storage::url()
        → Tidak: error validasi
```
