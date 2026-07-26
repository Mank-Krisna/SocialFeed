# Ticket 02: Authentication & Profile Management

Status: `ready-for-human`

## Description
Implement user registration, login, logout, and profile customization pages based on `design/landing_login/code.html` and `design/user_profile/code.html`.

## Tasks
- [x] User schema update: bio, avatar, cover_photo, username
- [x] Implement Landing / Login page UI and Auth flow
- [x] Implement Profile view page (`/u/{user?}`)
- [x] Implement Profile edit component (`/profile`) for bio, avatar, and cover photo updates

## Comments
- Added `username`, `bio`, `avatar`, `cover_photo` columns to users table and updated User model.
- Storage symlink configured via `storage:link`.
- Implemented file upload handling for avatar & cover photo in Livewire.
- Built public profile view at `/u/{user?}` showcasing user banner, avatar, bio, and user's posts.
