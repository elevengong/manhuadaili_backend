<?php

namespace App\Console;

use Faker\Provider\Company;
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
        // Commands\Inspire::class,
        //Commands\AllConsole::class,
        //Commands\CaiJi::class,


        Commands\Xingyunfeiting::class,
        Commands\Xinjiangshishicai::class,
        Commands\Beijingsaiche::class,
        Commands\Chongqingshishicai::class,
        Commands\Xianggangliuhecai::class,
        Commands\Jiangsukuaisan::class,
        Commands\Guangdongshiyixuanwu::class

//          Commands\Test::class




    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        //$schedule->command('testconsole')->everyMinute();
        //$schedule->command('caiji')->hourly();


        $schedule->command('xingyunfeiting')->everyFiveMinutes()->timezone('Asia/Shanghai');
        $schedule->command('xinjiangshishicai')->everyFiveMinutes()->timezone('Asia/Shanghai');
        $schedule->command('beijingsaiche')->everyFiveMinutes()->timezone('Asia/Shanghai');
        $schedule->command('chongqingshishicai')->everyFiveMinutes()->timezone('Asia/Shanghai');
        $schedule->command('xianggangliuhecai')->hourly()->timezone('Asia/Shanghai');
        $schedule->command('jiangsukuaisan')->everyFiveMinutes()->timezone('Asia/Shanghai');
        $schedule->command('guangdongshiyixuanwu')->everyFiveMinutes()->timezone('Asia/Shanghai');

          //$schedule->command('test')->everyMinute();


    }
}
