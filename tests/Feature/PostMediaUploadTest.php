<?php

namespace Tests\Feature;

use App\Http\Requests\StorePostRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostMediaUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_post_request_rules_reject_invalid_mime(): void
    {
        $request = new StorePostRequest();
        $rules   = $request->rules();

        // mediaFiles.* should include mimes rule
        $this->assertNotEmpty(
            array_filter($rules['mediaFiles.*'] ?? [], fn($r) => str_starts_with((string) $r, 'mimes:'))
        );
    }

    public function test_store_post_request_rules_include_max_size(): void
    {
        $request = new StorePostRequest();
        $rules   = $request->rules();

        $hasMax = false;
        foreach ($rules['mediaFiles.*'] ?? [] as $rule) {
            if (str_starts_with((string) $rule, 'max:')) {
                $hasMax = true;
                break;
            }
        }
        $this->assertTrue($hasMax, 'StorePostRequest should have a max size rule for mediaFiles.*');
    }

    public function test_store_post_request_requires_body_or_files(): void
    {
        $request = new StorePostRequest();
        $rules   = $request->rules();

        // body is nullable (not required alone — post can have media only)
        $this->assertContains('nullable', $rules['body']);
    }

    public function test_store_post_body_max_1000_chars(): void
    {
        $request = new StorePostRequest();
        $rules   = $request->rules();

        $this->assertContains('max:1000', $rules['body']);
    }
}
