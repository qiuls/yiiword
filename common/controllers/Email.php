<?php
namespace common\controllers;

class Email{

   public function Send($to,$body='phpmail演示',$isHtml=true,$subject='问卷回访')
   {

       require_once __DIR__.'/../mail/phpmailer/class.phpmailer.php';
       try {
           $mail = new \PHPMailer(true,\Yii::$app->params['phpmailer']['password']);
           $mail->IsSMTP();
           $mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
           $mail->SMTPAuth   = true;                  //开启认证
           $mail->Port       = 25;
           $mail->Host       = "smtp.139.com";
           $mail->Username   = "qiulsindex@139.com";
           //$mail->IsSendmail(); //如果没有sendmail组件就注释掉，否则出现“Could  not execute: /var/qmail/bin/sendmail ”的错误提示
          //$mail->AddReplyTo("qiulsindex@139.com","mckee");//回复地址
           $mail->From       = "qiulsindex@139.com";
           $mail->FromName   = "调查问卷回访";
           $mail->AddAddress($to);
           $mail->Subject  = $subject;
           $mail->Body = $body;
           $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
           $mail->WordWrap   = 80; // 设置每行字符串的长度
           //$mail->AddAttachment("f:/test.png");  //可以添加附件
           $mail->IsHTML($isHtml);
           return $mail->Send();
       } catch (phpmailerException $e) {
           return false;
//           echo "邮件发送失败：".$e->errorMessage();
       }
   }

}