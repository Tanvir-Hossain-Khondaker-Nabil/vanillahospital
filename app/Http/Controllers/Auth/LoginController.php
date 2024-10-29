<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

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
            'email' => 'required',
            'password' => 'required|string',
        ]);


        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {

            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }


        // Find Admin
        $user = User::where('email', $request->input('email'))->orWhere('phone', $request->input('email'))->first();

        if (!$user ) return $this->sendFailedLoginResponse($request);

        $date = Carbon::parse($user->member->expire_date);
        $now = Carbon::now();

        $diff = $date->diffInDays($now);

        if($diff<5)
        {
            Session::flash('expired_days', $diff);
            Session::put('set_expired_days', $diff);
        }

        $expiresAt = now()->addMinutes(config('session.lifetime'));
        $request->session()->put('expires_at', $expiresAt);

        if ( !$user->hasRole(['super-admin']) &&  $user->member->expire_date <= Carbon::today()) return $this->sendMembershipExpireLoginResponse($request);


        if($user->hasRole(['admin','super-admin','developer','master-member'])){
            if (Auth::attempt(['id' => $user->id, 'password' => $request->input('password')])) {
                $this->setUserInfo();
                $this->saveUserLog();
                return redirect('/admin'.$this->redirectTo);
            }
        }else{

            if (Auth::attempt(['id' => $user->id, 'password' => $request->input('password')])) {
                $this->setUserInfo();
                $this->saveUserLog();
                return redirect('/member'.$this->redirectTo);
            }
        }



        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    private function saveUserLog()
    {

        $user = Auth::user();

        $data = [];
        $data['user_id'] = $user->id;
        $data['login_at'] = Carbon::today();
        $data['created_at'] = Carbon::today();
        $data['browser_history'] = request()->userAgent();
        $data['ip_address'] = request()->ip();
        $data['mac_address'] = exec('getmac');

        DB::table('login_logs')->insert($data);
    }


    private function setUserInfo()
    {
//        Session::put('company_logo',  Auth::user()->company->company_logo_path);
//        Session::put('company_name',  Auth::user()->company->company_name);
//        Session::put('company_address',  Auth::user()->company->address);
//        Session::put('company_city',  Auth::user()->company->city);
//        Session::put('company_country',  Auth::user()->company->country->countryName);
//        Session::put('company_phone',  Auth::user()->company->phone);

//        print_r( Session::all()); exit;
    }

    public function showLoginForm()
    {

        return view('landing.pages.login');
    }
    public function showEmployeeLoginForm()
    {

        return view('landing.pages.employee_login');
    }

    protected function sendMembershipExpireLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.membership_expire')],
        ]);
    }


}
