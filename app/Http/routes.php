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
//---------------------后台------------------------
Route::group(['middleware' => ['web']],function () {
    Route::any('/','backend\LoginController@login');
    Route::get('/backend/code','backend\LoginController@code');
});

Route::group(['middleware' => ['web','daili.login']],function () {
    Route::get('/backend/index','backend\IndexController@index');
    Route::post('/backend/logout','backend\IndexController@logout');
    Route::post('/backend/changepwd','backend\IndexController@changepwd');
    Route::any('/backend/showindex','backend\IndexController@showindex');


    //下线充值
    Route::get('/backend/depositlist','backend\DepositController@depositlist');

    //下线注册
    Route::get('/backend/userlist','backend\IndexController@userlist');

    //代理提款
    Route::any('/backend/withdrawlist','backend\WithdrawController@withdrawlist');
    Route::delete('/backend/cancelwithdraw/{withdraw_id}','backend\WithdrawController@cancelwithdraw')->where(['withdraw_id' => '[0-9]+']);

    //流量分析
    Route::get('/backend/traffic','backend\TrafficController@traffic');

    //图片上传
    Route::any('/backend/uploadphoto/{id}','MyController@uploadphoto');
});



