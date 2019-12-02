<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * 使用批量赋值的属性（白名单）
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * 不使用批量赋值的字段（黑名单）
     *
     * @var array
     */
    protected $guarded = ['id']; // ['*'] 黑名单属性为 *，即所有字段都不会应用批量赋值

}
