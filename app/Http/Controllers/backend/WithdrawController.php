<?php

namespace App\Http\Controllers\backend;

use App\Model\Daili;
use App\Model\OrderWithdraw;
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








}
