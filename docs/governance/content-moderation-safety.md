# 🛡️ Content Moderation, File Validation & Anti-Spam Policy

## 1. Aturan Validasi Unggahan Berkas Media
- **Foto**: Format yang diizinkan (`JPG`, `JPEG`, `PNG`, `GIF`, `WEBP`). Ukuran maksimal per berkas: **50 MB**.
- **Video**: Format yang diizinkan (`MP4`, `MOV`, `AVI`, `WEBM`, `MKV`, `3GP`). Ukuran maksimal per berkas: **100 MB**.
- Validasi dilakukan di level client (preview) dan wajib diverifikasi ulang di level server (*MIME type & extension checking*).

## 2. Alur Pelaporan Konten (Report System)
1. Setiap postingan dan komentar memiliki opsi **Laporkan** (`Report`).
2. Kategori laporan: *Spam*, *Ujaran Kebencian / SARA*, *Konten Dewasa / NSFW*, *Penipuan / Fraud*.
3. Konten dengan ≥ 3 laporan otomatis ditandai `needs_review` dan diprioritaskan di dashboard Moderator.

## 3. Pencegahan Bot & Anti-Spam
- Integrasi **Cloudflare Turnstile** pada formulir pendaftaran akun baru.
- **Throttling Post**: Maksimal 10 postingan per 5 menit per akun untuk mencegah penyalahgunaan skrip otomatis.
