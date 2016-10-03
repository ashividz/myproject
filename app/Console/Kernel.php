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
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\ProductEmailer::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('product_email:send')
        ->dailyAt('11:00')
        ->appendOutputTo('/var/www/html/cron/emaillog')
        ->before(function () {
                $status = PHP_EOL.'-----------------------------------------------------------'.PHP_EOL;
                $status .= 'product_order_email_start_at :'.date('Y-m-d H:i:s').PHP_EOL;
                exec('echo "'.$status.'" >> '.' /var/www/html/cron/emaillog');
            })
        ->after(function() {
            $status = PHP_EOL.'-----------------------------------------------------------'.PHP_EOL;
                $status .= 'product_order_email_end_at :'.date('Y-m-d H:i:s').PHP_EOL;
                exec('echo "'.$status.'" >> '.' /var/www/html/cron/emaillog');
        });

        $schedule->command('inspire')
                 ->hourly();
    }
}
