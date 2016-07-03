<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2016/6/30
 * Time: 17:29
 */
namespace common\controllers;

//use common\controllers\Smtp;
use yii\base\Exception;
//use common\controllers\Smtp;
class Mail
{
    protected  static  $smtp='';
    public function __construct(){
    try{
  $param=include_once __DIR__.'/../config/mail.php';
  self::$smtp=new Smtp($param['smtpserver'],$param['smtpserverport'],$param['param'],$param['smtpuser'],$param['smtppass']);
    }catch(Exception $e){
        var_dump($e->getMessage());
    }
    }

    /**
     * //1.收件人，2发件人，3邮件主题，4内容，5格式
     * @param $smtpemailto
     * @param $smtpusermail
     * @param $mailsubject
     * @param $mailbody
     * @param $mailtype
     * return array
     */
    public function putEamil($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype='html'){
        self::$smtp->debug = TRUE;
        $error=self::$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);
        if($error){
            return ['error'=>$error,'message'=>'发送成功'];
        }else{
            return ['error'=>$error,'message'=>'发送失败'];
        }
    }
}