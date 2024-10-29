<?php

use App\Models\EmployeeInfo;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::statement("ALTER TABLE `users` CHANGE `full_name` `full_name` VARCHAR(700) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;");

        Excel::load(public_path('sample_excel/employee.xlsx'), function ($reader) {
            $data = $reader->toArray();

            foreach ($data as $key => $row) {

                $d_count = EmployeeInfo::where('designation_id', 1)
                    ->whereMonth('created_at', date("m"))
                    ->whereYear('created_at', date('Y'))
                    ->count();

                $roles = Role::where('name', 'User')->pluck('id')->toArray();

                $inputs = [];
                $inputs['email'] = $email = $row['nif']."@vanilathunder.com";
                $inputs['full_name'] = $row['name'];
                $inputs['member_id'] = 1;
                $inputs['membership_id'] = 1;
                $inputs['password'] = Hash::make("123456");
                $inputs['company_id'] = 1;
                $inputs['verify_token'] = verify_token_generate();
                $inputs['status'] = 'active';
                $inputs['support_pin'] = generate_pin();
                $saveUser = User::create($inputs);

                $saveUser->attachRoles($roles);

                $name = explode(" ", $row['name'], 2);

                $first_name = $name[0];
                $last_name = $name[1] ?? "";
                $dob = (string) $row['dob'];

                if(!is_string($row['dob']))
                {
                    $dob = $row['dob']->format("Y")."-".$row['dob']->format("d")."-".$row['dob']->format("m");
                }else{
                    $dob = DateTime::createFromFormat('d/m/Y', $dob)->format("Y-m-d");
                }


                $employee = [];
                $employee['employeeID'] = generate_employee_id($d_count, $saveUser->id);
                $employee['user_id'] = $saveUser->id;
                $employee['first_name'] = $first_name;
                $employee['last_name'] = $last_name;
                $employee['nid'] = $row['nif'];
                $employee['insurance_number'] = $row['nissn'];
                $employee['dob'] = $dob;
                $employee['company_id'] = 1;
                $employee['district_id'] = 1;
                $employee['division_id'] = 1;
                $employee['department_id'] = 5;
                $employee['designation_id'] = 11;
                $employee['shift_id'] = 3;
                $employee['nationality'] = 184;

                EmployeeInfo::create($employee);

            }

        });
    }
}
