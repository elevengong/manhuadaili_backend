<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Model\Statistics;
use App\Http\Requests;
use App\Http\Controllers\MyController;

class TrafficController extends MyController
{
    public function traffic(){
        echo session('daili_id');
        $statistics =  Statistics::where('daili_id',session('daili_id'))->orderBy('created_at', 'desc')->paginate(100);
        return view('backend.traffic',compact('statistics'));
    }
}
