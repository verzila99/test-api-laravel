<?php

namespace App\Console;

use App\Jobs\UpdateOZONFboPostings;
use App\Jobs\UpdateOZONFbsPostings;
use App\Jobs\UpdateOZONStocks;
use App\Jobs\UpdateWBOrders;
use App\Jobs\UpdateWBPrices;
use App\Jobs\UpdateWBSales;
use App\Jobs\UpdateWBSalesReports;
use App\Jobs\UpdateWBStocks;
use App\Jobs\UpdateWBSupplies;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->job(new UpdateOZONFbsPostings)->timezone('Europe/Moscow')->dailyAt('15:43');
        $schedule->job(new UpdateOZONFboPostings)->timezone('Europe/Moscow')->dailyAt('15:43');
        $schedule->job(new UpdateOZONStocks)->timezone('Europe/Moscow')->dailyAt('15:43');
        $schedule->job(new UpdateWBOrders)->timezone('Europe/Moscow')->dailyAt('15:43');
        $schedule->job(new UpdateWBPrices)->timezone('Europe/Moscow')->dailyAt('15:43');
        $schedule->job(new UpdateWBSales)->timezone('Europe/Moscow')->dailyAt('15:43');
        $schedule->job(new UpdateWBSalesReports)->timezone('Europe/Moscow')->dailyAt('15:43');
        $schedule->job(new UpdateWBStocks)->timezone('Europe/Moscow')->dailyAt('15:43');
        $schedule->job(new UpdateWBSupplies)->timezone('Europe/Moscow')->dailyAt('15:43');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}