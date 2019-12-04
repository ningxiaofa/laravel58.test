<?php


namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis as RedisServer;
use App\Redis;
use Exception;

class RedisController
{
    const REDIS_LIST = 'redis_list';
    const REDIS_DETAIL_ = 'redis_detail_';

    //获取列表, 使用缓存, 只要数据有变化[如更新/新增操作], 就应该使得缓存失效. 这里只是练习.
    public function getList(Request $request)
    {
        $rows = RedisServer::get(static::REDIS_LIST);
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
        $redisDetailId = static::REDIS_DETAIL_.$id;
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
            throw new Exception('redis 保存失败不能为空!');
            return ['code'=> 0, 'msg' => 'Failed!'];
        }
        //缓存失效
        $this->unworkRedis(static::REDIS_DETAIL_.$id);
        $this->unworkRedis(static::REDIS_LIST);
        return ['code' => 1, 'msg' => 'Success!'];
    }

    //插入
    public function store(Request $request)
    {
        $params = $request->all();
        $redis = new Redis();
        $redis->content = $params['content'];
        $row = $redis->save();
        if(!$row){
            return ['code' => 0, 'msg' => 'Failed!'];
        }
        //缓存失效
        $this->unworkRedis(static::REDIS_LIST);
        return ['code' => 1, 'msg' => 'Success!'];
    }

    private function unworkRedis($key)
    {
        if(!$key){
            //异常处理
            throw new Exception('key不能为空!');
        }
        //缓存失效
        if(RedisServer::exists($key)){//has方法不存在, 怎么查看laravel中redis支持的方法{名} ? TBD 可以查看vendor/predis/predis/src/Command目录下,命令大小写均可
            if(!RedisServer::del($key)){ //可以查看vendor/predis/predis/src/Command/KeyDelete.php
                //应该抛出异常,并处理 ==> 规范上应该怎么做? TBD
                throw new Exception('redis删除数据失败!');
            }
        }
        return true;
    }

    //测试异常
    public function testException()
    {
        throw new Exception('测试异常', 400);//会写入错误日志
    }
}
