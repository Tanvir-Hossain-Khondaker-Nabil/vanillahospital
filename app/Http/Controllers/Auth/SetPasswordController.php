<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class SetPasswordController extends Controller
{
    public function set_password(Request $request)
    {
        $request->validate($this->rules());

        $email = $request->email;
        $token = $request->verify_token;

        $user = User::where('email', $email)->where('verify_token', $token)->first();


        if($user)
        {
            $user->verify_token = '';
            $user->password = Hash::make($request->password);
            $user->email_verified_at = Carbon::now();
            $user->updated_by = $user->id;
            $user->status = "active";
            $user->save();

            return view('auth.confirmation');
        }


    }


    protected function rules()
    {
        return [
            'verify_token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ];
    }
}
