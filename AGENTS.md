## Agent skills

### Issue tracker

Issues are tracked as local markdown files under `.scratch/`. See `docs/agents/issue-tracker.md`.

### Triage labels

Default five-role vocabulary: `needs-triage`, `needs-info`, `ready-for-agent`, `ready-for-human`, `wontfix`. See `docs/agents/triage-labels.md`.

### Domain docs

Single-context layout — one `CONTEXT.md` + `docs/adr/` at the repo root. See `docs/agents/domain.md`.

### Livewire 3 & PHP Upload Rules

- **Single Root Element**: Every Livewire 3 Blade template must have exactly **1 root HTML element** (e.g. `<div>...</div>`). Never leave adjacent top-level elements.
- **Media Upload Previews**: Always check `$file->isPreviewable()` before calling `$file->temporaryUrl()` on uploaded files to prevent `FileNotPreviewableException` on videos.
- **Upload Normalization**: Always normalize single vs array file uploads using `$files = is_array($file) ? $file : [$file];`.
- **PHP Upload Limits**: For file upload failures ("The mediaFiles failed to upload"), check `upload_max_filesize` and `post_max_size` in `php.ini` and `config/livewire.php` (`temporary_file_upload.rules`).

