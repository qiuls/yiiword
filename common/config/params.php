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
    'redis'=>[
        'host'=>'127.0.0.1',
        'port'=>6379,
    ],
    'db' => [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost;dbname=yii2advanced',
        'username' => 'root',
        'password' => '1234',
        'charset' => 'utf8',
    ],
      'AppLog'=>[
          'console'=>[
              'dir'=>dirname(dirname(__DIR__)).'/console/runtime',
              'admin'=>'1805668790@qq.com',
          ],
          'frontend'=>[
              'dir'=>dirname(dirname(__DIR__)).'/frontend/runtime',
              'admin'=>'1805668790@qq.com',
          ],
      ],
    'phpmailer'=>[
        'password'=>'c2h1YW5nMTIz',
    ],
    'login'=>[
        'error'=>[
            'frontend'=>'frontendLoginError:',
             'num'=>6,
        ],
    ],
];
