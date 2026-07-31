<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function readAll()
    {
        auth()->user()->unreadNotifications()->update(['is_read' => true]);
        return back()->with('success', 'Semua notifikasi telah ditandai dibaca');
    }

    public function read(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}
