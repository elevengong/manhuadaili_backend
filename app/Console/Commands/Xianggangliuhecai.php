<?php

namespace App\Console\Commands;

use App\Model\Award;
use App\Model\Lottery;
use App\Model\Plan;
use Illuminate\Console\Command;

use QL\QueryList;

class Xianggangliuhecai extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xianggangliuhecai';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '香港六合彩';

    //自定义变量
    private $caiji_history = '';
    private $caiji_plan = array();

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->caiji_history = 'https://www.6hck01.com/marksix/history?type=1';
        $this->caiji_plan = array(
            '10' => 'http://6hcc99.com/jihua/xianggangliuhecai/xinshui.php?p=01',
            '11' => 'http://6hcc99.com/jihua/xianggangliuhecai/xinshui.php?p=02',
            '12' => 'http://6hcc99.com/jihua/xianggangliuhecai/xinshui.php?p=03',
            '13' => 'http://6hcc99.com/jihua/xianggangliuhecai/xinshui.php?p=04',
            '14' => 'http://6hcc99.com/jihua/xianggangliuhecai/xinshui.php?p=05',
            '15' => 'http://6hcc99.com/jihua/xianggangliuhecai/xinshui.php?p=06',
            '16' => 'http://6hcc99.com/jihua/xianggangliuhecai/xinshui.php?p=07',
            '17' => 'http://6hcc99.com/jihua/xianggangliuhecai/xinshui.php?p=08',
            '18' => 'http://6hcc99.com/jihua/xianggangliuhecai/xinshui.php?p=09',
            '19' => 'http://6hcc99.com/jihua/xianggangliuhecai/xinshui.php?p=10',
            '20' => 'http://6hcc99.com/jihua/xianggangliuhecai/xinshui.php?p=11',
            '21' => 'http://6hcc99.com/jihua/xianggangliuhecai/xinshui.php?p=12',
            '22' => 'http://6hcc99.com/jihua/xianggangliuhecai/xinshui.php?p=13',
            '23' => 'http://6hcc99.com/jihua/xianggangliuhecai/xinshui.php?p=14',
            '24' => 'http://6hcc99.com/jihua/xianggangliuhecai/xinshui.php?p=15'

        );
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //采集开奖记录--每五分钟采集一次
        $logHistoryDir = storage_path('logs/lottery/').DIRECTORY_SEPARATOR.$this->signature.DIRECTORY_SEPARATOR;
        $logHistoryName = date('Ymd',time()).".txt";
        $logPlanDir = storage_path('logs/plan/').DIRECTORY_SEPARATOR.$this->signature.DIRECTORY_SEPARATOR;
        $logPlanName = date('Ymd',time()).".txt";
        $writeContent = date('Y-m-d h:i:s',time()).'--开始采集香港六合彩开奖记录start'."\r\n";
        $this->writeLogs($writeContent,$logHistoryDir,$logHistoryName);

        //通过$signature获取该彩种的l_id
        $lottery = Lottery::select('l_id','status')->where('nickname',$this->signature)->get()->toArray();
        if(empty($lottery))
        {
            $writeContent = date('Y-m-d h:i:s',time()).'--香港六合彩不存在end'."\r\n";
            $this->writeLogs($writeContent,$logHistoryDir,$logHistoryName);
            exit;
        }
        if($lottery[0]['status'] == 0)
        {
            $writeContent = date('Y-m-d h:i:s',time()).'--香港六合彩没有开启end'."\r\n";
            $this->writeLogs($writeContent,$logHistoryDir,$logHistoryName);
            exit;
        }
        $l_id = $lottery[0]['l_id'];

        $rules_1 = [
            'all' => ['.history_numbers tr','html']
        ];
        $ql = QueryList::get($this->caiji_history)->rules($rules_1)->query();
        //$this->writeLogs(print_r($ql->getData(),true),$logHistoryDir,$logHistoryName);

        $datas = $ql->getData();

        if(empty($datas))
        {
            $writeContent = date('Y-m-d h:i:s',time()).'--目标网站404'."\r\n";
            $this->writeLogs($writeContent,$logHistoryDir,$logHistoryName);
            exit;
        }

        $count = count($datas);
        for ($i=$count-1;$i>=1;$i--)
        {
            $input = array();
            $qishu = trim($this->cut('<td>','期',$datas[$i]['all']));
            $qishu_exist = Award::where('l_id',$l_id)->where('qishu',$qishu)->get()->toArray();

            if(empty($qishu_exist))
            {
                $input['l_id'] = $l_id;
                $input['qishu'] = $qishu;

                $numberContent = $this->cut('class="pl_17 numbre">','</td>',$datas[$i]['all']);
                $numberArray = explode('class="data">',$numberContent);
                $input['number'] = trim($this->getStr($numberArray));
                $input['award_time'] = trim($this->cut('<br>','</td>',$datas[$i]['all'])).' 00:00:00';

                $input['type1'] = $this->getSpecial($numberArray);


                $otherArray = explode('<td',$datas[$i]['all']);
                $countType = count($otherArray);
                //$this->writeLogs(print_r($otherArray,true),$logHistoryDir,$logHistoryName);exit;
                for ($j=$countType-1;$j>=$countType-4;$j--)
                {
                    if($j==$countType-1)
                    {
                        $input['type5'] = trim($this->cut('>','</td>',$otherArray[$j]));
                    }
                    if($j==$countType-2)
                    {
                        $input['type4'] = trim($this->cut('>','</td>',$otherArray[$j]));
                    }
                    if($j==$countType-3)
                    {
                        $input['type3'] = trim($this->cut('>','</td>',$otherArray[$j]));
                    }
                    if($j==$countType-4)
                    {
                        $input['type2'] = trim($this->cut('>','</td>',$otherArray[$j]));
                        //$this->writeLogs(print_r($otherArray[$j],true),$logHistoryDir,$logHistoryName);
                    }
                }

                Award::create($input);

            }
        }
        $writeContent = date('Y-m-d h:i:s',time()).'--香港六合彩开奖记录采集完成end'."\r\n";
        $this->writeLogs($writeContent,$logHistoryDir,$logHistoryName);

        //开启采集香港六合彩的计划
        $writeContent = date('Y-m-d h:i:s',time()).'--开启采集香港六合彩计划start'."\r\n";
        $this->writeLogs($writeContent,$logPlanDir,$logPlanName);

        $rules_plan = [
//            'detail1' => ['p font','html']
            'detail1' => ['td p','html']
        ];

        foreach ($this->caiji_plan as $key => $value)
        {
            $ql_plan = QueryList::get($value)->rules($rules_plan)->query();
            //$this->writeLogs(print_r($ql_plan->getData(),true),$logHistoryDir,$logHistoryName);exit;
            $planDatas = $ql_plan->getData();
            if(empty($planDatas))
            {
                $writeContent = date('Y-m-d h:i:s',time()).'--目标网站404-'.$value."\r\n";
                $this->writeLogs($writeContent,$logPlanDir,$logPlanName);
                continue;
            }

            $update['detail2'] = $this->getTogether($planDatas);
            //$this->writeLogs(print_r($update['detail2'],true),$logHistoryDir,$logHistoryName);exit;
            Plan::where('p_id',$key)->update($update);

        }

        $writeContent = date('Y-m-d h:i:s',time()).'--香港六合彩计划采集完成end'."\r\n";
        $this->writeLogs($writeContent,$logPlanDir,$logPlanName);


        exit;


    }

    private function getTogether($array){
        $str = '';
        foreach ($array as $a)
        {
            if($a['detail1']!='')
            {
                $str = $str.'<p>'.$a['detail1'].'</p>';
            }
        }
        return $str;

    }

    private function getSpecial($array){
        $str = '';
        $count = count($array);
        $str = $str.trim($this->cut('">','</span>',$array[$count-1])).',';
        $str = $str.trim($this->cut('<b>','</b>',$array[$count-1]));
        //$newstr = substr($str,0,strlen($str)-1);

        return $str;
    }

    private function getStr($array){
        $str = '';
        $count = count($array);
        for ($i=1;$i<$count-1;$i++)
        {
            $str = $str.trim($this->cut('">','</span>',$array[$i])).',';
            $str = $str.trim($this->cut('<b>','</b>',$array[$i])).';';
        }
        $newstr = substr($str,0,strlen($str)-1);

        return $newstr;
    }




    private function writeLogs($Content,$dir,$txtFileName){
        $this->createDir($dir);
        $myfile = fopen($dir.$txtFileName, "a");
        fwrite($myfile, $Content);
        fclose($myfile);
        return true;

    }

    //创建文件夹
    private function createDir($path)
    {
        if (is_dir($path)) {
            return true;
        } else {
            $res = mkdir($path, 0777, true);
            return true;
        }

    }

    private function cut($begin,$end,$str){
        $b = mb_strpos($str,$begin) + mb_strlen($begin);
        $e = mb_strpos($str,$end) - $b;

        return mb_substr($str,$b,$e);
    }


}
