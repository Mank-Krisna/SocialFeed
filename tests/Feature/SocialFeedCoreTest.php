<?php

namespace Tests\Feature;

use App\Livewire\CommentSection;
use App\Livewire\CreatePost;
use App\Livewire\Feed;
use App\Livewire\PostItem;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SocialFeedCoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_a_text_post(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(CreatePost::class)
            ->set('body', 'Halo dunia! Ini postingan pertamaku di SocialFeed.')
            ->call('submit')
            ->assertDispatched('post-created');

        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'body' => 'Halo dunia! Ini postingan pertamaku di SocialFeed.',
        ]);
    }

    public function test_feed_displays_created_posts(): void
    {
        $user = User::factory()->create();
        $post = Post::create([
            'user_id' => $user->id,
            'body' => 'Postingan di feed global',
        ]);

        Livewire::actingAs($user)
            ->test(Feed::class)
            ->assertSee('Postingan di feed global');
    }

    public function test_user_can_like_and_unlike_a_post(): void
    {
        $user = User::factory()->create();
        $post = Post::create([
            'user_id' => $user->id,
            'body' => 'Testing like feature',
        ]);

        Livewire::actingAs($user)
            ->test(PostItem::class, ['post' => $post])
            ->call('toggleLike')
            ->assertSet('isLiked', true)
            ->assertSet('likesCount', 1);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        // Toggle again to unlike
        Livewire::actingAs($user)
            ->test(PostItem::class, ['post' => $post])
            ->call('toggleLike')
            ->assertSet('isLiked', false)
            ->assertSet('likesCount', 0);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }

    public function test_user_can_add_comment_and_nested_reply(): void
    {
        $user = User::factory()->create();
        $post = Post::create([
            'user_id' => $user->id,
            'body' => 'Testing comment feature',
        ]);

        // Add top level comment
        Livewire::actingAs($user)
            ->test(CommentSection::class, ['post' => $post])
            ->set('body', 'Komentar pertama!')
            ->call('submitComment')
            ->assertDispatched('comment-added');

        $comment = Comment::where('post_id', $post->id)->first();
        $this->assertNotNull($comment);
        $this->assertEquals('Komentar pertama!', $comment->body);
        $this->assertNull($comment->parent_id);

        // Add nested reply
        Livewire::actingAs($user)
            ->test(CommentSection::class, ['post' => $post])
            ->set('replyBody', 'Balasan untuk komentar pertama!')
            ->call('submitReply', $comment->id)
            ->assertDispatched('comment-added');

        $reply = Comment::where('parent_id', $comment->id)->first();
        $this->assertNotNull($reply);
        $this->assertEquals('Balasan untuk komentar pertama!', $reply->body);
    }
}
