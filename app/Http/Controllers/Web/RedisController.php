<?php


namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis as RedisServer;
use App\Redis;

class RedisController
{
    //获取列表, 使用缓存, 只要数据有变化[如更新/新增操作], 就应该使得缓存失效. 这里只是练习.
    public function getList(Request $request)
    {
        $rows = RedisServer::get('redis_list');
        if($rows){
            $rows = json_decode($rows);//json_decode($rows, 1) => 数组
        }else{
            $rows = Redis::all();//->toArray() => 数组
            RedisServer::set('redis_list', json_encode($rows));
        }
        return ['code' => 1, 'msg' => 'Success!', 'rows' => $rows];
    }

    //获取详情
    public function getDetail(Request $request)
    {
        $id = intval($request->id ?? null);
        if(!$id){
            return ['code' => 0, 'msg' => 'Id is empty or not number'];
        }
        $redisDetailId = 'redis_detail_'.$id;
        $row = RedisServer::get($redisDetailId);
        if($row){
            $row = json_decode($row);
        }else{
            $row = Redis::find($id);
            RedisServer::set($redisDetailId, json_encode($row));
        }
        return ['code' => 1, 'msg' => 'Success!', 'row' => $row];
    }

    public function updateForm()
    {
        return view('/redis/updateForm');
    }

    //更新
    public function update(Request $request)
    {
        $id = intval($request->id ?? null);
        if(!$id){
            return ['code' => 0, 'msg' => 'Id is empty or not number'];
        }
        $redis = Redis::find($id);
        $redis->content = $request->get('content', '');
        if(!$redis->save()){
            //规范应该如何处理 ? TBD
            return ['code'=> 0, 'msg' => 'Failed!'];
        }
        //缓存失效
        $redisDetailId = 'redis_detail_'.$id;
        if(RedisServer::exists($redisDetailId)){//has方法不存在, 怎么查看laravel中redis支持的方法{名} ? TBD 可以查看vendor/predis/predis/src/Command目录下,命令大小写均可
            if(!RedisServer::del($redisDetailId)){ //可以查看vendor/predis/predis/src/Command/KeyDelete.php
                //应该抛出异常,并处理 ==> 规范上应该怎么做? TBD
            }
        }
        return ['code' => 1, 'msg' => 'Success!'];
    }

    //插入
    public function add()
    {
        //...
    }
}