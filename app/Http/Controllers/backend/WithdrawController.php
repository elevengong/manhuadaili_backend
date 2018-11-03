<?php

namespace App\Http\Controllers\backend;

use App\Model\Daili;
use App\Model\OrderWithdraw;
use App\Model\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\MyController;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;

class WithdrawController extends MyController
{

    //daili withdraw list
    public function withdrawlist(){
        $orders = OrderWithdraw::where('daili_id',session('daili_id'))->orderBy('created_at','desc')->paginate(100);
        return view('backend.withdrawlist',compact('orders'));

    }

    //cancel withdraw order by daili
    public function cancelwithdraw(Request $request,$withdraw_id){
        if($request->isMethod('delete')){
            DB::beginTransaction();
            try {
                $OrderDetail = OrderWithdraw::where('withdraw_id', $withdraw_id)->where('daili_id', session('daili_id'))->lockForUpdate()->get()->toArray();
                $dailiDetail = Daili::where('daili_id', session('daili_id'))->lockForUpdate()->get()->toArray();

                if($OrderDetail[0]['status'] == 0)
                {
                    $current_commision = $dailiDetail[0]['current_commision'] + $OrderDetail[0]['withdraw_money'];
                    $frzon_commision = $dailiDetail[0]['frzon_commision'] - $OrderDetail[0]['withdraw_money'];

                    $re = OrderWithdraw::where('withdraw_id',$withdraw_id)->where('daili_id',session('daili_id'))->update(['status' => 3]);
                    $re1 = Daili::where('daili_id',session('daili_id'))->update(['current_commision' => $current_commision, 'frzon_commision' => $frzon_commision]);
                    if($re and $re1)
                    {
                        DB::commit();
                        $data['status'] = 1;
                        $data['msg'] = "成功取消提款订单";
                    }else{
                        DB::rollBack();
                        $data['status'] = 0;
                        $data['msg'] = "取消提款订单失败";
                    }
                    echo json_encode($data);
                }else{
                    $data['status'] = 0;
                    $data['msg'] = "参数 Error1!";
                    echo json_encode($data);
                }

            }catch (\Exception $e) {
                DB::rollBack();
                $data['status'] = 0;
                $data['msg'] = "Error!";
                echo json_encode($data);
            }
        }else{
            $data['status'] = 0;
            $data['msg'] = "参数 Error!";
            echo json_encode($data);
        }

    }

    public function setwithdrawaccount(Request $request){
        if($request->isMethod('post')){
            $input=$request->all();
            unset($input['_token']);
            $result = Daili::where('daili_id',session('daili_id'))->update($input);
            if($result)
            {
                $reData['status'] = 1;
                $reData['msg'] = "设置成功";
            }else{
                $reData['status'] = 0;
                $reData['msg'] = "设置失败";
            }
            echo json_encode($reData);
        }else{
            $daili = Daili::select('alipay','alipay_name','bank','bank_name','bank_accountname')->find(session('daili_id'))->toArray();
            return view('backend.setwithdrawaccount',compact('daili'));
        }

    }

    public function withdraw(Request $request){
        if($request->isMethod('post')){
            //判断当前代理是否填有支付信息
            $daili = Daili::select('alipay','alipay_name','bank','bank_name','bank_accountname')->find(session('daili_id'))->toArray();
            if(empty($daili['alipay']) and empty($daili['bank']))
            {
                $reData['status'] = 0;
                $reData['msg'] = "请先设置收款帐号!";
                echo json_encode($reData);
            }else{
                //判断前当代理是否已经有提款order
                $orderWithdrawCount = OrderWithdraw::where('daili_id',session('daili_id'))->where('status',0)->count();
                if($orderWithdrawCount >= 1){
                    $reData['status'] = 0;
                    $reData['msg'] = "当前已经有提款订单正在审核当中，如果想重新提款，请取消上一个提款订单!";
                    echo json_encode($reData);
                }else{
                    $reData['status'] = 1;
                    $reData['msg'] = "";
                    echo json_encode($reData);
                }
            }
        }else{
            $reData['status'] = 0;
            $reData['msg'] = "Error!";
            echo json_encode($reData);
        }
    }

    public function applywithdraw(Request $request){
        if($request->isMethod('post')){
            $withdrawcommision = request()->input('withdrawcommision');
            $paytype = request()->input('paytype');
            if(!is_numeric($withdrawcommision) or $withdrawcommision<=0)
            {
                $reData['status'] = 0;
                $reData['msg'] = "提款金额出错！请检查";
                echo json_encode($reData);
            }else{
                if($withdrawcommision < 100) {
                    $reData['status'] = 0;
                    $reData['msg'] = "提款金额最少100元";
                    echo json_encode($reData);
                }else{
                    $daili = Daili::select('current_commision')->find(session('daili_id'))->toArray();
                    //判断提款金额是否大于当前代理可提款金额
                    if($withdrawcommision > $daili['current_commision']){
                        $reData['status'] = 0;
                        $reData['msg'] = "提款金额大于可提款金额";
                        echo json_encode($reData);
                    }else{
                        //生成提款订单
                        DB::beginTransaction();
                        try{
                            $dailiDetail = Daili::where('daili_id', session('daili_id'))->lockForUpdate()->get()->toArray();
                            $current_commision = $dailiDetail[0]['current_commision'] - $withdrawcommision;
                            $frzon_commision = $dailiDetail[0]['frzon_commision'] + $withdrawcommision;
                            $re1 = Daili::where('daili_id',session('daili_id'))->update(['current_commision' => $current_commision, 'frzon_commision' => $frzon_commision]);
                            //----------生成提款订单
                            $input = array();
                            $input['daili_id'] = session('daili_id');
                            $input['withdraw_money'] = $withdrawcommision;
                            if($paytype == 0){
                                $input['withdraw_info'] = '支付宝:'.$dailiDetail[0]['alipay'].'--提款人:'.$dailiDetail[0]['alipay_name'];
                            }else{
                                $input['withdraw_info'] = '银行帐号:'.$dailiDetail[0]['bank'].'--开户银行:'.$dailiDetail[0]['bank_name'].'--开户人:'.$dailiDetail[0]['bank_accountname'];
                            }
                            $input['order_no'] = time().rand(10,99);
                            $input['status'] = 0;

                            $re = OrderWithdraw::create($input);
                            if($re->withdraw_id){
                                DB::commit();
                                $data['status'] = 1;
                                $data['msg'] = "成功生成订单，请耐心等待，谢谢！";
                            }else{
                                DB::rollBack();
                                $data['status'] = 0;
                                $data['msg'] = "生成订单失败，请联系客服，谢谢！";
                            }
                            echo json_encode($data);


                        }catch (\Exception $e) {
                            DB::rollBack();
                            $data['status'] = 0;
                            $data['msg'] = "Error!";
                            echo json_encode($data);
                        }
                    }
                }
            }




        }else{
            $daili = Daili::find(session('daili_id'))->toArray();
            return view('backend.applywithdraw',compact('daili'));
        }

    }







}
