<?php

use App\Http\Controllers\BookmarksController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HashtagController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SettingsController;
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

// Search (Livewire handles search, controller not needed)
Route::view('/search', 'search')
    ->middleware(['auth'])
    ->name('search');

// Friends
Route::view('/friends', 'friends')
    ->middleware(['auth'])
    ->name('friends');

// Admin Dashboard
Route::view('/admin', 'admin')
    ->middleware(['auth', 'can:admin'])
    ->name('admin');

// Messages
Route::view('/messages', 'messages')
    ->middleware(['auth'])
    ->name('messages');
Route::get('/messages/{conversation}', [ConversationController::class, 'show'])
    ->middleware(['auth'])
    ->name('messages.show');
Route::post('/messages/{conversation}/send', [ConversationController::class, 'send'])
    ->middleware(['auth'])
    ->name('messages.send');
Route::post('/messages/start', [MessageController::class, 'store'])
    ->middleware(['auth'])
    ->name('messages.store');

// Notifications
Route::get('/notifications', [NotificationController::class, 'index'])
    ->middleware(['auth'])
    ->name('notifications');
Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])
    ->middleware(['auth'])
    ->name('notifications.markAllRead');
Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('notifications.destroy');

// Bookmarks
Route::get('/bookmarks', [BookmarksController::class, 'index'])
    ->middleware(['auth'])
    ->name('bookmarks');

// Hashtags
Route::get('/explore', [HashtagController::class, 'index'])
    ->middleware(['auth'])
    ->name('hashtags.index');
Route::get('/tags/{tag}', [HashtagController::class, 'show'])
    ->middleware(['auth'])
    ->name('hashtags.show');

// Settings
Route::get('/settings', [SettingsController::class, 'index'])
    ->middleware(['auth'])
    ->name('settings');
Route::put('/settings', [SettingsController::class, 'update'])
    ->middleware(['auth'])
    ->name('settings.update');
Route::put('/settings/password', [SettingsController::class, 'updatePassword'])
    ->middleware(['auth'])
    ->name('settings.password');
Route::delete('/settings', [SettingsController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('settings.destroy');

require __DIR__.'/auth.php';
