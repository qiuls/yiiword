<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2016/4/20
 * Time: 17:06
 */

namespace frontend\models;
use yii\base\Model;

class SayForm extends Model
{
   public $username;
    public $password;
    public function rules()
    {
       // return parent::rules(); // TODO: Change the autogenerated stub
        return [
            [['username','password'],'required'],
       //     ['username','password'],
        ];
    }
}