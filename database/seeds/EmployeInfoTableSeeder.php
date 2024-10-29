<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as faker;
use App\Models\EmployeeInfo;
use App\Models\Designation;
use App\Models\Department;
use App\Models\Area;
use App\Models\Division;
use App\Models\District;
use App\Models\Country;
use App\Models\Shift;
use App\Models\Role;
use App\Models\User;
class EmployeInfoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $designation  = Designation::pluck('id');
        $department = Department::where('active_status', 1)->get();
        $designations = Designation::where('active_status', 1)->get();
        $area = Area::where('status', 'active')->get();
        $division = Division::where('active_status', 1)->get();
        $district = District::where('active_status', 1)->get();
        $countries = Country::get();
        $shift = Shift::get();

        foreach ($designations as $designation)
        {
        $first_name = Str::random(5);
        $last_name = Str::random(5);
        $inputs = [];
        $inputs['email'] = $email = $first_name.$last_name.'@gmail.com';
        $inputs['full_name'] = $first_name . " " . $last_name;
        $inputs['phone'] = rand(100000000000,999999999999);
        $roles = $request->roles ?? Role::where('name', 'User')->pluck('id')->toArray();
        $inputs['member_id'] = 1;
        $inputs['membership_id'] = 1;
        $inputs['password'] = Hash::make(123456);

            $inputs['company_id'] = 1;

        $inputs['verify_token'] = $verify_token = verify_token_generate();
        $inputs['status'] = $request->status ?? 'active';


        $inputs['support_pin'] = generate_pin();
        $saveUser = User::create($inputs);


        if (isset($roles))
            $saveUser->attachRoles($roles);

        $employeeInfo = new EmployeeInfo();
        $employeeInfo->employeeID = rand(100000000,999999999);
        $employeeInfo->first_name = $first_name;
        $employeeInfo->last_name = $last_name;
        $employeeInfo->phone2 = '01541635247';
        $employeeInfo->address = 'kachuai, chakrashala, Police station, Patiya , Chattogram';
        $employeeInfo->address2 = 'kachuai, chakrashala, Police station, Patiya , Chattogram';
        $employeeInfo->nid = rand(100000000,999999999);
        $employeeInfo->salary = rand(10000,99999);
        $employeeInfo->commission = 0;
        $employeeInfo->dob = '1996-08-18';
        $employeeInfo->join_date = '2023-01-01';
        $employeeInfo->company_id = 1;
        $employeeInfo->designation_id = $designation->id;
        // $employeeInfo->region_id = $request->region_id;
        $employeeInfo->area_id = $area[0]->id;
        // $employeeInfo->thana_id = $request->thana_id;
        $employeeInfo->district_id = $district[0]->id;
        $employeeInfo->division_id = $division[0]->id;

        $employeeInfo->nationality = $countries[0]->id;
        $employeeInfo->department_id = $department[0]->id;
        $employeeInfo->shift_id = $shift[0]->id;
        $employeeInfo->salary_system = 'Monthly';
        // $employeeInfo->weekend_accept = $request->weekend_accept;
        // $employeeInfo->passport_number = $request->passport_number;
        // $employeeInfo->passport_expire = db_date_format($request->passport_expire);
        // $employeeInfo->visa_expire = db_date_format($request->visa_expire);
        // $employeeInfo->pr_expire = db_date_format($request->pr_expire);
        // $employeeInfo->diving_license = $request->diving_license;
        // $employeeInfo->insurance_company = $request->insurance_company;
        // $employeeInfo->insurance_number = $request->insurance_number;
        // $employeeInfo->pr_number = $request->pr_number;

        $employeeInfo->user_id = $saveUser->id;
        $employeeInfo->save();
    }

}
}