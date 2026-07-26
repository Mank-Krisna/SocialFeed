# Alur Aplikasi — SocialFeed V4

## 1. Membuat Postingan

**Component:** `CreatePost`
**Route:** `/feed` (form di sidebar)
**Trigger:** User klik "Posting"

**Langkah:**
1. User mengisi body (max 500 chars), upload media (max 10 file, 100MB).
2. Validasi di `CreatePost::submit()`:
   - Body: `nullable|string|max:500`
   - Media: `array|max:10`, tiap file: `file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,webm,mkv|max:102400`
3. Cek grup: jika `groupId` ada, pastikan user member grup itu.
4. **DB Write:** `Post::create()` → tabel `posts`.
5. **Media:** tiap file `$file->store('posts', 'public')` → `PostMedia::create()` di tabel `post_media`.
6. **Hashtag detection:** regex `/#([\w]+)/u` → `Hashtag::firstOrCreate()` → sync pivot `hashtag_post`.
7. **Mention detection:** regex `/@([\w\-\.]+)/u` → cari user berdasarkan username → buat `Notification::create()` tipe `'mention'` di tabel `notifications`.
8. **UI Update:** `$this->dispatch('post-created')` → `Feed` component refresh feed.

```
User → CreatePost.submit()
  → Validasi (body, media, grup)
  → INSERT posts
  → FOREACH media: store() → INSERT post_media
  → FOREACH hashtag: firstOrCreate() → sync hashtag_post
  → FOREACH mention: cari user → INSERT notifications (type: mention)
  → dispatch('post-created') → Feed refresh
```

---

## 2. Repost (Bagikan Ulang)

**Component:** `RepostButton`
**Trigger:** User klik "Bagikan"

**Langkah:**
1. Cek apakah post asli ada.
2. **DB Write:** `Post::create()` dengan `parent_id` mengacu post asli.
3. **Notifikasi:** `NotificationService::postReposted(post, sender)` → INSERT `notifications` tipe `'repost'`.
4. **UI Update:** `dispatch('post-created')` → Feed refresh, tombol repost toggle.

```
User → RepostButton.repost()
  → INSERT posts (parent_id = original_post_id)
  → NotificationService.postReposted()
    → INSERT notifications (type: repost)
  → dispatch('post-created') → Feed refresh
```

---

## 3. Voting Poll

**Component:** `PollDisplay`
**Trigger:** User memilih opsi poll

**Langkah:**
1. Cek: user login? (`Auth::check()`), sudah vote? (`$this->hasVoted`), poll belum expired? (`ends_at->isPast()`).
2. Validasi opsi milik poll yang benar: `PollOption::where('poll_id', $this->poll->id)->find($optionId)`.
3. **DB Write:** `PollVote::create()` → tabel `poll_votes` (unique constraint user+opsi).
4. **UI Update:** `$this->hasVoted = true` → komponen render hasil (persentase tiap opsi via `withCount('votes')`).

```
User → PollDisplay.vote(optionId)
  → Cek sudah login? belum expired? belum vote?
  → Cek option milik poll yang benar?
  → INSERT poll_votes (unique: user+option)
  → Render hasil: options withCount(votes), totalVotes
```

---

## 4. Bookmark (Simpan Postingan)

**Component:** `BookmarkButton`
**Trigger:** User klik ikon bookmark

**Langkah:**
1. **Cek status:** apakah sudah pernah di-bookmark user ini?
2. Jika sudah: **DB Write:** `SavedPost::where(...)->delete()`.
3. Jika belum: **DB Write:** `SavedPost::create()`.
4. **UI Update:** toggle icon bookmark (tersimpan / tidak).

```
User → BookmarkButton.toggle()
  → Cek SavedPost exists?
  → Ya: DELETE saved_posts (unbookmark)
  → Tidak: INSERT saved_posts (bookmark)
  → Toggle icon UI
```

---

## 5. Hashtag & Mention

**Component:** `CreatePost` + `SearchCenter`
**Trigger:** User mengetik `#hashtag` atau `@username`

**Proses saat buat post (CreatePost):**
1. Regex `/#([\w]+)/u` scan body post → array hashtag unik.
2. Masing-masing `Hashtag::firstOrCreate(['name' => strtolower($tag)])`.
3. `$post->hashtags()->sync($tagIds)` → pivot `hashtag_post`.
4. Regex `/@([\w\-\.]+)/u` scan body → array username unik.
5. Masing-masing `User::where('username', $username)->first()`.
6. Jika ditemukan dan bukan diri sendiri: `Notification::create()` tipe `'mention'`.

**Pencarian (SearchCenter):**
- User search: `where('name', 'like', "%{q}%")->orWhere('username', 'like', "%{q}%")`
- Group search: cari grup by name
- Hashtag search: cari hashtag → tampilkan post terkait

```
Post dibuat → regex #hashtag
  → firstOrCreate hashtags
  → sync pivot hashtag_post
  → Post muncul di halaman pencarian hashtag

Post dibuat → regex @username
  → cari user by username
  → INSERT notifications (type: mention)
  → User dapat notifikasi mention
```

---

## 6. Direct Message

**Component:** `Messenger`
**Route:** `/messages`

**Alur Kirim Pesan:**
1. User buka percakapan atau cari user via `startNewFromSearch()`.
2. Jika belum ada percakapan: `Conversation::create()` + attach kedua user via pivot `conversation_user`.
3. User mengetik pesan → `Messenger::sendMessage()`.
4. **Validasi:** `body: required|string|max:2000`.
5. **Cek akses:** pastikan user adalah participant percakapan.
6. **DB Write:** `Message::create()` → tabel `messages`.
7. **Broadcast:** `broadcast(new MessageSent(...))` ke channel pusher — fallback ke `wire:poll`.
8. **Notifikasi:** INSERT `notifications` tipe `'message'` untuk penerima.
9. **UI Update:** load ulang messages + conversations, tandai sudah dibaca.

```
User → Messenger.sendMessage()
  → Validasi body (required, max 2000)
  → Cek akses percakapan
  → INSERT messages
  → broadcast (pusher) / fallback polling
  → INSERT notifications (type: message)
  → loadMessages(), loadConversations(), markAsRead()
  → UI update real-time
```

**Alur Baca Pesan:**
1. `openConversation()` → `loadMessages()` + `markAsRead()`.
2. `markAsRead()` update `last_read_at` di pivot `conversation_user`.
3. `unreadMessagesCount()` hitung pesan setelah `last_read_at`.

---

## 7. Upload Story

**Component:** `StorySection`
**Trigger:** User klik "Tambah Cerita"

**Langkah:**
1. User upload gambar (`required|image|max:10240`).
2. **Validasi:** `mediaFile: required|image|max:10240` (10MB).
3. **DB Write:** file `$this->mediaFile->store('stories', 'public')` → `Story::create()` dengan `expires_at = now()->addHours(24)`.
4. **Relasi:** `user_id` → `users.id`.
5. **UI Update:** reload stories list, muncul di urutan teratas.

**Alur Menonton Story:**
1. `StorySection::loadStories()` ambil semua story aktif dari teman + diri sendiri.
2. Dikelompokkan per user, grupkan → tampilkan avatar dengan indikator `hasUnviewed`.
3. `openStory()` → `StoryView::firstOrCreate()` (unique: story+user).
4. Navigasi prev/next story, auto close setelah story terakhir.

```
Upload:
  User → StorySection.uploadStory()
    → Validasi (image, max 10MB)
    → store('stories', 'public')
    → INSERT stories (expires_at = +24h)
    → Load ulang daftar story

Tonton:
  User klik story
    → StoryView::firstOrCreate() (unique: story_id, user_id)
    → Tampilkan media
    → Next/Prev/Close navigation
```

---

## 8. Moderasi Admin

**Component:** `AdminDashboard`
**Route:** `/admin`
**Akses:** `isAdmin()` → jika bukan admin, `abort(403)`.

**Tab Overview:**
- Menampilkan statistik: total users, posts, groups, active stories.
- 5 user terbaru.

**Tab Users:**
- Search user by name/email → paginate (10/userPage).
- "Hapus User" → konfirmasi → `$user->posts()->delete()` + `$user->delete()`.
- Admin tidak bisa hapus diri sendiri: `if ($user->id !== Auth::id())`.

**Tab Posts:**
- Search post by body → paginate (10/postsPage).
- "Hapus Post" → konfirmasi → `Post::find($id)?->delete()` (soft delete).

```
Admin → /admin
  → isAdmin() check → abort(403) jika bukan

Tab Overview:
  → COUNT users, posts, groups, active stories
  → Tampilkan 5 user terbaru

Tab Users:
  → SEARCH users (name, email)
  → DELETE user + cascade hapus posts
  → Prevent self-delete

Tab Posts:
  → SEARCH posts (body)
  → DELETE post (soft delete)

UI Update:
  → dispatch('notify') → toast success
  → Pagination refresh
```

---

## Ringkasan Alur Data

```
[User Action] → [Livewire Component] → [Validasi] → [DB Write] → [Notifikasi] → [UI Update]

Buat Post     → CreatePost           → body, media   → posts + post_media  → mention notif    → Feed refresh
Repost        → RepostButton         → parent_id     → posts               → repost notif      → Feed refresh
Vote Poll     → PollDisplay          → auth, expiry  → poll_votes          → -                 → Result chart
Bookmark      → BookmarkButton       → -             → saved_posts         → -                 → Icon toggle
DM            → Messenger            → body max 2k   → messages            → message notif     → Chat bubble
Story Upload  → StorySection         → image max 10MB→ stories             → -                 → Story list
Moderasi      → AdminDashboard       → isAdmin()     → soft delete user/post → Toast success   → Table refresh
```
