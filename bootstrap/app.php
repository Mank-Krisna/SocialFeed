<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })
    ->booted(function (): void {
        // Rate limiters for SocialFeed actions
        RateLimiter::for('post-create', function (Request $request) {
            return Limit::perMinute(config('feed.rate_post_per_minute', 10))
                ->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('comment-create', function (Request $request) {
            return Limit::perMinute(config('feed.rate_comment_per_minute', 30))
                ->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('like-toggle', function (Request $request) {
            return Limit::perMinute(config('feed.rate_like_per_minute', 60))
                ->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('friend-request', function (Request $request) {
            return Limit::perMinute(config('feed.rate_friend_per_minute', 5))
                ->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('media-upload', function (Request $request) {
            return Limit::perMinute(config('feed.rate_upload_per_minute', 5))
                ->by($request->user()?->id ?: $request->ip());
        });
    })
    ->create();
