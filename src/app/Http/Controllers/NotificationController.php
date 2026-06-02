<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
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
