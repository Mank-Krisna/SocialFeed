<?php

namespace Tests\Feature;

use App\Livewire\CreatePost;
use App\Livewire\FriendsManager;
use App\Livewire\NotificationsCenter;
use App\Livewire\PostItem;
use App\Models\Friendship;
use App\Models\Notification;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class SocialFeedV2Test extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_post_with_attached_images(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $image = UploadedFile::fake()->image('photo.jpg');

        Livewire::actingAs($user)
            ->test(CreatePost::class)
            ->set('body', 'Postingan dengan foto!')
            ->set('mediaFiles', [$image])
            ->call('submit')
            ->assertDispatched('post-created');

        $post = Post::where('user_id', $user->id)->first();
        $this->assertNotNull($post);
        $this->assertCount(1, $post->media);
        $this->assertEquals('image', $post->media->first()->type);
        Storage::disk('public')->assertExists($post->media->first()->file_path);
    }

    public function test_friendship_uses_sender_receiver_columns(): void
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        // Send friend request using PRD column names
        Livewire::actingAs($sender)
            ->test(FriendsManager::class)
            ->call('sendFriendRequest', $receiver->id);

        $friendship = Friendship::where('sender_id', $sender->id)
            ->where('receiver_id', $receiver->id)
            ->first();

        $this->assertNotNull($friendship);
        $this->assertEquals('pending', $friendship->status);
    }

    public function test_user_can_send_and_accept_friend_request(): void
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        // Sender sends request
        Livewire::actingAs($sender)
            ->test(FriendsManager::class)
            ->call('sendFriendRequest', $receiver->id);

        $friendship = Friendship::where('sender_id', $sender->id)
            ->where('receiver_id', $receiver->id)
            ->first();

        $this->assertNotNull($friendship);
        $this->assertEquals('pending', $friendship->status);

        // Notification created for receiver
        $this->assertDatabaseHas('notifications', [
            'user_id' => $receiver->id,
            'sender_id' => $sender->id,
            'type' => 'friend_request',
        ]);

        // Receiver accepts
        Livewire::actingAs($receiver)
            ->test(FriendsManager::class)
            ->call('acceptFriendRequest', $friendship->id);

        $this->assertEquals('accepted', $friendship->fresh()->status);
    }

    public function test_user_can_unfriend(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $friendship = Friendship::create([
            'sender_id' => $user1->id,
            'receiver_id' => $user2->id,
            'status' => 'accepted',
        ]);

        Livewire::actingAs($user1)
            ->test(FriendsManager::class)
            ->call('unfriend', $friendship->id);

        $this->assertDatabaseMissing('friendships', ['id' => $friendship->id]);
    }

    public function test_like_triggers_notification_with_json_data(): void
    {
        $author = User::factory()->create();
        $liker = User::factory()->create();
        $post = Post::create(['user_id' => $author->id, 'body' => 'Post by author']);

        Livewire::actingAs($liker)
            ->test(PostItem::class, ['post' => $post])
            ->call('react', 'like');

        $notif = Notification::where('user_id', $author->id)
            ->where('sender_id', $liker->id)
            ->where('type', 'reaction')
            ->first();

        $this->assertNotNull($notif);
        $this->assertIsArray($notif->data);
        $this->assertArrayHasKey('message', $notif->data);
    }

    public function test_user_can_mark_notification_as_read(): void
    {
        $user = User::factory()->create();
        $sender = User::factory()->create();
        $notif = Notification::create([
            'user_id' => $user->id,
            'sender_id' => $sender->id,
            'type' => 'like',
            'data' => ['message' => 'menyukai postingan Anda.'],
        ]);

        Livewire::actingAs($user)
            ->test(NotificationsCenter::class)
            ->call('markAsRead', $notif->id);

        $this->assertNotNull($notif->fresh()->read_at);
    }

    public function test_post_soft_delete_works(): void
    {
        $user = User::factory()->create();
        $post = Post::create(['user_id' => $user->id, 'body' => 'Test post']);

        Livewire::actingAs($user)
            ->test(PostItem::class, ['post' => $post])
            ->call('deletePost');

        // Post should be soft deleted, not hard deleted
        $this->assertSoftDeleted('posts', ['id' => $post->id]);
    }

    public function test_feed_filter_by_friends_works(): void
    {
        $user = User::factory()->create();
        $friend = User::factory()->create();
        $stranger = User::factory()->create();

        // Create friendship
        Friendship::create([
            'sender_id' => $user->id,
            'receiver_id' => $friend->id,
            'status' => 'accepted',
        ]);

        Post::create(['user_id' => $friend->id, 'body' => 'Friend post']);
        Post::create(['user_id' => $stranger->id, 'body' => 'Stranger post']);

        $friendIds = $user->friendIds();
        $this->assertContains($friend->id, $friendIds);
        $this->assertNotContains($stranger->id, $friendIds);
    }

    public function test_posts_show_route_accessible(): void
    {
        $user = User::factory()->create();
        $post = Post::create(['user_id' => $user->id, 'body' => 'Detail post']);

        $response = $this->actingAs($user)->get(route('posts.show', $post->id));
        $response->assertStatus(200);
        $response->assertSee('Detail post');
    }

    public function test_profile_show_route_accessible_by_username(): void
    {
        $user = User::factory()->create(['username' => 'testuser']);
        $viewer = User::factory()->create();

        $response = $this->actingAs($viewer)->get(route('profile.show', 'testuser'));
        $response->assertStatus(200);
    }

    public function test_feed_route_redirects_from_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertRedirect('/feed');
    }
}
