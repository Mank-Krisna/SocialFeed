# 🔑 Access Control & Role-Based Access Governance Policy (RBAC)

## 1. Hirarki Peran (Role Hierarchy)

### 👑 Super Admin
- Akses penuh ke seluruh sistem dan konfigurasi aplikasi.
- Berhak mengelola peran pengguna lain, menangguhkan akun, dan melihat audit log.

### 🛡️ Community Moderator
- Bertanggung jawab menjaga keamanan konten publik.
- Berhak meninjau antrean pelaporan (*Report Queue*), menyembunyikan postingan yang melanggar, dan memberi peringatan kepada pengguna.

### 👨‍👩‍👧‍👦 Group Admin / Owner
- Berhak mengelola status grup (Publik / Privat), menyetujui permintaan bergabung anggota, dan menunjuk moderator grup.
- Berhak menghapus postingan di dalam grup yang dikelolanya.

### ⭐ Verified User / Creator
- Pengguna yang telah melalui verifikasi identitas (lencana biru).
- Mendapatkan prioritas kuota upload multimedia yang lebih besar dan akses fitur analitik postingan.

### 👤 Standard Member
- Pengguna terdaftar standar.
- Berhak membuat postingan, berinteraksi (Suka/Komentar), mengirim permintaan pertemanan, dan bergabung dengan grup publik.

---

## 2. Prinsip Hak Akses Minimum (Principle of Least Privilege)
- Setiap peran hanya diberikan hak akses minimum yang diperlukan untuk menjalankan fungsinya.
- Tindakan administratif sensitif wajib melalui konfirmasi ulang password (*Password Confirmation Middleware*).
