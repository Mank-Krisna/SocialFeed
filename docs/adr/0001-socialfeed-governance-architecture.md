# ADR 0001: Governance & System Architecture for SocialFeed

- **Status**: Approved
- **Date**: 2026-07-24
- **Authors**: SocialFeed Architecture & Governance Team

## Context
SocialFeed telah berkembang dari MVP media sosial dasar menjadi platform sosial berbasis komunitas dengan dukungan multimedia (foto & video 100MB), grup komunitas, sistem pertemanan, notifikasi, dan optimasi mobile UI. Seiring bertumbuhnya jumlah pengguna dan volume media, diperlukan kerangka kerja **Tata Kelola (Governance)** yang lengkap untuk mengontrol keamanan, moderasi, peran pengguna, perlindungan data, dan skalabilitas sistem.

## Decision
Kami menetapkan 5 Pilar Utama Governance untuk SocialFeed:

1. **Access Control Governance (RBAC)**:
   - Peran: `Super Admin`, `Community Moderator`, `Group Manager`, `Verified User`, `Standard Member`.
   - Admin dapat melakukan soft-delete pada postingan yang melanggar aturan dan memblokir akun spammer.

2. **Content Safety & Moderation Governance**:
   - Sistem Pelaporan Konten (Report System) untuk postingan dan komentar.
   - Pembatasan file upload (Foto: JPG/PNG/WEBP, Video: MP4/MOV/WEBM up to 100MB) dan pemeriksaan MIME-type server-side.
   - Proteksi bot menggunakan Cloudflare Turnstile / Captcha pada registrasi dan form posting.

3. **Data Protection & Privacy Governance (UU PDP Compliance)**:
   - Hak pengguna untuk menghapus akun & riwayat postingan (Soft delete + Permanent purge batch).
   - Enkripsi password (`bcrypt`), sanitasi teks input, dan proteksi CSRF di seluruh form Livewire.

4. **Technical & Infrastructure Governance**:
   - Penggunaan SQLite (Development) / MySQL/PostgreSQL (Production) dengan indexing pada `user_id`, `group_id`, `created_at`.
   - Penyimpanan media menggunakan Laravel Storage Abstraction (Local/R2/S3 Cloud Storage).

5. **Codebase & Engineering Governance**:
   - Mengikuti struktur single-context `CONTEXT.md` dan pelacakan issue `.scratch/`.
   - Penggunaan TDD (Test-Driven Development) untuk fitur baru dan CI/CD automated test validation.

## Consequences
- Memastikan platform terlindungi dari spam, penyalahgunaan media, dan kebocoran data.
- Meningkatkan kepatuhan terhadap regulasi privasi data.
- Memberikan panduan pengembangan fitur baru yang terstruktur bagi developer.
