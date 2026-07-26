<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Conversation;
use App\Models\Friendship;
use App\Models\Group;
use App\Models\Hashtag;
use App\Models\Like;
use App\Models\Message;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\Post;
use App\Models\SavedPost;
use App\Models\Story;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Password plain text — User model 'hashed' cast handles hashing
        $pass = 'password';

        // ── Users ──
        $budi = User::create(['name' => 'Budi Santoso',      'username' => 'budis',     'email' => 'demo@socialfeed.com', 'password' => $pass, 'bio' => 'Full-stack Web Developer & Tech Enthusiast. Suka membangun aplikasi modern dengan Laravel 11 & Livewire 3. 🚀', 'is_admin' => true]);
        $siti = User::create(['name' => 'Siti Rahma',        'username' => 'siti_r',    'email' => 'siti@example.com',    'password' => $pass, 'bio' => 'UI/UX Designer & Content Creator 🎨 ✨']);
        $ahmad = User::create(['name' => 'Ahmad Fauzi',      'username' => 'ahmad_f',   'email' => 'ahmad@example.com',   'password' => $pass, 'bio' => 'Backend Engineer | Open Source Contributor 💻']);
        $dian = User::create(['name' => 'Dian Permata',      'username' => 'dian_p',    'email' => 'dian@example.com',    'password' => $pass, 'bio' => 'Digital Marketer & Coffee Lover ☕']);
        $rudi = User::create(['name' => 'Rudi Hartono',      'username' => 'rudi_h',    'email' => 'rudi@example.com',    'password' => $pass, 'bio' => 'Mobile Developer & Kotlin Enthusiast 📱']);
        $dewi = User::create(['name' => 'Dewi Lestari',      'username' => 'dewi_l',    'email' => 'dewi@example.com',    'password' => $pass, 'bio' => 'Data Scientist & Python Lover 🐍']);

        // ── Friendships ──
        Friendship::create(['sender_id' => $budi->id,  'receiver_id' => $siti->id,  'status' => 'accepted']);
        Friendship::create(['sender_id' => $budi->id,  'receiver_id' => $ahmad->id, 'status' => 'accepted']);
        Friendship::create(['sender_id' => $budi->id,  'receiver_id' => $rudi->id,  'status' => 'accepted']);
        Friendship::create(['sender_id' => $budi->id,  'receiver_id' => $dewi->id,  'status' => 'accepted']);
        Friendship::create(['sender_id' => $siti->id,  'receiver_id' => $ahmad->id, 'status' => 'accepted']);
        Friendship::create(['sender_id' => $siti->id,  'receiver_id' => $dian->id,  'status' => 'accepted']);
        Friendship::create(['sender_id' => $ahmad->id, 'receiver_id' => $rudi->id,  'status' => 'accepted']);
        Friendship::create(['sender_id' => $dian->id,  'receiver_id' => $budi->id,  'status' => 'pending']);

        // ── Groups ──
        $groupLaravel = Group::create(['user_id' => $budi->id, 'name' => 'Laravel Developer Indonesia', 'slug' => 'laravel-developer-indonesia', 'description' => 'Komunitas pengembang web Laravel di Indonesia.', 'type' => 'public']);
        $groupLaravel->members()->attach($budi->id,  ['role' => 'admin']);
        $groupLaravel->members()->attach($ahmad->id, ['role' => 'member']);
        $groupLaravel->members()->attach($siti->id,  ['role' => 'member']);
        $groupLaravel->members()->attach($rudi->id,  ['role' => 'member']);

        $groupDesign = Group::create(['user_id' => $siti->id, 'name' => 'UI/UX & Visual Design Lab', 'slug' => 'ui-ux-visual-design-lab', 'description' => 'Wadah tempat desainer berbagi ide layout dan animasi mikro.', 'type' => 'public']);
        $groupDesign->members()->attach($siti->id, ['role' => 'admin']);
        $groupDesign->members()->attach($budi->id, ['role' => 'member']);
        $groupDesign->members()->attach($dian->id, ['role' => 'member']);

        $groupPrivate = Group::create(['user_id' => $rudi->id, 'name' => 'Mobile Dev Circle', 'slug' => 'mobile-dev-circle', 'description' => 'Grup diskusi khusus developer mobile.', 'type' => 'private']);
        $groupPrivate->members()->attach($rudi->id, ['role' => 'admin']);
        $groupPrivate->members()->attach($ahmad->id, ['role' => 'member']);

        // ── Posts (with hashtags in body) ──
        $p1 = Post::create(['user_id' => $budi->id, 'body' => "Selamat datang di SocialFeed! 🎉\n\nAplikasi media sosial ini dibangun menggunakan #Laravel 11, #Livewire 3, dan Tailwind CSS. Komponen feed mendukung postingan status teks, upload foto, serta grup diskusi real-time!", 'created_at' => now()->subHours(2)]);

        $p2 = Post::create(['user_id' => $siti->id, 'body' => "Baru saja menyelesaikan konsep UI/UX untuk aplikasi terbaru! Penggunaan warna primary blue (#0058bc) dan font Inter membuat tampilan terasa bersih dan modern. #UIUX #Design", 'created_at' => now()->subHours(5)]);

        $p3 = Post::create(['user_id' => $ahmad->id, 'group_id' => $groupLaravel->id, 'body' => "Halo anggota grup Laravel Dev Indonesia! Ada yang sudah mencoba fitur Volt dan Folio di #Laravel? Sangat mempercepat prototyping aplikasi web!", 'created_at' => now()->subHour()]);

        $p4 = Post::create(['user_id' => $rudi->id, 'body' => "Kotlin Multiplatform now supports Swift Package Manager! Ini game changer banget buat developer mobile. #Kotlin #MobileDev", 'created_at' => now()->subHours(3)]);

        $p5 = Post::create(['user_id' => $dewi->id, 'body' => "Baru baca paper tentang transformer architecture terbaru. Perkembangan #AI dan #MachineLearning semakin cepat!", 'created_at' => now()->subHours(6)]);

        $p6 = Post::create(['user_id' => $dian->id, 'body' => "Tips marketing: konten #SEO friendly itu penting tapi jangan lupa user experience. Google makin pinter ngenalin konten berkualitas.", 'created_at' => now()->subHours(4)]);

        // ── Hashtags (auto-parsed from post bodies via CreatePost, but seed manually for reliability) ──
        $tagNames = ['Laravel', 'Livewire', 'UIUX', 'Design', 'Kotlin', 'MobileDev', 'AI', 'MachineLearning', 'SEO'];
        $tagIds = [];
        foreach ($tagNames as $name) {
            $tag = Hashtag::firstOrCreate(['name' => $name]);
            $tagIds[$name] = $tag->id;
        }
        $p1->hashtags()->sync([$tagIds['Laravel'], $tagIds['Livewire']]);
        $p2->hashtags()->sync([$tagIds['UIUX'], $tagIds['Design']]);
        $p3->hashtags()->sync([$tagIds['Laravel']]);
        $p4->hashtags()->sync([$tagIds['Kotlin'], $tagIds['MobileDev']]);
        $p5->hashtags()->sync([$tagIds['AI'], $tagIds['MachineLearning']]);
        $p6->hashtags()->sync([$tagIds['SEO']]);

        // ── Repost ──
        $repost = Post::create(['user_id' => $siti->id, 'parent_id' => $p1->id, 'body' => '', 'created_at' => now()->subMinutes(30)]);

        // ── Poll ──
        $poll = Poll::create(['post_id' => $p6->id, 'question' => 'Platform sosial media apa yang paling efektif untuk marketing?', 'ends_at' => Carbon::now()->addDays(3)]);
        $op1 = PollOption::create(['poll_id' => $poll->id, 'label' => 'Instagram']);
        $op2 = PollOption::create(['poll_id' => $poll->id, 'label' => 'TikTok']);
        $op3 = PollOption::create(['poll_id' => $poll->id, 'label' => 'LinkedIn']);
        $op4 = PollOption::create(['poll_id' => $poll->id, 'label' => 'Twitter / X']);

        // Poll votes (budi votes Instagram, siti votes TikTok)
        \App\Models\PollVote::create(['poll_option_id' => $op1->id, 'user_id' => $budi->id]);
        \App\Models\PollVote::create(['poll_option_id' => $op2->id, 'user_id' => $siti->id]);
        \App\Models\PollVote::create(['poll_option_id' => $op1->id, 'user_id' => $rudi->id]);

        // ── Bookmark (budi bookmarks p2) ──
        SavedPost::create(['user_id' => $budi->id, 'post_id' => $p2->id]);

        // ── Likes ──
        Like::create(['user_id' => $siti->id,  'post_id' => $p1->id]);
        Like::create(['user_id' => $ahmad->id, 'post_id' => $p1->id]);
        Like::create(['user_id' => $dian->id,  'post_id' => $p1->id]);
        Like::create(['user_id' => $budi->id,  'post_id' => $p2->id]);
        Like::create(['user_id' => $ahmad->id, 'post_id' => $p2->id]);
        Like::create(['user_id' => $budi->id,  'post_id' => $p3->id]);
        Like::create(['user_id' => $siti->id,  'post_id' => $p4->id]);
        Like::create(['user_id' => $budi->id,  'post_id' => $p5->id]);

        // ── Comments ──
        $c1 = Comment::create(['user_id' => $siti->id,  'post_id' => $p1->id, 'parent_id' => null, 'body' => 'Keren sekali mas Budi! Tampilannya terasa sangat mulus.']);
        Comment::create(['user_id' => $budi->id,  'post_id' => $p1->id, 'parent_id' => $c1->id, 'body' => 'Terima kasih mba Siti! Livewire 3 membuat interaksinya terasa sangat responsif.']);
        Comment::create(['user_id' => $ahmad->id, 'post_id' => $p2->id, 'parent_id' => null, 'body' => 'Warna primary blue-nya bikin feed keliatan premium!']);
        Comment::create(['user_id' => $dewi->id,  'post_id' => $p5->id, 'parent_id' => null, 'body' => 'Setuju banget! AI berkembang super cepat akhir-akhir ini.']);

        // ── Stories ──
        // Stories need actual media files, so we use placeholder paths
        // In staging, replace these with real uploaded images
        Story::create(['user_id' => $budi->id, 'media_path' => 'stories/placeholder.jpg', 'caption' => 'Ngoding malam!', 'expires_at' => Carbon::now()->addHours(20)]);
        Story::create(['user_id' => $siti->id, 'media_path' => 'stories/placeholder.jpg', 'caption' => 'Desain baru ✨', 'expires_at' => Carbon::now()->addHours(22)]);
        Story::create(['user_id' => $ahmad->id, 'media_path' => 'stories/placeholder.jpg', 'caption' => 'Testing fitur baru', 'expires_at' => Carbon::now()->addHours(18)]);

        // ── Direct Messages ──
        $conv1 = Conversation::create(['type' => 'private']);
        $conv1->users()->attach([$budi->id, $siti->id]);
        Message::create(['conversation_id' => $conv1->id, 'user_id' => $siti->id, 'body' => 'Halo Budi! Aplikasinya keren banget!', 'created_at' => now()->subHours(2)]);
        Message::create(['conversation_id' => $conv1->id, 'user_id' => $budi->id, 'body' => 'Makasih Siti! Masih banyak fitur yang mau ditambahin sih.', 'created_at' => now()->subMinutes(90)]);
        Message::create(['conversation_id' => $conv1->id, 'user_id' => $siti->id, 'body' => 'Semangat! Kalo butuh bantuan desain bilang aja ya.', 'created_at' => now()->subHour()]);
        $conv1->users()->updateExistingPivot($budi->id, ['last_read_at' => now()]);

        $conv2 = Conversation::create(['type' => 'private']);
        $conv2->users()->attach([$budi->id, $ahmad->id]);
        Message::create(['conversation_id' => $conv2->id, 'user_id' => $ahmad->id, 'body' => 'Bud, pull request-ku udah aku kirim. Cek yuk!', 'created_at' => now()->subMinutes(45)]);
        Message::create(['conversation_id' => $conv2->id, 'user_id' => $ahmad->id, 'body' => 'Ada beberapa perubahan di bagian scope query.', 'created_at' => now()->subMinutes(44)]);
    }
}
