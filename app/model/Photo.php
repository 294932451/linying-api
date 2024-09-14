<?php

namespace app\model;

use support\Model;

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

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    protected $guarded = [];

    public function getUrlAttribute($value)
    {
        return 'http://39.98.115.211:8787'.$value;
    }
}