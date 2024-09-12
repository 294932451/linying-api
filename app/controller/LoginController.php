<?php
namespace app\controller;

use plugin\admin\app\model\User;
use support\Request;

class LoginController
{
    public function login(Request $request)
    {
        $username = $request->post('username');
        $user = User::where('username', $username)->first();
        if (!$user) {
            return json(['code' => 400, 'msg' => '账号不存在']);
        }
        if ($user->password != md5(sha1($request->post('password')))) {
            return json(['code' => 400, 'msg' => '密码错误']);
        }
        return json(['code' => 200, 'msg' => '登录成功']);
    }
}