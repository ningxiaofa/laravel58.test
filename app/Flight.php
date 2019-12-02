<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
//    const CREATED_AT = 'creation_date';//默认为created_at
//    const UPDATED_AT = 'last_update';//默认为updated_at
//
//    /**
//     * 关联到模型的数据表
//     *
//     * @var string
//     */
//    protected $table = 'my_flights';//默认为flights
//
//    /**
//     * 表明模型是否应该被打上时间戳
//     *
//     * @var bool
//     */
//    public $timestamps = false;//自动维护时间
//
//    /**
//     * 模型日期列的存储格式
//     *
//     * @var string
//     */
//    protected $dateFormat = 'U';
//
//    /**
//     * The connection name for the model.
//     *
//     * @var string
//     */
//    protected $connection = 'connection-name';//暂时不使用
//
//    /**
//     * The model's default values for attributes.
//     *
//     * @var array
//     */
//    protected $attributes = [
//        'delayed' => 0,
//    ];

    /**
     * 使用批量赋值的属性（白名单）
     *
     * @var array
     */
    protected $fillable = [
        //'_token'//为了防止使用 $params = $request->all();批量赋值报错. 但是数据表中没有该字段, 依然会出现报错

    ];

    protected $guarded = ['id']; // ['*'] 黑名单属性为 *，即所有字段都不会应用批量赋值

}
