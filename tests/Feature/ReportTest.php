<?php

namespace Tests\Feature;

use App\Models\Report;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_report_for_post(): void
    {
        $reporter = User::factory()->create();
        $post     = Post::factory()->create();

        $report = Report::create([
            'user_id'         => $reporter->id,
            'reportable_type' => Post::class,
            'reportable_id'   => $post->id,
            'reason'          => 'spam',
            'status'          => Report::STATUS_PENDING,
        ]);

        $this->assertDatabaseHas('reports', [
            'user_id'         => $reporter->id,
            'reportable_type' => Post::class,
            'reportable_id'   => $post->id,
            'reason'          => 'spam',
            'status'          => 'pending',
        ]);
    }

    public function test_admin_can_resolve_report(): void
    {
        $admin    = User::factory()->create(['is_admin' => true]);
        $reporter = User::factory()->create();
        $post     = Post::factory()->create();

        $report = Report::create([
            'user_id'         => $reporter->id,
            'reportable_type' => Post::class,
            'reportable_id'   => $post->id,
            'reason'          => 'harassment',
            'status'          => Report::STATUS_PENDING,
        ]);

        $report->update([
            'status'      => Report::STATUS_RESOLVED,
            'resolved_by' => $admin->id,
            'resolved_at' => now(),
        ]);

        $this->assertDatabaseHas('reports', [
            'id'     => $report->id,
            'status' => 'resolved',
        ]);
    }

    public function test_admin_can_dismiss_report(): void
    {
        $admin    = User::factory()->create(['is_admin' => true]);
        $reporter = User::factory()->create();
        $post     = Post::factory()->create();

        $report = Report::create([
            'user_id'         => $reporter->id,
            'reportable_type' => Post::class,
            'reportable_id'   => $post->id,
            'reason'          => 'spam',
            'status'          => Report::STATUS_PENDING,
        ]);

        $report->update([
            'status'      => Report::STATUS_DISMISSED,
            'resolved_by' => $admin->id,
            'resolved_at' => now(),
        ]);

        $this->assertDatabaseHas('reports', [
            'id'     => $report->id,
            'status' => 'dismissed',
        ]);
    }

    public function test_report_has_correct_reason_labels(): void
    {
        $this->assertArrayHasKey('spam', Report::REASONS);
        $this->assertArrayHasKey('harassment', Report::REASONS);
        $this->assertArrayHasKey('hate_speech', Report::REASONS);
        $this->assertArrayHasKey('misinformation', Report::REASONS);
        $this->assertArrayHasKey('violence', Report::REASONS);
        $this->assertArrayHasKey('nudity', Report::REASONS);
        $this->assertArrayHasKey('other', Report::REASONS);
    }

    public function test_pending_scope_filters_correctly(): void
    {
        $post = Post::factory()->create();
        $user = User::factory()->create();

        Report::create([
            'user_id' => $user->id,
            'reportable_type' => Post::class,
            'reportable_id'   => $post->id,
            'reason' => 'spam',
            'status' => 'pending',
        ]);

        Report::create([
            'user_id' => $user->id,
            'reportable_type' => Post::class,
            'reportable_id'   => $post->id,
            'reason' => 'violence',
            'status' => 'resolved',
        ]);

        $this->assertCount(1, Report::pending()->get());
    }
}
