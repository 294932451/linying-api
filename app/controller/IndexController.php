<?php

namespace app\controller;

use app\model\Photo;
use support\Cache;
use support\Request;

class IndexController
{
    public function index(Request $request)
    {

    }


    /**
     * @return void
     * 轮播图
     */
    public function banner()
    {
        $list =Cache::get('random_banner');

        if (!$list) {
            // 如果缓存中没有图片列表，生成并缓存它
            $list = Photo::pluck('url')->random(5);
            Cache::set('random_banner', $list, 86400); // 缓存一天，86400 秒
        }

        return json([
            'code' => 1,
            'data' => $list,
            'msg' => '轮播图获取成功'
        ]);
   }

}
