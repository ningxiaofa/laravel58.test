<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* 备注: 框架各个版本语法上会有调整, 严格验证不同手册去开发 */

Route::get('/', function(){
    return 'welcome to laravel world!';
});

//注册
Route::get('/registerForm', 'Web\UserController@registerForm');
Route::post('/register', 'Web\UserController@register');
//登录
Route::get('/loginForm', 'Web\UserController@loginForm');
Route::post('/login', 'Web\UserController@login');

Route::middleware(['check.login'])->group(function () {
    Route::match(['get', 'post'], '/logout', 'Web\UserController@logout');
    Route::get('/flight/list', 'Web\FlightController@getList');
    Route::get('/flight/{id}', 'Web\FlightController@getOne')
        ->where('id', '[0-9]+');//http://roast.test/flight/getOne/1 ,id是必传递参数, 而且是数字 get请求可以在直接路由中验证过滤
    Route::match(['get', 'post'], '/flight/addForm', 'Web\FlightController@addForm'); //post请求,请求参数都在请求体中, 最好在控制器方法中验证过滤
    Route::post('/flight/add', 'Web\FlightController@add'); //post请求,请求参数都在请求体中, 最好在控制器方法中验证过滤
    Route::post('/flight/update', 'Web\FlightController@update');
    Route::match(['get', 'post'], '/flight/updateForm', 'Web\FlightController@updateForm'); //post请求,请求参数都在请求体中, 最好在控制器方法中验证过滤
    Route::post('/flight/save', 'Web\FlightController@save');
    /* match用法 */
    //Route::match(['get', 'post'], '/flight/update', 'Web\FlightController@insert');

    //测试redis
    Route::match(['get', 'post'], '/redis/list', 'Web\RedisController@getList');
    Route::post('/redis/update', 'Web\RedisController@update');
    Route::post('/redis/store', 'Web\RedisController@store');
    Route::get('/redis/updateForm', 'Web\RedisController@updateForm');
    Route::get('/redis/{id}', 'Web\RedisController@getDetail');
    Route::get('/exception', 'Web\RedisController@testException');

    //测试laravel mail and queue [use database first]
    Route::get('/index', 'Web\PostController@index');
    Route::post('/posts', 'Web\PostController@store');

});
