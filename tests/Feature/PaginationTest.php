<?php

namespace Tests\Feature;

use App\Livewire\Feed;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_feed_initial_per_page_matches_config(): void
    {
        $user = User::factory()->create();
        $component = Livewire::actingAs($user)->test(Feed::class);

        $component->assertSet('perPage', (int) config('feed.per_page', 10));
    }

    public function test_feed_returns_correct_count(): void
    {
        $user = User::factory()->create();
        Post::factory()->count(5)->create(['user_id' => $user->id]);

        $component = Livewire::actingAs($user)->test(Feed::class);

        $component->assertSet('perPage', 10);
        $component->assertViewHas('posts', fn ($posts) => $posts->count() === 5);
        $component->assertViewHas('hasMore', false);
    }

    public function test_feed_load_more_increases_per_page(): void
    {
        $user = User::factory()->create();
        Post::factory()->count(15)->create(['user_id' => $user->id]);

        $component = Livewire::actingAs($user)->test(Feed::class);

        $component->assertViewHas('posts', fn ($posts) => $posts->count() === 10);
        $component->assertViewHas('hasMore', true);

        $component->call('loadMore');

        $component->assertSet('perPage', 20);
        $component->assertViewHas('posts', fn ($posts) => $posts->count() === 15);
        $component->assertViewHas('hasMore', false);
    }

    public function test_feed_filter_resets_per_page(): void
    {
        $user = User::factory()->create();
        Post::factory()->count(15)->create(['user_id' => $user->id]);

        $component = Livewire::actingAs($user)->test(Feed::class);

        $component->call('loadMore');
        $component->assertSet('perPage', 20);

        $component->call('setFilter', 'friends');
        $component->assertSet('perPage', (int) config('feed.per_page', 10));
    }

    public function test_feed_filter_friends_returns_correct_count(): void
    {
        $user = User::factory()->create();
        $friend = User::factory()->create();
        $stranger = User::factory()->create();

        Post::factory()->count(3)->create(['user_id' => $user->id]);
        Post::factory()->count(4)->create(['user_id' => $friend->id]);
        Post::factory()->count(5)->create(['user_id' => $stranger->id]);

        // Make them friends
        \App\Models\Friendship::create([
            'sender_id' => $user->id,
            'receiver_id' => $friend->id,
            'status' => 'accepted',
        ]);

        $component = Livewire::actingAs($user)->test(Feed::class);
        $component->call('setFilter', 'friends');

        $component->assertViewHas('posts', fn ($posts) => $posts->count() === 7);
    }

    public function test_feed_end_of_feed_marker_shown_when_no_more(): void
    {
        $user = User::factory()->create();
        Post::factory()->count(3)->create(['user_id' => $user->id]);

        $component = Livewire::actingAs($user)->test(Feed::class);

        $component->assertViewHas('hasMore', false);
        $component->assertSet('perPage', 10);
    }

    public function test_feed_empty_state(): void
    {
        $user = User::factory()->create();

        $component = Livewire::actingAs($user)->test(Feed::class);

        $component->assertViewHas('posts', fn ($posts) => $posts->isEmpty());
        $component->assertViewHas('hasMore', false);
    }
}
