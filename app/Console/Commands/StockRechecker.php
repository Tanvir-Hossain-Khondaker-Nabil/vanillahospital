<?php

namespace App\Console\Commands;

use App\Http\Traits\StockTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class StockRechecker extends Command
{

    use StockTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:recheck';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stock Recheck and Update every 5 min';

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
        $date = Carbon::today()->format('Y-m-d');
        $this->update_stock_report('', $date);

        $this->info('Stock Rechecked and Updated');

    }
}
