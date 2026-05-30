<?php

namespace App\Listeners;

use App\Models\ActivityLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
 
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogUserActivity
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handleLogin(object $event): void
    {
        ActivityLog::create([
            'user_id' => $event->user->id,
            'type' => 'login',
            'description' => 'User logged in',
        ]);
    }

    public function handleLogout(object $event): void
    {
        ActivityLog::create([
            'user_id' => $event->user->id,
            'type' => 'logout',
            'description' => 'User logged out',
        ]);
    }
}
