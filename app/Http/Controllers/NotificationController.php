<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::latest();

        if ($request->type && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        $notifications = $query->paginate(20);

        Notification::where('is_read', false)->update(['is_read' => true]);

        return view('notifications.index', compact('notifications'));
    }

    public function markRead($id)
    {
        Notification::find($id)?->update(['is_read' => true]);

        return back();
    }

    public function markAllRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function clearAll()
    {
        Notification::truncate();

        return back()->with('success', 'Semua notifikasi telah dihapus.');
    }

    public function unreadCount()
    {
        $count = Notification::unread()->count();

        return response()->json(['count' => $count]);
    }
}
