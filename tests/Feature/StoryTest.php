<?php

namespace Tests\Feature;

use App\Models\Story;
use App\Models\StoryView;
use App\Models\User;
use App\Models\Friendship;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class StoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_story_section_renders(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/feed');
        $response->assertOk();
    }

    public function test_user_can_create_story_via_livewire(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Storage::fake('public');

        Livewire::test(\App\Livewire\StorySection::class)
            ->set('showUploadForm', true)
            ->set('caption', 'Test story')
            ->set('mediaFile', \Illuminate\Http\UploadedFile::fake()->image('story.jpg'))
            ->call('uploadStory');

        $this->assertDatabaseHas('stories', [
            'user_id' => $user->id,
            'caption' => 'Test story',
        ]);
    }

    public function test_story_expires_after_24_hours(): void
    {
        $user = User::factory()->create();

        $story = Story::create([
            'user_id' => $user->id,
            'media_path' => 'stories/test.jpg',
            'expires_at' => now()->addHours(24),
        ]);

        $this->assertTrue($story->isExpired() === false);

        $expiredStory = Story::create([
            'user_id' => $user->id,
            'media_path' => 'stories/expired.jpg',
            'expires_at' => now()->subHour(),
        ]);

        $this->assertTrue($expiredStory->isExpired());
    }

    public function test_expired_stories_are_not_shown(): void
    {
        $user = User::factory()->create();
        Story::create([
            'user_id' => $user->id,
            'media_path' => 'stories/expired.jpg',
            'expires_at' => now()->subHour(),
        ]);

        $activeStories = Story::active()->where('user_id', $user->id)->get();
        $this->assertCount(0, $activeStories);
    }

    public function test_story_view_is_tracked(): void
    {
        $user = User::factory()->create();
        $viewer = User::factory()->create();

        $story = Story::create([
            'user_id' => $user->id,
            'media_path' => 'stories/test.jpg',
            'expires_at' => now()->addHours(24),
        ]);

        StoryView::create([
            'story_id' => $story->id,
            'user_id' => $viewer->id,
        ]);

        $this->assertDatabaseHas('story_views', [
            'story_id' => $story->id,
            'user_id' => $viewer->id,
        ]);

        $this->assertTrue($story->isViewedBy($viewer));
    }

    public function test_user_can_view_friend_stories(): void
    {
        $user = User::factory()->create();
        $friend = User::factory()->create();

        Friendship::create([
            'sender_id' => $user->id,
            'receiver_id' => $friend->id,
            'status' => 'accepted',
        ]);

        Story::create([
            'user_id' => $friend->id,
            'media_path' => 'stories/friend.jpg',
            'expires_at' => now()->addHours(24),
        ]);

        $this->actingAs($user);

        $stories = Story::active()
            ->whereIn('user_id', [...$user->friendIds(), $user->id])
            ->with(['user', 'views'])
            ->get();

        $this->assertCount(1, $stories);
    }
}
