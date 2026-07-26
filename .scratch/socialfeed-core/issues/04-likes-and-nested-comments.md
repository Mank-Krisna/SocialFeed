# Ticket 04: Likes & Nested Comments

Status: `ready-for-human`

## Description
Implement post likes/unlikes and nested comment threads for status posts.

## Tasks
- [x] Create `likes` and `comments` table schema and models
- [x] Implement toggle like action in Livewire with instant UI updates & count
- [x] Implement nested comments component with support for parent_id replies
- [x] Add post detail route `/u/{user?}` and view

## Comments
- Implemented `likes` & `comments` tables and models with `parent_id` foreign key.
- Created `PostItem` Livewire component for instant like toggle & comments expander.
- Created `CommentSection` Livewire component supporting top-level comments & inline nested replies.
- Verified with 30 passing unit and feature tests (93 assertions).
