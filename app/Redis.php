<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class Redis extends Model
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'redises';//默认为? 这里要额外指定， 否则出现报错， 找不到rediss表 默认表名可能为rediss
}