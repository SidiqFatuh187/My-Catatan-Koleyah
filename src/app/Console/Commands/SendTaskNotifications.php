<?php

namespace App\Console\Commands;

use App\Models\Todo;
use App\Notifications\TaskOverdue;
use App\Notifications\TaskDeadlineReminder;
use App\Notifications\TaskDueTodayReminder;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:send-task-notifications')]
#[Description('Send task overdue, due today, and deadline reminder notifications')]
class SendTaskNotifications extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        // overdue 
        Todo::with('user')
            ->where('status', '!=', 'completed')
            ->where('deadline', '<', now()->startOfDay())
            ->whereNotnull('deadline')
            // 100 notif per bacth 
            ->chunk(100, function ($todos) {
                foreach ($todos as $todo) {
                    $alreadyNotified = $todo->user->notifications()
                        ->where('type', TaskOverdue::class)
                        ->whereJsonContains('data->task_id', $todo->id)
                        ->exists();
                    if (!$alreadyNotified) {
                        $todo->user->notify(new TaskOverdue($todo));
                
                    }   
                }
            });

        // due today
        Todo::with('user')
            ->where('status', '!=', 'completed')
            ->whereNotNull('deadline')
            ->whereDate('deadline', today())
             // 100 notif per bacth 
            ->chunk(100, function ($todos) {
                foreach ($todos as $todo) {
                    $alreadyNotified = $todo->user->notifications()
                        ->where('type', TaskDueTodayReminder::class)
                        ->whereJsonContains('data->task_id', $todo->id)
                        ->exists();
                    if (!$alreadyNotified){
                        $todo->user->notify(new TaskDueTodayReminder($todo));
                    }     
                }
            });
            
        // deadline reminder
        Todo::with('user')
            ->where('status', '!=', 'completed')
            ->whereNotNull('deadline')
            ->whereDate('deadline', today()->addDay())
             // 100 notif per bacth 
            ->chunk(100, function ($todos) {
                foreach ($todos as $todo) {
                    $alreadyNotified = $todo->user->notifications()
                        ->where('type', TaskDeadlineReminder::class)
                        ->whereJsonContains('data->task_id', $todo->id)
                        ->exists();
                    if (!$alreadyNotified){
                        $todo->user->notify(new TaskDeadlineReminder($todo));
                    }
                }
            });
 
        $this->info('Task notifications sent successfully.');
    }
}
