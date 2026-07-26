<?php

namespace App\Livewire;

use App\Models\Story;
use App\Models\StoryView;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class StorySection extends Component
{
    use WithFileUploads;

    public $stories;
    public $activeStory = null;
    public $storyIndex = 0;
    public $showUploadForm = false;
    public $mediaFile;
    public $caption = '';

    public function mount(): void
    {
        $this->loadStories();
    }

    public function loadStories(): void
    {
        $user = Auth::user();
        $friendIds = $user->friendIds();
        $friendIds[] = $user->id;

        $this->stories = Story::active()
            ->whereIn('user_id', $friendIds)
            ->with(['user', 'views'])
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('user_id')
            ->map(function ($stories) use ($user) {
                $u = $stories->first()->user;
                $allViewed = $stories->every(fn ($s) => $s->isViewedBy($user));
                return [
                    'user' => $u,
                    'stories' => $stories,
                    'hasUnviewed' => !$allViewed,
                ];
            })
            ->values();
    }

    public function openStory(int $groupId, int $storyIdx = 0): void
    {
        $group = $this->stories[$groupId] ?? null;
        if (!$group) return;

        $this->activeStory = [
            'groupId' => $groupId,
            'storyIdx' => $storyIdx,
            'stories' => $group['stories']->toArray(),
            'user' => [
                'id' => $group['user']->id,
                'name' => $group['user']->name,
                'avatar_url' => $group['user']->avatar_url,
            ],
        ];
        $this->storyIndex = $storyIdx;
        $this->markViewed($group['stories'][$storyIdx]->id);
    }

    public function nextStory(): void
    {
        if (!$this->activeStory) return;
        $stories = $this->activeStory['stories'];
        $next = $this->storyIndex + 1;

        if ($next < count($stories)) {
            $this->storyIndex = $next;
            $this->markViewed($stories[$next]['id']);
            $this->activeStory['storyIdx'] = $next;
        } else {
            $this->closeStory();
        }
    }

    public function prevStory(): void
    {
        if (!$this->activeStory || $this->storyIndex <= 0) return;
        $prev = $this->storyIndex - 1;
        $this->storyIndex = $prev;
        $this->activeStory['storyIdx'] = $prev;
    }

    public function closeStory(): void
    {
        $this->activeStory = null;
        $this->storyIndex = 0;
        $this->loadStories();
    }

    public function markViewed(int $storyId): void
    {
        StoryView::firstOrCreate([
            'story_id' => $storyId,
            'user_id' => Auth::id(),
        ]);
    }

    public function toggleUploadForm(): void
    {
        $this->showUploadForm = !$this->showUploadForm;
        $this->mediaFile = null;
        $this->caption = '';
    }

    public function uploadStory(): void
    {
        $this->validate([
            'mediaFile' => 'required|image|max:10240',
            'caption' => 'nullable|string|max:200',
        ]);

        $path = $this->mediaFile->store('stories', 'public');

        Story::create([
            'user_id' => Auth::id(),
            'media_path' => $path,
            'caption' => $this->caption,
            'expires_at' => Carbon::now()->addHours(24),
        ]);

        $this->showUploadForm = false;
        $this->mediaFile = null;
        $this->caption = '';
        $this->loadStories();

        $this->dispatch('notify', message: 'Cerita berhasil diunggah', type: 'success');
    }

    public function render()
    {
        return view('livewire.story-section');
    }
}
