<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 3/20/2022
 * Time: 5:45 PM
 */

namespace App\Http\Services;


use App\Events\ProfitBalanceGenerate;
use App\Events\ProfitTransferToEquity;
use App\Models\FiscalYear;
use App\Models\ProfitBalanceTrack;
use Illuminate\Support\Facades\Auth;

class NetProfit
{
    public $request;
    public $fromDate;
    public $toDate;
    public $profit;

    public function __construct($fromDate, $toDate, $request, $profit)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->request = $request;
        $this->profit = $profit;
    }


    public function save()
    {

        $profitInsert = [];
        $profitInsert['balance'] = create_float_format($this->profit);

        if (!empty($this->request->year)) {
            $fiscal_year = FiscalYear::where('start_date', '>=', $this->fromDate)->where('end_date', '<=', $this->toDate)->first();
            $profitInsert['fiscal_year_id'] = $fiscal_year ? $fiscal_year->id : '';
        } elseif (!empty($this->request->fiscal_year)) {
            $fiscal_year = FiscalYear::find($this->request->fiscal_year);
            $profitInsert['fiscal_year_id'] = $fiscal_year->id;
        } else {

            if (Auth::user()->company) {
                $set_company_fiscal_year = Auth::user()->company->fiscal_year->first();
            }

            $profitInsert['fiscal_year_id'] = $set_company_fiscal_year->id;
        }

        $profitInsert['start_date'] = $this->fromDate;
        $profitInsert['end_date'] = $this->toDate;
        $profitInsert['company_id'] = $company_id = Auth::user()->company_id;

        $profitCheck = ProfitBalanceTrack::where('start_date', $this->fromDate)->where('end_date', $this->toDate)->where('company_id', $company_id)->first();

        if ($profitCheck) {
            $profitCheck->update($profitInsert);
        } else {
            $profitCheck = ProfitBalanceTrack::create($profitInsert);

        }

//        event(new ProfitBalanceGenerate($profitCheck));
        event(new ProfitTransferToEquity($profitCheck));


    }
}
