<?php

namespace App\Http\Controllers\Web;

use App\Order;
use App\Mail\OrderShipped;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * 处理传入订单.
     *
     * @param  Request  $request
     * @param  int  $orderId
     * @return Response
     * @translator laravelacademy.org
     */
    public function ship(Request $request, $orderId)
    {
        //验证Windows下文件路径已经获取内容
        $filePath = 'G:\phpstudy_pro\WWW\test.txt';
        /*$content = '';
        if(file_exists($filePath)){
            $content = file_get_contents($filePath);
        }*/

        //return view('emails.orders.shipped', ['order' => '学院君']); //正常显示 变量 {{ $order }}
        //var_dump($request->user);// ?user=15237181025@163.com
        //这里的user应该是该用户注册时的邮箱,而不用前端传递,直接从数据库查询或者直接在session中获取
        //[推荐从session中获取, 如果获取不到,则从数据表中查询,如果查询不到, 则不再发送邮件, 短信提示用户完善邮箱]

        $order = Order::findOrFail($orderId);//如果为查询到, 会出现404|NOT FOUND

        // 处理订单...

        //发送邮件 方式一 推荐方式
        Mail::to($request->user)->send(new OrderShipped($order, $filePath));


        //方式二 直接面向过程开发
        /*$name = 'William_ning';
        // Mail::send() 需要传三个参数，第一个为引用的模板，第二个为给模板传递的变量，第三个为一个闭包，参数绑定Mail类的一个实例,返回值为空，所以可以其他方法进行判断
        Mail::send('emails.test', ['name' => $name], function($message){
            $to = '15237181025@163.com';
            $message ->to($to)->subject('邮件测试');
        });
        // 返回的一个错误数组，利用此可以判断是否发送成功
        dd(Mail::failures());*/

    }

    //新增订单
    public function store(Request $request)
    {
        //测试数据
        $param = [
            'name' => 'test',
            'price' => 5
        ];
        $order = new Order();
        $order->name = $param['name'];
        $order->price = $param['price'];
        $order->save();
        return ['code' => 1, 'msg' => 'Success!'];
    }
}