<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2016/9/9
 * Time: 15:43
 */

namespace swoole;


class SwooleServer
{
  public static $swooleApp=null;
    public $swooleServer=null;
    public $eamil=null;
    public $redis=null;
    public $pdo  =null;
    public $server_config=null;
    public  static $master_pid=null;
 public function __construct($eamil,$pdo,$redis,$config)
 {
        $this->eamil=$eamil;
        $this->pdo=$pdo;
        $this->redis=$redis;
        $this->server_config=$config;
        self::$swooleApp=$this;
 }

    public  function start(){
        $http=new \swoole_http_server($this->server_config['host'], $this->server_config['port']);
//        $http->set([
//            'worker_num' => 2,
//            'open_tcp_nodelay' => true,
//            'task_worker_num' => 2,
//            'daemonize' => true,
//            'log_file' => '/tmp/swoole_http_server.log',
//        ]);
        $http->set($this->server_config['worker']);
        $http->on('request', function(\swoole_http_request $request, \swoole_http_response $response) use ($http) {
            //请求过滤
            if($request->server['path_info'] == '/favicon.ico' || $request->server['request_uri'] == '/favicon.ico'){
                return $response->end();
            }
            $params=json_encode($request->post);
            $taskId = $http->task($params);
            $response->end($taskId);
        });
        $http->on('Finish', function($serv, $taskId, $data){
            //TDDO 任务结束之后处理任务或者回调
            echo "$taskId task finish";
        });
        $http->on('task', function($serv, $taskId, $fromId, $data){
            $data=json_decode($data,true);
            foreach($data['mailto'] as $key=>$value){
                $status=$this->eamil->Send($value,$data['content']);
                $status=intval($status);
                $time=time();
                if($status){
                    $sql="insert into `call`(call_content,mailto,create_id,status,p_id,call_time) VALUES('{$data['content']}','{$value}',{$data['session_id']},$status,{$data['pid']},$time)";
//                 $redis->set($value,$sql);
                    $this->pdo->query($sql);
                }else{
                    $sql="insert into `call`(call_content,mailto,create_id,`status`,p_id,call_time) VALUES('{$data['content']}','{$value}',{$data['session_id']},$status,{$data['pid']},{$time})";
//                 $redis->set($value,$sql);
                    $this->pdo->query($sql);
                }
            }
            return $taskId;
        });
        $http->on('start',function($serv){
            $time=date('Y-m-d H:i:s',time());
            $start=' start time--'.$time.'pid--'.$serv->master_pid;
            $this->redis->set('swoolePid',$serv->master_pid);
            echo $start;
        });
        $http->on('onWorkerStop',function($serv, $taskId){
            $time=date('Y-m-d H:i:s',time());
            echo "  stop time-- $serv->master_pid ";
        });
//        self::$master_pid=$http->master_pid;
        $this->swooleServer=$http;
        $http->start();
    }
}
