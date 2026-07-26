<?php

namespace App\Livewire;

use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PollDisplay extends Component
{
    public Poll $poll;
    public ?int $selectedOptionId = null;
    public bool $hasVoted = false;

    public function mount(Poll $poll): void
    {
        $this->poll = $poll->load('options');

        $user = Auth::user();
        $vote = PollVote::whereIn('poll_option_id', $poll->options->pluck('id'))
            ->where('user_id', $user?->id)
            ->first();

        if ($vote) {
            $this->hasVoted = true;
            $this->selectedOptionId = $vote->poll_option_id;
        }
    }

    public function vote(int $optionId): void
    {
        if (!Auth::check() || $this->hasVoted) return;

        $option = PollOption::where('poll_id', $this->poll->id)->find($optionId);
        if (!$option) return;

        if ($this->poll->ends_at?->isPast()) return;

        PollVote::create([
            'poll_option_id' => $optionId,
            'user_id' => Auth::id(),
        ]);

        $this->hasVoted = true;
        $this->selectedOptionId = $optionId;
        $this->poll->load('options');
    }

    public function render()
    {
        $options = $this->poll->options()->withCount('votes')->get();
        $total = max($options->sum('votes_count'), 1);

        return view('livewire.poll-display', [
            'options' => $options,
            'totalVotes' => $total,
        ]);
    }
}
