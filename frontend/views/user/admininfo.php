<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2016/7/1
 * Time: 11:36
 */
?>
<div class="userinfo">
    <div style="height: 30px;">
    <span style="font-size: 17px;"><b>用户信息</b></span>
  </div>
    <div>
      <ul style="list-style: none;">
          <li>用户名</li>
          <li class="userinfo_li"><?php echo $user['username'];?></li>
          <li>用户邮箱</li>
          <li class="userinfo_li"><?php echo $user['malibox'];?></li>
          <li>登录时间</li>
          <li class="userinfo_li"><?php echo date('Y-m-d H:i:s',$user['login_time']);?></li>
          <li>最后登录时间</li>
          <li class="userinfo_li"><?php echo date('Y-m-d H:i:s',$user['last_login_time']);?></li>
      </ul>
    </div>

</div>
<div style="width: 100%;height: 20px;"></div>
<div class="userinfo_from" style="width: 50%;height: auto;border: 1px solid dimgrey; -webkit-border-radius:10px;-moz-border-radius:10px;border-radius: 15px;">
<div style="height: 30px;background-color: #6a727d">
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
            <li><input type="password" class="form-control" name="password"  value="" placeholder="请输入新的密码"></li>
            <il class="newpassword" >确认密码:</il>
            <li  class="newpassword"><input type="password" name="newpassword"  value="" placeholder="请输入新的密码"></li>
            <li>是否修改密码: <input type="checkbox" class="form-control" value="1" name="query"></li>
            <li><input type="button"  style="width: 50px;height: 35px;text-align: center;" class='k-button k-button-icontext' id="from-button" value="确认"></li>
            <li><input type="hidden" value="<?php  echo $user['id'];?>" name="id"></li>
            <li><input type="hidden" value="<?php  echo \Yii::$app->request->csrfToken;?>" id="_csrf" name="_csrf"></li>
        </ul>
    </form>

</div>
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
    .userinfo{
      width: 50%;
        height: auto;
       border: 1px solid;
        background-color: #6a727d;
        border-color: royalblue;
      -webkit-border-radius:10px;
        -moz-border-radius:10px;
        border-radius: 15px;
    }
    .userinfo ul li{
        background-color: #6f91b3;
        /*width: ;*/
        height: 25px;;
    }
  .userinfo ul .userinfo_li{
      /*list-style: none;*/
        background-color: #faebcc;
    }
    .userinfo ul .userinfo_li:hover{
        background-color: snow;
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

   var  eamil='<?php echo $user['malibox'];?>';
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
      setTimeout(function() {settime(obj) },1000)
  }
    function from_action(e){
        var input_mail=$('input[type=email]').val();
        if(!input_mail.match(/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/)){
            $('.error').css('display','block');
            $('.error').html('<span style="color: red">两邮箱格式不正确</span>');
            return;
        }
        if($('input:checkbox:checked').val()==1){
            var pass=$('input[name=password]').val();
            var newpass=$('input[name=newpassword]').val();
            if(pass!==newpass){
                $('.error').css('display','block');
                $('.error').html('<span style="color: red">两次密码不一致</span>');
                return;
            }
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
        });
    }
</script>