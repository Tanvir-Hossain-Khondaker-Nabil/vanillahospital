<?php

namespace App\Console\Commands;

use App\Http\Traits\CompanyInfoTrait;
use App\Http\Traits\DefaultEmailName;
use App\Models\Branch;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SalesReportSendbyDaily extends Command
{
    use CompanyInfoTrait, DefaultEmailName;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'salesReport:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a daily sales report by daily';

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

        $query = new Sale();
        $data['sales_report'] = $query->where('date', Carbon::today())
            ->select(
                'branch_id',
                DB::raw('SUM(total_price) as total_price'),
                DB::raw('SUM(paid_amount) as total_paid'),
                DB::raw('SUM(total_discount) as total_discount'),
                DB::raw('SUM(due) as total_due'),
                DB::raw('SUM(shipping_charge) as total_shipping_charge')
            )
            ->groupBy('branch_id')->get();
        $data['branches'] = Branch::get()->pluck('display_name', 'id');

        $data['report_title'] =  "Daily Sales Report";
        $data = $this->envCompany($data);
        $data = $this->defaultEmailPerson($data);

        $pdf = PDF::loadView('member.reports.print_sales_report_by_branch', $data);

        $data['name'] = "Mobarok Hossen";

        Mail::send('common.send_report_by_mail', $data, function($message)use($data, $pdf) {
            $message->to($data['email'] , $data['name'])
                ->subject("Daily Sales Report")
                ->attachData($pdf->output(), Carbon::today()."Daily_sales_report.pdf");
        });
    }
}
