<?php

namespace App\Livewire;

use App\Models\Report;
use Livewire\Component;
use Illuminate\Support\Facades\RateLimiter;

class ReportModal extends Component
{
    public bool $show = false;
    public ?string $reportableType = null;
    public ?int $reportableId = null;
    public string $reason = '';
    public string $successMessage = '';

    protected $listeners = [
        'openReportModal' => 'open',
    ];

    public function open(string $type, int $id): void
    {
        $this->reportableType = $type;
        $this->reportableId   = $id;
        $this->reason         = '';
        $this->successMessage = '';
        $this->show           = true;
    }

    public function close(): void
    {
        $this->show = false;
    }

    public function submit(): void
    {
        $this->validate([
            'reason' => ['required', 'in:' . implode(',', array_keys(Report::REASONS))],
        ], [
            'reason.required' => 'Pilih alasan laporan.',
            'reason.in'       => 'Alasan laporan tidak valid.',
        ]);

        $user = auth()->user();

        // Rate limiting: max 5 reports per minute
        $key = 'report:' . $user->id;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('reason', 'Terlalu banyak laporan. Coba lagi sebentar.');
            return;
        }
        RateLimiter::hit($key, 60);

        // Prevent duplicate pending report
        $alreadyReported = Report::where('user_id', $user->id)
            ->where('reportable_type', $this->reportableType)
            ->where('reportable_id', $this->reportableId)
            ->where('status', 'pending')
            ->exists();

        if ($alreadyReported) {
            $this->addError('reason', 'Kamu sudah pernah melaporkan konten ini.');
            return;
        }

        Report::create([
            'user_id'          => $user->id,
            'reportable_type'  => $this->reportableType,
            'reportable_id'    => $this->reportableId,
            'reason'           => $this->reason,
            'status'           => Report::STATUS_PENDING,
        ]);

        $this->successMessage = 'Laporan berhasil dikirim. Tim moderator akan meninjaunya.';
        $this->reason = '';

        $this->dispatch('reportSubmitted');
    }

    public function render()
    {
        return view('livewire.report-modal', [
            'reasons' => Report::REASONS,
        ]);
    }
}
