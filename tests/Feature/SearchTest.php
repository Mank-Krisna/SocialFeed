<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_model_is_searchable(): void
    {
        $post = Post::factory()->create(['body' => 'Unique search term test']);
        $searchable = $post->toSearchableArray();

        $this->assertArrayHasKey('id', $searchable);
        $this->assertArrayHasKey('body', $searchable);
        $this->assertStringContainsString('Unique search term test', $searchable['body']);
    }

    public function test_user_model_is_searchable(): void
    {
        $user = User::factory()->create(['name' => 'Alice Walker', 'username' => 'alicew']);
        $searchable = $user->toSearchableArray();

        $this->assertArrayHasKey('name', $searchable);
        $this->assertArrayHasKey('username', $searchable);
        $this->assertSame('Alice Walker', $searchable['name']);
        $this->assertSame('alicew', $searchable['username']);
    }

    public function test_group_model_is_searchable(): void
    {
        $user = User::factory()->create();
        $group = Group::create([
            'user_id' => $user->id,
            'name' => 'Laravel Developers',
            'slug' => 'laravel-developers',
            'description' => 'A group for PHP devs',
            'type' => 'public',
        ]);
        $searchable = $group->toSearchableArray();

        $this->assertArrayHasKey('name', $searchable);
        $this->assertArrayHasKey('description', $searchable);
        $this->assertSame('Laravel Developers', $searchable['name']);
    }
}
