<?php

namespace App\Http\Controllers\backend;

use App\Model\Withdraw;
use App\Model\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\MyController;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;

class WithdrawController extends MyController
{
    //站长提款审核列表
    public function applywithdraw(Request $request){
        if($request->isMethod('post'))
        {
            $member = request()->input('member');
            $WithdrawArray = Withdraw::select('withdraw.*','member.name','paytype.paytype')->where('withdraw.status',0)
                ->leftJoin('member',function ($join){
                    $join->on('member.member_id','=','withdraw.member_id');
                })
                ->leftJoin('paytype',function ($join){
                    $join->on('paytype.paytype_id','=','withdraw.paytype_id');
                })
                ->where('member.name','like',$member . '%')
                ->orderBy('withdraw.created_at', 'desc')->paginate($this->backendPageNum);

        }else{
            $WithdrawArray = Withdraw::select('withdraw.*','member.name','paytype.paytype')->where('withdraw.status',0)
                ->leftJoin('member',function ($join){
                    $join->on('member.member_id','=','withdraw.member_id');
                })
                ->leftJoin('paytype',function ($join){
                    $join->on('paytype.paytype_id','=','withdraw.paytype_id');
                })
                ->orderBy('withdraw.created_at', 'desc')->paginate($this->backendPageNum);
        }
        return view('backend.withdrawlist', compact('WithdrawArray'))->with('admin', session('admin'));
    }

    //处理站长提款订单
    public function dealwithdraworder(Request $request,$withdraw_id){
        $WithdrawDetail = Withdraw::select('withdraw.*','member.name','paytype.paytype')->where('withdraw.withdraw_id',$withdraw_id)
            ->leftJoin('member',function ($join){
                $join->on('member.member_id','=','withdraw.member_id');
            })
            ->leftJoin('paytype',function ($join){
                $join->on('paytype.paytype_id','=','withdraw.paytype_id');
            })->get()->toArray();
        return view('backend.dealwithdraworder', compact('WithdrawDetail'));
    }

    //更新确认站长的提款申请订单
    public function updatewithdraworder(Request $request,$withdraw_id){
        if($request->isMethod('post'))
        {
            $status = request()->input('status');
            $remark = request()->input('remark');
            if($status == 1)
            {
                //提款成功,冻结的奖金扣减
                DB::beginTransaction();
                try{
                    //行锁
                    $OrderDetail = Withdraw::where('withdraw_id',$withdraw_id)->where('status',0)->lockForUpdate()->get()->toArray();
                    $MemberDetail = Member::where('member_id',$OrderDetail[0]['member_id'])->lockForUpdate()->get()->toArray();
                    if($MemberDetail[0]['frozen'] < $OrderDetail[0]['money'])
                    {
                        DB::rollBack();
                        $data['status'] = 0;
                        $data['msg'] = "冻结金额比提款金额要小，可能作弊，请注意";
                        echo json_encode($data);
                        exit;
                    }
                    $result = Withdraw::where('withdraw_id', $withdraw_id)->update(['status' => $status, 'remark' => $remark]);
                    $result1 = Member::where('member_id',$OrderDetail[0]['member_id'])->decrement('frozen',$OrderDetail[0]['money']);
                    if($result and $result1)
                    {
                        DB::commit();
                        $data['status'] = 1;
                        $data['msg'] = "提款订单处理成功";
                        echo json_encode($data);
                    }else{
                        DB::rollBack();
                        $data['status'] = 0;
                        $data['msg'] = "提款订单处理失败";
                        echo json_encode($data);
                    }

                }catch (\Exception $e) {
                    DB::rollBack();
                    $data['status'] = 0;
                    $data['msg'] = "Error!";
                    echo json_encode($data);
                }



            }else{
                //提款关闭，那就要把该站长冻结的资金退回到balance总额里面
                DB::beginTransaction();
                try{
                    //行锁
                    $OrderDetail = Withdraw::where('withdraw_id',$withdraw_id)->where('status',0)->lockForUpdate()->get()->toArray();
                    $MemberDetail = Member::where('member_id',$OrderDetail[0]['member_id'])->lockForUpdate()->get()->toArray();
                    if($MemberDetail[0]['frozen'] < $OrderDetail[0]['money'])
                    {
                        DB::rollBack();
                        $data['status'] = 0;
                        $data['msg'] = "冻结金额比提款金额要小，可能作弊，请注意";
                        echo json_encode($data);
                        exit;
                    }
                    $result = Withdraw::where('withdraw_id', $withdraw_id)->update(['status' => $status, 'remark' => $remark]);
                    $result1 = Member::where('member_id',$OrderDetail[0]['member_id'])->increment('balance',$OrderDetail[0]['money']);
                    $result2 = Member::where('member_id',$OrderDetail[0]['member_id'])->decrement('frozen',$OrderDetail[0]['money']);
                    if($result and $result1 and $result2)
                    {
                        DB::commit();
                        $data['status'] = 1;
                        $data['msg'] = "提款订单关闭成功";
                        echo json_encode($data);
                    }else{
                        DB::rollBack();
                        $data['status'] = 0;
                        $data['msg'] = "提款订单关闭失败";
                        echo json_encode($data);
                    }
                }catch (\Exception $e) {
                    DB::rollBack();
                    $data['status'] = 0;
                    $data['msg'] = "Error!";
                    echo json_encode($data);
                }
            }

        }
    }

    //站长提款记录列表
    public function withdrawrecord(Request $request){
        if($request->isMethod('post'))
        {
            $member = request()->input('member');
            $WithdrawArray = Withdraw::select('withdraw.*','member.name','paytype.paytype')->where('withdraw.status','!=','0')
                ->leftJoin('member',function ($join){
                    $join->on('member.member_id','=','withdraw.member_id');
                })
                ->leftJoin('paytype',function ($join){
                    $join->on('paytype.paytype_id','=','withdraw.paytype_id');
                })
                ->where('member.name','like',$member . '%')
                ->orderBy('withdraw.updated_at', 'desc')->paginate($this->backendPageNum);

        }else{
            $WithdrawArray = Withdraw::select('withdraw.*','member.name','paytype.paytype')->where('withdraw.status','!=','0')
                ->leftJoin('member',function ($join){
                    $join->on('member.member_id','=','withdraw.member_id');
                })
                ->leftJoin('paytype',function ($join){
                    $join->on('paytype.paytype_id','=','withdraw.paytype_id');
                })
                ->orderBy('withdraw.updated_at', 'desc')->paginate($this->backendPageNum);
        }
        return view('backend.withdrawrecord', compact('WithdrawArray'))->with('admin', session('admin'));
    }








}
