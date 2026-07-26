# Ticket 03: Text Posts & Global Feed

Status: `ready-for-human`

## Description
Build post creation functionality for text status updates and display a real-time updating global feed ordered chronologically.

## Tasks
- [x] Create `posts` table schema and `Post` Eloquent model
- [x] Implement Livewire `CreatePost` component with character limit and validation
- [x] Implement Livewire `Feed` component listing all recent posts
- [x] Implement Post card UI with author avatar, name, timestamp, body text

## Comments
- Implemented `posts` table migration & `Post` model.
- Created `CreatePost` Livewire component with 500-character limit, real-time counter, and live event dispatch (`post-created`).
- Created `Feed` Livewire component for chronological stream rendering.
