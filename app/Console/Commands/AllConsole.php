<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class AllConsole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testconsole';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '这是一个测试Laravel定时任务的描述';

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
        Log::info('这是我写的log');
    }


}
