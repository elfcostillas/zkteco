<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            $response = Http::accept('application/json')->get('127.0.0.1/zk/');

            $att = [];

            $logs = json_decode($response);

            if($logs->Row)
            {
                foreach($logs->Row as $log)
                {
                    $datetime = Carbon::createFromFormat('Y-m-d H:i:s',$log->DateTime);

                    switch($log->Status) {
                        case 0 : case '0':
                                $cstate = 'C/In';
                            break;
                        
                        case 1 : case '1':
                                $cstate = 'C/Out';
                            break;
                        case 2 : case '2':
                                $cstate = 'OT/In';
                            break;
                        case 3 : case '3':
                                $cstate = 'OT/Out';
                            break;
                    }

                    DB::table('biometric_raw')
                            ->updateOrInsert(
                        [
                            'biometric_id' => $log->PIN,
                            'punch_date' => $datetime->format('Y-m-d'),
                            'punch_time' => $datetime->format('H:i'),
                        ],
                        [
                            'cstate' => $cstate,
                            'state' => $log->Status
                        ]
                    );

                }

            }    
        })->everyFiveMinutes();
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

/*
->everyMinute();	Run the task every minute
->everyTwoMinutes();	Run the task every two minutes
->everyThreeMinutes();	Run the task every three minutes
->everyFourMinutes();	Run the task every four minutes
->everyFiveMinutes();	Run the task every five minutes
->everyTenMinutes();	Run the task every ten minutes
->everyFifteenMinutes();	Run the task every fifteen minutes
->everyThirtyMinutes();	Run the task every thirty minutes
->hourly();	Run the task every hour
->hourlyAt(17);	Run the task every hour at 17 minutes past the hour
->everyTwoHours();	Run the task every two hours
->everyThreeHours();	Run the task every three hours
->everyFourHours();	Run the task every four hours
->everySixHours();	Run the task every six hours
->daily();	Run the task every day at midnight
*/