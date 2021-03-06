<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2016/6/7
 * Time: 19:02
 */

namespace frontend\controllers;

use app\models\Call;
use app\models\Csawk;
use app\models\Csuser;
use app\models\User;
//use common\controllers\Mail;
use yii\web\Controller;
use common\controllers\CurveyLog;
use common\controllers\Email;

class UserController extends Controller
{
    public $layout = 'afooter';
    public $session_id='';
    public $session=null;

    /**
     *
     */
    public function beforeAction($action)
    {
        $session = \Yii::$app->session;
        $session->open();
        $this->session=$session;
        if(empty($session['id'])){
            $this->redirect('/?r=admin/index');
        }else{
            $this->session_id=$this->session['id'];
        }
        return parent::beforeAction($action); // TODO: Change the autogenerated stub
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
     * 更新问卷状态
     * @return string
     */
     public function actionUpdatestatus(){
         if(\Yii::$app->request->isPost){
             $id=intval(\Yii::$app->request->post('id',''));
             $status=\Yii::$app->request->post('status','');
             if(empty($id)){
                 return json_encode(['code' => 300, 'message' => '参数错误']);
             }
             if(!is_numeric($status)){
                 return json_encode(['code' => 300, 'message' => '参数错误']);
             }
               $res=Csawk::find()->where('id=:id',[':id'=>$id])->one();
                $arr=$res->toArray();
                if($arr['status']==$status){
                    switch($arr['status']){
                        case 0:
                           $status='下线';
                            break;
                        case 1:
                            $status='发布';
                            break;
                        default:
                            $status='下线';
                    }
                    return json_encode(['code' => 300, 'message' => "状态已经是{$status}了"]);
                }else{
                    $res->status=$status;
                    if ($res->save()){
                        return json_encode(['code' => 200, 'message' => '更新成功,刷新页面']);
                      } else {
                        return json_encode(['code' => 300, 'message' => '添加失败']);
                     }
                }
         }else{
             return json_encode(['code' => 300, 'message' => '非法请求']);
         }
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
                if ($type == 1) {//问卷title
                     $url=$_SERVER['HTTP_HOST'].'/?r=test/begin%26w_id='.$v['id'];
                     $file_name=md5($url).'.png';
                     $dir=YII_WEB.'/images/';
                     $file_name_arr=scandir($dir);
                    if(!in_array($file_name,$file_name_arr)){
                        $readfile="http://qr.topscan.com/api.php?text={$url}";
                        $str=file_get_contents($readfile);
                        if(!file_put_contents($dir.$file_name,$str)) {
                            $file_name='';
                        }
                    }
                      $arr[] = [
                        'id'        => $v['id'],
                        'type'      => $v['type'],
                        'task_name' => htmlspecialchars_decode($v['task_name']),
                        'status'    =>$v['status'],
                        'create_time'=>date('Y-m-d H:i:s',$v['create_time']),
                        'last_update_time'=>date('Y-m-d H:i:s',$v['last_update_time']),
                        'meta'      => $v['meta'],
                          'url'=>$file_name,
                    ];
                } elseif ($type == 2) {
                    $p_title = '';
                    if (!empty($v['pid'])) {
                        $pres    = Csawk::find()->where('id=:id', [':id' => $v['pid']])->one();
                        $pres    = $pres->toArray();
                        $p_title = $pres['task_name'];
                    }
                    switch ($v['input']){
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
                        'task_name'    => htmlspecialchars_decode($v['task_name']),
                        'type'         => $v['type'],
                        'meta'         => $v['meta'],
                        'a'            => $v['a'],
                        'b'            => $v['b'],
                        'c'            => $v['c'],
                        'd'            => $v['d'],
                        'input'        => $v['input'],
                        'status'    =>$v['status'],
                        'p_title_name' => htmlspecialchars_decode($p_title),
                        'create_time'=>date('Y-m-d H:i:s',$v['create_time']),
                        'last_update_time'=>date('Y-m-d H:i:s',$v['last_update_time']),
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
        if (\Yii::$app->request->isPost){
            $model   = new Csawk();
            $session =$this->session;
            $type  = intval(\Yii::$app->request->post('type', ''));
            $title = \Yii::$app->request->post('task_name');
            $meta  = \Yii::$app->request->post('meta', '');
            $model->last_update_time=time();
            $model->create_time     =time();
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
            return $this->render('add');
        }
    }

    /**
     * 获取用户问卷作答列表
     * @return string
     */
    public function actionListapi()
    {
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
                'task_ptitle' => htmlspecialchars_decode($v->task_ptitle),
                'create_time'=>date('Y-m-d H:i:s',$v->create_time),
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
                'task_name' => htmlspecialchars_decode($value['task_name']),
            ];
        }
        return json_encode($arr);
    }

    /**
     * 获取用户问卷答题详情
     * @return string
     */
    public function actionUsercwkapi()
    {
        if (empty($_POST)) {
            return json_encode(['code' => 403, 'message' => '请求异常']);

        } else {
            $task_pid = intval(\Yii::$app->request->post('pid',''));
            if (empty($task_pid)) {
                return json_encode(['code' => 403, 'message' => '缺少参数']);
            }
            $mb_phone = \Yii::$app->request->post('mobile','');
            if (empty($mb_phone)) {
                return json_encode(['code' => 403, 'message' => '缺少参数']);
            }
            if(!filter_var($mb_phone,FILTER_VALIDATE_EMAIL)){
                return json_encode(['code' => 403, 'message' => '邮箱格式不正确']);
            }
            $sql      = "SELECT a.task_id,a.res,b.task_name,b.a,b.b,b.c,b.d,b.input from csuser as a,csawk as b where a.task_id=b.id AND a.mb_phone='{$mb_phone}' and a.task_pid=$task_pid";
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
                    'task_name' => htmlspecialchars_decode($v['task_name']),
//                    'create_time'=>date('Y-m-d H:i:s',$v['create_time']),
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
                    'task_name' => htmlspecialchars_decode($v->task_name),
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

    /**
     * 更新问卷题目
     * @return string
     */
    public function actionUpdate()
    {
        if (\Yii::$app->request->isPost) {
            $type = \Yii::$app->request->post('type', '');
            $session = $this->session;
            $id = \Yii::$app->request->post('id', '');
            if (empty($id)) {
                return json_encode(['code' => 300, 'message' => '缺少参数']);
            }
            $model = Csawk::find()->where('id=:id', [':id' => $id])->one();
            $title = \Yii::$app->request->post('task_name');
            $meta  = \Yii::$app->request->post('meta');
            $model->last_update_time=time();
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

    /**
     * 管理员用户信息 更新管理员信息
     * @return string|\yii\web\Response
     */
    public function actionAdmininfo()
    {
        $session = $this->session;
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
            $username=\Yii::$app->request->post('username','');
            if(empty($username)){
                return json_encode(['code' => 400, 'message' => '用户名为空']);
            }else{
                if(!$this->Userunique($username)){
                    return json_encode(['code' => 400, 'message' => '用户名已存在']);
                }
            }
            $admin_res           = User::find()->where('id=:id', [':id' => $session['id']])->one();
            $admin_res->username = $username;
            $admin_res->malibox  = \Yii::$app->request->post('malibox');
            $password            = \Yii::$app->request->post('password', '');
            $query               = \Yii::$app->request->post('query', '');
            if (!empty($password) && !empty($query)) {
                $admin_res->password = substr(md5(md5($password)),0,34);
                unset($session['id'], $session['time'], $session['str']);
            }
            if ($admin_res->save()) {
                return json_encode(['code' => 200, 'message' => '修改成功']);
            } else {
                return json_encode(['code' => 400, 'message' => '修改失败']);
            }
        }
    }

    /**
     * 发送邮件修改密码
     * @return string
     */
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
                $this->write("邮件发送失败-$mailTo",__METHOD__,1);
                return json_encode(['message' => '发送失败,请关闭后在操作']);
            }
        } else {
            $message='请求异常';
            $userIp=\Yii::$app->request->getUserIP();
            \Yii::error($message.'用户ip'.$userIp,__METHOD__);
            return json_encode(['code' => 300, 'message' => $message]);
        }
    }

    /**
     * 添加管理员
     * @return string
     */
    public function actionAdminadd(){
        if(\Yii::$app->request->isPost){
            $admin_res=new User();
            $username=\Yii::$app->request->post('username');
            if(!empty($username)){
                if(!$this->Userunique($username)){
                    return json_encode(['code' => 300, 'message' => '用户名已存在']);
                }
            }else{
                return json_encode(['code' => 300, 'message' => '用户名不能为空']);
            }
            $password=\Yii::$app->request->post('password','');
            if(empty($password)){
                return json_encode(['code' => 300, 'message' => '密码不能为空']);
            }
            $malibox=\Yii::$app->request->post('malibox','');
            if(empty($malibox)){
                return json_encode(['code' => 300, 'message' => '邮箱不能为空']);
            }
            $admin_res->username=$username;
            $admin_res->password=substr(md5(md5($password)),0,34);
            $admin_res->malibox=$malibox;
            if(\Yii::$app->request->post('status','')){
                $admin_res->status=1;
            }
            $admin_res->last_login_time=time();
            $admin_res->login_time=time();
            if($admin_res->save()){
                return json_encode(['code' => 200, 'message' => '添加成功']);
            }else{
                return json_encode(['code' => 300, 'message' => '添加失败请稍后再试~']);
            }
        }else{
            return $this->render('adminadd');
        }
    }

    /**
     * 管理员浏览答题列表发送回访邮件
     */
    public function actionCall(){
        if(\Yii::$app->request->isPost){
            $content=\Yii::$app->request->post('content','');
            if(empty($content)){
                return json_encode(['code' => 300, 'message' => '内容不能为空']);
            }
            $mailto=\Yii::$app->request->post('mailto','');
            if(empty($mailto)){
                return json_encode(['code' => 300, 'message' => '内容不能为空']);
            }
            if(!filter_var($mailto, FILTER_VALIDATE_EMAIL)){
                return json_encode(['code' => 300, 'message' => '邮箱格式错误']);
            }
            if(empty($this->session_id)){
                return $this->redirect(['admin']);
            }
            $cu_id=\Yii::$app->request->post('cu_id');
            $subject='问卷回访';
//            $tr = \Yii::$app->db->beginTransaction();
            $status=$this->Usermail($mailto,$subject,$content,true);
            $create_id=$this->session_id;
            $call_ob=new Call();
            $call_ob->call_content=$content;
            $call_ob->mailto=$mailto;
            $call_ob->create_id=$create_id;
            $call_ob->status=intval($status);
            $call_ob->p_id=$cu_id;
            $call_ob->call_time=time();
            $call_ob->save();
            if($status){
                return json_encode(['code' => 200, 'message' => '邮件发送成功']);
            }else{
                $this->write("邮件发送失败-{$mailto}",__METHOD__,1);
                return json_encode(['code' => 300, 'message' => '邮件发送失败请稍后再试~']);
            }
        }else{
            return json_encode(['code' => 300, 'message' => '非法请求~']);
        }
     }


    /**
     * 异步批量发送
     */
    public function  actionCallAll(){
        $content=\Yii::$app->request->post('content','');
        if(empty($content)){
            return json_encode(['code' => 300, 'message' => '内容不能为空']);
        }
        $mailto=\Yii::$app->request->post('mailto','');
        if(empty($mailto)){
            return json_encode(['code' => 300, 'message' => '发送邮箱不能为空']);
        }
        $mailto=explode(',',$mailto);
        $to=[];
        foreach($mailto as $key=>$mailtos){
        if(filter_var($mailto[$key], FILTER_VALIDATE_EMAIL)){
           $to[]=$mailto[$key];
          }
        }
        if(empty($to)){
            return json_encode(['code' => 300, 'message' => '邮箱格式错误']);
        }
        unset($mailto);
        if(empty($this->session_id)){
            return $this->redirect(['admin']);
        }
        $p_id=\Yii::$app->request->post('cu_id');
        $data['content']=htmlspecialchars_decode($content);
        $data['mailto']=$to;
        $data['session_id']=$this->session_id;
        $data['pid']=$p_id;
        $url='http://'.\Yii::$app->params['swoole']['host'].':'.\Yii::$app->params['swoole']['port'];
        $post_data=http_build_query($data);
        unset($data);
        $ch=curl_init();
        curl_setopt ( $ch, CURLOPT_URL, $url);
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
        $re=curl_exec($ch);
        if(is_bool($re) && $re===false){
            $this->write('swoole未启动或者系统错误',__METHOD__,2);
            return json_encode(['code' => 400, 'message' => '系统错误']);
        }else{
            return json_encode(['code' => 200, 'message' => '操作成功']);
        }
    }

    /**
     * 回访邮件列表
     * @return string
     */
    public function actionCallshow(){
        if(\Yii::$app->request->isPost){
            $cu_id=\Yii::$app->request->post('cu_id','');
            if(empty($cu_id)){
                \Yii::warning('cu_id为空',__METHOD__);
                return json_encode(['code' => 300, 'message' => '参数错误']);
            }
            $mailto=\Yii::$app->request->post('mailto','');
            if(empty($mailto)){
                \Yii::warning('mailto为空',__METHOD__);
                return json_encode(['code' => 300, 'message' => '内容不能为空']);
            }
            $sql      = 'SELECT a.call_content,a.`status`,a.call_time,a.mailto,b.username from `call` as a,`user` as b where a.create_id=b.id AND a.p_id=%u and mailto="%s"';
            $sql      = sprintf($sql,$cu_id,$mailto);
            $callRes = \Yii::$app->db->createCommand($sql)->queryAll();
            if(empty($callRes)){
                return json_encode([]);
            }
            $arr=[];
            foreach($callRes as $v){
                $v['status']=$v['status'] ? '发送成功' :'发送失败';
                $arr[]=[
                    'mailto'=>$v['mailto'],
                    'call_content'=>htmlspecialchars_decode($v['call_content']),
                    'status'=>$v['status'],
                    'call_time'=>date('Y-m-d H:i:s',$v['call_time']),
                    'create_name'=>$v['username'],
                ];
            }
              return json_encode($arr);
        }else{
            $message='非法请求~';
            $userIp=\Yii::$app->request->getUserIP();
            \Yii::error($message.'用户ip'.$userIp,__METHOD__);
            return json_encode(['code' => 300, 'message' => $message]);
        }
    }

    /**
     * 后台用户列表
     */
      public function actionBackendlist(){
        if(\Yii::$app->request->isPost){
            $userRes=User::find()->select(['id','username','status','malibox'])->all();
            $returnArr=[];
            foreach($userRes as $v){
                $returnArr[]=[
                    'id'=>$v['id'],
                    'username'=>$v['username'],
                    'malibox'=>$v['malibox'],
                    'status_message'=>$v['status']==1 ?'激活' :'未激活',
                    'status'=>$v['status'],
                ];
            }
            $arr=[
                'list'=>$returnArr,
                 'total'=>count($returnArr),
            ];
            return json_encode($arr);
         }else{
            return $this->render('backendlist');
        }
      }

    /**
     * 管理后台用户 禁用 启用
     * @return string
     */
    public function actionBackendupdate(){
        if(\Yii::$app->request->isPost){
              $id=\Yii::$app->request->post('id','');
            if(empty($id)){
                \Yii::warning('id为空 '.__METHOD__);
                return json_encode(['code' => 300, 'message' => '参数错误']);
            }
            $status=\Yii::$app->request->post('status','');
            if(empty($id)){
                \Yii::warning('status为空 '.__METHOD__);
                return json_encode(['code' => 300, 'message' => '参数错误']);
            }
            $res=User::find()->where('id=:id',[':id'=>$id])->one();
            $resArr=$res->toArray();
            if($resArr['status']==$status){
                return json_encode(['code' => 300, 'message' => '请刷新页面重试~']);
            }
            $session = \Yii::$app->session;
            $session->open();
            if($session['roles']!=='root'){
                return json_encode(['code' => 300, 'message' => '角色权限不足~']);
            }
            $res->status=$status;
            if($res->save()){
                return json_encode(['code' => 200, 'message' => '操作成功~,请刷新页面']);
            }else{
                $post=json_encode(\Yii::$app->request->post());
                \Yii::warning('请求参数：'.$post.'  '.__METHOD__);
                return json_encode(['code' => 200, 'message' => '操作失败~,请稍后再试']);
            }
          }else{
            $message='非法请求~';
            $userIp=\Yii::$app->request->getUserIP();
            \Yii::error($message.'用户ip:'.$userIp.__METHOD__);
            return json_encode(['code' => 300, 'message' => $message]);
        }
    }


    /**
     * 判断 管理员用户名是否存在
     * @param $username
     * @return bool
     */
    protected function Userunique($username){
        $res=User::find()->where('username=:username',[':username'=>$username])->one();
        if(empty($res)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $i
     * @return string
     * 生成数字验证码
     */
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
     * 使用 自定义发送邮件类
     * @param $eamilto  要发送给那个人的邮箱
     * @param $Subject  邮件主题
     * @param $boby     发送的消息内容
     * @param $type   是否发送html实体
     * @return bool
     *
     */
    protected function Usermail($eamilto, $Subject, $boby,$type=false)
    {
        $mail=new Email();
        $bool=$mail->Send($eamilto,htmlspecialchars_decode($boby),$type,$Subject);
       return $bool;
    }

    /**
     * @param $message
     * @param $metmod
     * @param int $level
     * 自定义写日志
     */
    protected function write($message,$metmod,$level=1){
        $CurveyLog=new CurveyLog('frontend');
        $CurveyLog->write($message,$metmod,$level,'frontend');
    }
    }
