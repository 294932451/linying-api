<?php

namespace plugin\admin\app\controller;

use support\Request;
use support\Response;
use plugin\admin\app\model\Photo;
use plugin\admin\app\controller\Crud;
use support\exception\BusinessException;

/**
 * 相册列表 
 */
class PhotoController extends Crud
{
    
    /**
     * @var Photo
     */
    protected $model = null;

    /**
     * 构造函数
     * @return void
     */
    public function __construct()
    {
        $this->model = new Photo;
    }


    /**
     * @param Request $request
     * @return Response
     * @throws BusinessException
     */
    public function select(Request $request): Response
    {
        [$where, $format, $limit, $field, $order] = $this->selectInput($request);
        $query = $this->doSelect($where, $field, $order);
        $query = $query->with('photo_cate');
        return $this->doFormat($query, $format, $limit);
    }


    
    /**
     * 浏览
     * @return Response
     */
    public function index(): Response
    {
        return view('photo/index');
    }

    /**
     * 插入
     * @param Request $request
     * @return Response
     * @throws BusinessException
     */
    public function insert(Request $request): Response
    {
        if ($request->method() === 'POST') {
            $images = explode(',', $request->input('url'));
            foreach ($images as $image) {
                $this->model->create([
                    'cate_id' => $request->input('cate_id'),
                    'url' => $image,
                ]);
            }
        }
        return view('photo/insert');
    }

    /**
     * 更新
     * @param Request $request
     * @return Response
     * @throws BusinessException
    */
    public function update(Request $request): Response
    {
        if ($request->method() === 'POST') {
            return parent::update($request);
        }
        return view('photo/update');
    }

}
