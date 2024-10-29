<?php

use Illuminate\Database\Seeder;
use App\Models\Member;

class MemberTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $member = Member::create([
            'member_code' => member_code_generate(),
            'api_access_key' => api_access_key_generate('admin'),
            'country_id' => 19,
            'membership_id' => 2,
            'expire_date' => \Carbon\Carbon::maxValue(),
        ]);

    }
}