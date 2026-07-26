<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

// Landing Page
Route::view('/', 'welcome');

// Feed (replaces /dashboard)
Route::view('/feed', 'feed')
    ->middleware(['auth'])
    ->name('feed');

// Keep /dashboard as redirect to /feed
Route::redirect('/dashboard', '/feed')->name('dashboard');

// Profile edit
Route::view('/profile/edit', 'profile')
    ->middleware(['auth'])
    ->name('profile.edit');

// Keep /profile route pointing to edit
Route::view('/profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Public profile /profile/{user}
Route::get('/profile/{user?}', [UserProfileController::class, 'show'])
    ->middleware(['auth'])
    ->name('profile.show');

// Old /u/{user} redirect
Route::redirect('/u/{user}', '/profile/{user}');

// Post detail /posts/{id}
Route::get('/posts/{post}', [PostController::class, 'show'])
    ->middleware(['auth'])
    ->name('posts.show');

// Groups V3
Route::view('/groups', 'groups')
    ->middleware(['auth'])
    ->name('groups');

Route::get('/groups/{group:slug}', [GroupController::class, 'show'])
    ->middleware(['auth'])
    ->name('groups.show');

// Search V3
Route::view('/search', 'search')
    ->middleware(['auth'])
    ->name('search');

// Friends
Route::view('/friends', 'friends')
    ->middleware(['auth'])
    ->name('friends');

// Notifications
Route::view('/notifications', 'notifications')
    ->middleware(['auth'])
    ->name('notifications');

// Messages
Route::view('/messages', 'messages')
    ->middleware(['auth'])
    ->name('messages');

// Admin Dashboard
Route::view('/admin', 'admin')
    ->middleware(['auth'])
    ->name('admin');

require __DIR__.'/auth.php';
