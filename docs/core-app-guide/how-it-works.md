# 🔄 Cara Kerja & Alur Aplikasi SocialFeed

## ⚡ Alur Perjalanan Pengguna (User Flow)

```
 [1. Pengguna Buka App] ──► [2. Login / Register] ──► [3. Masuk Halaman Feed Utama]
                                                                  │
       ┌──────────────────────────┬───────────────────────────────┼──────────────────────────────┐
       ▼                          ▼                               ▼                              ▼
 [4. Buat Postingan]     [5. Interaksi Post]             [6. Kelola Teman]              [7. Gabung Grup]
 (Teks + Foto/Video)    (Suka & Komentar)              (Cari & Tambah Teman)           (Publik & Privat)
       │                          │                               │                              │
       └──────────────────────────┴───────────────────────────────┴──────────────────────────────┘
                                                  │
                                                  ▼
                                       [8. Terima Notifikasi]
```

---

## 🛠️ Bagaimana Sistem Bekerja Di Belakang Layar?

1. **Ketika Anda Upload Foto/Video**:
   - File dikirim dari HP/Browser ke server via Livewire.
   - Server mengecek ukuran (max 100MB) & jenis file.
   - Berkas disimpan di folder `storage/app/public/posts/`.
   - Data link berkas disimpan di database pada tabel `post_media`.

2. **Ketika Anda Menekan Tombol "Suka" (Like)**:
   - Livewire mengirim perintah instan ke server tanpa perlu *reload* halaman web.
   - Database menambahkan data di tabel `likes`.
   - Jumlah Suka di layar langsung berubah otomatis (misal: dari 5 Suka menjadi 6 Suka).
   - Pemilik postingan otomatis menerima Notifikasi di akunnya.

3. **Ketika Anda Membuka Aplikasi Dari HP**:
   - Sistem mendeteksi layar kecil (< 640px).
   - Menu navigasi bawah (*Bottom Navigation Bar*) otomatis muncul di dekat jempol tangan.
   - Menu terdiri dari 5 tombol: **Feed**, **Teman**, **Grup**, **Notif**, dan **Profil**.
