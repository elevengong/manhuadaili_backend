<?php

namespace App\Http\Controllers\backend;

use App\Model\Daili;
use Illuminate\Http\Request;
use App\Http\Controllers\MyController;
use App\Http\Requests;
use Crypt;

class IndexController extends MyController
{
    public function index(Request $request){
        return view('backend.layout.main')->with('daili_name',session('daili_name'));
    }

    public function showindex(){
        $dailiInfo = Daili::find(session('daili_id'))->toArray();
        return view('backend.index',compact('dailiInfo'));
    }

    //注销用户
    public function logout(Request $request){
        $this->deleteAllSession($request);
        $data['status'] = 1;
        $data['msg'] = "注销成功";
        echo json_encode($data);
    }

    //改当前代理密码
    public function changepwd(Request $request){
        if($request->isMethod('post')){
            $newpwd = request()->input('newpwd');
            $newPassword= Crypt::encrypt($newpwd);

            $result = Daili::where('daili_id',session('daili_id'))->update(['pwd'=>$newPassword]);

            if($result)
            {
                $data['status'] = 1;
                $data['msg'] = "修改成功";
            }else{
                $data['status'] = 0;
                $data['msg'] = "修改失败";
            }
            echo json_encode($data);

        }



    }

}
