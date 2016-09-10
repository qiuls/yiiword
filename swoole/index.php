<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2016/9/9
 * Time: 15:54
 */
include_once 'SwooleServer.php';
include_once '/home/user/web/yii/common/controllers/Email.php';
//var_dump($argv);
//die();
$redis = new \Redis();
$pdo=new \PDO("mysql:host=localhost;dbname=yii2advanced","root","1234");
$mail=new \Email();
$config=require_once '/home/user/web/yii/common/config/params.php';
$config=$config['swoole'];
//$argv[1]();
switch($argv[1]){
    case 'start':
        (new \swoole\SwooleServer($mail,$pdo,$redis,$config))->start();
    case 'reload':
        \swoole\SwooleServer::$swooleApp->swooleServer->reload();
    case 'stop':
        \swoole\SwooleServer::$swooleApp->swooleServer->stop();
    default:
        (new \swoole\SwooleServer($mail,$pdo,$redis,$config))->start();
}