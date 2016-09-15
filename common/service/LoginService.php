<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2016/9/13
 * Time: 11:11
 */

namespace common\service;

use app\models\User;
use Yii;

class LoginService
{
    public $username    = null;
    public $password    = null;
    protected $redis    = null;
    public $loginPrefix = null;
    public $loginError  = [];
    public $session     = null;
    const OK            = 200;
    const ERROR         = 300;

    public function __construct($post, $redis, $session)
    {
        $this->loginPrefix = \Yii::$app->params['login']['error']['frontend'];
        $this->username    = isset($post['username']) ? trim($post['username']) : '';
        $this->password    = isset($post['password']) ? trim($post['password']) : '';
        $this->redis       = $redis;
        $this->session     = $session;

    }

    public function login()
    {
        if (!$this->loginErrorMaxNum()) {
            return false;
        }
        if (!$this->validate()) {
            return false;
        }
        if (!$this->checkAccount()) {
            return false;
        }
    }

    public function validate()
    {
        if (empty($this->username) || empty($this->password)) {
            $this->_addError(self::ERROR, '帐号密码不能为空');
            return false;
        }
        if (strlen($this->username) < 6 || strlen($this->password) < 6) {
            $this->_addError(self::ERROR, '帐号密码不能小于6位');
            return false;
        }
        if (strlen($this->username) > 50 || strlen($this->password) > 50) {
            $this->_addError(self::ERROR, '帐号密码不能大于50位');
            return false;
        }
        return true;

    }
    public function checkAccount()
    {
        $password = substr(md5(md5($this->password)), 0, 34);
        $res      = User::find()->where('username=:username and password=:password', [":username" => $this->username, ":password" => $password])->asArray()->one();
        $key      = $this->loginPrefix . $this->username;
        if (empty($res)) {
            $val = $this->redis->get($key);
            if (empty($val)) {
                $date   = strtotime(date('Y-m-d', time())) + 86400;
                $expire = $date - time();
                $this->redis->set($key, 1);
                $this->redis->expire($key, $expire);
            } elseif ($val < \Yii::$app->params['login']['error']['num']) {
                $this->redis->set($key, ++$val);
            }
            $this->_addError(self::ERROR, '账号密码错误');
            return false;
        }
        if (!$res['status'] == 1) {
            $this->_addError(self::ERROR, '账号没有激活,请联系管理员~');
            return false;
        }
        if (!$this->updateLoginTime($res['login_time'], $res['id'])) {
            return false;
        }
        if (isset($res['id']) && !empty($res['id'])) {
            $session                    = $this->session;
            $session['id']              = $res['id'];
            $session['name']            = $res['username'];
            $session['roles']           = $res['roles'];
            $session['last_login_time'] = date('Y-m-d H:i:s', $res['login_time']);
            $this->redis->set($key, 0);
            $this->_addError(self::OK, '成功登陆', '/?r=user/index');
            return true;
        } else {
            $this->_addError(self::ERROR, '系统异常请稍后重试');
            return false;
        }
    }

    public function loginErrorMaxNum()
    {
        $key = $this->loginPrefix . $this->username;
        $val = $this->redis->get($key);
        if ($val >= \Yii::$app->params['login']['error']['num']) {
            $this->_addError(self::ERROR, '禁止登陆,错误达到上限');
            return false;
        }
        return true;
    }

    public function updateLoginTime($login_time, $id)
    {
        $attrbutes = [
            'last_login_time' => $login_time,
            'login_time'      => time()];
        if (!User::updateAll($attrbutes, ['id' => $id])) {
            $this->_addError(self::ERROR, '系统异常请稍后重试');
            return false;
        } else {
            return true;
        }
    }

    public function _addError($code, $message, $res = '')
    {
        $arr = [
            'error' => ['code' => $code, 'message' => $message],
            'data'  => $res,
        ];
        $this->loginError = $arr;
    }
    public function getResult()
    {
        return json_encode($this->loginError);
    }

}
