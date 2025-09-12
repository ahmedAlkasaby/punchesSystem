<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StorePunchRequest;
use App\Models\Punch;
use App\Services\PunchService;
use Illuminate\Http\Request;

class PunchController extends MainController
{
    protected $punchService;
    public function __construct(PunchService $punchService)
    {
        $this->punchService = $punchService;
    }
    public function store(StorePunchRequest $request)
    {
        $userId = auth()->guard('api')->id();
        $data = $request->validated();
        $data['user_id'] = $userId;
        $data['type'] = $this->punchService->getTypePunchOfUser($userId);
        $data['is_late'] = $data['type'] == 'in' ? $this->punchService->checkLatePunch($data['type']) : null;
        $data['is_early_leave'] = $data['type'] == 'out' ? $this->punchService->checkEarlyPunch($data['type']) : null;
        $data['is_out_of_radius'] = $this->punchService->checkOutOfRadiusForUserLocations($userId, $data['latitude'], $data['longitude']);
        $data['approved'] = $this->punchService->checkApproved($data['is_late'], $data['is_early_leave'], $data['is_out_of_radius']);
        if ($data['is_out_of_radius'] == false) {
            list($locationId, $distance) = $this->punchService
                ->getNearestLocationForUser($userId, $data['latitude'], $data['longitude']);

            $data['location_id'] = $locationId;
            $data['distance_from_location'] = $distance;
        }
        $data['punched_at'] = now();
        $punch = Punch::create($data);
        $messages = $this->punchService->getMessages(
            $punch->approved,
            $punch->is_out_of_radius,
            $punch->is_late,
            $punch->is_early_leave
        );

        return $this->sendData([
            'punch' => $punch,
            'messages' => $messages
        ]);
    }
}
