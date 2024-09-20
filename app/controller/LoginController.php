<?php

namespace app\controller;

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use plugin\admin\app\model\User;
use support\Db;
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
        $key = getenv('JWT_KEY');
        $accessPayload = [
            'iss' => 'https://39.98.115.211',  // 发行者
            'aud' => 'https://39.98.115.211',  // 受众
            'iat' => time(),               // 颁发时间
            'exp' => time() + 3600,         // 过期时间（例如1小时）
            'sub' => $user->id,                // 用户的ID (subject)
            'user_info' => [
                'id' => $user->id,
                'username' => $user->username,
            ] // 你可以添加更多的用户信息
        ];
        $refreshPayload = [
            'iss' => 'https://39.98.115.211',
            'aud' => 'https://39.98.115.211',
            'iat' => time(),
            'exp' => time() + 86400 * 30  // 30天有效期
        ];
        $refreshToken = JWT::encode($refreshPayload, $key, 'HS256');
        // 生成 JWT
        $token = JWT::encode($accessPayload, $key, 'HS256');
        $data = ['accessToken' => $token, 'userInfo' => $user, 'refreshToken' => $refreshToken];
        return json(['code' => 200, 'msg' => '登录成功', 'data' => $data]);
    }


    public function refreshToken(Request $request)
    {
        $key = getenv('JWT_KEY');
        $refreshToken = $request->post('refresh_token');
        try {
            // 验证刷新 Token
            $decoded = $this->decodeToken($refreshToken, $key);

            // 生成新的访问 Token
            $accessPayload = [
                'iss' => 'https://39.98.115.211',
                'aud' => 'https://39.98.115.211',
                'iat' => time(),
                'exp' => time() + 3600  // 新的1小时访问 Token
            ];

            $newAccessToken = JWT::encode($accessPayload, $key, 'HS256');

            // 返回新的访问 Token
            return json([
                'access_token' => $newAccessToken,
            ]);

        } catch (ExpiredException $e) {
            return json(['error' => 'Refresh token expired'], 401);
        } catch (\Throwable $e) {
            return json(['error' => 'Invalid refresh token', 'message' => $e->getMessage()], 401);
        }
    }

    // 公共的 JWT 解码方法
    private function decodeToken($token, $key)
    {
        return JWT::decode($token, new Key($key, 'HS256'));
    }


    public function updateApp()
    {
        $model = Db::table('ying_app')->find(1);
        $data = [
            'name' => $model->name,
            'version' => $model->version,
            'wgtUrl' => $model->wgetUrl,
            'isNew' => $model->is_new,
        ];
        return json(['code' => 200, 'msg' => 'success', 'data' => $data]);
    }

    public function updateIsNew()
    {
        $model = Db::table('ying_app')->find(1);
        $model->is_new = 0;
        $model->save();
        return json(['code' => 200, 'msg' => 'success']);
    }


}