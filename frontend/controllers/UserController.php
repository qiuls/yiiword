<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2016/6/7
 * Time: 19:02
 */

namespace frontend\controllers;

use app\models\Csawk;
use app\models\Csuser;
use app\models\User;
//use common\controllers\Mail;
use yii\web\Controller;

//use yii\swiftmailer;

class UserController extends Controller
{
    public $layout = 'afooter';
//    public $enableCsrfValidation = false;
    /**
     * 初始化方法
     */
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $session = \Yii::$app->session;
        $session->open();
        if (empty($session['id'])) {
            $this->redirect('/?r=admin/index');
        } else {
            if (!\Yii::$app->cache->get('myid') && !\Yii::$app->cache->get('my_last_login')) {
                \Yii::$app->cache->set('myid', $session['name'], 3600);
                \Yii::$app->cache->set('my_last_login', date('Y-m-d H:i:s', $session['last_login_time']), 3600);
            }
        }
    }

    /**
     * 问卷题目主页面
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('user');

    }

    /**
     * 问卷 题目 api
     * @return string
     */
    public function actionIndexapi()
    {
        if (\Yii::$app->request->isGet) {
            $type      = intval(\Yii::$app->request->get('type', 1));
            $page      = intval(\Yii::$app->request->get('page', 1));
            $pageSzie  = intval(\Yii::$app->request->get('pageSize', 10));
            $offset    = ($page - 1) * $pageSzie;
            $count     = Csawk::find()->where('type=:type', [':type' => $type])->count();
            $csawk_res = Csawk::find()->where('type=:type', [':type' => $type])->offset($offset)->limit($pageSzie)->orderBy('id desc')->all();
            $arr       = [];
            foreach ($csawk_res as $v) {
                if ($type == 1) {
                    $arr[] = [
                        'id'        => $v['id'],
                        'type'      => $v['type'],
                        'task_name' => $v['task_name'],
                        'meta'      => $v['meta'],
                    ];
                } elseif ($type == 2) {
                    $p_title = '';
                    if (!empty($v['pid'])) {
                        $pres    = Csawk::find()->where('id=:id', [':id' => $v['pid']])->one();
                        $pres    = $pres->toArray();
                        $p_title = $pres['task_name'];
                    }
                    switch ($v['input']) {
                        case 1:
                            $v['input'] = '文本框';
                            break;
                        case 2:
                            $v['input'] = '单选框';
                            break;
                        case 3:
                            $v['input'] = '复选框';
                            break;
                        default:
                            $v['input'] = '未知';
                    }
                    $arr[] = [
                        'id'           => $v['id'],
                        'task_name'    => $v['task_name'],
                        'type'         => $v['type'],
                        'meta'         => $v['meta'],
                        'a'            => $v['a'],
                        'b'            => $v['b'],
                        'c'            => $v['c'],
                        'd'            => $v['d'],
                        'input'        => $v['input'],
                        'p_title_name' => $p_title,
                    ];
                } else {
                    break;
                }
            }
            $arr['recordList'] = $arr;
            $arr['totalCount'] = $count;
            return json_encode($arr);
        }
    }

    /**
     * 添加 问卷 题目
     * @return bool|string
     */
    public function actionAdd()
    {
        if (!empty($_POST)) {
            $model   = new Csawk();
            $session = \Yii::$app->session;
            $session->open();
            $type  = intval(\Yii::$app->request->post('type', ''));
            $title = \Yii::$app->request->post('task_name');
            $meta  = \Yii::$app->request->post('meta', '');
            if ($type == 1) {
                $model->task_name = $title;
                $model->type      = $type;
                $model->create_id = $session['id'];
                if (!empty($meta)) {
                    $model->meta = $meta;
                }
                if ($model->save()) {
                    unset($meta, $model);
                    return json_encode(['code' => 200, 'message' => '添加success']);

                } else {
                    unset($meta, $model);
                    return json_encode(['code' => 300, 'message' => '添加error']);
                }
            } elseif ($type == 2) {
                $pid = \Yii::$app->request->post('pid', '');
                if (empty($pid)) {
                    return json_encode(['code' => 300, 'message' => '添加失败']);
                }
                $model->task_name = $title;
                $model->type      = $type;
                $model->a         = \Yii::$app->request->post('a');
                $model->b         = \Yii::$app->request->post('b');
                $model->c         = \Yii::$app->request->post('c');
                $model->d         = \Yii::$app->request->post('d');
                $model->input     = intval(\Yii::$app->request->post('input'));
                $model->create_id = $session['id'];
                $model->pid       = $pid;
                if (!empty($meta)) {
                    $model->meta = $meta;
                }
                if ($model->save()) {
                    return json_encode(['code' => 200, 'message' => '添加success']);
                } else {
                    return json_encode(['code' => 300, 'message' => '添加error']);
                }
            } else {
                return json_encode(['code' => 300, 'message' => '非法请求']);
            }
        } else {
//            $arr_res=Csawk::find()->where('type=:type',[':type'=>2])->all();

            return $this->render('add');
        }
    }

    /**
     * 获取用户问卷作答列表
     * @return string
     */
    public function actionListapi()
    {
//        if (empty($_GET['pid'])){
        //            $pid_res = Csawk::find()->where('type=:type', [':type' => 1])->one();
        //            $pid     = $pid_res->toArray();
        //            $pid     = $pid['id'];
        //            $pid     = 2;
        //        } else {
        //            $pid = $_GET['pid'];
        //        }
        $pid = \Yii::$app->request->get('pid', '');
        if (empty($pid)) {
            return json_encode(['code' => 300, 'message' => '非法请求']);
        }
        $res   = Csuser::find()->where('task_pid=:task_pid', [':task_pid' => $pid])->groupBy('mb_phone')->orderBy('id desc')->all();
        $res_s = [];
        foreach ($res as $v) {
            $res_s[] = [
                'pid'         => $pid,
                'mb_phone'    => $v->mb_phone,
                'name'        => $v->name,
                'task_ptitle' => $v->task_ptitle,
            ];
        }
        return json_encode($res_s);
    }

    /**
     * 用户答题主页面
     * @return string
     */
    public function actionUserlist()
    {
        return $this->render('userlist');
    }

    /**
     * 获取问卷title
     * @return string
     */
    public function actionApi()
    {
        $type = intval(\Yii::$app->request->get('type', ''));
        if (empty($type)) {
            return json_encode(['code' => 403, 'message' => '请求异常']);
        }
        $p_title = Csawk::find()->where('type=:type', [':type' => $type])->all();
        $arr     = [];
        foreach ($p_title as $value) {
            $arr[] = [
                'id'        => $value['id'],
                'task_name' => $value['task_name'],
            ];
        }
        return json_encode($arr);
    }

    /**
     * 获取用户问卷答题
     * @return string
     */
    public function actionUsercwkapi()
    {
        if (empty($_POST)) {
            return json_encode(['code' => 403, 'message' => '请求异常']);
        } else {
            $task_pid = intval(\Yii::$app->request->post('pid', ''));
            if (empty($task_pid)) {
                return json_encode(['code' => 403, 'message' => '缺少参数']);
            }
            $mb_phone = intval(\Yii::$app->request->post('mobile', ''));
            if (empty($mb_phone)) {
                return json_encode(['code' => 403, 'message' => '缺少参数']);
            }

            //$mb_phone=intval($mb_phone);
            //             if(!preg_match("/^1\d{10}$/",$mb_phone)){
            //                 return json_encode(['code' => 403, 'message' => '格式有误']);
            //             }
            $sql      = "SELECT a.task_id,a.res,b.task_name,b.a,b.b,b.c,b.d,b.input from csuser as a,csawk as b where a.task_id=b.id AND a.mb_phone=$mb_phone and a.task_pid=$task_pid";
            $user_res = \Yii::$app->db->createCommand($sql)->queryAll();
            $arr      = [];
            foreach ($user_res as $v) {
                switch ($v['input']) {
                    case 1:
                        $v['input'] = '文本框';
                        break;
                    case 2:
                        $v['input'] = '单选框';
                        break;
                    case 3:
                        $v['input'] = '复选框';
                        break;
                    default:
                        $v['input'] = '未知';
                }
                $arr[] = [
                    'task_id'   => $v['task_id'],
                    'a'         => $v['a'],
                    'b'         => $v['b'],
                    'c'         => $v['c'],
                    'd'         => $v['d'],
                    'input'     => $v['input'],
                    'task_name' => $v['task_name'],
                    'res'       => $v['res'],
                ];
            }
            return json_encode($arr);
        }
    }
    /**
     * 获取问卷单选框统计值
     */
    public function actionParentcount()
    {
        $pid = \Yii::$app->request->get('pid', '');
        if (empty($pid)) {
            return $this->redirect(['userlist']);
        }
        $pid     = intval($pid);
        $awk_res = Csawk::find()->where('pid=:pid', [':pid' => $pid])->all();
        $arr     = [];

        foreach ($awk_res as $v) {
            if ($v['input'] == 2) {
                $count   = Csuser::find()->where('task_id=:task_id', ['task_id' => $v->id])->count();
                $a_count = Csuser::find()->where('task_id=:task_id and res=:res', ['task_id' => $v->id, ':res' => 'a'])->count();
                $b_count = Csuser::find()->where('task_id=:task_id and res=:res', ['task_id' => $v->id, ':res' => 'b'])->count();
                $c_count = Csuser::find()->where('task_id=:task_id and res=:res', ['task_id' => $v->id, ':res' => 'c'])->count();
                $d_count = Csuser::find()->where('task_id=:task_id and res=:res', ['task_id' => $v->id, ':res' => 'd'])->count();
                $a_count = $a_count == 0 ? 0 : sprintf('%.2f', ($a_count / $count) * 100);
                $b_count = $b_count == 0 ? 0 : sprintf('%.2f', ($b_count / $count) * 100);
                $c_count = $c_count == 0 ? 0 : sprintf('%.2f', ($c_count / $count) * 100);
                $d_count = $d_count == 0 ? 0 : sprintf('%.2f', ($d_count / $count) * 100);
                $arr[]   = [
                    'count'     => $count,
                    'task_id'   => $v->id,
                    'task_name' => $v->task_name,
                    'a'         => $v->a,
                    'b'         => $v->b,
                    'c'         => $v->c,
                    'd'         => $v->d,
                    'a_count'   => $a_count . '%',
                    'b_count'   => $b_count . '%',
                    'c_count'   => $c_count . '%',
                    'd_count'   => $d_count . '%',
                ];
            } else {
                continue;
            }

        }
        return json_encode($arr);
    }

    public function actionUpdate()
    {
        if (\Yii::$app->request->isPost) {
            $type = \Yii::$app->request->post('type', '');

            $session = \Yii::$app->session;
            $session->open();
            $id = \Yii::$app->request->post('id', '');
            if (empty($id)) {
                return json_encode(['code' => 300, 'message' => '缺少参数']);
            }
            $model = Csawk::find()->where('id=:id', [':id' => $id])->one();
            $title = \Yii::$app->request->post('task_name');
            $meta  = \Yii::$app->request->post('meta');
            if ($type == 1) {
                $model->task_name = $title;
//                $model->create_id = $session['id'];
                if (!empty($meta)) {
                    $model->meta = $meta;
                }
                if ($model->save()) {
                    unset($meta, $model);
                    return json_encode(['code' => 200, 'message' => '更新success']);
                } else {
                    unset($meta, $model);
                    return json_encode(['code' => 300, 'message' => '更新error']);
                }
            } elseif ($type == 2) {
                $pid = \Yii::$app->request->post('pid', '');
                if (empty($pid)) {
                    return json_encode(['code' => 300, 'message' => '更新失败']);
                }
                $model->task_name = $title;
                $model->a         = \Yii::$app->request->post('a');
                $model->b         = \Yii::$app->request->post('b');
                $model->c         = \Yii::$app->request->post('c');
                $model->d         = \Yii::$app->request->post('d');
                $model->input     = intval(\Yii::$app->request->post('input'));
//                $model->create_id = $session['id'];
                $model->pid = $pid;
                if (!empty($meta)) {
                    $model->meta = $meta;
                }
                if ($model->save()) {
                    return json_encode(['code' => 200, 'message' => '更新success']);
                } else {
                    return json_encode(['code' => 300, 'message' => '更新error']);
                }
            } else {
                return json_encode(['code' => 403, 'message' => '参数错误']);
            }

            //code
        } else {
            if (!\Yii::$app->request->get('id', '')) {
                return json_encode(['code' => 403, 'message' => '缺少参数']);
            }
            $id        = intval(\Yii::$app->request->get('id'));
            $type      = intval(\Yii::$app->request->get('type', 1));
            $cs_awkres = Csawk::find()->where('id=:id AND type=:type', [':id' => $id, ':type' => $type])->one();
            $cs_awkarr = $cs_awkres->toArray();
            return $this->render('update', ['arr' => $cs_awkarr]);
        }
    }

//    public function

    public function actionAdmininfo()
    {
        $this->init();
        $session = \Yii::$app->session;
        $session->open();
        if (!\Yii::$app->request->isPost) {
            $userinfo = User::find()->where('id=:id', [':id' => $session['id']])->one();
            $userinfo = $userinfo->toArray();
            return $this->render('admininfo', ['user' => $userinfo]);
        } else {
//            var_dump(\Yii::$app->request->post());
            $id = \Yii::$app->request->post('id', '');
            if (empty($id)) {
                return json_encode(['code' => 400, 'message' => '请求异常请重新打开']);
            }
            $yzm = \Yii::$app->request->post('yzm', '');
            if (empty($id)) {
                return json_encode(['code' => 400, 'message' => '验证码为空']);
            }
            $time = time() - $session['time'];
            if ($time > 60) {
                return json_encode(['code' => 400, 'message' => '验证码过期']);
            }
            if ($yzm !== $session['str']) {
                return json_encode(['code' => 400, 'message' => '验证码错误']);
            }
            $admin_res           = User::find()->where('id=:id', [':id' => $session['id']])->one();
            $admin_res->username = \Yii::$app->request->post('username', substr(sha1(time()), 0, 10));
            $admin_res->malibox  = \Yii::$app->request->post('malibox');
            $password            = \Yii::$app->request->post('password', '');
            $query               = \Yii::$app->request->post('query', '');
            if (!empty($password) && !empty($query)) {
                $admin_res->password = \Yii::$app->request->post('password');
                unset($session['id'], $session['time'], $session['str']);
            }
            if ($admin_res->save()) {
                return json_encode(['code' => 200, 'message' => '修改成功']);
            } else {
                return json_encode(['code' => 400, 'message' => '修改失败']);
            }
        }
    }

    public function actionMail()
    {
        if (\Yii::$app->request->isPost) {
            $mailTo = \Yii::$app->request->post('mailto', '');
            if (empty($mailTo)) {
                return json_encode(['message' => '收件人为空']);
            }
            $mailsubject = '修改密码提示';
            $str         = $this->str_num(6);
            $session     = \Yii::$app->session;
            $session->open();
            $session['str']  = $str;
            $session['time'] = time();
            $mailbody        = "你的验证码为{$str} 有效期为60秒";
            $bool            = $this->Usermail($mailTo, $mailsubject, $mailbody);
            if ($bool) {
                return json_encode(['message' => '发送成功']);
            } else {
                return json_encode(['message' => '发送失败,请关闭后在操作']);
            }

        } else {
            return json_encode(['code' => 300, 'message' => '请求异常']);
        }
    }

    protected function str_num($i)
    {
        $str = '';
        while ($i) {
            $str .= mt_rand(0, 9);
            $i--;
        }
        return $str;
    }

    /**
     * 使用 yii框架自带发邮件类
     * @param $eamilto  要发送给那个人的邮箱
     * @param $Subject  邮件主题
     * @param $boby     发送的消息内容
     * @return bool
     */
    protected function Usermail($eamilto, $Subject, $boby)
    {
        $mail = \Yii::$app->mailer->compose();
        $mail->setTo($eamilto);
        $mail->setSubject($Subject);
        $mail->setHtmlBody($boby);
        return $mail->send();
    }

}