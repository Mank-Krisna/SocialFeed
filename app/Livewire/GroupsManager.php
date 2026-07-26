<?php

namespace App\Livewire;

use App\Models\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class GroupsManager extends Component
{
    use WithFileUploads;

    public bool $showCreateModal = false;

    // Create Group Form Fields
    public string $name = '';
    public string $description = '';
    public string $type = 'public'; // public | private
    public $photo = null;

    protected array $rules = [
        'name' => 'required|string|min:3|max:100',
        'description' => 'nullable|string|max:500',
        'type' => 'required|in:public,private',
        'photo' => 'nullable|image|max:4096',
    ];

    public function toggleCreateModal(): void
    {
        $this->showCreateModal = !$this->showCreateModal;
    }

    public function createGroup(): void
    {
        $this->validate();

        $baseSlug = Str::slug($this->name);
        $slug = $baseSlug;
        $counter = 1;
        while (Group::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $photoPath = null;
        if ($this->photo) {
            $photoPath = $this->photo->store('groups', 'public');
        }

        $group = Group::create([
            'user_id' => Auth::id(),
            'name' => trim($this->name),
            'slug' => $slug,
            'description' => trim($this->description),
            'photo' => $photoPath,
            'type' => $this->type,
        ]);

        // Attach creator as admin
        $group->members()->attach(Auth::id(), ['role' => 'admin']);

        $this->reset(['name', 'description', 'type', 'photo', 'showCreateModal']);

        $this->redirect(route('groups.show', $group->slug), navigate: true);
    }

    public function joinGroup(int $groupId): void
    {
        $group = Group::find($groupId);
        if ($group && !$group->isMember(Auth::user())) {
            $group->members()->attach(Auth::id(), ['role' => 'member']);
        }
    }

    public function leaveGroup(int $groupId): void
    {
        $group = Group::find($groupId);
        if ($group && $group->isMember(Auth::user())) {
            $group->members()->detach(Auth::id());
        }
    }

    public function render()
    {
        $user = Auth::user();
        $myGroups = $user ? $user->groups()->withCount('members')->get() : collect();
        $myGroupIds = $myGroups->pluck('id')->toArray();

        $discoverGroups = Group::where('type', 'public')
            ->whereNotIn('id', $myGroupIds)
            ->withCount('members')
            ->take(12)
            ->get();

        return view('livewire.groups-manager', [
            'myGroups' => $myGroups,
            'discoverGroups' => $discoverGroups,
        ]);
    }
}
