<!DOCTYPE html>
<head>
    <title>问卷调查后台管理系统</title>
    <link href="assets/kendoui/styles/kendo.common.min.css" rel="stylesheet" />
    <link href="assets/kendoui/styles/kendo.default.min.css" rel="stylesheet" />
    <script src="assets/kendoui/js/jquery.min.js"></script>
    <script src="assets/kendoui/js/kendo.web.min.js"></script>
</head>
<body style=";padding-top: -30px;height: auto">
<div id="container" style="width: 100%;padding-top: -30px;">
    <div id="header" style="background-color:#00a1e8;width: 100%;height:auto;">
       <div style="text-align: center">
           <span style="text-align:right;font-size: 25px;font-style: normal;">问卷调查管理系统</span>
       </div>
        <div>
            欢迎~<span style="color: whitesmoke"><?php echo \Yii::$app->session['name']; ?></span>
            <span class="k-icon k-i-clock"></span><span style="color: whitesmoke">最后登录时间：<?php echo \Yii::$app->session['last_login_time']; ?></span>
            <div>

<!--        <div style="float: right"></div>-->
    </div>
    <div id="menu" data-type="0" style="background-color:#000000;height:1000px;width:12%;float:left;">
        <div class="div-1">
            <p onclick="div(1);" style="font-size: 16px;color: white;width:100%;height: 40px;"><span  class="k-icon k-i-arrow-s"></span>问卷管理</p>
             <ul style="list-style: none;display: none;">
            <li class="lable_li" onclick="javascript:document.getElementById('lable-01').click();"><a id="lable-01" href="/?r=user/"><b style="color: white">&nbsp;问卷列表</b></a></li>
            <li class="lable_li" onclick="javascript:document.getElementById('lable-02').click();"><a id="lable-02" href="/?r=user/add" ><b style="color: white">&nbsp;添加问卷</b></a></li>
                <li>
        </div>
        <div class="div-2">
            <p style="font-size: 16px;color: white;width:100%;height: 40px;" onclick="div(2);"><span  class="k-icon k-i-arrow-s"></span>用户调查阅览</p>
               <ul style="list-style: none;display: none;">
                   <li class="lable_li"  onclick="javascript:document.getElementById('lable-03').click();"><a id="lable-03" href="/?r=user/userlist"><b style="color: white">&nbsp;答卷列表</b></a></li>
               </ul>
      </div>
        <div class="div-3">
           <p style="font-size: 16px;color: white;height: 40px;width:100%;"  onclick="div(3);"><span class="k-icon k-i-arrow-s"></span>用户中心</p>
           <ul style="list-style: none;display: none;">
           <li class="lable_li"  onclick="javascript:document.getElementById('lable-05').click();"><a id="lable-05" href="/?r=user/adminadd"><b style="color: white">&nbsp;添加新用户</b></a></li>
               <li class="lable_li" id="userinfo" onclick="javascript:document.getElementById('lable-06').click();"><a href="/?r=user/backendlist" id="lable-06"><b style="color: white">&nbsp;管理员列表</b></a><li>
           <li class="lable_li" id="userinfo" onclick="javascript:document.getElementById('lable-04').click();"><a href="/?r=user/admininfo" id="lable-04"><b style="color: white">&nbsp;我的信息</b></a><li>
               <li class="lable_li" id="tuichu" style="color: dimgrey">&nbsp;<b>退出</b><span class="k-icon k-i-close"></span></li></ul>
        </div>
        <div id="show" data-type="1">
            <span style="width: 25px;height: 20px;"  class="k-icon k-i-hbars"></span>
        </div>
    </div>
    <div id="content" style="background-color:#EEEEEE;height:1000px;width:88%;float:left;">
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
        /*background-color: #00a1e8;*/
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
    .div-1 p:hover{
        cursor:pointer;
    }
    .div-2 p:hover{
        cursor:pointer;
    }
    .div-3 p:hover{
        cursor:pointer;
    }
    #tuichu:hover{
        cursor:pointer;
    }
    #show:hover{
        cursor:pointer;
    }
</style>
<script>
    $(function(){
       $('#tuichu').on('click',function(){
           if(confirm('确定退出吗？亲~')){
               window.location.href='/?r=admin/userout';
           }else {
               return false;
           }
       });
        $('#show').on('click',function(){
               if($(this).attr('data-type')==1){
             $('#menu').css('width','2%');
            $('#content').css('width','98%');
            $('.div-1').css('display','none');
            $('.div-2').css('display','none');
            $('.div-3').css('display','none');
                   $(this).attr('data-type','2');
               }else {
                   $('#menu').css('width','12%');
                   $('#content').css('width','88%');
                   $('.div-1').css('display','block');
                   $('.div-2').css('display','block');
                   $('.div-3').css('display','block');
                   $(this).attr('data-type','1');
               }

        });
    });
     function div(e){
         var ob='.div-'+e+' ul';
         $(ob).toggle(500);
         var class_ob='.div-'+e+' p span';
         var class_name=$(class_ob).attr('class');
         if(class_name=='k-icon k-i-arrow-n'){
             var attr='k-icon k-i-arrow-s';//下
             $(class_ob).attr('class',attr);
         }else {
             var attr='k-icon k-i-arrow-n';//上
             $(class_ob).attr('class',attr);
         }
//         alert(class_name);
     }
</script>