<!DOCTYPE html>
<head>
    <title>问卷调查后台管理系统</title>
    <link href="assets/kendoui/styles/kendo.common.min.css" rel="stylesheet" />
    <link href="assets/kendoui/styles/kendo.default.min.css" rel="stylesheet" />
    <script src="assets/kendoui/js/jquery.min.js"></script>
    <script src="assets/kendoui/js/kendo.web.min.js"></script>
</head>
<body style=";padding-top: -30px;">
<div id="container" style="width: 100%;padding-top: -30px;">
    <div id="header" style="background-color:#00a1e8;width: 100%;height:auto;">
       <div style="text-align: center">
           <span style="text-align:right;font-size: 25px;font-style: normal;">问卷调查管理系统</span>
       </div>
        <div>
            欢迎~<span><?php echo \Yii::$app->cache->get('myid'); ?></span>
            最后登录时间：<?php echo \Yii::$app->cache->get('my_last_login'); ?>
            <div>

<!--        <div style="float: right"></div>-->
    </div>
    <div id="menu" style="background-color:#00a1e8;height:800px;width:12%;float:left;">
        <ul style="list-style: none;">
            <li class="lable_li" onclick="javascript:document.getElementById('lable-01').click();"><a id="lable-01" href="/?r=user/"><b style="color: white">首页</b></a></li>
        <li class="lable_li" onclick="javascript:document.getElementById('lable-02').click();"><a id="lable-02" href="/?r=user/add" ><b style="color: white">添加问卷</b></a></li>
            <li class="lable_li"  onclick="javascript:document.getElementById('lable-03').click();"><a id="lable-03" href="/?r=user/userlist"><b style="color: white">答卷列表</b></a></li>
            <li class="lable_li" id="userinfo" onclick="javascript:document.getElementById('lable-03').click();"><span style="color: white"><a href="/?r=user/admininfo" id=""lable-04">我的信息</a></span><li>
<!--        <li  onclick="javascript:document.getElementById('lable-04').click();" style="background-color:#99ee99;width: 100%;height: 35px;"><a id="lable-04" href="/?r=admin/userout">退出</a></li>-->
            <li class="lable_li" id="tuichu" style="color: dimgrey">退出</li>
        </ul>
    </div>
    <div id="content" style="background-color:#EEEEEE;height:800px;width:88%;float:left;">
        <?= $content ?>
    </div>
    <div id="footer" style="background-color:#000000;clear:both;text-align:center;">
        <span style="color: WindowFrame;">Copyright © W3Schools.com 2015-2016</span></div>

</div>

</body>
</html>
<style type="text/css">
    body, div,ul,h1{
        margin:0px;
        padding:0px;
    }
    .lable_li{
        width: 100%;
        height: 35px;
        background-color: #00a1e8;
        font-size: 16px;
        /*font-color:#;*/
    }
    .lable_li:hover{
        /*//width: 50px;*/
        /*height: 35px;*/
        background-color: rosybrown;
    }

    a{
        text-decoration:none;
    }

</style>
<!--<script src="/assets/b8f13ea9/jquery.js"></script>-->
<script>
    $(function(){
       $('#tuichu').on('click',function(){
           if(confirm('确定退出吗？亲~')){
               window.location.href='/?r=admin/userout';
               //window.open('/?r=admin/userout');
           }else {
               return false;
//               window.location.reload();
           }
       });
    });

</script>