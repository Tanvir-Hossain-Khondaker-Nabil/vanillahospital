<?php

namespace App\Http\Controllers\Auth;

use App\Http\Traits\SmsTrait;
use App\Http\Traits\VerifyUserTrait;
use App\Mail\UserRegisterConfirm;
use App\Mail\UserRegisterVerify;
use App\Models\Company;
use App\Models\Country;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Role;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\CompanyType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use SmsTrait, VerifyUserTrait;
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');

    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'company_name' => ['required', 'string'],
            // 'company_email' => ['required', 'string', 'email'],
            // 'phone' => ['required', 'string', 'unique:companies'],
            'company_address' => ['required', 'string'],
            'country_id' => ['required'],
            'num_of_emp' => ['required'],
            'company_type_id' => ['required'],
        ];

        if(!empty($data['phone']))
            $rules['phone'] = ['required', 'string','unique:users'];
        else
            $rules['email'] = ['required', 'string', 'email', 'max:255', 'unique:users'];

        return Validator::make($data,  $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $inputs = $data;
        
        $member = array();
        $member['member_code'] = member_code_generate();
        $member['api_access_key'] = api_access_key_generate($data['name']);
        $member['membership_id'] = 2;

        $packages = Membership::find(2);
        $member['expire_date'] = Carbon::now()->addMonths($packages->time_period);
        $member['country_id'] = $data['country_id'];
        $member['status'] = 'active';

        $inputs['full_name'] = $data['name'];
        $inputs['email'] =  $email = $data['email'];
        $inputs['phone'] = $phone_number = phone_number_format($data['phone']);
        $inputs['password'] = Hash::make($data['password']);
        $inputs['verify_token'] = $verify_token = verify_token_generate();
        $inputs['status'] = "active";


        $company = [];
        $company['company_name'] = $data['company_name'];
        $company['phone'] = $phone_number = phone_number_format($data['company_phone']);
        $company['email'] = $data['company_email'];
        $company['address'] = $data['company_address'];
        $company['country_id'] = $data['country_id'];
        $company['number_of_employee'] = $data['num_of_emp'];
        $company['company_type_id'] = $data['company_type_id'];
        $company['status'] = 'active';

        DB::beginTransaction();

        try{
            $saveMember = Member::create($member);
            $inputs['member_id'] = $saveMember->id;
            $inputs['membership_id'] = 2;

            $saveUser = User::create($inputs);

            $company['created_by'] = $saveUser->id;
            $company['member_id'] = $saveMember->id;
            $saveCompany = Company::create($company);

            // Find Admin Role
            $adminRole = Role::where('name', 'admin')->first();
            // Add Admin Roles
            $saveUser->attachRole($adminRole);

            $saveUser->company_id = $saveCompany->id;
            $saveUser->support_pin = generate_pin();
            $saveUser->save();

            $msg = "Thanks for your registration in Hisabe.";
            
            if(!empty($data['phone']))
                $this->sendSMS($phone_number, $msg);
            else
               $phone_number = $email;

            $this->sendAdminSMS($saveUser->full_name, $phone_number);
            // Mail::to($email)->send( new UserRegisterConfirm($data));
//            $this->sendVerifyToken($verify_token, $email, 'User');
            $status = ['type' => 'success', 'message' => "Registration Successfully"];

            DB::commit();

            return $saveUser;

        }catch (\Exception $e){

            $status = ['type' => 'danger', 'message' => 'Unable to User Add'];
            DB::rollBack();

            return back()->with('status', $status);
        }


    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $data['countries'] = Country::all()->pluck('countryName', 'id');
        $data['company_types'] = CompanyType::all()->pluck('name', 'id');
        return view('landing.pages.register', $data);
    }
}
