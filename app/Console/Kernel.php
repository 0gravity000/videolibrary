<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Events\DailyCheckAmazonPrimeVideo01;
use App\Events\CheckVideosTableYear;
use App\Events\CheckVideosTableSeason;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            event(new DailyCheckAmazonPrimeVideo01());
        })->dailyAt('3:00');    //毎日3:00に実行する

        /* 必要なときだけコメントをはずす
        $schedule->call(function () {
            event(new CheckVideosTableYear());
        })->dailyAt('4:00');    //毎日4:00に実行する
        */

        /* 必要なときだけコメントをはずす
        $schedule->call(function () {
            event(new CheckVideosTableSeason());
        })->dailyAt('4:00');    //毎日4:00に実行する
        */
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
}
