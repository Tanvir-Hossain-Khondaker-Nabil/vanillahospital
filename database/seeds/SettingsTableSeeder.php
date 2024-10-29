<?php

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\Company;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // set company fiscal year
        $company  = Company::where('id', 1)->first();
        $company->fiscal_year_id = 1;
        $company->save();

        // Save users company
        $user  = User::where('id', 1)->first();
        $user->company_id = 1;
        $user->save();

        $features = new \App\Services\CompanyFeature();
        $features = $features->defaultOptions();

        foreach ($features as $value)
        {
            $data = [];
            $data['company_id'] = 1;
            $data['key'] = $value;
            $data['value'] = true;

            \App\Models\Setting::create($data);
        }
    }
}