<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Friendship;
use App\Models\Hashtag;
use App\Models\Like;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use App\Models\Post;
use App\Models\SavedPost;
use App\Models\Story;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LargeDataSetSeeder extends Seeder
{
    private const USER_COUNT = 200;
    private const POST_COUNT = 500;
    private const COMMENT_COUNT = 300;
    private const STORY_COUNT = 50;
    private const HASHTAG_NAMES = ['Laravel', 'Livewire', 'PHP', 'JavaScript', 'TailwindCSS', 'VueJS', 'ReactJS', 'AI', 'MachineLearning', 'DevOps', 'Docker', 'Kubernetes', 'Python', 'GoLang', 'Rust', 'UIUX', 'Design', 'SEO', 'Marketing', 'Startup', 'OpenSource', 'Tech', 'Programming', 'Database', 'API'];

    public function run(): void
    {
        $this->command->info('Seeding large dataset...');
        $start = microtime(true);

        // ── Users (200) ──
        $this->command->info('Creating ' . self::USER_COUNT . ' users...');
        $userIds = $this->seedUsers();

        // ── Friendships (random ~5 per user) ──
        $this->command->info('Creating friendships...');
        $this->seedFriendships($userIds);

        // ── Hashtags ──
        $this->command->info('Creating hashtags...');
        $hashtagIds = $this->seedHashtags();

        // ── Posts (500) ──
        $this->command->info('Creating ' . self::POST_COUNT . ' posts...');
        $postIds = $this->seedPosts($userIds, $hashtagIds);

        // ── Reposts (10% of posts) ──
        $this->command->info('Creating reposts...');
        $this->seedReposts($userIds, $postIds);

        // ── Polls (5% of posts) ──
        $this->command->info('Creating polls...');
        $this->seedPolls($userIds, $postIds);

        // ── Bookmarks (10% of posts, per user subset) ──
        $this->command->info('Creating bookmarks...');
        $this->seedBookmarks($userIds, $postIds);

        // ── Likes (random) ──
        $this->command->info('Creating likes...');
        $this->seedLikes($userIds, $postIds);

        // ── Comments (300) ──
        $this->command->info('Creating ' . self::COMMENT_COUNT . ' comments...');
        $this->seedComments($userIds, $postIds);

        // ── Stories (50) ──
        $this->command->info('Creating ' . self::STORY_COUNT . ' stories...');
        $this->seedStories($userIds);

        // ── DMs (random conversations) ──
        $this->command->info('Creating conversations & messages...');
        $this->seedMessages($userIds);

        $elapsed = round(microtime(true) - $start, 2);
        $this->command->info("Large dataset seeded in {$elapsed}s.");
    }

    private function seedUsers(): array
    {
        $now = now();
        $password = Hash::make('password');
        $rows = [];
        for ($i = 0; $i < self::USER_COUNT; $i++) {
            $name = fake()->name();
            $rows[] = [
                'name' => $name,
                'username' => Str::slug($name) . rand(100, 9999),
                'email' => fake()->unique()->safeEmail(),
                'password' => $password,
                'bio' => fake()->sentence(8),
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            if (count($rows) >= 50) {
                User::insert($rows);
                $rows = [];
            }
        }
        if (!empty($rows)) User::insert($rows);
        return User::pluck('id')->toArray();
    }

    private function seedFriendships(array $userIds): void
    {
        $now = now();
        $rows = [];
        $seen = [];
        foreach ($userIds as $uid) {
            $targets = fake()->randomElements(array_diff($userIds, [$uid]), rand(3, 8));
            foreach ($targets as $target) {
                $key = min($uid, $target) . '-' . max($uid, $target);
                if (isset($seen[$key])) continue;
                $seen[$key] = true;
                $rows[] = [
                    'sender_id' => $uid,
                    'receiver_id' => $target,
                    'status' => fake()->randomElement(['accepted', 'accepted', 'accepted', 'pending']),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            if (count($rows) >= 100) {
                DB::table('friendships')->insert($rows);
                $rows = [];
            }
        }
        if (!empty($rows)) DB::table('friendships')->insert($rows);
    }

    private function seedHashtags(): array
    {
        $now = now();
        $rows = [];
        foreach (self::HASHTAG_NAMES as $name) {
            $rows[] = ['name' => Str::lower($name), 'created_at' => $now, 'updated_at' => $now];
        }
        Hashtag::insertOrIgnore($rows);
        return Hashtag::pluck('id', 'name')->toArray();
    }

    private function seedPosts(array $userIds, array $hashtagIds): array
    {
        $now = now();
        $bodies = [
            'Baru belajar #Laravel hari ini, keren banget!',
            'Siapa yang suka pake #TailwindCSS? Menurutku ini framework CSS terbaik.',
            'Tips #SEO untuk pemula: konten adalah raja!',
            'Belajar #AI dan #MachineLearning di tahun ini, tantangan baru!',
            '#PHP masih jadi bahasa backend paling populer di 2026.',
            'Hari ini ngoding #Livewire 3, interaktif tanpa JavaScript!',
            '#Docker bikin deployment jadi gampang banget.',
            'Suka atau tidak, #JavaScript masih menguasai web development.',
            '#UIUX bukan cuma soal tampilan, tapi juga pengalaman pengguna.',
            '#OpenSource is the way to go! Kontribusi sekarang!',
        ];

        $postIds = [];
        $pivotRows = [];

        for ($i = 0; $i < self::POST_COUNT; $i++) {
            $body = $bodies[array_rand($bodies)];
            $userId = $userIds[array_rand($userIds)];
            $created = (clone $now)->subHours(rand(0, 720));

            $post = Post::create([
                'user_id' => $userId,
                'body' => $body,
                'created_at' => $created,
                'updated_at' => $created,
            ]);
            $postIds[] = $post->id;

            // Attach random hashtags (1-3 per post)
            $tagSample = array_rand(array_flip(self::HASHTAG_NAMES), rand(1, 3));
            foreach ((array)$tagSample as $tagName) {
                if (isset($hashtagIds[Str::lower($tagName)])) {
                    $pivotRows[] = [
                        'hashtag_id' => $hashtagIds[Str::lower($tagName)],
                        'post_id' => $post->id,
                        'created_at' => $created,
                        'updated_at' => $created,
                    ];
                }
            }
        }

        // Bulk insert pivot
        if (!empty($pivotRows)) {
            foreach (array_chunk($pivotRows, 100) as $chunk) {
                DB::table('hashtag_post')->insert($chunk);
            }
        }

        return $postIds;
    }

    private function seedReposts(array $userIds, array $postIds): void
    {
        $repostCount = (int)(count($postIds) * 0.1);
        $sample = array_rand(array_flip($postIds), min($repostCount, count($postIds)));
        $now = now();
        foreach ((array)$sample as $parentId) {
            Post::create([
                'user_id' => $userIds[array_rand($userIds)],
                'parent_id' => $parentId,
                'body' => '',
                'created_at' => (clone $now)->subHours(rand(0, 48)),
                'updated_at' => $now,
            ]);
        }
    }

    private function seedPolls(array $userIds, array $postIds): void
    {
        $pollCount = (int)(count($postIds) * 0.05);
        $questions = [
            'Framework favorit kamu?',
            'Lebih suka WFH atau WFO?',
            'OS preferensi untuk development?',
            'Database favorit?',
            'Editor kode terbaik?',
        ];
        $options = [
            ['Laravel', 'Rails', 'Django', 'Spring'],
            ['WFH', 'WFO', 'Hybrid'],
            ['Linux', 'macOS', 'Windows'],
            ['PostgreSQL', 'MySQL', 'SQLite', 'MongoDB'],
            ['VS Code', 'PHPStorm', 'Neovim', 'Sublime'],
        ];

        $sample = array_rand(array_flip($postIds), min($pollCount, count($postIds)));
        foreach ((array)$sample as $i => $pid) {
            $qi = $i % count($questions);
            $poll = Poll::create(['post_id' => $pid, 'question' => $questions[$qi], 'ends_at' => Carbon::now()->addDays(rand(1, 7))]);
            foreach ($options[$qi] as $opt) {
                $option = PollOption::create(['poll_id' => $poll->id, 'label' => $opt]);
                // Random votes
                $voters = fake()->randomElements($userIds, rand(1, 8));
                foreach ($voters as $vid) {
                    PollVote::firstOrCreate(['poll_option_id' => $option->id, 'user_id' => $vid]);
                }
            }
        }
    }

    private function seedBookmarks(array $userIds, array $postIds): void
    {
        $sampleUsers = array_rand(array_flip($userIds), 30);
        foreach ((array)$sampleUsers as $uid) {
            $targets = fake()->randomElements($postIds, rand(1, 8));
            foreach ($targets as $pid) {
                SavedPost::firstOrCreate(['user_id' => $uid, 'post_id' => $pid]);
            }
        }
    }

    private function seedLikes(array $userIds, array $postIds): void
    {
        $now = now();
        $rows = [];
        $seen = [];
        foreach ($postIds as $pid) {
            $likers = fake()->randomElements($userIds, rand(0, 15));
            foreach ($likers as $uid) {
                $key = $pid . '-' . $uid;
                if (isset($seen[$key])) continue;
                $seen[$key] = true;
                $rows[] = ['user_id' => $uid, 'post_id' => $pid, 'created_at' => $now, 'updated_at' => $now];
            }
            if (count($rows) >= 200) {
                DB::table('likes')->insert($rows);
                $rows = [];
            }
        }
        if (!empty($rows)) DB::table('likes')->insert($rows);
    }

    private function seedComments(array $userIds, array $postIds): void
    {
        $now = now();
        $texts = [
            'Setuju banget!', 'Nice info!', 'Keren!', 'Makasih sharingnya.',
            'Baru juga nyoba, recommended!', 'Mantap!', 'Luar biasa!',
            'Saya juga pake itu, recommended banget.', 'Wah, menarik!',
        ];
        for ($i = 0; $i < self::COMMENT_COUNT; $i++) {
            Comment::create([
                'user_id' => $userIds[array_rand($userIds)],
                'post_id' => $postIds[array_rand($postIds)],
                'parent_id' => null,
                'body' => $texts[array_rand($texts)],
                'created_at' => (clone $now)->subHours(rand(0, 168)),
                'updated_at' => $now,
            ]);
        }
    }

    private function seedStories(array $userIds): void
    {
        $now = now();
        $sample = array_rand(array_flip($userIds), min(self::STORY_COUNT, count($userIds)));
        $captions = ['Hari ini!', 'Ngoding malam', 'Baru belajar', 'Liburan', 'Meetup', 'Lagi di kantor', 'Coffee time!', 'New project ✨'];
        foreach ((array)$sample as $uid) {
            Story::create([
                'user_id' => $uid,
                'media_path' => 'stories/placeholder.jpg',
                'caption' => $captions[array_rand($captions)],
                'expires_at' => (clone $now)->addHours(rand(1, 24)),
                'created_at' => (clone $now)->subHours(rand(0, 12)),
                'updated_at' => $now,
            ]);
        }
    }

    private function seedMessages(array $userIds): void
    {
        $now = now();
        $bodySamples = ['Halo!', 'Apa kabar?', 'Sudah lihat feed terbaru?', 'Pull request-ku sudah dikirim.', 'Ok sip!', 'Nanti aku cek ya.', 'Makasih infonya!', 'Siap!'];

        for ($i = 0; $i < 30; $i++) {
            $u1 = $userIds[array_rand($userIds)];
            $u2 = $userIds[array_rand(array_diff($userIds, [$u1]))];

            $conv = Conversation::create(['type' => 'private']);
            $conv->users()->attach([$u1, $u2]);

            $msgCount = rand(2, 6);
            for ($j = 0; $j < $msgCount; $j++) {
                $sender = $j % 2 === 0 ? $u1 : $u2;
                Message::create([
                    'conversation_id' => $conv->id,
                    'user_id' => $sender,
                    'body' => $bodySamples[array_rand($bodySamples)],
                    'created_at' => (clone $now)->subHours(rand(0, 48))->addMinutes($j * 5),
                    'updated_at' => $now,
                ]);
            }
            $conv->users()->updateExistingPivot($u1, ['last_read_at' => $now]);
        }
    }
}
