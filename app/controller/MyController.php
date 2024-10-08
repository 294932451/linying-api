<?php

namespace app\controller;

use DateTime;
use app\model\Photo;
use DateTimeZone;
use nswl\utils\Redis;
use support\Cache;
use support\Request;

class MyController
{
    public function index(Request $request)
    {
        $lin = '0930';
        $tong = '1019';

        // 获取当前日期
        $usTimezone = new DateTimeZone('America/New_York');
        $linNow = new DateTime('now',$usTimezone);
        $now = new DateTime();

// 计算阳历生日
        $linBirthday = DateTime::createFromFormat('md', $lin, $usTimezone);;
        $linBirthday->setDate($linNow->format('Y'), $linBirthday->format('m'), $linBirthday->format('d'));
        $tongBirthday = DateTime::createFromFormat('md', $tong);
        $tongBirthday->setDate($now->format('Y'), $tongBirthday->format('m'), $tongBirthday->format('d'));

// 如果阳历生日已经过去，计算下一年的日期
        if ($linBirthday < $linNow || $tongBirthday < $now) {
            $linBirthday->modify('+1 year');
        }

// 计算距离阳历生日还有多少天
        $linDaysRemaining = $now->diff($linBirthday)->days;

        $tongDaysRemaining = $now->diff($tongBirthday)->days;

        $list = [
            ['name' => '距离YingLin的生日还有', 'days' => $linDaysRemaining,'iconClass'=>'http://39.98.115.211:8787/app/admin/upload/img/20240912/66e2b6609949.jpg'],
            ['name' => '距离童超的生日还有', 'days' => $tongDaysRemaining ,'iconClass'=>'http://39.98.115.211:8787/app/admin/upload/img/20240912/66e2b6577353.jpg'],
            ['name' =>  '第一次打电话睡觉','days' => '2024-07-28','iconClass'=>'http://39.98.115.211:8787/app/admin/upload/img/20240925/66f3d36c5605.jpg'],
            ['name' =>  '第一次说我爱你','days' => '2024-08-12','iconClass'=>'http://39.98.115.211:8787/app/admin/upload/img/20240925/66f3d36ccde6.jpg'],
            ['name' =>  '第一次过中秋节','days' => '2024-09-17','iconClass'=>'http://39.98.115.211:8787/app/admin/upload/img/20240918/66ea37559322.jpg'],
        ];
        return json([
           'code' => 1,
            'data' => $list,
            'msg' => '获取成功'
        ]);
    }


    /**
     * @return void
     * 轮播图
     */
    public function images()
    {
        $list = Cache::get('random_photos');

        if (!$list) {
            // 如果缓存中没有图片列表，生成并缓存它
            $list = Photo::where('cate_id','<>',4)->pluck('url')->random(9);
            $now = time(); // 当前时间的时间戳
            $endOfDay = strtotime('tomorrow midnight') - 1; // 明天凌晨 00:00 的时间戳 - 1 秒，表示当天晚上 23:59:59
            $secondsUntilMidnight = $endOfDay - $now; // 当前时间到午夜的秒数
            Cache::set('random_photos', $list, $secondsUntilMidnight); // 缓存一天，86400 秒
        }
        return json([
            'code' => 1,
            'data' => $list,
            'msg' => '轮播图获取成功'
        ]);
    }

}
