<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(10);

        return view('notifications.index', compact('notifications'));
    }
    
    public function markAllRead ()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    public function destroy (string $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete();
 
        return back();
    }
}
