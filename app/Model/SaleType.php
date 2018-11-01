<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SaleType extends Model
{
    protected $table = 'saletype';
    protected $primaryKey = 't_id';
    public $timestamps = false;

    protected $fillable = ['t_id','name','money'];
}
