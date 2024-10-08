<?php

namespace plugin\admin\app\model;

use plugin\admin\app\model\Base;

/**
 * @property integer $id 主键(主键)
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property string $name 名称
 */
class MusicCate extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ying_music_cate';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
    
    
}
