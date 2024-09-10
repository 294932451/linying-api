<?php

namespace app\controller;

use plugin\admin\app\model\DailyArticle;
use support\Log;
use support\Request;

class DailyArticleController extends BaseController
{
    public function index(Request $request)
    {
        $data = DailyArticle::orderByDesc('id')->get(['id','title','content','created_at']);
        return $this->success($data);
    }


    public function save(Request $request)
    {
       $data = $request->post();
       $data['images'] = isset($data['images']) ? implode(',',$data['images']) : [];
       if(DailyArticle::create($data)){
           return $this->success();
       }else{
           return $this->error();
       }
    }

    public function detail($id)
    {
        $data = DailyArticle::find($id);
        return $this->success($data);
    }




}
