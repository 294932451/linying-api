<?php
/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Webman\Route;
use app\controller;
Route::group('/api', function () {
    Route::any('/login/login', [controller\LoginController::class, 'login']); #登录
    Route::any('/upload/file', [controller\UploadController::class, 'image']); #上传图片
    Route::any('/login/refresh_token', [controller\LoginController::class, 'refreshToken']); #刷新token
    Route::any('/login/update_app', [controller\LoginController::class, 'updateApp']); #更新
    Route::any('/login/update_new', [controller\LoginController::class, 'updateIsNew']); #更新完成
    Route::any('/index/get_music_cate', [controller\IndexController::class, 'getMusicCate']); #获取音乐分类

});


Route::group('/api', function () {
    Route::any('/index/get_music', [controller\IndexController::class, 'getMusic']); #获取音乐
    Route::any('/index', [controller\IndexController::class, 'index']); #首页
    Route::any('/banner', [controller\IndexController::class, 'banner']); #轮播图
    Route::any('/my/images', [controller\MyController::class, 'images']); #我的首页图
    Route::any('/my/index', [controller\MyController::class, 'index']); #我的
    Route::any('/photo/cate', [controller\PhotoController::class, 'photo_cate']); #相册分类
    Route::any('/photo/save_photos', [controller\PhotoController::class, 'savePhotos']); #上传图片
    Route::any('/photo/list/{cate_id}', [controller\PhotoController::class, 'photo_list']); #相册列表
    Route::any('/daily_article/list', [controller\DailyArticleController::class, 'index']); #日记列表
    Route::any('/daily_article/save', [controller\DailyArticleController::class, 'save']); #记日记
    Route::any('/daily_article/detail/{id}', [controller\DailyArticleController::class, 'detail']); #记日记
    Route::any('/photo/create_photo_cate', [controller\PhotoController::class, 'createPhotoCate']); #创建相册
})->middleware(\app\middleware\ApiAuthMiddleware::class,\app\middleware\AccessControl::class);






