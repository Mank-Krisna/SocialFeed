<?php

namespace App\Livewire;

use App\Models\Group;
use App\Models\Post;
use App\Models\Report;
use App\Models\Story;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AdminDashboard extends Component
{
    use WithPagination;

    public string $tab = 'overview';
    public string $userSearch = '';
    public string $postSearch = '';
    public ?int $confirmDeleteUserId = null;
    public ?int $confirmDeletePostId = null;
    public ?int $confirmResolveReportId = null;
    public ?int $confirmDismissReportId = null;

    protected $queryString = ['tab'];

    public function mount(): void
    {
        if (!Auth::user()?->isAdmin()) {
            abort(403);
        }
    }

    public function switchTab(string $tab): void
    {
        $this->tab = $tab;
        $this->resetPage();
    }

    public function deleteUser(int $id): void
    {
        $user = User::find($id);
        if ($user && $user->id !== Auth::id()) {
            $user->posts()->delete();
            $user->delete();
            $this->dispatch('notify', message: 'User berhasil dihapus', type: 'success');
        }
        $this->confirmDeleteUserId = null;
    }

    public function deletePost(int $id): void
    {
        Post::find($id)?->delete();
        $this->dispatch('notify', message: 'Postingan berhasil dihapus', type: 'success');
        $this->confirmDeletePostId = null;
    }

    public function resolveReport(int $id): void
    {
        $report = Report::find($id);
        if ($report) {
            $report->update([
                'status' => Report::STATUS_RESOLVED,
                'resolved_by' => Auth::id(),
                'resolved_at' => now(),
            ]);
            $report->reportable?->delete();
            $this->dispatch('notify', message: 'Laporan diselesaikan dan konten dihapus', type: 'success');
        }
        $this->confirmResolveReportId = null;
    }

    public function dismissReport(int $id): void
    {
        $report = Report::find($id);
        if ($report) {
            $report->update([
                'status' => Report::STATUS_DISMISSED,
                'resolved_by' => Auth::id(),
                'resolved_at' => now(),
            ]);
            $this->dispatch('notify', message: 'Laporan ditolak', type: 'info');
        }
        $this->confirmDismissReportId = null;
    }

    public function render()
    {
        $stats = [
            'users' => User::count(),
            'posts' => Post::count(),
            'groups' => Group::count(),
            'stories' => Story::active()->count(),
            'reports' => Report::pending()->count(),
        ];

        $perPage = (int) config('feed.per_page', 10);
        $users = User::when($this->userSearch, fn ($q) => $q->where('name', 'like', "%{$this->userSearch}%")->orWhere('email', 'like', "%{$this->userSearch}%"))->latest()->paginate($perPage, pageName: 'usersPage');

        $posts = Post::when($this->postSearch, fn ($q) => $q->where('body', 'like', "%{$this->postSearch}%"))->with('user')->latest()->paginate($perPage, pageName: 'postsPage');

        $reports = Report::with(['reportable', 'reporter'])->latest()->paginate($perPage, pageName: 'reportsPage');

        return view('livewire.admin-dashboard', [
            'stats' => $stats,
            'users' => $users,
            'posts' => $posts,
            'reports' => $reports,
        ]);
    }
}
