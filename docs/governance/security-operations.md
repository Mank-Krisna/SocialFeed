# ⚙️ Security Operations, Infrastructure & Audit Log Policy

## 1. Penyimpanan Berkas Media (Storage Infrastructure)
- Menggunakan Abstraksi Storage Laravel (`Storage::disk('public')`).
- Dalam lingkungan produksi, berkas media disarankan menggunakan layanan Object Storage terdistribusi seperti Amazon S3 atau Cloudflare R2 untuk skalabilitas tinggi.

## 2. Audit Trail & Log Operasional
- Semua aksi sensitif (penghapusan akun, perubahan peran pengguna, penghapusan postingan oleh moderator) dicatat ke dalam log audit (`audit_logs`).
- Log menyimpan informasi: `user_id`, `action`, `target_id`, `ip_address`, `timestamp`.
