<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\StockReportSendbyDaily::Class,
        Commands\SalesReportSendbyDaily::Class,
        Commands\DailyStockReportCheckAndUpdate::class,
        Commands\DaywiseLedgerBalanceUpdate::class,
        Commands\StockRechecker::class,
        Commands\CreateDatabase::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

//        $schedule->command('stockReport:daily')
//            ->everyMinute();
//        $schedule->command('salesReport:daily')
//            ->everyMinute();
//        $schedule->command('backup:clean')->daily()->at('01:00');
//        $schedule->command('backup:run')->daily()->at('02:00');
        $schedule->command('stock:daily_stock_check_update');
        $schedule->command('account_head_day_wise_balance:update');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Get the timezone that should be used by default for scheduled events.
     *
     * @return \DateTimeZone|string|null
     */
    protected function scheduleTimezone()
    {
        return 'Asia/Dhaka';
    }
}
