# Feature Spec: SocialFeed Core (V1 - MVP)

## Overview
SocialFeed core encompasses the foundation of the social media application: Laravel setup, authentication, user profiles, text post creation, global feed, likes, and nested comments.

## Target Scope (V1)
- Laravel 11 + Livewire 3 + Blade stack initialization
- Breeze authentication (Login, Register, Logout)
- User Profile management (Bio, avatar upload, cover photo upload)
- Status posting (text-only for V1 MVP)
- Global chronological feed displaying all posts
- Like / Unlike functionality for posts
- Post comments with nested replies support

## Technical Specifications
- **Design system:** Modern minimalism using Inter font, `#0058bc` primary color, 3-column layout (Navbar, Left Sidebar, Main Feed, Right Sidebar).
- **Database tables:** `users`, `posts`, `likes`, `comments`.
- **Livewire components:** Feed component, CreatePost component, PostItem component, CommentSection component, ProfileEdit component.
