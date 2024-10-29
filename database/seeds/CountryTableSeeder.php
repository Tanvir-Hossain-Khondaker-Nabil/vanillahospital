<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = public_path('data/developer/countries.sql');

        DB::unprepared(file_get_contents($path));

        $this->command->info('Country table seeded!');
    }
}
