<?php

namespace App\Console\Commands;

use App\Models\Stock;
use App\Models\StockReport;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DailyStockReportCheckAndUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:daily_stock_check_update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Stock report Check and update auto everyday night';

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

        foreach ($tenants as $tenant)
        {
            Config::set("database.connections.mysql", [
                "driver" => 'mysql',
                "host" => env('DB_HOST', '127.0.0.1'),
                "database" => $tenant->database,
                "username" => env('DB_USERNAME','root'),
                "password" => env('DB_PASSWORD', '')
            ]);

            $stock_report = new StockReport();
            $stock_report = $stock_report->setConnection('mysql')->orderBy('item_id','asc')->orderBy('date','asc')->get();


            $item_id = "";
            foreach ($stock_report as $key => $value)
            {
                if($value->item_id != $item_id)
                {

                    if($item_id>0 )
                    {
                        $stock = new Stock();
                        $stock = $stock->setConnection('mysql')->where('item_id', $item_id)->first();
                        $stock->stock = $closing_qty;
                        $stock->save();
                    }

                    $opening_qty = $value->opening_stock;
                }else{
                    $opening_qty = $closing_qty;
                }

                $stock = new StockReport();
                $stock  = $stock->setConnection('mysql')->find($value->id);

                $item_id = $value->item_id;

                $sale_qty =  DB::connection("mysql")->table("sales")
                    ->leftJoin('sales_details', 'sales_details.sale_id', 'sales.id')
                    ->where('sales.date', $value->date)
                    ->where('sales_details.item_id', $item_id)
                    ->groupBy('sales_details.item_id')
                    ->groupBy('sales.date')
                    ->sum('sales_details.qty');

                $free_qty =  DB::table("sales")
                    ->leftJoin('sales_details', 'sales_details.sale_id', 'sales.id')
                    ->where('sales.date', $value->date)
                    ->where('sales_details.item_id', $item_id)
                    ->groupBy('sales_details.item_id')
                    ->groupBy('sales.date')
                    ->sum('sales_details.free');

                $sale_qty = $free_qty+$sale_qty;

                $purchase_qty =  DB::connection("mysql")->table("purchases")
                    ->leftJoin('purchase_details', 'purchase_details.purchase_id', 'purchases.id')
                    ->where('purchases.date', $value->date)
                    ->where('purchase_details.item_id', $item_id)
                    ->groupBy('purchase_details.item_id')
                    ->groupBy('purchases.date')
                    ->sum('purchase_details.qty');

                $sale_return_qty =  DB::table("sales")
                    ->leftJoin('sales_return', 'sales_return.sale_id', 'sales.id')
                    ->where('sales_return.return_date', $value->date)
                    ->where('sales_return.item_id', $item_id)
                    ->groupBy('sales_return.item_id')
                    ->groupBy('sales_return.return_date')
                    ->sum('sales_return.return_qty');

                $purchase_return_qty =  DB::table("purchases")
                    ->leftJoin('return_purchases', 'return_purchases.purchase_id', 'purchases.id')
                    ->where('return_purchases.return_date', $value->date)
                    ->where('return_purchases.item_id', $item_id)
                    ->groupBy('return_purchases.item_id')
                    ->groupBy('return_purchases.return_date')
                    ->sum('return_purchases.return_qty');

                $stock->opening_stock = $opening_qty;
                $stock->sale_qty = $sale_qty;
                $stock->purchase_qty =  $purchase_qty;
                $stock->sale_return_qty = $sale_return_qty;
                $stock->purchase_return_qty = $purchase_return_qty;

                $closing_qty = $opening_qty+$purchase_qty-$sale_qty+$sale_return_qty-$purchase_return_qty-$stock->loss_qty+$stock->stock_overflow_qty;
                $closing_qty = create_float_format($closing_qty);

                $stock->closing_qty = $closing_qty;

                $stock->update();

                if(count($stock_report)-1 == $key )
                {
                    $stock = new Stock();
                    $stock = $stock->setConnection('mysql')->where('item_id', $item_id)->first();
                    $stock->stock = $closing_qty;
                    $stock->save();
                }
            }


            Log::channel('dailyStockUpdate')->info(Carbon::now().' :-'.$tenant->database.': Daily Stock check and update done successfully');

        }


    }
}
