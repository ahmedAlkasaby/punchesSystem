<?php

namespace App\Observers;

use App\Models\Punch;
use App\Models\User;
use Filament\Notifications\Notification;

class PunchObserver
{
    public function created(Punch $punch): void
    {
        $user = $punch->user; 
        
        if ($user) {
            $user->updateQuietly([
                'last_punched_at' => $punch->punched_at,
            ]);
        }

        if (!$punch->approved) {
            $issues = [];

            if ($punch->is_late) {
                $issues[] = __('api.punch.late');
            }

            if ($punch->is_early_leave) {
                $issues[] = __('api.punch.early_leave');
            }

            if ($punch->is_out_of_radius) {
                $issues[] = __('api.punch.out_of_radius');
            }

            if (!empty($issues)) {
                $message = "👤 {$punch->user->name} ({$punch->type}) - " . implode(' + ', $issues);

                $admins = User::where('type', 'admin')->get();

                foreach ($admins as $admin) {
                    Notification::make()
                        ->title(__('api.punch.issue_detected'))
                        ->body($message)
                        ->warning()
                        ->sendToDatabase($admin);
                }
            }
        }
    }
}
