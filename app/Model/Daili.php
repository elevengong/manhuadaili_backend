<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Daili extends Model
{
    protected $table = 'daili';
    protected $primaryKey = 'daili_id';

    protected $fillable = ['daili_id','daili_name','pwd','status','alipay','alipay_name','weixin','weixin_name','current_commision','frzon_commision','commission_rate','login_ip','last_login_time','created_at','updated_at'];

}
