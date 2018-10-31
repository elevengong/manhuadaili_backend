<?php

namespace App\Console\Commands;

use App\Model\Award;
use App\Model\Lottery;
use App\Model\Plan;
use Illuminate\Console\Command;

use QL\QueryList;

class Beijingsaiche extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'beijingsaiche';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '采集北京赛车';

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
        $this->caiji_history = 'http://6hcc99.com/history/beijingsaiche/beijingsaicheiframe.php';
        $this->caiji_plan = array(
            '25' => 'http://6hcc99.com/jihua/beijingsaiche/beijingsaicheiframett.php?p=gj',
            '26' => 'http://6hcc99.com/jihua/beijingsaiche/beijingsaicheiframe.php?p=yj',
            '27' => 'http://6hcc99.com/jihua/beijingsaiche/beijingsaicheiframe.php?p=jj'
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
        $writeContent = date('Y-m-d h:i:s',time()).'--开始采集北京赛车开奖记录start'."\r\n";
        $this->writeLogs($writeContent,$logHistoryDir,$logHistoryName);

        //通过$signature获取该彩种的l_id
        $lottery = Lottery::select('l_id','status')->where('nickname',$this->signature)->get()->toArray();
        if(empty($lottery))
        {
            $writeContent = date('Y-m-d h:i:s',time()).'--北京赛车不存在end'."\r\n";
            $this->writeLogs($writeContent,$logHistoryDir,$logHistoryName);
            exit;
        }
        if($lottery[0]['status'] == 0)
        {
            $writeContent = date('Y-m-d h:i:s',time()).'--北京赛车没有开启end'."\r\n";
            $this->writeLogs($writeContent,$logHistoryDir,$logHistoryName);
            exit;
        }
        $l_id = $lottery[0]['l_id'];

        $rules_1 = [
            'qishu' => ['.tr .time span','text'],
            'time' => ['.tr .time', 'text', '-span'],
            'number' => ['.tr .td-pk10 .pk10-num','text'],
            'other' => ['#table-pk10 .tr','html','-.time, -.td-pk10']
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
        for ($i=$count-1;$i>=0;$i--)
        {
            $input = array();
            $qishu = trim($datas[$i]['qishu']);
            $qishu_exist = Award::where('l_id',$l_id)->where('qishu',$qishu)->get()->toArray();
            if(empty($qishu_exist))
            {
                $input['l_id'] = $l_id;
                $input['qishu'] = $qishu;
                $numberArray = str_split(str_replace('10','a',trim($datas[$i]['number'])));
                $input['number'] = $this->getStr($numberArray);
                $other = $datas[$i]['other'];
                $otherArray = explode('<td',$other);
                $input['type1'] = $this->cut('">','</td>',$otherArray[1]);
                $input['type2'] = $this->cut('">','</td>',$otherArray[2]);
                $input['type3'] = $this->cut('">','</td>',$otherArray[3]);
                $input['type4'] = $this->cut('">','</td>',$otherArray[4]);
                $input['type5'] = $this->cut('">','</td>',$otherArray[5]);
                $input['type6'] = $this->cut('">','</td>',$otherArray[6]);
                $input['type7'] = $this->cut('">','</td>',$otherArray[7]);
                $input['type8'] = $this->cut('">','</td>',$otherArray[8]);
                Award::create($input);

            }
        }
        $writeContent = date('Y-m-d h:i:s',time()).'--北京赛车开奖记录采集完成end'."\r\n";
        $this->writeLogs($writeContent,$logHistoryDir,$logHistoryName);

        //开启采集幸运飞艇的计划
        $writeContent = date('Y-m-d h:i:s',time()).'--开启采集北京赛车计划start'."\r\n";
        $this->writeLogs($writeContent,$logPlanDir,$logPlanName);

        $rules_plan = [
            'detail1' => ['#table-cq_ssc td:eq(2)','text'],
            'detail2' => ['#table-cq_ssc td:eq(3)','html']
        ];

        foreach ($this->caiji_plan as $key => $value)
        {
            $ql_plan = QueryList::get($value)->rules($rules_plan)->query();
            //$this->writeLogs(print_r($ql_plan->getData(),true),$logHistoryDir,$logHistoryName);
            $planDatas = $ql_plan->getData();
            if(empty($planDatas))
            {
                $writeContent = date('Y-m-d h:i:s',time()).'--目标网站404-'.$value."\r\n";
                $this->writeLogs($writeContent,$logPlanDir,$logPlanName);
                continue;
            }

            $update['detail1'] = $planDatas[0]['detail1'];
            $update['detail2'] = $planDatas[0]['detail2'];
            if($update['detail1'] == '' and $update['detail2'] == '')
            {
                continue;
            }else{
                Plan::where('p_id',$key)->update($update);
            }
        }

        $writeContent = date('Y-m-d h:i:s',time()).'--北京赛车计划采集完成end'."\r\n";
        $this->writeLogs($writeContent,$logPlanDir,$logPlanName);


        exit;


    }

    private function getStr($array){
        $str = '';
        foreach ($array as $aa)
        {
            if($aa == 'a')
            {
                $str = $str.'10,';
            }else{
                $str = $str.$aa.',';
            }
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
