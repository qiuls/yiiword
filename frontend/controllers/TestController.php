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
            if(empty($pid)){
                return json_encode(['code'=>300,'message'=>'缺少参数']);
            }
            $mb_phone=\Yii::$app->request->post('mb_phone');
            $name    =\Yii::$app->request->post('name');
            $unique=Csuser::find()->where('task_pid=:task_pid and mb_phone=:mb_phone',[':task_pid'=>$pid,':mb_phone'=>$mb_phone])->one();
//            $unique=Csuser::find()->where('mb_phone=:mb_phone',[':mb_phone'=>$mb_phone])->one();
            if(!empty($unique)) {
                return json_encode(['code' => 300, 'message' => '您已经参加过本次问卷了']);
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
            $tr = \Yii::$app->db->beginTransaction();
            $num=0;
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
                $model->create_time=time();
                $model->task_id  = $v['id'];
                $model->task_pid =$task_pid;
                $model->task_ptitle=$p_res['task_name'];
                $model->mb_phone = $mb_phone;
                $model->name     = $name;
                if($model->save()){
                   $num++;
                }
            }

                if($num==count($arr)){
                    $tr->commit();
                    return json_encode(['code' => 200,'message'=>'添加成功']);
                }else{
                    $tr->rorollBack();
                    return json_encode(['code' => 300,'message'=>'添加失败1']);
                }
            } catch(Exception $e){
                $tr->rorollBack();
                return json_encode(['code'=>300,'message'=>'添加失败']);
            }
        } else {
            $pid = \Yii::$app->request->get('w_id');
            $res = Csawk::find()->where('id=:id and status=:status', [':id' => $pid,':status'=>1])->one();
            //$message='';
            $w_res = Csawk::find()->where('pid=:pid and status=:status', [':pid' => $pid,':status'=>1])->all();
//            if (empty($w_res)) {
//                return json_encode(['code' => '405', 'message' => '请重新打开链接']);
//            }
            if(!empty($res)){
            $res   = $res->toArray();
            }else{
                $res['task_name']='页面错误~ 请联系管理员获取新的链接';
                $res['id']='';
            }
            $model = ['title' => $res, 'arr' => $w_res];
            return $this->render('begin', ['model' => $model]);
        }
    }
       public function actionEnd()
       {
            $str=\Yii::$app->request->get('str');
            //$type = 1;
            $p_title = Csawk::find()->where('type=:type and status=:status', [':type' => 1,':status'=>1])->limit(5)->all();
            $arr     = [];
           foreach ($p_title as $value) {
               $arr[] = [
                   'id'        => $value['id'],
                   'task_name' => $value['task_name'],
               ];
           }
           return $this->render('end',['arr'=>$arr,'str'=>$str]);
       }
}
