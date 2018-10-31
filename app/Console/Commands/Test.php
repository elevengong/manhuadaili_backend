<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;


class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test';

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
        $Content = date('Y-m-d h:i:s',time())."\r\n";
        $txtFile = storage_path('logs').DIRECTORY_SEPARATOR."1.txt";
        $this->writeLogs($Content,$txtFile);

        exit;



    }

    private function writeLogs($Content,$txtFile){
        $myfile = fopen($txtFile, "a");
        fwrite($myfile, $Content);
        fclose($myfile);
        return true;

    }




}
