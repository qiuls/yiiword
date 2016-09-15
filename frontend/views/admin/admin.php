
        <meta charset="utf-8">
        <title>登录(Login)</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- CSS -->
        <link rel="stylesheet" href="/assets/admin/css/reset.css">
        <link rel="stylesheet" href="assets/admin/css/supersized.css">
        <link rel="stylesheet" href="assets/admin/css/style.css">

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
        <script src="assets/admin/js/html5.js"></script>
        <![endif]-->


        <script src="/assets/b8f13ea9/jquery.js"></script>
    <body>

        <div class="page-container">
            <h1>登录(Login)</h1>
            <form action="" method="post" id="login_form">
                <input type="text" name="username" class="username" placeholder="请输入您的用户名！">
                <input type="password" name="password" class="password" placeholder="请输入您的用户密码！">
<!--                <input type="Captcha" class="Captcha" name="Captcha" placeholder="请输入验证码！">-->
                <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
<!--                <button id="submit_button">登录</button>-->
                <div class="error"><span>+</span></div>
            </form>
            <input type="submit" id="submit" value="登录">
            <div class="l_error" style="display: none;">

            </div>
            <div class="connect">
                <p>快捷</p>
                <p>
                    <a class="facebook" href=""></a>
                    <a class="twitter" href=""></a>
                </p>
            </div>
        </div>
		
        <!-- Javascript -->
<!--        <script src="assets/admin/js/jquery-1.8.2.min.js" ></script>-->
<!--        <script src="assets/admin/js/supersized.3.2.7.min.js" ></script>-->
<!--        <script src="assets/admin/js/supersized-init.js" ></script>-->
<!--        <script src="assets/admin/js/scripts.js" ></script>-->

    </body>
<div style="text-align:center;">
<!--<p>来源：<a href="http://www.mycodes.net/" title="源码之家" target="_blank">源码之家</a></p>-->
</div>
</html>
<style type="text/css">
    body{
        background-image: url("/assets/admin/img/1.jpg");
    }
</style>
<script language="JavaScript">
$(function(){
    $('#submit').click(function(){
        $.ajax({
           url:'/?r=admin/index',
            type:'POST',
            data:$('#login_form').serialize(),
            dataType:'json',
            success:function(res){
             if(res.error.code==200){
                 window.location.href=res.data;
             }else {
                 $('.l_error').empty();
                 $('.l_error').html(res.error.message);
                 $('.l_error').css('display','block');
             }
            }
        });
    });
});
</script>
