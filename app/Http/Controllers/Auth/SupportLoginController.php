<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class SupportLoginController extends Controller
{
    
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'support_pin' => 'required',
        ]);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }


        $user = User::where('email', $request->input('email'))->orWhere('phone', $request->input('email'))->first();
        if (!$user ) return $this->sendFailedLoginResponse($request);
        $support_pin = Crypt::decryptString($user->support_pin);

        if($support_pin == $request->input('support_pin'))
        {
            
            if($user->hasRole(['admin','super-admin','developer','master-member'])){
              
                if (Auth::login($user)) {
                   
                    return redirect('/admin'.$this->redirectTo);
                }
            }else{
                if (Auth::login($user)) {
              
                    return redirect('/member'.$this->redirectTo);
                }
            }
        }else{

        }




        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    
    public function showLoginForm()
    {
        return view('landing.pages.support_pin_login');
    }
}
