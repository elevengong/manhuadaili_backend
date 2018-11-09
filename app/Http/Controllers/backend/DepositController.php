<?php

namespace App\Http\Controllers\backend;

use App\Model\OrderDeposit;
use App\Model\SaleType;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\MyController;

class DepositController extends MyController
{
    public function depositlist(){
        $saleType = SaleType::orderBy('t_id','asc')->get()->toArray();
        $newType = array();
        foreach ($saleType as $sale)
        {
            $newType[$sale['t_id']] = $sale['name'];
        }
        $orders = OrderDeposit::select('orderdeposit.*','users.user_name')
            ->leftJoin('users',function ($join){
                $join->on('users.uid','=','orderdeposit.uid');
            })->where('orderdeposit.daili_id',session('daili_id'))
            ->orderBy('orderdeposit.created_at', 'desc')->paginate($this->backendPageNum);
        return view('backend.depositlist',compact('orders','newType'));
    }
}
