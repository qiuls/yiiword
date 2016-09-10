<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,

     'swoole'=>[
        'host'=>'127.0.0.1',
         'port'=>'9555',
       'worker'=>[
           'worker_num' => 2,
           'open_tcp_nodelay' => true,
           'task_worker_num' => 2,
           'daemonize' => true,
           'log_file' => '/tmp/swoole_http_server.log',
       ],
     ],
];
