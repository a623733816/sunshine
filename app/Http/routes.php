<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('send', 'MQTestController@send');
Route::get('reveive', 'MQTestController@reveive');
Route::get('/', function () {
    return view('welcome');
});
Route::get('/index', function () {
    return 'hello world!';
});
Route::group(['middleware'=>'test'],function(){
    Route::get('/write/laravelacademy',function(){
        return '使用Test中间件';
    });
    Route::get('/update/laravelacademy',function(){
        //使用Test中间件
    });
});

Route::get('/age/refuse',['as'=>'refuse',function(){
    return "未成年人禁止入内！";
}]);

Route::get('testCsrf',function(){
    $csrf_field = csrf_field();
    $html = <<<GET
        <form method="POST" action="/testCsrf">
            {$csrf_field}//表单提交Laravel框架验证必须字段
            <input type="submit" value="Test"/>
        </form>
GET;
    return $html;
});

Route::post('testCsrf',function(){
    return 'Success!';
});

Route::resource('post','PostController');