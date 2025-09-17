<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use App\Services\PunchService;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPunchTime
{
    protected $punchService;
    public function __construct(PunchService $punchService)
    {
        $this->punchService = $punchService;
    }
    public function handle(Request $request, Closure $next)
    {
        $userId = auth()->guard('api')->id();
        $type   = $this->punchService->getTypePunchOfUser($userId);
    
        $result = $this->punchService->canPunchNow($type);
    
        if ($result['allowed']) {
            return $next($request);
        }
    
        return response()->json([
            'success' => false,
            'message' => $result['message'],
        ], 403);
    }

}
