<?php
namespace app\controller;

class BaseController
{
    protected function success($data = [], $msg = 'success',$code = 200)
    {
        return json(['data'=>$data,'code' => $code, 'msg' => $msg]);
    }

    protected function error($msg = 'error',$code = 400,$data=[])
    {
        return json(['data'=>$data,'code' => $code, 'msg' => $msg]);
    }
}