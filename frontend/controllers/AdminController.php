<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2016/6/4
 * Time: 15:28
 */

namespace frontend\controllers;
use yii\base\Exception;
use yii\web\Controller;
use common\service\LoginService;

class AdminController extends  Controller
{
    public $layout='layouts';
    protected  $redis=null;
    protected $session=null;
    protected  $loginService=null;

    public function beforeAction($action)
    {
        $this->session=$session=\Yii::$app->session;
        $this->session->open();
        $redis=new \Redis();
        $redis->connect(\Yii::$app->params['redis']['host'],\Yii::$app->params['redis']['port']);
        $this->redis=$redis;
        $this->loginService=new LoginService(\Yii::$app->request->post(),$this->redis,$this->session);
        return parent::beforeAction($action);
    }

    /**
     * 登陆
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        $session=$this->session;
        if($session['id'] && $session['name'] && $session['last_login_time']){
            return $this->redirect('/?r=user/index');
        }
        if(\Yii::$app->request->isPost){
             $this->loginService->login();
            return $this->loginService->getResult();
        }else{
          return $this->render('admin');
        }
    }
    public function actionUserout(){
        $session=$this->session;
        unset($session['id'],$session['name'],$session['last_login_time']);
        return $this->redirect(['index']);
    }

//    public function actionSend(){
//        include '/home/user/web/yii/common/controllers/Email.php';
//        $Email=new \Email();
//        var_dump($Email->Send('1805668790@qq.com'));
//
//    }
}