<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class OrderDeposit extends Model
{
    protected $table = 'orderdeposit';
    protected $primaryKey = 'deposit_id';

    protected $fillable = ['deposit_id','uid','daili_id','ip','order_money','order_no','order_type','transfer_no','pay_type','status','pay_daili','pay_name','remark','deal_time','created_at','updated_at'];
}
