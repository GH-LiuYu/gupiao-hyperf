<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/5
 * Time: 17:23
 */
$serv = new Swoole\Server("127.0.0.1", 9502);

//监听连接进入事件
$serv->on('Connect', function ($serv, $fd) {
    echo "客户端:".$fd."连接成功";
});

//监听数据接收事件
$serv->on('Receive', function ($serv, $fd, $from_id, $data) {
    $serv->send($fd, "服务端: 我回复你了".$data);
});

//监听连接关闭事件
$serv->on('Close', function ($serv, $fd) {
    echo "客户端:".$fd."关闭连接";
});

//启动服务器
$serv->start();