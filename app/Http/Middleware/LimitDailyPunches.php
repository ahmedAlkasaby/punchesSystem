<?php

namespace App\Http\Middleware;

use App\Models\Punch;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LimitDailyPunches
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
     public function handle(Request $request, Closure $next)
    {
        $userId = auth()->guard('api')->id(); 
        $today = Carbon::today();

        $punchCount = Punch::where('user_id', $userId)
            ->whereDate('punched_at', $today)
            ->count();

        if ($punchCount >= 2) {
            return response()->json([
                'message' => __('api.daily_punch_limit'),
            ], 403);
        }

        return $next($request);
    }
}
