<?php

namespace App\Listeners;

use App\Models\ActivityLog;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogTaskActivity
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
    public function LogCreate(int $userId): void
    {
        ActivityLog::create([
            'user_id' => $userId,
            'type' => 'task_created',
            'description' => 'Task created',
        ]);
    }

    public function LogUpdate(int $userId): void
    {
        ActivityLog::create([
            'user_id' => $userId,
            'type' => 'task_updated',
            'description' => 'Task updated',
        ]);
    }
    
    public function logComplete(int $userId): void
    {
        ActivityLog::create([
            'user_id'     => $userId,
            'type'        => 'complete',
            'description' => 'completed a task',
        ]);
    }
}
