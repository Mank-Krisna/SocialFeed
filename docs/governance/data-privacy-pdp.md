# 🔒 Data Protection, Privacy & UU PDP Compliance Policy

## 1. Hak Pengguna Atas Data Pribadi (UU Perlindungan Data Pribadi)
- **Hak Akses & Portabilitas Data**: Pengguna berhak meminta ekspor data profil dan riwayat aktivitas mereka.
- **Hak Penghapusan Akun (Right to Erasure)**: Pengguna dapat mengajukan penghapusan akun. Sistem menjalankan *Soft Delete* selama 30 hari sebelum dilakukan *Hard Purge* permanen.

## 2. Enkripsi & Proteksi Sesi
- Kata sandi wajib dienkripsi menggunakan algoritma `bcrypt` dengan faktor putaran minimal 12.
- Semua form menggunakan proteksi CSRF token (`@csrf`).
- Sesi pengguna otomatis kedaluwarsa setelah masa inaktivitas yang ditentukan.
