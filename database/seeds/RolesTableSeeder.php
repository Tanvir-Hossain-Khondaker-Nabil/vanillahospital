<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'Super Admin' => 'super-admin',
            'Developer' => 'developer',
            'Admin' => 'admin',
            'Accountant' => 'accountant',
            'User' => 'user',
            'Project Manager' => 'project_manager',
            'Dealer' => 'dealer',
            'Sales Man' => 'sales_man',
            'Sale Manager' => 'sale_manager',
        ];

        foreach ($roles as $key => $role){

            $data = array();
            $data['name'] = $role;
            $data['display_name'] = $key;

            $saveRole = \App\Models\Role::create($data);

        }
    }
}
