<?php

namespace App\Http\Controllers\signin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\MyController;
class TestController extends MyController
{
    public function index(Request $request){
        //cho $this->getIp();


        //$this->deleteAllSession($request);
        $this->deleteSession($request,'data');
        echo session('data');

        $sessionAll = $request->session()->all();
        print_r($sessionAll);
        return view('signin.test');

    }
}
