<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'uid';

    protected $fillable = ['uid','daili_id','user_name','pwd','status','vip','vip_start_time','vip_end_time','vip_time','login_ip','register_ip','last_login_time','coin','created_at','updated_at'];
}
