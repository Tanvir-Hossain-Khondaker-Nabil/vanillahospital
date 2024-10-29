<?php

use Illuminate\Database\Seeder;

class CompanyTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
             'Software', 'Trading', 'Grocery', 'E-Commerce'
        ];


        foreach ($data as  $item) {
            \App\Models\CompanyType::create([
                'name' => $item,
            ]);

        }
    }
}
