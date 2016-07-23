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
use app\models\User;

class AdminController extends  Controller
{
public $layout='layouts';


    public function actionIndex()
    {
        $session=\Yii::$app->session;
        $session->open();
        if($session['id'] && $session['name'] && $session['last_login_time']){
            return $this->redirect('/?r=user/index');
        }
        if(\Yii::$app->request->isPost){
            $username=\Yii::$app->request->post('username','');
            $password=\Yii::$app->request->post('password','');
            $password=substr(md5(md5($password)),0,34);
            $res=User::find()->where('username=:username and password=:password',[":username"=>$username,":password"=>$password])->one();
            if(empty($res)){
               return json_encode(['code'=>300,'message'=>'用户名或密码错误,请重试~']);
           }
            if($res['status']!==1){
                return json_encode(['code'=>300,'message'=>'账号没有激活,请联系管理员~']);
            }
            $res_arr=$res->toArray();
            $res->last_login_time=$res_arr['login_time'];
            $res->login_time=time();
            if(!$res->save()){
                return json_encode(['code'=>300,'message'=>'系统异常请稍后重试~']);
            }
            if(isset($res_arr['id']) && !empty($res_arr['id'])){
                $session['id']=$res_arr['id'];
                $session['name']=$res_arr['username'];
                $session['roles']=$res_arr['roles'];
                $session['last_login_time']=date('Y-m-d H:i:s',$res_arr['login_time']);
                return json_encode(['code'=>200,'message'=>'','jump'=>'/?r=user/index']);
               }else{
                return json_encode(['code'=>300,'message'=>'用户名或密码错误,请重试~']);
            }
        }else{
          return $this->render('admin');
        }
    }
    public function actionUserout(){
        $session=\Yii::$app->session;
        $session->open();
        unset($session['id'],$session['name'],$session['last_login_time']);
//        unset();
        return $this->redirect(['index']);
    }
}