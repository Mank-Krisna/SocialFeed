<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(20);
        return view('livewire.notifications-center', compact('notifications'));
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca');
    }

    public function destroy($id)
    {
        Auth::user()->notifications()->where('id', $id)->delete();
        return back();
    }

    public function destroyAll()
    {
        Auth::user()->notifications()->delete();
        return back()->with('success', 'Semua notifikasi dihapus');
    }
}
