<?php

namespace App\Console\Commands;

use App\Http\Traits\TransactionTrait;
use App\Models\AccountHeadDayWiseBalance;
use App\Models\Company;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DaywiseLedgerBalanceUpdate extends Command
{
    use TransactionTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account_head_day_wise_balance:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Two weekly account head day wise balance report Check and update auto.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $tenants = DB::connection("landlord")->table('tenants')->get();

        foreach ($tenants as $tenant) {

            Config::set("database.connections.mysql", [
                "driver" => 'mysql',
                "host" => env('DB_HOST', '127.0.0.1'),
                "database" => $tenant->database,
                "username" => env('DB_USERNAME', 'root'),
                "password" => env('DB_PASSWORD', '')
            ]);

            DB::connection('mysql')->statement('Delete from transaction_details where transaction_id not in (select id from transactions)');

            $company = new Company();
            $company = $company->setConnection('mysql')->active()->get();

            foreach ($company as $value2) {
                
                $transactionDetails = new TransactionDetail();
                $transactionDetails = $transactionDetails->setConnection('mysql');
                
                $accountHeads = $transactionDetails->where('company_id', $value2->id)
                    ->groupBy('account_type_id')->get()->pluck('account_type_id');

                foreach ($accountHeads as $value1) {
                    $accountTransactionDate = $transactionDetails->where('account_type_id', $value1)->where('company_id', $value2->id)->where('amount', '!=', 0)->groupBy('date')->get()->pluck('date');

                    $accountHeadDayWiseBalance = new AccountHeadDayWiseBalance();
                    $accountHeadDayWiseBalance = $accountHeadDayWiseBalance->setConnection('mysql');

                    $accountHeadDayWiseBalance->whereNotIn('date', $accountTransactionDate)->where('account_type_id', $value1)->where('company_id', $value2->id)->delete();

                    $transactions = $transactionDetails->selectRaw('account_type_id, sum(amount) as sum_amount, transaction_type, date, company_id')
                        ->where('transaction_details.amount', '>', 0)
                        ->where('account_type_id', $value1)
                        ->where('company_id', $value2->id)
                        ->groupBy('date')
                        ->groupBy('transaction_type')
                        ->orderBy('transaction_details.date', 'asc')
                        ->orderBy('company_id', "asc")
                        ->get();

                    $balance = 0;
                    foreach ($transactions as $value) {
                        $inputs = [];
                        $date = $inputs['date'] = db_date_format($value->date);
                        $account_cash = $inputs['account_type_id'] = $value->account_type_id;
                        $headBalance = $accountHeadDayWiseBalance->where('account_type_id', $account_cash)
                            ->where('company_id', $value2->id)
                            ->where('date', $date)
                            ->first();

                        if ($headBalance) {
                            if ($value->transaction_type == 'dr') {
                                $balance += $value->sum_amount;
                            } else {
                                $balance -= $value->sum_amount;
                            }

                            $headBalance->balance = create_float_format($balance);
                            $headBalance->update();

                        } else {
                            $accountHeadDayWiseBalance->create(
                                [
                                    'account_type_id' => $account_cash,
                                    'date' => $date,
                                    'balance' => $balance,
                                    'company_id' => $value2->id,
                                ]
                            );
                        }
                    }


                    $this->adjustmentBalance($value1);
                }
            }
            Log::channel('daywiseLedgerAccountUpdate')->info(Carbon::now().' :-'.$tenant->database.' Account Head Daywise Balance check and update done successfully');

        }


    }
}
