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

    public function toggleUploadForm(): void
    {
        $this->showUploadForm = !$this->showUploadForm;
        $this->mediaFile = null;
        $this->caption = '';
    }

    public function uploadStory(): void
    {
        $this->validate([
            'mediaFile' => 'required|file|mimes:jpg,jpeg,png,mp4,mov|max:51200',
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
