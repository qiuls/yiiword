<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2016/7/1
 * Time: 11:36
 */
?>
<div style="height: 30px;">
    <span style="font-size: 17px;"><b>修改用户信息</b></span>
</div>
<div class="form">
    <form action="" method="post" id="from">
        <ul style="list-style: none;" id="from-ul">
            <li>新用户名：</li>
            <li><input type="text" class="form-control" name="username" value="<?php  echo $user['username'];?>" placeholder="请输入新的用户名"></li>
            <li>新邮箱：</li>
            <li><input type="email" class="form-control" name="malibox" value="<?php  echo $user['malibox'];?>" placeholder="请输入新的邮件"></li>
            <li>新密码：<li>
            <li><input type="password" class="form-control" name="password"  value="<?php  echo $user['password'];?>" placeholder="请输入新的密码"></li>
            <li>是否修改密码: <input type="checkbox" class="form-control" name="query"></li>
            <li><input type="button"  style="width: 50px;height: 35px;text-align: center;" class='k-button k-button-icontext' id="from-button" value="确认"></li>
            <li><input type="hidden" value="<?php  echo $user['id'];?>" name="id"></li>
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
  $(function(){
      $('#from-button').on('click',function(){
          $(this).before('<li><input type="text" name="yzm" /><input type="button" id="btn" class="k-button k-button-icontext" value="免费获取验证码" onclick="settime(this)" /><li>');
          $(this).css('display','none');
          $('#from-ul').append('<li><input type="button" class="k-button k-button-icontext"  style="width: 50px;height: 35px;text-align: center;" onclick="from_action();" id="from-submit" value="修改"></li>');
      });
  });

var eamil=<?php  echo $user['malibox']?>;
  var countdown=60;
  function settime(obj) {
      if (countdown == 0) {
          obj.removeAttribute("disabled");
          obj.value="免费获取验证码";
          countdown = 60;
          return;
      } else {
          obj.setAttribute("disabled", true);
          obj.value="重新发送(" + countdown + ")";
          if(countdown==60){
              $('input[name=yzm]').val('');
//              var eamil=$('input[type=email]').val();
               var csrf=$('#_csrf').val();
              $.ajax({
                url:'/?r=user/mail',
                type:'POST',
                  data:{
                      mailto:eamil,
                      _csrf:csrf
                  },
                  dataType:'json',
                  success:function(res){
                    alert(res.message);
                  }
              });
          }
          countdown--;
      }
      setTimeout(function() {
              settime(obj) }
          ,1000)
  }
    function from_action(e){
        var input_mail=$('input[type=email]').val();
        if(!input_mail.match(/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/))
        {
            $('.error').css('display','block');
            $('.error').html('邮箱格式不正确');
            return;
        }
        $.ajax({
            url:'/?r=user/admininfo',
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