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
$redis->connect('127.0.0.1',6379);
$pdo=new \PDO("mysql:host=localhost;dbname=yii2advanced","root","1234");
$mail=new \Email();
$config=require_once '/home/user/web/yii/common/config/params.php';
$config=$config['swoole'];
//$argv[1]();
$masterPid=$redis->get('swoolePid');
switch($argv[1]){
    case 'start':
        if(empty($masterPid)){
            (new \swoole\SwooleServer($mail,$pdo,$redis,$config))->start();
        }else{
            print_r('Server is already running. Please stop it first.');
            return;
        }
    case 'reload':
//        \swoole\SwooleServer::$swooleApp->swooleServer->reload();
        if (!empty($masterPid)) {
            posix_kill($masterPid, SIGUSR1); // 重启所有worker
            posix_kill($masterPid, SIGUSR2); // 重启所有task
        } else {
            print_r('master pid is null, maybe you delete the pid file we created. you can manually kill the master process with signal SIGUSR1.');
        }
        break;
    case 'stop':
//        \swoole\SwooleServer::$swooleApp->swooleServer->stop();
        if (!empty($masterPid)) {
            posix_kill($masterPid, SIGTERM);
        } else {
            print_r('master pid is null, maybe you delete the pid file we created. you can manually kill the master process with signal SIGTERM.');
        }
        break;
    case 'kill':
        $redis->del('swoolePid');
        break;
    default:
//        (new \swoole\SwooleServer($mail,$pdo,$redis,$config))->start();
        print_r("php {$argv[0]} start|reload|stop");
        break;
}