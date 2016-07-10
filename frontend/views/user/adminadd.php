<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2016/7/3
 * Time: 20:36
 */
?>
<div style="height: 30px;">
    <span style="font-size: 17px;"><b>添加管理员信息</b></span>
</div>
<div class="form">
    <form action="" method="post" id="from">
        <ul style="list-style: none;" id="from-ul">
            <li>用户名：</li>
            <li><input type="text" class="form-control" name="username" value="" placeholder="请输入用户名"></li>
            <li>邮箱：</li>
            <li><input type="email" class="form-control" name="malibox" value="" placeholder="请输入邮件"></li>
            <li>密码：<li>
            <li><input type="password" class="form-control" name="password"  value="" placeholder="请输入密码"></li>
            <li>确认密码:</li>
            <li><input type="password" class="form-control" name="newpassword"  value="" placeholder="确认密码"></li>
<!--            <li>-->
            <li>激活:<input type="checkbox" name="status" value="1"><li>
            <li><input type="button"  style="width: 50px;height: 35px;text-align: center;"  onclick="from_action(this);" class='k-button k-button-icontext' id="from-button" value="确认"></li>
            <li><input type="hidden" value="<?php  echo \Yii::$app->request->csrfToken;?>" id="_csrf" name="_csrf"></li>
        </ul>
    </form>

</div>
<div class="error" style="display: none">


</div>
<style type="text/css">
    input[type=text],input[type=password],input[type=email]{
        margin: 5px;
        padding: 0 10px;
        width: 200px;
        height: 34px;
        color: #404040;
        font-size: 18px;
        background: white;
        border: 1px solid;
        border-color: #c4c4c4 #d1d1d1 #d4d4d4;
    }

    input[type=text]:focus,input[type=email]:focus,
    input[type=password]:focus{
        outline: none;
        border-color: #51a7e8;
        box-shadow: inset 0 1px 2px rgba(0,0,0,.075), 0 0 5px rgba(81,167,232,.5);
    }
    input[type=text]:hover,input[type=email]:hover,
    input[type=password]:hover{
        outline: none;
        border-color: #51a7e8;
        box-shadow: inset 0 1px 2px rgba(0,0,0,.075), 0 0 5px rgba(81,167,232,.5);
    }
    input[type=text]:active,input[type=email]:active,
    input[type=password]:active{
        outline: none;
        border-color: #51a7e8;
        box-shadow: inset 0 1px 2px rgba(0,0,0,.075), 0 0 5px rgba(81,167,232,.5);
    }
</style>
<script>
    function from_action(e){
        var input_mail=$('input[type=email]').val();
        if(!input_mail.match(/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/))
        {
            $('.error').css('display','block');
            $('.error').html('邮箱格式不正确');
            return;
        }
        var username=$('input[name=username]').val();
        var password=$('input[name=password]').val();
        var newpassword=$('input[name=newpassword]').val();
        var mes='';
        if(password!==newpassword){
            $('.error').css('display','block');
            $('.error').html('密码两次输入不正确');
            return;
        }
        if(username==''){
            mes+='用户名不能为空';
        }

        if(password==''){
            mes+='密码不能为空';
        }
        if(newpassword==''){
            mes+='确认密码不能为空';
        }

        if(mes!==''){
            $('.error').css('display','block');
            $('.error').html(mes);
            return;
        }

        $.ajax({
            url:'/?r=user/adminadd',
            type:'POST',
            data:$('#from').serialize(),
            dataType:'json',
            success:function(res){
                if(res.code==200){
                    window.location.href='/?r=user/';
                }else {
                    alert(res.message);
                }
            }

        })
    }
</script>