<?php

namespace plugin\admin\app\model;

use plugin\admin\app\model\Base;

/**
 * @property integer $id 主键(主键)
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property string $title 标题
 * @property string $content 内容
 * @property string $images 图片
 * @property integer $uid 用户
 */
class DailyArticle extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ying_daily_article';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    protected $guarded = [];

    public function getImagesAttribute($value)
    {
        if ($value) {
            foreach (explode(',', $value) as $v) {
                $arr[] = 'http://39.98.115.211:8787' . $v;
            }
            return $arr;
        }
    }



}
