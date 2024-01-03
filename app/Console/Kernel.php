<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SendSmsNotifications;
use App\Console\Commands\SendEmailNotifications;
use App\Console\Commands\WatsaapNotification;
use App\Console\Commands\Trouboshooting;
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
 
          $schedule->command('insert:data')->everyMinute();

           //$schedule->command(Trouboshooting::class)->everySecond();
         // $schedule->command(Trouboshooting::class)->everyMinute();
            // $schedule->command(SendEmailNotifications::class)
            // ->everyMinute();
            // ->dailyAt('15:51')
            // ->timezone('Africa/Luanda');

            //  $schedule->command('EmailReminder:email')
            //              ->dailyAt('08:00'); // Adjust the time as needed



            // $schedule->command('backup:clean')->daily()->at('01:00');



            // $schedule->command('backup:run')
            // ->dailyAt('22:15')
            // ->timezone('Africa/Luanda');

        // $schedule->command(WatsaapNotification::class)->everyMinute();

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


