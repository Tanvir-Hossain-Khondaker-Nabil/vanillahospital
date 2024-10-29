<?php

namespace App\Console\Commands;

use App\Http\Traits\CompanyInfoTrait;
use App\Http\Traits\DefaultEmailName;
use App\Models\Branch;
use App\Models\Item;
use App\Models\Stock;
use App\Models\StockReport;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class StockReportSendbyDaily extends Command
{
    use CompanyInfoTrait, DefaultEmailName;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stockReport:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a Daily Stock Report to Admin';

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
        $data['products'] = Item::get()->pluck('item_name', 'id');
        $data['branches'] = Branch::get()->pluck('display_name', 'id');

        $query = new StockReport();
        $query = $query->where('date','>=', Carbon::today());
        $data['stocks']  = $query->get();

        $data['report_title'] =  "Daily Stock Report";
        $data = $this->envCompany($data);
        $data = $this->defaultEmailPerson($data);

        $pdf = PDF::loadView('member.reports.print_daily_stock_report', $data);

        Mail::send('common.send_report_by_mail', $data, function($message)use($data, $pdf) {
            $message->to($data['email'] , $data['name'])
                ->subject("Daily Stock Report")
                ->attachData($pdf->output(), Carbon::today()."Daily_stock_report.pdf");
        });

    }
}
