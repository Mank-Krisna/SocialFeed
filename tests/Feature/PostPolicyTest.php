<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_delete_their_own_post(): void
    {
        $owner = User::factory()->create();
        $post  = Post::factory()->create(['user_id' => $owner->id]);

        $this->assertTrue($owner->can('delete', $post));
    }

    public function test_other_user_cannot_delete_post(): void
    {
        $owner  = User::factory()->create();
        $other  = User::factory()->create();
        $post   = Post::factory()->create(['user_id' => $owner->id]);

        $this->assertFalse($other->can('delete', $post));
    }

    public function test_admin_can_delete_any_post(): void
    {
        $owner = User::factory()->create();
        $admin = User::factory()->create(['is_admin' => true]);
        $post  = Post::factory()->create(['user_id' => $owner->id]);

        $this->assertTrue($admin->can('delete', $post));
    }

    public function test_owner_can_update_their_own_post(): void
    {
        $owner = User::factory()->create();
        $post  = Post::factory()->create(['user_id' => $owner->id]);

        $this->assertTrue($owner->can('update', $post));
    }

    public function test_other_user_cannot_update_post(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $post  = Post::factory()->create(['user_id' => $owner->id]);

        $this->assertFalse($other->can('update', $post));
    }
}
