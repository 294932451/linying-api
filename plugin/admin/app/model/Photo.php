<?php

namespace plugin\admin\app\model;

use plugin\admin\app\model\Base;

/**
 * @property integer $id 主键(主键)
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property string $url 相册地址
 * @property integer $uid 
 * @property integer $cate_id 相册分类
 */
class Photo extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ying_photos';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';


    protected $guarded = [];

    public function photo_cate()
    {
        return $this->belongsTo(PhotoCate::class, 'cate_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'uid');
    }
    
    
}
