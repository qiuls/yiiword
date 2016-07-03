<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2016/4/21
 * Time: 10:51
 */

namespace frontend\controllers;

use app\models\Csawk;
use app\models\Csuser;
use app\models\User;
use yii\base\Exception;
use yii\web\Controller;

class TestController extends Controller
{
    public $layout = 'footer';
//    public function actionIndex()
//    {
//    }
//
//    public function actionUser($message = 'hello')
//    {
//        if (!empty($_POST)) {
//            //$post=\yii::$app->request->post();
//            $mb              = \yii::$app->request->post('mb_phone');
//            $user            = \yii::$app->request->post('username');
//            $model           = new User();
//            $model->mb_phone = $mb;
//            $model->user     = $user;
//            $id              = $model->save();
//            if ($id > 0) {
//                $_SESSION['id'] = $id;
//            } else {
//                return ['message' => '系统异常请稍后再试', 'jump' => ''];
//            }
//            //return $post;
//        } else {
//            return $this->render('login', ['message' => $message]);
//
//        }
//    }

    public function actionBegin()
    {
        if (!empty($_POST)) {
            $pid = \Yii::$app->request->get('w_id');
            $mb_phone=\Yii::$app->request->post('mb_phone');
            $name    =\Yii::$app->request->post('name');
            $uniqpue=Csuser::find()->where('task_pid=:task_pid AND mb_phone=:mb_phone',[':task_pid'=>$pid,':mb_phone'=>$mb_phone])->one();
            $uniqpue=$uniqpue->toArray();
            if(!empty($uniqpue)){
                return json_encode(['code'=>300,'message'=>'您已经参加过本次问卷了']);
            }
            $w_res = Csawk::find()->where('pid=:pid', [':pid' => $pid])->all();
            $arr=[];
          foreach($w_res as $v){
          $arr[]=[
              'id'=>$v['id'],
            'input'=>$v['input'],
               ];
           }
            unset($w_res);
            $task_pid=intval(\Yii::$app->request->post('task_pid'));
            $p_res=Csawk::find()->where('id=:id',[':id'=>$task_pid])->one();
            $p_res=$p_res->toArray();
            try{
                foreach ($arr as $v) {
                $model = new Csuser();
                if ($v['input'] == 1) {
                    $model->res = \Yii::$app->request->post(sha1($v['id']));
                } elseif ($v['input'] == 2) {
                    $model->res = \Yii::$app->request->post(sha1($v['id']));
                } elseif($v['input'] == 2) {
                    $checkbox   = \Yii::$app->request->post(sha1($v['id']));
                    $model->res = join(',', $checkbox);
                }else{
                    break;
                }
                $model->task_id  = $v['id'];
                $model->task_pid =$task_pid;
                $model->task_ptitle=$p_res['task_name'];
                $model->mb_phone = $mb_phone;
                $model->name     = $name;
                $num=$model->save();
            }
                if(!empty($num)){
                    return json_encode(['code' => 200,'message'=>'添加成功']);
                }
            } catch(Exception $e){
                return json_encode(['code'=>300,'message'=>'添加失败']);
//                return false;
            }
        } else {
            $pid = \Yii::$app->request->get('w_id');
            $res = Csawk::find()->where('id=:id', [':id' => $pid])->one();
            if (empty($res)) {
                return json_encode(['code' => '404', 'message' => '请重新打开链接']);
//                return false;
            }
            $w_res = Csawk::find()->where('pid=:pid', [':pid' => $pid])->all();
            if (empty($w_res)) {
                return json_encode(['code' => '405', 'message' => '请重新打开链接']);
//                 false;
            }
            $res   = $res->toArray();
            $model = ['title' => $res, 'arr' => $w_res];
            return $this->render('begin', ['model' => $model]);
        }
    }
       public function actionEnd()
       {
           var_dump(\Yii::$app->request->get('str'));
       }
}
