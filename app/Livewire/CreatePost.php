<?php

namespace App\Livewire;

use App\Models\Group;
use App\Models\Hashtag;
use App\Models\Post;
use App\Models\PostMedia;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePost extends Component
{
    use WithFileUploads;

    public string $body = '';
    public $mediaFiles = [];
    public ?int $groupId = null;

    public function mount(?int $groupId = null): void
    {
        $this->groupId = $groupId;
    }

    public function updatedMediaFiles(): void
    {
        $this->resetErrorBag(['body', 'mediaFiles']);
    }

    public function submit(): void
    {
        $this->validate([
            'body' => 'nullable|string|max:500',
            'mediaFiles' => 'nullable|array|max:10',
            'mediaFiles.*' => 'file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,webm,mkv|max:102400',
        ]);

        $bodyText = trim($this->body);
        $files = is_array($this->mediaFiles) ? array_values($this->mediaFiles) : [$this->mediaFiles];
        $files = array_values(array_filter($files));

        if ($bodyText === '' && empty($files)) {
            $this->addError('body', 'Tulis sesuatu atau unggah foto/video untuk membuat postingan.');
            return;
        }

        if ($this->groupId) {
            $group = Group::find($this->groupId);
            if (!$group || !$group->isMember(Auth::user())) {
                $this->addError('groupId', 'Anda tidak dapat memposting ke grup ini.');
                return;
            }
        }

        $post = Post::create([
            'user_id' => Auth::id(),
            'group_id' => $this->groupId,
            'body' => $bodyText,
        ]);

        foreach ($files as $file) {
            $path = $file->store('posts', 'public');

            PostMedia::create([
                'post_id' => $post->id,
                'file_path' => $path,
                'type' => PostMedia::detectType($file),
            ]);
        }

        preg_match_all('/#([\w]+)/u', $bodyText, $hashtagMatches);
        if (!empty($hashtagMatches[1])) {
            $tagIds = [];
            foreach (array_unique($hashtagMatches[1]) as $tagName) {
                $tag = Hashtag::firstOrCreate(['name' => mb_strtolower($tagName)]);
                $tagIds[] = $tag->id;
            }
            $post->hashtags()->sync($tagIds);
        }

        preg_match_all('/@([\w\-\.]+)/u', $bodyText, $mentionMatches);
        if (!empty($mentionMatches[1])) {
            foreach (array_unique($mentionMatches[1]) as $username) {
                $mentioned = User::where('username', $username)->first();
                if ($mentioned && $mentioned->id !== Auth::id()) {
                    Notification::create([
                        'user_id' => $mentioned->id,
                        'sender_id' => Auth::id(),
                        'type' => 'mention',
                        'data' => [
                            'message' => 'menyebut Anda dalam postingan.',
                            'link' => route('posts.show', $post->id),
                        ],
                    ]);
                }
            }
        }

        $this->reset(['body', 'mediaFiles']);
        $this->dispatch('post-created');
    }

    public function removeMedia(int $index): void
    {
        $files = is_array($this->mediaFiles) ? $this->mediaFiles : [$this->mediaFiles];
        array_splice($files, $index, 1);
        $this->mediaFiles = array_values($files);
    }

    public function render()
    {
        return view('livewire.create-post');
    }
}
