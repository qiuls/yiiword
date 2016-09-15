<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2016/9/12
 * Time: 13:25
 */

namespace common\controllers;

use yii\log\FileTarget;

class CurveyLog
{
    public $level = [
        'info',
        'warning',
        'fatal',

    ];
    public $FileTarget = null;
    public $FileDir    = null;
    public $FileName   = null;
    public function __construct($key = 'console')
    {
        $this->FileDir    = \Yii::$app->params['AppLog'][$key]['dir'];
        $this->FileTarget = new FileTarget();
        $this->FileName   = date('Y-m-d', time());
    }

    public function write($message, $method, $level, $type = 'console')
    {
        $time = microtime(true);
        $message .= ' function => ' . $method . ' \n';
        if ($level < 0) {
            $level = 0;
        } elseif ($level > 2) {
            $level = 2;
        }
        $logFile = $this->FileDir . '/logs/' . $this->FileName . $type . '.log';
        if (!file_exists($logFile)) {
            $file = fopen($logFile, 'a+');
            fputs($file, '创建时间:' . date('Y-m-d H:i:s', time()));
            fclose($file);
        }
        $logInfo                      = [$message, $level, $type, $time];
        $this->FileTarget->logFile    = $logFile; //文件名自定义
        $this->FileTarget->messages[] = $logInfo;
        if ($this->level[$level] === 'fatal') {
            $mail        = new Email();
            $logInfoJson = json_encode($logInfo);
            $mail->Send(\Yii::$app->params['AppLog'][$type]['admin'], $logInfoJson, false, 'fatal报警');
        }
        $this->FileTarget->export();
    }

}
