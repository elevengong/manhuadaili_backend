<?php

namespace App\Http\Controllers;

use App\Model\Deposit;
use Illuminate\Http\Request;
use App\Http\Requests;

class MyController extends Controller
{
    public $backendPageNum = '20';
    public $Ipbaimingdan = '0'; //ip白名单，目前只有菲律宾的ip可以login，1为开启，0为关闭

    public function __construct()
    {
        date_default_timezone_set('Asia/Shanghai');
    }

    //删除指定session数据
    public function deleteSession($request, $key){
        return $request->session()->forget($key);
    }

    //删除所有session数据
    public function deleteAllSession($request){
        return $request->session()->flush();
    }

    //判断是否为数字
    public function isNumeric($value){
        return is_numeric($value);
    }

    //判断密码长度是否达到最少6位数
    public function isPassword($pwd){
        if(strlen($pwd)<6)
        {
            return false;
        }else{
            return true;
        }
    }

    //判断邮箱是否正确
    public function isEmail($email){
        $mode = '/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/';
        if(preg_match($mode,$email)){
            return true;
        }
        else{
            return false;
        }
    }


    //上传图片
    public function uploadphoto(Request $request,$id){
        $file = $request->file($id);//获取图片
        $allowed_extensions = ["png", "jpg", "gif"];
        if ($file->getClientOriginalExtension() && !in_array(strtolower($file->getClientOriginalExtension()), $allowed_extensions)) {
            return Response()->json([
                'status' => 0,
                'msg' => '只能上传 png | jpg | gif格式的图片'
            ]);
        }
        $destinationPath = 'public/uploads/';
        $extension = $file->getClientOriginalExtension();
        $fileName = time().str_random(5).'.'.$extension;
        $file->move($destinationPath, $fileName);
        return Response()->json(
            [
                'status' => 1,
                //'pic' => asset($destinationPath.$fileName),
                'pic' => "/".$destinationPath.$fileName,
                'msg' => '上传成功！'
            ]
        );
    }


}
