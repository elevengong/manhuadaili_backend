<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\MyController;
use App\Model\Admin;
use Illuminate\Http\Request;
use Crypt;
use App\Http\Requests;

class AdminController extends MyController
{
    public function adminList(Request $request)
    {
        if ($request->isMethod('post')) {
            $searchWord = request()->input('searchword');
            $admin_array = Admin::where('admin_id', '!=', '1')->where('name', 'like', $searchWord . '%')->orderBy('admin_id', 'asc')->paginate($this->backendPageNum);

        } else {
            $admin_array = Admin::where('admin_id', '!=', '1')->orderBy('admin_id', 'asc')->paginate($this->backendPageNum);
        }

        return view('backend.adminlist', ['datas' => $admin_array])->with('admin', session('admin'));

    }

    public function changestatus(Request $request)
    {

        if ($request->isMethod('post')) {
            $admin_id = request()->input('admin_id');
            $status = (request()->input('state') == 1) ? 0 : 1;
            $result = Admin::where('admin_id', $admin_id)->update(['status' => $status]);
            if ($result) {
                $data['status'] = 1;
                $data['msg'] = "修改成功";
            } else {
                $data['status'] = 0;
                $data['msg'] = "修改失败";
            }
            echo json_encode($data);

        } else {
            $data['status'] = 0;
            $data['msg'] = "修改失败";
            echo json_encode($data);

        }

    }

    public function delete($admin_id)
    {

        $result = Admin::destroy($admin_id);
        if ($result) {
            $data = array('status' => 1, 'msg' => "删除成功");
            return json_encode($data);
        } else {
            $data = array('status' => 0, 'msg' => "删除失败");
            return json_encode($data);
        }

    }

    public function adminadd(Request $request)
    {
        if ($request->isMethod('post')) {
            $data['name'] = request()->input('name');
            $data['pwd']= Crypt::encrypt(request()->input('pwd'));

            //先查找一下这个管理员名有没有在数据中
            $result = Admin::where('name',$data['name'])->get()->toArray();
            if(empty($result))
            {
                $insert_result = Admin::create($data);
                if($insert_result->admin_id){
                    $reData['status'] = 1;
                    $reData['msg'] = "添加成功";
                }else{
                    $reData['status'] = 0;
                    $reData['msg'] = "添加失败";
                }
            }else{
                $reData['status'] = 0;
                $reData['msg'] = "管理员名字相同";
            }

            echo json_encode($reData);

        }


    }



}