<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\MainController;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Device;
use App\Models\User;
use App\Notifications\VerfyEmail;
use Ichtrojan\Otp\Otp;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;



class AuthController extends MainController
{





    public function login(LoginRequest $request){



        $credentials = $request->only('email', 'password');
        $token=Auth::guard('api')->attempt($credentials);
        if (!$token) {
            return $this->messageError(__('auth.invalid_credentials'), 400);
        }
        $auth = Auth::guard('api')->user();

        $user=User::find($auth->id);

        $dataDevice = $request->only('token', 'device_type', 'imei');

        $user->devices()->updateOrCreate(
            [
                'user_id' => $user->id,
                'imei' => $dataDevice['imei'],
            ],
            array_merge($dataDevice, ['user_id' => $user->id])
        );



        if(!$user->active){
            return $this->messageError(__('auth.account_not_active'), 400);
        }

        return $this->sendData([
            'user' => new UserResource($user),
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], __('auth.login_successfully'));

    }



    public function logout(Request $request){
        Auth::guard('api')->logout();
        return $this->messageSuccess(__('auth.logout'));
    }
}
