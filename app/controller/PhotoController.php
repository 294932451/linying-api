<?php

namespace app\controller;

use app\model\Photo;
use plugin\admin\app\model\PhotoCate;
use support\Request;

class PhotoController
{
    public function photo_cate()
    {
        $list = PhotoCate::all()->toArray();
        $albums = [];
        foreach ($list as $key => $value) {
            $albums[$key]['id'] = $value['id'];
            $albums[$key]['name'] = $value['name'];
            $albums[$key]['cover'] = Photo::where('cate_id', $value['id'])->orderByDesc('id')->value('url');
        }
        return json([
            'code' => 0,
            'data' => $albums,
            'msg' => '获取成功',
        ]);
    }
}
