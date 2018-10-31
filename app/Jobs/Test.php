<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class Test extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $logFile = storage_path('logs/').'queue.txt';
        $failTime = $this->attempts();// 返回该队列失败次数
        $Content = '执行次数'.$failTime.' Id:'.$this->id.'  create at '.date('Y-m-d H:i:s',time())." \r\n";
        $this->writeLogs($Content,$logFile);

        //$this->release(10);// 将任务放回到队列,10秒后次执行

    }

    private function writeLogs($Content,$logFile){
        $myfile = fopen($logFile, "a");
        fwrite($myfile, $Content);
        fclose($myfile);
        return true;

    }

}
