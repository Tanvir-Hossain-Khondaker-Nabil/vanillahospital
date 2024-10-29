<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'full_name' => 'admin',
            'email' => 'admin@hisebi.com',
            'password' => Hash::make('admin'),
            'phone' => '01000000001',
            'member_id' => 1,
            'membership_id' => 1,
            'verify_token' => str_random(10),
        ]);

        // Find Admin Role
        $adminRole = Role::where('name', 'admin')->first();
        // Add Admin Roles
        $user->attachRole($adminRole);

        $user = User::create([
            'full_name' => 'developer',
            'email' => 'developer@hisebi.com',
            'password' => Hash::make('developer'),
            'member_id' => 1,
            'membership_id' => 1,
            'verify_token' => str_random(10),
        ]);

        // Find Admin Role
        $adminRole = Role::where('name', 'developer')->first();

        // Add User Roles
        $user->attachRole($adminRole);

        $user = User::create([
            'full_name' => 'superadmin',
            'email' => 'superadmin@hisebi.com',
            'password' => Hash::make('superadmin'),
            'member_id' => 1,
            'membership_id' => 1,
            'verify_token' => str_random(10),
        ]);

        // Find Super Admin Role
        $superAdminRole = Role::where('name', 'super-admin')->first();
        // Add Super Admin Roles
        $user->attachRole($superAdminRole);


        $user = User::create([
            'full_name' => 'master-member',
            'email' => 'mastermember@hisebi.com',
            'password' => Hash::make('123456'),
            'member_id' => 1,
            'membership_id' => 1,
            'verify_token' => verify_token_generate(),
        ]);

        // Find Master Member Role
        $memberRole = Role::where('name', 'master-member')->first();
        // Add Master Member Roles
        $user->attachRole($memberRole);

        // Find Admin Role
        $adminRole = Role::where('name', 'admin')->first();
        // Add Admin Roles
        $user->attachRole($adminRole);

    }
}
