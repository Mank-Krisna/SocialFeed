<?php

namespace App\Livewire;

use App\Models\Group;
use App\Models\Hashtag;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;

class SearchCenter extends Component
{
    #[Url(as: 'q')]
    public string $query = '';

    public string $tab = 'all'; // all | users | groups

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
    }

    public function render()
    {
        $q = trim($this->query);
        $hashtagName = str_starts_with($q, '#') ? mb_substr($q, 1) : null;

        $users = collect();
        $groups = collect();
        $hashtagPosts = collect();

        if (strlen($q) >= 1) {
            $users = User::where('id', '!=', Auth::id())
                ->where(function ($query) use ($q) {
                    $query->where('name', 'like', "%{$q}%")
                        ->orWhere('username', 'like', "%{$q}%");
                })->take(20)->get();

            $groups = Group::where('name', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%")
                ->withCount('members')
                ->take(20)->get();

            if ($hashtagName) {
                $hashtag = Hashtag::where('name', mb_strtolower($hashtagName))->first();
                if ($hashtag) {
                    $hashtagPosts = $hashtag->posts()
                        ->withFeedRelations(Auth::id())
                        ->latest()
                        ->take(20)
                        ->get();
                }
            }
        }

        return view('livewire.search-center', [
            'users' => $users,
            'groups' => $groups,
            'hashtagPosts' => $hashtagPosts,
            'hashtagName' => $hashtagName,
        ]);
    }
}
