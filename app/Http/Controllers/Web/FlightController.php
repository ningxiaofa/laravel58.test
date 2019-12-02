<?php

namespace app\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Flight;
use mysql_xdevapi\Exception;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    /* laravel 默认返回的数据格式是json */
    public function getApp()
    {
        return view('app');
    }

    public function getLogin()
    {
        return view('login');
    }

    /**
     * 获取列表
     */
    public function getList()
    {
        //return 'success!';//浏览器: success
        //return json_encode('success!'); //浏览器: "success"
        $rows = Flight::all()->toArray();
        /*$rows = Flight::where('active', 1) //多行注释/解注释， ctrl+shift+/
            ->orderBy('name', 'desc')
            ->take(10)
            ->get();*/ //->first()获取一条记录
        return ['code' => 1, 'msg' => 'success', 'rows' => $rows];
    }

    public function getOne(Request $request)
    {

        //备注: $request获取的所有参数都是string ...
        //var_dump($request->id); //string(1)
        $row = Flight::find($request->id);
        // 获取匹配查询条件的第一个模型...
        //$row = Flight::where('delayed', 0)->first();
        return ['code' => 1, 'msg' => 'success', 'row' => $row];
    }


    //返回表单视图
    public function addForm()
    {
        return view('flight/addForm');
    }

    public function updateForm()
    {
        return view('flight/updateForm');
    }

    /**
     * 新增/更新
     * @param Request $request
     * @return false|string
     */
    public function save(Request $request)
    {
        // 验证请求... post请求方式  token怎么解决[postman/form表单]

        //$name = $request->input('user.name'); //请求参数格式: json/application
        /*$flight = new Flight();
        $flight->name = $request->name;//单个属性赋值*/
        //获取参数的方式, 有如下两种
        //$request->name; 或者 $request->get('name');

        //批量赋值,有时不太推荐[字段比较少时,而且可以试探出数据表字段]
        $params = $request->all();//获取所有参数,关联数组形式
        $params['delayed'] = intval($params['delayed']);
        unset($params['_token']);

        if ($params['id'] ?? 0) {//更新
            $flight = Flight::find($params['id']);
            if(!$flight){
                return ['code' => 0, 'msg' => 'update failed, id is not exists!'];
            }
            foreach ($params as $key => $value){
                if(key_exists($key, $flight->getAttributes())){
                    $flight->$key = $value;
                }
            }
        } else {//新增
            unset($params['id']);
            $flight = new Flight($params);
        }

        //var_export($flight->getAttributes());//更新时, 可以获取数据表字段 [新增时, 未取得模型实例]
        //$flight->attributes = $params;//批量赋值.yii亦是, 但是laravel出现报错, 'Array to string convert' 具体情况TBD

        if(!$flight->save())
            throw new Exception('insert failed', 500);//不会报错这样的错误信息, 如果是接口开发, 就要统统使用接口方式, 返回json数据, 无论成功或失败
        return ['code' => 1, 'msg' => 'success'];
    }

    /**
     * 更新
     * @param Request $request
     * @return false|string
     */
    public function update(Request $request)
    {
        //验证参数...
        $flight = Flight::find($request->id);
        $flight->name = $request->name;
        $flight->delayed = $request->delayed;
        if(!$flight->save()){
            throw new Exception('update Failed', 500);
        }
        return ['code' => 1, 'msg' => 'Success'];
    }

    public function add(Request $request)
    {
        $params = $request->all();
        unset($params['id']);
        unset($params['_token']);
        $params['delayed'] = intval($params['delayed']);
        $flight = new Flight($params);
        if(!$flight->save()){
            return  ['code' => 0, 'msg' => 'Failed!'];
        }
        return  ['code' => 1, 'msg' => 'Success!'];
    }


}