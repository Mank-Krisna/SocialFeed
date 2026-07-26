<?php

namespace Tests\Feature;

use App\Livewire\GroupDetail;
use App\Livewire\GroupsManager;
use App\Livewire\SearchCenter;
use App\Models\Group;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SocialFeedV3Test extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_a_new_group(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(GroupsManager::class)
            ->set('name', 'Grup Programmer Laravel')
            ->set('description', 'Komunitas koding laravel')
            ->set('type', 'public')
            ->call('createGroup');

        $group = Group::where('name', 'Grup Programmer Laravel')->first();
        $this->assertNotNull($group);
        $this->assertEquals('grup-programmer-laravel', $group->slug);
        $this->assertTrue($group->isMember($user));
        $this->assertTrue($group->isAdmin($user));
    }

    public function test_user_can_join_and_leave_public_group(): void
    {
        $creator = User::factory()->create();
        $user = User::factory()->create();

        $group = Group::create([
            'user_id' => $creator->id,
            'name' => 'Klub Sepak Bola',
            'slug' => 'klub-sepak-bola',
            'type' => 'public',
        ]);
        $group->members()->attach($creator->id, ['role' => 'admin']);

        // User joins group
        Livewire::actingAs($user)
            ->test(GroupsManager::class)
            ->call('joinGroup', $group->id);

        $this->assertTrue($group->fresh()->isMember($user));

        // User leaves group
        Livewire::actingAs($user)
            ->test(GroupsManager::class)
            ->call('leaveGroup', $group->id);

        $this->assertFalse($group->fresh()->isMember($user));
    }

    public function test_member_can_post_in_group(): void
    {
        $creator = User::factory()->create();
        $group = Group::create([
            'user_id' => $creator->id,
            'name' => 'Desain Grafis',
            'slug' => 'desain-grafis',
            'type' => 'public',
        ]);
        $group->members()->attach($creator->id, ['role' => 'admin']);

        Livewire::actingAs($creator)
            ->test(GroupDetail::class, ['group' => $group])
            ->assertSee('Desain Grafis');

        Post::create([
            'user_id' => $creator->id,
            'group_id' => $group->id,
            'body' => 'Postingan pertama di grup!',
        ]);

        $this->assertDatabaseHas('posts', [
            'group_id' => $group->id,
            'body' => 'Postingan pertama di grup!',
        ]);
    }

    public function test_search_returns_matching_users_and_groups(): void
    {
        $user = User::factory()->create(['name' => 'Budi Sudarsono', 'username' => 'budisudar']);
        $group = Group::create([
            'user_id' => $user->id,
            'name' => 'Budi Fan Club',
            'slug' => 'budi-fan-club',
            'type' => 'public',
        ]);

        $searcher = User::factory()->create();

        Livewire::actingAs($searcher)
            ->test(SearchCenter::class)
            ->set('query', 'Budi')
            ->assertSee('Budi Sudarsono')
            ->assertSee('Budi Fan Club');
    }

    public function test_groups_and_search_routes_accessible(): void
    {
        $user = User::factory()->create();
        $group = Group::create([
            'user_id' => $user->id,
            'name' => 'Komunitas Komputer',
            'slug' => 'komunitas-komputer',
            'type' => 'public',
        ]);

        $responseGroupIndex = $this->actingAs($user)->get(route('groups'));
        $responseGroupIndex->assertStatus(200);

        $responseGroupShow = $this->actingAs($user)->get(route('groups.show', $group->slug));
        $responseGroupShow->assertStatus(200)->assertSee('Komunitas Komputer');

        $responseSearch = $this->actingAs($user)->get(route('search'));
        $responseSearch->assertStatus(200);
    }
}
