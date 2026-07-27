<?php

namespace App\Livewire;

use App\Models\Story;
use App\Models\StoryView;
use Livewire\Component;

class StoryViewer extends Component
{
    public $userId = null;
    public $currentStoryIndex = 0;
    public $stories = [];
    public $currentStory = null;
    public $isOpen = false;

    protected $listeners = ['open-story-viewer' => 'loadStories'];

    public function loadStories($userId)
    {
        $this->reset('currentStoryIndex', 'stories', 'currentStory');
        $this->userId = $userId;
        $this->isOpen = true;

        $this->stories = Story::where('user_id', $userId)
            ->where('expires_at', '>', now())
            ->with('user')
            ->oldest()
            ->get()
            ->toArray();

        $this->updateCurrentStory();
    }

    public function updateCurrentStory(): void
    {
        $this->currentStory = $this->stories[$this->currentStoryIndex] ?? null;

        if ($this->currentStory) {
            $this->markAsViewed($this->currentStory['id']);
        }
    }

    public function markAsViewed($storyId)
    {
        if (!$storyId) return;
        
        StoryView::firstOrCreate([
            'story_id' => $storyId,
            'user_id' => auth()->id(),
        ]);
    }

    public function nextStory()
    {
        if ($this->currentStoryIndex < count($this->stories) - 1) {
            $this->currentStoryIndex++;
            $this->updateCurrentStory();
            $this->dispatch('story-advanced');
        } else {
            $this->closeViewer();
        }
    }

    public function prevStory()
    {
        if ($this->currentStoryIndex > 0) {
            $this->currentStoryIndex--;
            $this->updateCurrentStory();
            $this->dispatch('story-advanced');
        }
    }

    public function closeViewer()
    {
        $this->isOpen = false;
        $this->reset('userId', 'stories', 'currentStoryIndex', 'currentStory');
        $this->dispatch('story-viewer-closed');
    }

    public function render()
    {
        return view('livewire.story-viewer');
    }
}
