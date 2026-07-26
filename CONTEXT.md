# CONTEXT.md — Domain Context & Architecture Glossary

## 1. Domain Vision & Purpose
**SocialFeed** adalah platform media sosial modern berorientasi komunitas (Laravel 11 + Livewire 3 + Tailwind CSS).
Aplikasi ini memfasilitasi interaksi pengguna secara real-time, berbagi konten multimedia (foto & video hingga 100MB), pembuatan grup komunitas, sistem pertemanan, serta manajemen notifikasi.

## 2. Core Domain Glossary (Kamus Istilah Resmi)
- **User**: Pengguna terdaftar dalam platform dengan profil, avatar, dan identitas unik (`@username`).
- **Post**: Entitas konten utama yang dibuat pengguna, dapat berisi teks (max 1000 karakter) dan lampiran multimedia.
- **PostMedia**: Berkas media (foto/video) yang terlampir pada suatu `Post`.
- **Group**: Wadah komunitas publik/privat tempat pengguna dapat bergabung, berdiskusi, dan berbagi konten bertema spesifik.
- **Friendship**: Hubungan koneksi dua arah antar pengguna (status: `pending`, `accepted`, `rejected`).
- **Notification**: Notifikasi event (seperti Suka, Komentar, Permintaan Teman) yang dapat ditandai `read_at`.
- **Moderation Policy**: Aturan dan mekanisme tata kelola konten untuk memastikan lingkungan sosial yang aman dan bebas spam.

## 3. Governance Principles
1. **Content Safety First**: Semua unggahan multimedia dan teks diawasi oleh kebijakan moderasi.
2. **Privacy & Security**: Pengendalian hak akses berjenjang (RBAC: Admin, Group Leader, Member).
3. **High Availability & Speed**: Optimasi pratinjau media, pagination, dan skeleton loading (Phantom UI).
4. **Clean Code & ADR Compliance**: Keputusan arsitektur besar harus didokumentasikan dalam `docs/adr/`.
