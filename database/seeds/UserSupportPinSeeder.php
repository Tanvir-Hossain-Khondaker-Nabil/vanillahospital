<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSupportPinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach($users as $user)
        {
            $user->support_pin = generate_pin();
            $user->save();
        }
    }
}
