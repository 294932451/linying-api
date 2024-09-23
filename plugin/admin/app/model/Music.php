<?php

namespace plugin\admin\app\model;

use plugin\admin\app\model\Base;

/**
 * @property integer $id 主键(主键)
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property integer $cate_id 分类ID
 * @property string $name 名称
 * @property string $url 地址
 * @property integer $type 1音乐2视频
 */
class Music extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ying_music';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
    
    
}
