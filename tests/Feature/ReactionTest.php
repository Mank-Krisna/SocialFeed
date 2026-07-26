<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_react_to_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        Livewire::test(\App\Livewire\PostItem::class, ['post' => $post])
            ->call('react', 'like');

        $this->assertDatabaseHas('reactions', [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'type' => 'like',
        ]);
    }

    public function test_user_can_change_reaction(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        Reaction::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'type' => 'like',
        ]);

        $this->actingAs($user);

        Livewire::test(\App\Livewire\PostItem::class, ['post' => $post->fresh()])
            ->call('react', 'love');

        $this->assertDatabaseHas('reactions', [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'type' => 'love',
        ]);

        $this->assertDatabaseMissing('reactions', [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'type' => 'like',
        ]);
    }

    public function test_user_can_remove_reaction_by_clicking_same_type(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        Reaction::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'type' => 'like',
        ]);

        $this->actingAs($user);

        Livewire::test(\App\Livewire\PostItem::class, ['post' => $post->fresh()])
            ->call('react', 'like');

        $this->assertDatabaseMissing('reactions', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }

    public function test_reaction_counts_are_correct(): void
    {
        $post = Post::factory()->create();
        
        Reaction::create(['user_id' => User::factory()->create()->id, 'post_id' => $post->id, 'type' => 'like']);
        Reaction::create(['user_id' => User::factory()->create()->id, 'post_id' => $post->id, 'type' => 'love']);
        Reaction::create(['user_id' => User::factory()->create()->id, 'post_id' => $post->id, 'type' => 'like']);

        $counts = $post->fresh()->reaction_counts;

        $this->assertEquals(2, $counts['like']);
        $this->assertEquals(1, $counts['love']);
    }

    public function test_invalid_reaction_type_is_rejected(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        Livewire::test(\App\Livewire\PostItem::class, ['post' => $post])
            ->call('react', 'invalid');

        $this->assertDatabaseMissing('reactions', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }

    public function test_all_six_reaction_types_are_valid(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        foreach (array_keys(Reaction::TYPES) as $type) {
            Livewire::test(\App\Livewire\PostItem::class, ['post' => $post->fresh()])
                ->call('react', $type);

            $this->assertDatabaseHas('reactions', [
                'user_id' => $user->id,
                'post_id' => $post->id,
                'type' => $type,
            ]);

            // Remove for next iteration
            Reaction::where('user_id', $user->id)->where('post_id', $post->id)->delete();
        }
    }
}
