<?php

namespace App\Services;

use App\Models\AttendanceSetting;
use App\Models\DailyAttendance;
use App\Models\DailyReport;
use App\Models\Exception;
use App\Models\ExceptionModel;
use App\Models\Punch;
use App\Models\Setting;
use Carbon\Carbon;

class AttendanceService
{
    /**
     * حساب الحضور اليومي لموظف معين
     */
    public function computeDailyAttendanceByIds(int $employeeId, string $dateLocalString)
    {
        $date = Carbon::parse($dateLocalString); 

        $start = $date->copy()->startOfDay();
        $end   = $date->copy()->endOfDay();

        $settings = Setting::first();


        $punches = Punch::where('user_id', $employeeId)
            ->whereBetween('punched_at', [$start, $end])
            ->orderBy('punched_at')
            ->get();

       
        $result = [
            'employee_id' => $employeeId,
            'date' => $dateLocalString,
            'day_status' => 'absent',
        ];

        if ($punches->isNotEmpty()) {
            $result['day_status'] = 'present';
           
            $punchIn= $punches->where('type', 'in')->first();
            if($punchIn){
                $result['first_in']=$punchIn->punched_at;
                $result['has_missing_in']=false;
                $result['is_late']=$punchIn->is_late;
                $result['late_seconds']=$punchIn->late_seconds;
                $result['flagged_in']=$punchIn->is_out_of_radius;
            }
            $punchOut= $punches->where('type', 'out')->first();
            if($punchOut){
                $result['last_out']=$punchOut->punched_at;
                $result['has_missing_out']=false;
                $result['is_early_leave']=$punchOut->is_early_leave;
                $result['early_leave_seconds']=$punchOut->early_leave_seconds;
                $result['flagged_out']=$punchOut->is_out_of_radius;
            }
            if ($punchIn && $punchOut ) {
                $totalSeconds = max(0,  $result['last_out']->diffInSeconds($result['first_in']));
                $result['total_seconds'] = $totalSeconds;
                $result['total_hours'] = round($totalSeconds / 3600, 2);
                $result['is_under_hours']=$result['total_hours']<$settings->min_hours;
            }
            


          

           

           

          

          
        }

        // حفظ النتيجة + التحقق من الاستثناء
        return $this->saveResult($result, $employeeId, $dateLocalString);
    }

    /**
     * حفظ التقرير اليومي والتحقق من الاستثناء
     */
    protected function saveResult(array $result, int $employeeId, string $dateLocalString)
    {
        // التحقق من الاستثناء
        $exception = Exception::where('employee_id', $employeeId)
            ->where('status', 'approved')
            ->whereDate('from_date', '<=', $dateLocalString)
            ->whereDate('to_date', '>=', $dateLocalString)
            ->first();

        if ($exception) {
            $result['exception_id'] = $exception->id;
            $result['exception_type'] = $exception->type;  
        }

        return DailyReport::updateOrCreate(
            ['employee_id' => $result['employee_id'], 'date' => $result['date']],
            array_merge($result, ['computed_at' => now()])
        );
    }
}
