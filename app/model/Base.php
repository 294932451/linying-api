<?php
namespace app\model;

use DateTimeInterface;
use support\Model;

class Base extends Model
{
    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format($this->dateFormat ?: 'Y-m-d H:i:s');
    }
}