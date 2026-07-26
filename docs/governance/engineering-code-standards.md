# 💻 Engineering Governance, ADR, Triage & Development Workflow

## 1. Tata Kelola Repositori & Istilah Domain
- Dokumen domain utama berada di `CONTEXT.md` pada root repositori. Semua pengembang wajib menggunakan glosarium resmi yang telah ditentukan.
- Keputusan arsitektur besar wajib didokumentasikan di `docs/adr/000X-*.md`.

## 2. Issue Tracking & Label Triage
- Masalah/task dilacak di folder `.scratch/` dalam format markdown.
- Menggunakan 5 label standar triage:
  - `needs-triage`
  - `needs-info`
  - `ready-for-agent`
  - `ready-for-human`
  - `wontfix`

## 3. Standar Pengujian & Verifikasi
- Setiap perbaikan bug atau fitur baru wajib menyertakan unit test / integration test.
- Menjalankan `php artisan view:clear` dan `php artisan test` sebelum meletakkan kode ke cabang produksi.
