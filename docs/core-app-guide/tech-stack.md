# ⚙️ Teknologi & Cara Menjalankan SocialFeed

## 🛠️ Susunan Teknologi (Tech Stack)

1. **Backend Framework**: **Laravel 11** (PHP 8.3)
   - Mengelola logika bisnis, autentikasi pengguna, rute aplikasi, dan keamanan database.
2. **Frontend Engine**: **Livewire 3** & **Volt**
   - Membuat tampilan interaktif real-time tanpa perlu me-reload halaman web.
3. **Styling (CSS)**: **Tailwind CSS**
   - Mengatur desain visual modern, warna cerah, dan responsivitas layar HP & Desktop.
4. **Loading Animation**: **Phantom UI** (`@aejkatappaja/phantom-ui`)
   - Web Component 8KB untuk efek animasi *shimmer skeleton loading*.
5. **Database**: **SQLite** (Development) / **MySQL** (Production)
   - Menyimpan data akun, postingan, media, grup, dan komentar.

---

## 🚀 Cara Menjalankan Aplikasi Di Komputer Lokal

> ⚠️ **Catatan penting**: Karena `php` belum masuk di PATH Windows kamu, gunakan path lengkap PHP Laragon berikut:

```powershell
# Jalankan Server Utama Laravel (Port 9000)
C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe artisan serve --port=9000

# Buka di Browser
http://127.0.0.1:9000
```
