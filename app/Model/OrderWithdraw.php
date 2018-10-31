<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderWithdraw extends Model
{
    protected $table = 'orderwithdraw';
    protected $primaryKey = 'withdraw_id';

    protected $fillable = ['withdraw_id','daili_id','ip','withdraw_money','order_no','withdraw_info','status','remark','transfer_no','deal_time','created_at','updated_at'];
}
