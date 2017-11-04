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
        \App\Console\Commands\ProductReminderMail::class,
        \App\Console\Commands\AutoAdjustStartDate::class,
        \App\Console\Commands\DndChecker::class,
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
        ->weekly()->sundays()->at('9:30')

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

        $schedule->command('product_reminder:send')
        ->weekly()->sundays()->at('9:10')

        ->appendOutputTo('/var/www/html/cron/emaillog')
        ->before(function () {
                $status = PHP_EOL.'-----------------------------------------------------------'.PHP_EOL;
                $status .= 'product_reorder_email_start_at :'.date('Y-m-d H:i:s').PHP_EOL;
                exec('echo "'.$status.'" >> '.' /var/www/html/cron/emaillog');
            })
        ->after(function() {
            $status = PHP_EOL.'-----------------------------------------------------------'.PHP_EOL;
                $status .= 'product_reorder_email_end_at :'.date('Y-m-d H:i:s').PHP_EOL;
                exec('echo "'.$status.'" >> '.' /var/www/html/cron/emaillog');
        });

        $schedule->command('fee:adjuststartdate')
        ->dailyAt('10:30')
        ->appendOutputTo('/var/www/html/cron/feelog')
        ->before(function () {
                $status = PHP_EOL.'-----------------------------------------------------------'.PHP_EOL;
                $status .= 'fee_adjust_start_at :'.date('Y-m-d H:i:s').PHP_EOL;
                exec('echo "'.$status.'" >> '.' /var/www/html/cron/feelog');
            })
        ->after(function() {
            $status = PHP_EOL.'fee_adjust_end_at :'.date('Y-m-d H:i:s').PHP_EOL;
            $status .= '-----------------------------------------------------------'.PHP_EOL;
            exec('echo "'.$status.'" >> '.' /var/www/html/cron/feelog');
        });


        /*$schedule->command('dnd:check') 
        ->everyTenMinutes()
        ->withoutOverlapping()
        ->appendOutputTo('/var/www/html/cron/dndlog')
        ->before(function () {
                $status = PHP_EOL.'-----------------------------------------------------------'.PHP_EOL;
                $status .= 'dnd_check_start_at :'.date('Y-m-d H:i:s').PHP_EOL;
                exec('echo "'.$status.'" >> '.' /var/www/html/cron/dndlog');
            })
        ->after(function() {
            $status = PHP_EOL.'dnd_check_end_at :'.date('Y-m-d H:i:s').PHP_EOL;
            $status .= '-----------------------------------------------------------'.PHP_EOL;
            exec('echo "'.$status.'" >> '.' /var/www/html/cron/dndlog');
        });*/

        $schedule->command('inspire')
                 ->hourly();
    }
}
