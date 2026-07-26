<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UsernameGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsernameGeneratorTest extends TestCase
{
    use RefreshDatabase;

    private UsernameGenerator $generator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generator = $this->app->make(UsernameGenerator::class);
    }

    public function test_generate_from_fullname(): void
    {
        $result = $this->generator->generate('Moses Hirthe');
        $this->assertEquals('moses.hirthe', $result);
    }

    public function test_generate_single_name(): void
    {
        $result = $this->generator->generate('Alice');
        $this->assertEquals('alice', $result);
    }

    public function test_generate_strips_html(): void
    {
        $result = $this->generator->generate('<b>John</b> <i>Doe</i>');
        $this->assertEquals('john.doe', $result);
    }

    public function test_generate_strips_template_code(): void
    {
        $result = $this->generator->generate('Test User', '{{ $suggested[\'username\'] ?? ... }}');
        $this->assertStringNotContainsString('{{', $result);
        $this->assertNotEmpty($result);
    }

    public function test_collision_suffix(): void
    {
        User::factory()->create(['username' => 'alice']);
        $result = $this->generator->generate('Alice');
        $this->assertEquals('alice-2', $result);
    }

    public function test_collision_increments(): void
    {
        User::factory()->create(['username' => 'bob']);
        User::factory()->create(['username' => 'bob-2']);
        $result = $this->generator->generate('Bob');
        $this->assertEquals('bob-3', $result);
    }

    public function test_fallback_on_empty_name(): void
    {
        $result = $this->generator->generate('');
        $this->assertNotEmpty($result);
        $this->assertMatchesRegularExpression('/^[a-z0-9._-]+$/', $result);
    }

    public function test_fallback_on_null_name(): void
    {
        $result = $this->generator->generate(null);
        $this->assertNotEmpty($result);
    }

    public function test_sanitize_only_allowed_chars(): void
    {
        $result = $this->generator->generate('User@Name#Test!');
        $this->assertMatchesRegularExpression('/^[a-z0-9._-]+$/', $result);
    }

    public function test_truncate_long_name(): void
    {
        $long = str_repeat('a', 30) . ' ' . str_repeat('b', 30);
        $result = $this->generator->generate($long);
        $this->assertLessThanOrEqual(24, strlen($result));
    }

    public function test_username_display_accessor_valid(): void
    {
        $user = User::factory()->create(['username' => 'valid_user']);
        $this->assertEquals('valid_user', $user->username_display);
    }

    public function test_username_display_accessor_template_tainted(): void
    {
        $user = User::factory()->create([
            'username' => '{{ $suggested[\'username\'] ?? ... }}',
            'name' => 'Budi Test',
        ]);
        $display = $user->username_display;
        $this->assertStringNotContainsString('{{', $display);
        $this->assertStringNotContainsString('}}', $display);
    }

    public function test_username_display_accessor_null(): void
    {
        $user = User::factory()->create([
            'username' => null,
            'name' => 'Siti Rahma',
        ]);
        $this->assertEquals('siti.rahma', $user->username_display);
    }
}
