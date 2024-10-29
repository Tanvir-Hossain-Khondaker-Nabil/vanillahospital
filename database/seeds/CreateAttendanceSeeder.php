<?php

use Illuminate\Database\Seeder;

class CreateAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attend = new \App\Http\Controllers\Member\AttendanceController();
        $attend->generate_attendance();
    }
}
