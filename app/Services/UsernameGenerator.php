<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;

class UsernameGenerator
{
    private const MAX_LENGTH = 24;

    private const FALLBACK_ADJECTIVES = [
        'cerah', 'senyum', 'biru', 'hangat', 'segar',
        'riang', 'tenang', 'asri', 'megah', 'elok',
        'ramah', 'damai', 'luhur', 'murni', 'setia',
    ];

    private const FALLBACK_NOUNS = [
        'rimba', 'kopi', 'bintang', 'ombak', 'sawah',
        'pelangi', 'mentari', 'embun', 'kuncup', 'purnama',
        'sagara', 'wulan', 'lintang', 'guntur', 'maya',
    ];

    public function generate(?string $fullName = null, ?string $fallback = null): string
    {
        $slug = $this->fromName($fullName);

        if ($slug === '') {
            $slug = $this->fromFallback($fallback);
        }

        $slug = $this->sanitize($slug);

        if ($slug === '') {
            $slug = $this->randomWordFallback();
        }

        $slug = $this->ensureUnique($slug);

        return $slug;
    }

    private function fromName(?string $fullName): string
    {
        if ($fullName === null || trim($fullName) === '') {
            return '';
        }

        $name = strip_tags($fullName);
        $name = html_entity_decode($name, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $name = mb_strtolower(trim($name));

        // Strip wrapper tags like <WebsiteContent_...>
        $name = preg_replace('/<WebsiteContent_[^>]+>|<\/WebsiteContent_[^>]+>/', '', $name);
        $name = trim($name);

        if ($name === '') {
            return '';
        }

        // Replace non-alphanumeric (except '-') with space
        $name = preg_replace('/[^a-z0-9\-]/', ' ', $name);
        $name = preg_replace('/\s+/', ' ', $name);
        $name = trim($name);

        if ($name === '') {
            return '';
        }

        $parts = explode(' ', $name);
        $parts = array_values(array_filter($parts, fn ($p) => $p !== ''));

        if (count($parts) === 0) {
            return '';
        }

        $first = $parts[0];
        $last = count($parts) > 1 ? end($parts) : null;

        $slug = $last !== null ? $first . '.' . $last : $first;

        return mb_substr($slug, 0, self::MAX_LENGTH);
    }

    private function fromFallback(?string $fallback): string
    {
        if ($fallback === null || trim($fallback) === '') {
            return '';
        }

        $cleaned = strip_tags($fallback);
        $cleaned = html_entity_decode($cleaned, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $cleaned = preg_replace('/<WebsiteContent_[^>]+>|<\/WebsiteContent_[^>]+>/', '', $cleaned);
        $cleaned = mb_strtolower(trim($cleaned));

        // Check if the fallback contains template code ({{ ... }})
        if (preg_match('/\{\{.*\}\}/', $cleaned)) {
            return '';
        }

        $cleaned = preg_replace('/[^a-z0-9\-]/', '', $cleaned);
        $cleaned = trim($cleaned);

        if ($cleaned === '') {
            return '';
        }

        return mb_substr($cleaned, 0, self::MAX_LENGTH);
    }

    private function sanitize(string $slug): string
    {
        // Only allow [a-z0-9._-]
        $slug = preg_replace('/[^a-z0-9._-]/', '', $slug);
        $slug = trim($slug, '._-');

        return $slug;
    }

    private function ensureUnique(string $slug): string
    {
        $base = $slug;
        $suffix = 2;

        while (User::where('username', $slug)->exists()) {
            $suffixStr = (string) $suffix;
            $maxLen = self::MAX_LENGTH - strlen($suffixStr) - 1;
            $truncated = mb_substr($base, 0, max($maxLen, 0));
            $slug = $truncated . '-' . $suffixStr;
            $suffix++;
        }

        return $slug;
    }

    private function randomWordFallback(): string
    {
        $adj = self::FALLBACK_ADJECTIVES[array_rand(self::FALLBACK_ADJECTIVES)];
        $noun = self::FALLBACK_NOUNS[array_rand(self::FALLBACK_NOUNS)];
        $num = random_int(10, 999);

        $slug = $adj . '-' . $noun . '-' . $num;
        return mb_substr($slug, 0, self::MAX_LENGTH);
    }
}
