<?php

namespace App\Http\Middleware;

use App\Models\Holiday;
use App\Models\Setting;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventAttendanceOnHoliday
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $today = Carbon::today();

        $holiday = Holiday::whereDate('date', $today)
            ->where('active', true)
            ->first();

        if ($holiday) {
            return response()->json([
                'message' => __('api.holiday', [
                    'name' => $holiday->nameLang()
                ]),
            ], 403);
        }

        $setting = Setting::first(); 
        if ($setting && in_array(strtolower($today->format('l')), $setting->weekend_days)) {
            return response()->json([
                'message' => __('api.weekend', [
                    'day' => __('days.' . strtolower($today->format('l')))
                ]),
            ], 403);
        }

        return $next($request);
    }
}
