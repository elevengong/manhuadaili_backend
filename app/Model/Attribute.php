<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $table = 'attribute';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['id','name','value'];
}
