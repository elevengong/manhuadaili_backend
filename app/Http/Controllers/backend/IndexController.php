<?php

namespace App\Http\Controllers\backend;

use App\Model\Attribute;
use App\Model\Daili;
use App\Model\OrderDeposit;
use App\Model\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\MyController;
use App\Http\Requests;
use Crypt;

class IndexController extends MyController
{
    public function index(Request $request){
        return view('backend.layout.main')->with('daili_name',session('daili_name'));
    }

    public function showindex(){
        $dailiInfo = Daili::find(session('daili_id'))->toArray();
        $statics = Attribute::orderBy('id','asc')->get()->toArray();
        $staticArray = array();
        foreach ($statics as $value){
            $staticArray[$value['id']] = $value['value'];
        }
        $date = date('Y-m-d',time());
        //获取当前代理今天到目前为目的佣金总额
        echo $todayStart = date('Y-m-d',time()).' 00:00:00';
        echo "<br>";
        echo $todayEnd = date('Y-m-d',time()).' 23:59:59';
        $todaycommision = 0;
        $todaycommisionArray = OrderDeposit::where('daili_id',session('daili_id'))->where('status',1)->where('pay_daili',1)->where('deal_time','>',$todayStart)->where('deal_time','<',$todayEnd)->get()->toArray();
        if(!empty($todaycommisionArray))
        {
            foreach ($todaycommisionArray as $array)
            {
                $todaycommision = $todaycommision + round($array['order_money'] * $dailiInfo['commission_rate'],2);
            }
        }
        return view('backend.index',compact('dailiInfo','staticArray','date','todaycommision'));
    }

    //注销用户
    public function logout(Request $request){
        $this->deleteAllSession($request);
        $data['status'] = 1;
        $data['msg'] = "注销成功";
        echo json_encode($data);
    }

    //改当前代理密码
    public function changepwd(Request $request){
        if($request->isMethod('post')){
            $newpwd = request()->input('newpwd');
            $newPassword= Crypt::encrypt($newpwd);

            $result = Daili::where('daili_id',session('daili_id'))->update(['pwd'=>$newPassword]);

            if($result)
            {
                $data['status'] = 1;
                $data['msg'] = "修改成功";
            }else{
                $data['status'] = 0;
                $data['msg'] = "修改失败";
            }
            echo json_encode($data);

        }

    }

    //代理下线注册
    public function userlist(){
        $users = Users::where('daili_id',session('daili_id'))->orderBy('created_at','desc')->paginate($this->backendPageNum);
        return view('backend.userlist',compact('users'));
    }

}
