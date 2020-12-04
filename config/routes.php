<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use Hyperf\HttpServer\Router\Router;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

Router::get('/favicon.ico', function () {
    return '';
});

Router::get('/getCodeList','App\Controller\IndexController@getCodeList');
Router::get('/getCodeList1','App\Controller\IndexController@getCodeList1');
Router::get('/getCodeList2','App\Controller\IndexController@getCodeList2');
Router::get('/getCodeList3','App\Controller\IndexController@getCodeList3');
Router::get('/getCodeList4','App\Controller\IndexController@getCodeList4');
Router::get('/getCodeList5','App\Controller\IndexController@getCodeList5');
Router::get('/getCodeList6','App\Controller\IndexController@getCodeList6');
Router::get('/getCodeList7','App\Controller\IndexController@getCodeList7');
Router::get('/getCodeList8','App\Controller\IndexController@getCodeList8');
Router::get('/getCodeList9','App\Controller\IndexController@getCodeList9');
Router::get('/getCodeList10','App\Controller\IndexController@getCodeList10');
Router::get('/index','App\Controller\IndexController@index');
Router::get('/getList','App\Controller\IndexController@getList');
Router::get('/addOpt','App\Controller\IndexController@addOpt');
Router::get('/updateOpt','App\Controller\IndexController@updateOpt');
Router::get('/deleteOpt','App\Controller\IndexController@deleteOpt');
Router::get('/getFile','App\Controller\IndexController@getFile');
Router::get('/addLots','App\Controller\IndexController@addLots');