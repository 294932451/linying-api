<?php

namespace app\controller;

use app\model\Photo;
use support\Cache;
use support\Request;

class IndexController extends BaseController
{
    public function index(Request $request)
    {

        //日历
        $rili = [
            ['date' => '2024-09-27', 'info' => '经期', 'data' => ['type' => 0, 'has_data' => 0]],
            ['date' => '2024-09-26', 'info' => '经期', 'data' => ['type' => 0, 'has_data' => 0]],
            ['date' => '2024-09-25', 'info' => '经期', 'data' => ['type' => 0, 'has_data' => 0]],
            ['date' => '2024-09-24', 'info' => '经期', 'data' => ['type' => 0, 'has_data' => 0]],
            ['date' => '2024-09-23', 'info' => '经期', 'data' => ['type' => 0, 'has_data' => 0]],
            ['date' => '2024-09-22', 'info' => '经期', 'data' => ['type' => 0, 'has_data' => 0]],
            ['date' => '2024-09-21', 'info' => '经期', 'data' => ['type' => 0, 'has_data' => 0]],
            ['date' => '2024-09-20', 'info' => '经期', 'data' => ['type' => 0, 'has_data' => 0]],
            ['date' => '2024-10-18', 'info' => '回国', 'data' => ['type' => 2, 'has_data' => 0]],
            ['date' => '2024-09-30', 'info' => 'birthday', 'data' => ['type' => 1, 'has_data' => 0]],
        ];
        //星座
        $xingzuo = Cache::get('xing_zuo');
        $xingzuo_details = Cache::get('xingzuo_details');
        $data = ['rili' => $rili, 'xing_zuo' => $xingzuo,'xingzuo_details' => $xingzuo_details];
        if (!$xingzuo) {
            /**
             * 158-运势查询 - 代码参考（根据实际业务情况修改）
             */
// 基本参数配置
            $apiUrl = "http://web.juhe.cn/constellation/getAll"; // 接口请求URL
            $method = "GET"; // 接口请求方式
            $headers = ["Content-Type: application/x-www-form-urlencoded"]; // 接口请求header
            $apiKey = "dc81fbdee3ef21bbfd9c11807472c356"; // 在个人中心->我的数据,接口名称上方查看
// 接口请求入参配置
            $requestParams = [
                'key' => $apiKey,
                'consName' => '天秤座',
                'type' => 'today',
            ];
            $requestParamsStr = http_build_query($requestParams);

// 发起接口网络请求
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curl, CURLOPT_URL, $apiUrl . '?' . $requestParamsStr);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_FAILONERROR, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            if (1 == strpos("$" . $apiUrl, "https://")) {
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            }
            $response = curl_exec($curl);
            $httpInfo = curl_getinfo($curl);
            curl_close($curl);

// 解析响应结果
            $responseResult = json_decode($response, true);
            if ($responseResult) {
                // 网络请求成功。可依据业务逻辑和接口文档说明自行处理。
                $xingzuo = $responseResult['name'] . '今日运势：' . '综合指数' . $responseResult['all'] . ';幸运色-' . $responseResult['color'] . ';健康指数-' . $responseResult['health'] . ';爱情指数-' . $responseResult['love'] . ';财运指数-' . $responseResult['money'] . ';工作指数-' . $responseResult['work'] . ';幸运数字-' . $responseResult['number'] . ';今日概述-' . $responseResult['summary'];
                $now = time(); // 当前时间的时间戳
                $endOfDay = strtotime('tomorrow midnight') - 1; // 明天凌晨 00:00 的时间戳 - 1 秒，表示当天晚上 23:59:59
                $secondsUntilMidnight = $endOfDay - $now; // 当前时间到午夜的秒数
                Cache::set('xing_zuo', $xingzuo, $secondsUntilMidnight); // 缓存一天，86400 秒
                Cache::set('xingzuo_details', $responseResult, $secondsUntilMidnight); // 缓存一天，86400 秒
                $data = ['xing_zuo' => $xingzuo, 'rili' => $rili,'xingzuo_details' => $responseResult];
                return $this->success($data);
            } else {
                // 网络异常等因素，解析结果异常。可依据业务逻辑自行处理。
                // var_dump($httpInfo);
                return $this->error();
            }
        }
        return $this->success($data);
    }


    /**
     * @return void
     * 轮播图
     */
    public function banner()
    {
        $list = Cache::get('random_banner');
        if (!$list) {
            // 如果缓存中没有图片列表，生成并缓存它
            $list = Photo::where('cate_id', '<>', 4)->pluck('url')->random(5);
            $now = time(); // 当前时间的时间戳
            $endOfDay = strtotime('tomorrow midnight') - 1; // 明天凌晨 00:00 的时间戳 - 1 秒，表示当天晚上 23:59:59
            $secondsUntilMidnight = $endOfDay - $now; // 当前时间到午夜的秒数
            Cache::set('random_banner', $list, $secondsUntilMidnight); // 缓存一天，86400 秒
        }
        return json([
            'code' => 1,
            'data' => $list,
            'msg' => '轮播图获取成功'
        ]);
    }

}
