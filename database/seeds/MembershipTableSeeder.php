<?php

use Illuminate\Database\Seeder;
use App\Models\Membership;

class MembershipTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $membership = Membership::create([
           'name' => snake_case('Main'),
           'display_text' => 'Main',
           'description' => 'Company Main Control',
           'price' => 0,
           'time_period' => 0,
        ]);

        $membership = Membership::create([
           'name' => snake_case('Trail'),
           'display_text' => 'Trail',
           'description' => 'Company Trail',
           'price' => 0,
           'time_period' => 3,
        ]);

        $membership = Membership::create([
           'name' => snake_case('Single Month'),
           'display_text' => 'Single Month',
           'description' => 'Monthly Used',
           'price' => 1000,
           'time_period' => 1,
        ]);

        $membership = Membership::create([
           'name' => snake_case('Half Yearly'),
           'display_text' => 'Half Yearly',
           'description' => 'Half Yearly Used',
           'price' => 5000,
           'time_period' => 6,
        ]);

        $membership = Membership::create([
           'name' => snake_case('Yearly'),
           'display_text' => 'Yearly',
           'description' => 'Yearly Used',
           'price' => 10000,
           'time_period' => 12,
        ]);

    }
}
