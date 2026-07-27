<?php

namespace Tests\Feature;

use App\Events\PostLiked;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class BroadcastingTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_liked_event_can_be_instantiated(): void
    {
        $post  = Post::factory()->create();
        $liker = User::factory()->create();

        $event = new PostLiked($post, $liker);

        $this->assertSame($post->id, $event->post->id);
        $this->assertSame($liker->id, $event->liker->id);
        $this->assertSame('post.liked', $event->broadcastAs());
    }

    public function test_post_liked_broadcasts_on_post_owner_channel(): void
    {
        $owner = User::factory()->create();
        $liker = User::factory()->create();
        $post  = Post::factory()->create(['user_id' => $owner->id]);

        $event    = new PostLiked($post, $liker);
        $channels = $event->broadcastOn();

        $this->assertCount(1, $channels);
        $this->assertStringContainsString("user.{$owner->id}", $channels[0]->name);
    }

    public function test_events_are_dispatched_via_event_fake(): void
    {
        Event::fake([PostLiked::class]);

        $owner = User::factory()->create();
        $liker = User::factory()->create();
        $post  = Post::factory()->create(['user_id' => $owner->id]);

        PostLiked::dispatch($post, $liker);

        Event::assertDispatched(PostLiked::class, function ($event) use ($post, $liker) {
            return $event->post->id === $post->id
                && $event->liker->id === $liker->id;
        });
    }
}
