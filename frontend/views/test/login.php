<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2016/6/2
 * Time: 15:03
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<script src="/assets/b8f13ea9/jquery.js"></script>
<script  type="text/javascript" src="/assets/uniform/jquery.uniform.js"></script>
<link rel="stylesheet" type="text/css" href="/assets/uniform/uniform.default.css">
<div class="login" style="height: 500px;">
<div id="title" style="text-align:center;">
    <span style="color: #0000aa;font-size: 15px;">欢迎访问 留下你宝贵的建议 请输入你的姓名方便我们后续联系你</span>

<div class="portlet-body form" id="search_box" style="text-align:center;">
    <div class="error" style="display: none"></div>
            <div class="label"><label>手机号:</label></div>
                    <div class="input">
            <input  style="width: 100px;height: 20px;"  onchange="this.value=this.value.replace(/\D/g,'')"  name="num" id="mb_phone" type="text" size="11"  placeholder="请输入手机号"></div>
            <div class="label"><label>&nbsp;&nbsp;&nbsp;姓名:</label></div>
                    <div  class="input">
            <input style="width: 100px;height: 20px;" name="username" type="text" id="username"></div>
<div class="img" style="background: #00aa00;text-align: center;">
      <img src="/assets/image/log.jpg" id="search_btn"  width="25px" height="25px" /></div>
</div>
</div>
</div>

   <script language="JavaScript">

     $(function () {
         $("input:checkbox, input:radio, input:text").uniform();
        $("#search_btn").on('click',function(e) {
            var num = $('#mb_phone').val();
            var name=$('#username').val();

            if (num == undefined) {
                $('.error').css('display', 'block');
                $('.error').html('<span style="color: red">手机号不能为空</span>');
                return false;
            }
            if (num.length !== 11) {
                $('.error').css('display', 'block');
                $('.error').html('<span style="color: red">长度应该是11位</span>');
                return false;
            }
            if (num == undefined) {
                $('.error').css('display', 'block');
                $('.error').html('<span style="color: red">姓名不能为空</span>');
                return false;
            }
            $.ajax({
                url: '/?r=test/user',
                type: 'POST',
                data: {
                    mb_phone: num,
                    name:name,
                },
                dataType: 'json',
                success: function (res) {

                    console.log(res);
                }
            });
        });
         // alert('success');


     });


    </script>
<style type="text/css">
    #search_btn:hover{
        cursor:pointer;
    }


    }

</style>
