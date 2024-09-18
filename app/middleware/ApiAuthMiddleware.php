<?php
namespace app\middleware;

use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Webman\MiddlewareInterface;
use Webman\Http\Response;
use Webman\Http\Request;

class ApiAuthMiddleware implements MiddlewareInterface
{
    public function process(Request $request, callable $handler): Response
    {
        // 获取 Authorization 头
        $authHeader = $request->header('Authorization');

        // 验证 Token 是否存在及格式
        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return json(['code'=>401,'msg'=>"Unauthorized"]);
        }

        $token = $matches[1]; // 提取 Token
        $key = getenv('JWT_KEY'); // 获取 JWT Key

        try {
            // 解码 JWT 并进行验证
            $decoded = $this->decodeToken($token, $key);
            $request->user = $decoded->user_info; // 将用户信息附加到请求对象
            return $handler($request); // 验证通过，继续请求
        } catch (ExpiredException $e) {
            // 如果 Token 过期，返回特定错误信息
            return json(['code'=>401,'msg'=>"Token expired"]);
        } catch (\Throwable $e) {
            // 其他 JWT 验证失败的情况
            return json(['error' => 'Unauthorized', 'msg' => $e->getMessage()], 401);
        }
    }

    // 公共的 JWT 解码方法
    private function decodeToken($token, $key)
    {
        return JWT::decode($token, new Key($key, 'HS256'));
    }
}