<?php

namespace app\controller;

use app\model\Photo;
use plugin\admin\app\model\PhotoCate;
use support\Request;

class PhotoController extends BaseController
{
    public function photo_cate()
    {
        $list = PhotoCate::where('id','<>',4)->get()->toArray();
        $albums = [];
        foreach ($list as $key => $value) {
            $albums[$key]['id'] = $value['id'];
            $albums[$key]['name'] = $value['name'];
            $albums[$key]['cover'] = Photo::where('cate_id', $value['id'])->orderByDesc('id')->value('url');
        }
        return $this->success($albums);

    }

    public function createPhotoCate(Request $request)
    {
        $params = $request->post();
        PhotoCate::create(['name' => $params['cate_name'], 'uid' => $params['uid']]);
        return $this->success();
    }


    public function photo_list($cate_id)
    {
        $list = Photo::where('cate_id', $cate_id)
            ->orderByDesc('id')
            ->get()
            ->groupBy(function($photo) {
                // 按照日期格式化创建时间
                return $photo->created_at->format('d M, Y'); // 输出格式如 '23 08月, 2024'
            })
            ->map(function($photos, $date) {
                return [
                    'created_at' => $date,
                    'photos' => $photos->map(function($photo) {
                        return ['url' => $photo->url]; // 假设 `url` 是照片模型中的字段
                    })->toArray()
                ];
            })
            ->values()
            ->toArray();
        $count = Photo::where('cate_id', $cate_id)->count();
        $list['count'] = $count;

        return $this->success($list);
    }

    public function savePhotos(Request $request)
    {
        $data = $request->post();
        try {
            foreach ($data['photos'] as $photo) {
                Photo::create([
                    'url' => $photo,
                    'cate_id' => $data['cate_id'],
                    'uid' => $data['uid']
                ]);
            }
            return $this->success();
        }catch (\Throwable $e){
            return $this->error('保存失败');
        }
       
    }
}
