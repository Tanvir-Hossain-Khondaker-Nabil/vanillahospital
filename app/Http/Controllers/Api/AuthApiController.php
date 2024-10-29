<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class AuthApiController extends Controller
{
    public function login(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|max:255',
            'password' => 'required|string',
        ]);

        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all(), 'status' =>'failed'], 422);
        }

        $user = User::where('email', $request->input('email'))->orWhere('phone', $request->input('email'))->first();

        if($user){

            if ( !$user->hasRole(['super-admin']) &&  $user->member->expire_date <= Carbon::today()) {
                $response = $this->sendMembershipExpireLoginResponse($request);
                return response($response, 422);

            }

            if (Hash::check($request->password, $user->password)) {

                $token = hash('sha256',Str::random(60));

                $user->forceFill([
                    'api_token' =>  $token,
                ])->save();

                Auth::login($user);

                $response = ['token' => $token, 'status' =>'success'];

                return response($response);

            } else {
                $response = ["message" => "Password mismatch", 'status' =>'failed'];
                return response($response, 422);
            }

        } else {

            $response = ["message" =>'User does not exist', 'status' =>'failed'];
            return response($response, 422);
        }

    }



    protected function sendMembershipExpireLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            "message" => [trans('auth.membership_expire')],
        ]);
    }


    public function logout()
    {
        $user = Auth::user();
        $user->api_token = null;
        $user->save();

        return response([ 'status'=>'success', 'message'=> 'Logout Successful' ]);
    }


}
