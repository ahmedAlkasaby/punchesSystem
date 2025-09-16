<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ComputeDailyAttendance extends Command
{
    protected $signature = 'attendance:compute {date?}';
    protected $description = 'حساب الحضور اليومي لكل الموظفين';

    public function handle(AttendanceService $attendanceService)
    {
        $date = $this->argument('date') ?? Carbon::yesterday()->toDateString();

        $this->info("Computing attendance for: {$date}");

        $users = User::where('type', 'employee')->where('active', true)->get();

        foreach ($users as $user) {
            $attendanceService->computeDailyAttendanceByIds($user->id, $date);
        }

        $this->info("تم حساب الحضور لـ " . $users->count() . " موظف.");
    }
}
