<?php

namespace app\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\User;
use Illuminate\View\View;

class UserController extends Controller
{
    /* laravel 默认的响应数据格式为json, 当数组/对象时 */

    /**
     * 返回注册表单
     * @return Factory|View
     */
    public function registerForm()
    {
        return view('registerForm');
    }

    /**
     * 注册
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function register(Request $request)
    {
        //1.验证/接收参数
        /*$params = $request->validate([ //如何返回验证错误提示消息 ?? TBD [如何验证不通过,则会重定向到原url]
            'name' => "bail|required|unique:users|max:255", //bail 首次验证失败后停止检查 该属性 的其它验证规则 【即先required 然后unique ..】 unique:users查询users表来确定是否唯一 应该怎么理解? 是数据表中存在唯一还是不存在? TBD
            'password' => 'required',
            'confirm_password' => 'required',
        ]);*/
        $params = $request->all();

        //2.逻辑处理
        //判断用户名是否已存在
        $row = User::where('name', $params['name'])->first();
        if($row){
            return ['code' => 0, 'msg' => 'username is already exists'];
//            return response()->json(['code' => 0, 'msg' => 'username is already exists']);
        }

        //2.1判断两次密码是否一致
        if($params['password'] !== $params['confirm_password']){
            return response()->json(['code' => 0, 'msg' => 'Password are inconsistent with confirm_password!']);  //接口返回状态码应该怎么定义? TBD
        }
        unset($params['confirm_password']);
        //2.2密码加密
        $params['password'] = md5($params['password']);

        //$all = $request->all();
        /*array (
            '_token' => 'euXSWL7gigEGqJSm9SJZFprIjpAFGH4tTPDD4Kln',//验证token
            'username' => 'test',
            'password' => 'test',
        );*/

        //3.批量赋值
        $user = new User($params);

        //4.保存数据
        $bool = $user->save();
        if(!$bool){
            return ['code' => 0, 'msg' => 'Fail'];
            //return response()->json(['code' => 0, 'msg' => 'fail']);
        }
        /*if($bool){ //开发模式,会报错很详细的错误, 生产模式, 则只显示 500|Server Error 实际上[应该]不应该出现这样的问题, 而应该异常处理 TBD
            throw new Exception('Save Failed!', 500);
        }*/
        //5.返回响应
        /*return response()->json([
            'code' => 1,
            'msg' => 'success'
        ]);*/
        //这里先跳转到登录页面
        return redirect('/loginForm');
    }

    //登录表单
    public function loginForm()
    {
        return view('loginForm');
    }

    /**
     * 登录
     * @param Request $request
     * @return JsonResponse|void
     */
    public function login(Request $request)
    {
        //0.判断用户已是登录状态
        if($request->session()->get('user')){
            return ['code' => 1, 'msg' => 'Already login!'];
            //return response()->json(['code' => 1, 'msg' => 'Already login!']);
        }
        //1.验证/接收参数
        /*$params = $request->validate([
            'name' => "bail|required|max:255",
            'password' => 'required',
        ]);*/
        $params = $request->all(); //未验证参数, 不安全

        //2.业务逻辑处理
        //2.2 判断用户名是否存在
        $user = User::where('name', $params['name'])->first();
        if(!$user){
            return ['code' => 1, 'msg' => 'User is not exists!'];
            //return response()->json(['code' => 1, 'msg' => 'User is not exists!']);
        }

        //2.3 判读密码是否准确
        if($user->password !== md5($params['password'])){
            return  ['code' => 1, 'msg' => 'password is wrong!'];
            //return  response()->json(['code' => 1, 'msg' => 'password is wrong!']);
        }

        //2.4 将用户信息加入session
       /* var_export($_SESSION);//laravel中是直接访问不到$_SESSION, Laravel 并没有使用 PHP 内置的 Session 功能，而是自己实现了一套更加灵活更加强大的 Session 机制
        exit;*/
       //判断session是否开启,使用session_status();
        session(['user' => $params['name']]);
        //session('username');//获取session username值

        //3.返回响应
        return ['code' => 1, 'msg' => 'Login success!'];
        //return response()->json(['code' => 1, 'msg' => 'Login success!']);
    }

    //退出
    public function logout(Request $request)
    {
        //删除session用户信息
        //$request->session()->get('user');//string('test')
        $request->session()->forget('user');
        if($request->has('users')){
           return ['code' => 0, 'msg' => 'Logout failed!'];
           //return response()->json(['code' => 0, 'msg' => 'Logout failed!']);
        }
        return ['code' => 1, 'msg' => 'Logout success!'];
        //return response()->json(['code' => 1, 'msg' => 'Logout success!']);
    }
}