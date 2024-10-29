<?php

use Illuminate\Database\Seeder;
use App\Models\FiscalYear;
use Carbon\Carbon;

class FiscalYearTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for ($i=1; $i<5; $i++) {

            $today = \Carbon\Carbon::now();
            $start_date = $i==1? db_date_format($today->startOfYear()): db_date_format($today->addYear($i-1)->startOfYear());
            $end_date = $i==1? db_date_format($today->endOfYear()): db_date_format($today->endOfYear());

            $inputs = [];
            $inputs['start_date'] = $start_date;
            $inputs['end_date'] = $end_date;
            $inputs['title'] = "FY (".formatted_date_string($start_date)."-".formatted_date_string($end_date).")";

            $inputs['member_id'] = 1;
            $inputs['created_by'] = 1;
            $inputs['status'] = 'active';
            FiscalYear::create($inputs);
        }
    }
}
