<?php

use Illuminate\Database\Seeder;

class DivisionDistrictUpazillaUnionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = public_path('data/developer/divisions.sql');
        \Illuminate\Support\Facades\DB::unprepared(file_get_contents($path));
        $this->command->info('Division table seeded!');

        $path = public_path('data/developer/districts.sql');
        \Illuminate\Support\Facades\DB::unprepared(file_get_contents($path));
        $this->command->info('District table seeded!');

        $path = public_path('data/developer/upazillas.sql');
        \Illuminate\Support\Facades\DB::unprepared(file_get_contents($path));
        $this->command->info('Upazilla table seeded!');

        $path = public_path('data/developer/unions.sql');
        \Illuminate\Support\Facades\DB::unprepared(file_get_contents($path));
        $this->command->info('Union table seeded!');
    }
}
