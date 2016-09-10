<?php
use yii\helpers\Html;
?>
<div class="title">
    <p>答卷列表</p>
</div>
<div id="top">
    <div class="select">
        <select style="width:200px;height:35px;"><option value="-1">请选择问卷标题</option></select>
    </div>
    <div class="button">
        <input type='button' class='k-button k-button-icontext roll' id='roll' onclick="roleclick();" value='统计详情'>

<!--        <input type='button' class='k-button k-button-icontext roll' id='roll' onclick="roleclick();" value='统计详情'>-->
        <input type='button' class='k-button k-button-icontext all' style="display: none"  value='批量回访'>
<!--        <p class="all"  style="display: none"><span>批量回访只对当前页邮箱填写正确的发送邮件~</span></p>-->
        <!--        <input type="button" value="点击我看看效果" onclick="window.open('http://www.baidu.com')" />-->
    </div>
    <!--    <div id="roll" style="width:100px;height: 25px;background-color: #00aa00;display: none;"><a class='btn btn-success'>+添加工单</a></div>-->
</div>
<div style="width: 100%;height: 50px;">

</div>
<!-- 问卷列表-->
<div id="wei"></div>

<?= Html::csrfMetaTags() ?>


<div id="call_all" style="display:none; border: 2px solid #aaa; border-radius: 10px !important;background: #eee; height:80%; overflow-y: scroll; width:60%;">
    <div class="call_all_title" style="height: 35px;"><span style="font-weight: bold;">批量回访</span><button onclick="fun5();" id="call_all_botton">X</button></div>
    <input class="call_all_mailto" type="hidden" value="">
    <div class="call_all_from">
        <form action="" method="post" class="call_all_from_action">
            <ul style="list-style: none;">
                <li style="color: rosybrown">批量回访只对当前页邮箱填写正确的发送邮件~</li>
                <li>请输入邮件内容:</li>
 <textarea name="content" style="width:90%;height:90%;"  class="k-textbox call_all_textarea">
 </textarea >
                <li><input type="button" name="" class="k-button k-button-icontext call_all_class" value="发送邮件" onclick="Mailto_All(this);"></li>
            </ul>
        </form>
        </div>
    </div>


    <div id="zqzr" style="display:none; border: 2px solid #aaa; border-radius: 10px !important;background: #eee; height:80%; overflow-y: scroll; width:auto;">
        <div class="zqzr_titie" style="height: 35px;"><span style="font-weight: bold;">问卷答题统计</span><button onclick="fun2();" id="zqzr_botton">X</button></div>
        <div id="h1"></div>
        <div class="zqzr_content">
        </div>
    </div>

    <div id="cwk_info" style="display:none; border: 2px solid #aaa; border-radius: 10px !important;background: #eee; height: 80%; overflow: scroll; width:auto;">
        <div class="cwk_info_titie" style="height: 35px;"><span style="font-weight: bold;">问卷答题详情</span><button onclick="fun3();" id="cwk_info_botton">X</button></div>
        <input class="Mailto" type="hidden" value="">
        <div class="userinfo"></div>
        <div class="cwk_info_content">
        </div>

        <div class="from">
            <form action="" method="post" class="from_action">
                <ul style="list-style: none;">
                    <li>请输入邮件内容:</li>
 <textarea name="content" style="width:70%;height: 220px;" id="simple-textarea formaction" class="k-textbox formaction">
 </textarea >
                    <li><input type="button" name="" class="k-button k-button-icontext from_id" value="发送邮件" onclick="Mailto(this);"></li>
                </ul>
            </form>
        </div>
    </div>


    <div id="Follow" style="display:none; border: 2px solid #aaa; border-radius: 10px !important;background: #eee; height:80%; overflow-y: scroll; width:auto;">
        <div class="Follow_titie" style="height: 35px;"><span style="font-weight: bold;">回访内容</span><button onclick="fun4();" id="Follow_info_botton">X</button></div>
        <div id="h1"></div>
        <div class="Follow_content">
        </div>
    </div>
    <!--<input id="datepicker" />-->
    <!--<script>-->
    <!--    $(function () {-->
    <!--        $("#datepicker").kendoDatePicker();-->
    <!--    });-->
    <!--</script>-->
    <script language="JavaScript">
        $(function(){
            $(".formaction").kendoEditor({
                tools: [
                    "bold",
                    "italic",
                    "underline",
                    "strikethrough",
                    "justifyLeft",
                    "justifyCenter",
                    "justifyRight",
                    "justifyFull",
                    "insertUnorderedList",
                    "insertOrderedList",
                    "indent",
                    "outdent",
                    "createLink",
                    "unlink",
                    "insertImage",
                    "insertFile",
                    "subscript",
                    "superscript",
                    "createTable",
                    "addRowAbove",
                    "addRowBelow",
                    "addColumnLeft",
                    "addColumnRight",
                    "deleteRow",
                    "deleteColumn",
                    "viewHtml",
                    "formatting",
                    "cleanFormatting",
                    "fontName",
                    "fontSize",
                    "foreColor",
                    "backColor",
                    "print"
                ]
            });


            $('.call_all_textarea').kendoEditor({
                tools: [
                    "bold",
                    "italic",
                    "underline",
                    "strikethrough",
                    "justifyLeft",
                    "justifyCenter",
                    "justifyRight",
                    "justifyFull",
                    "insertUnorderedList",
                    "insertOrderedList",
                    "indent",
                    "outdent",
                    "createLink",
                    "unlink",
                    "insertImage",
                    "insertFile",
                    "subscript",
                    "superscript",
                    "createTable",
                    "addRowAbove",
                    "addRowBelow",
                    "addColumnLeft",
                    "addColumnRight",
                    "deleteRow",
                    "deleteColumn",
                    "viewHtml",
                    "formatting",
                    "cleanFormatting",
                    "fontName",
                    "fontSize",
                    "foreColor",
                    "backColor",
                    "print"
                ]
            });
            $.ajax({
                url:'/?r=user/api',
                type:'GET',
                data:{
                    type:1
                },
                dataType:'json',
                success:function(e){
                    for(var i in e){
                        $('#top .select select').append('<option value="'+ e[i].id+'">'+ e[i].task_name+'</option>');
                    }
                }

            });

            $('#top .select select').on('change',function() {
                var pid = $(this).val();
                if(pid==-1){
                    //alert();
                    return false;
                }
                $.ajax({
                    url: '/?r=user/listapi',
                    type: 'GET',
                    data: {
                        pid: pid
//                    csrfToken:csrfToken
                    },
                    dataType: 'json',
                    success: function (res) {
                        $('#wei').html('');
                        $('.all').css('display','block');
//                    console.log(e);
                        $("#wei").kendoGrid({
                            dataSource: {
                                data: res,
                                pageSize: 10
                            },
                            pageable: {
                                refresh: true,
                                pageSizes: true,
                                buttonCount: 5
                            },
                            resizable: true,
                            columns: [{
                                field: "mb_phone",
                                title: "手机号",
                                title: "邮箱",
                                width: 140
                            },{
                                field:"name",
                                title:"姓名",
                                width:140
                            },{
                                field:"task_ptitle",
                                title:"文件标题",
                                width:140,
                                template:function(item){
                                    var task_ptitle=item.task_ptitle;
                                    return task_ptitle;
                                }
                            },{
                                field:"create_time",
                                title:"创建时间",
                                width:140,
                            },{
//                            command: [{text: "详细", click: showDetails}, {text: "内容", click: showContent}],
//                            command: [{text: "详细", click: showContent}],
                                field:"task_ptitle",
                                title:'操作',
                                width:140,
                                template:function(item){
                                    var call_all_mailto=$('.call_all_mailto').val();
                                        call_all_mailto=call_all_mailto+','+item.mb_phone;
                                        $('.call_all_mailto').val(call_all_mailto);
                                    var str='<input type="button" data-mb="'+item.mb_phone+'" data-name="'+item.name+'" data-pid="'+item.pid+'" class="k-button k-button-icontext" onclick="showContent(this);" value="详情">';
                                    str+=' <input type="button" data-mb="'+item.mb_phone+'" data-name="'+item.name+'" data-pid="'+item.pid+'" class="k-button k-button-icontext" onclick="showFollow(this);" value="回访内容">';
                                    return str;
                                }
                            }]
                        });
                        /**/

                    }
                });
            });
        });


        $('.all').on('click',function(){
            var csrf_token=$('meta[name="csrf-token"]').attr("content");
            var mailto=$('.call_all_mailto').val();
            if(mailto==undefined){
                return false;
            }else {
                $('#call_all').css('display','block');
                console.log(mailto);
            }

        });
        function roleclick(){
            var pid=$('#top .select select').val();
            $('#zqzr').css({
                display:'block',
            });
            $.ajax({
                url: '/?r=user/parentcount',
                type: 'GET',
                data: {
                    pid: pid
                },
                dataType: 'json',
                success:function(res){
                    $('#zqzr .zqzr_content').html('');
                    $('#zqzr .zqzr_content').kendoGrid({
                        dataSource: {
                            data: res,
                            pageSize: 10
                        },
                        pageable: {
                            refresh: true,
                            pageSizes: true,
                            buttonCount: 5
                        },
                        resizable: true,
                        columns: [{
                            field:'task_name',
                            title:'标题',
                            width:140,
                            template:function(item){
                                var str=item.task_name;
                                return str;
                            }
                        },{
                            field:'a',
                            title:'选项a',
                            width:140,
                            template:function(item){
                                var a=item.a+' | '+item.a_count;
                                return a;
                            }
                        },{
                            field:'b',
                            title:'选项b',
                            width:140,
                            template:function(item){
                                var b=item.b+' | '+item.b_count;
                                return b;
                            }
                        },{
                            field:'c',
                            title:'选项c',
                            width:140,
                            template:function(item){
                                var c=item.c+'|'+item.c_count;
                                return c;
                            }

                        },{
                            field:'d',
                            title:'选项d',
                            width:140,
                            template:function(item){
                                var d=item.d+'|'+item.d_count;
                                return d;
                            }
                        },{
                            field:'count',
                            title:'总量',
                            width:140,
                        }]
                    });
                }
            });
        }
        function fun2(){
            $('#zqzr').css({
                display:'none',
            });
        }
        function fun3(){
            $('#cwk_info').css({
                display:'none',
            });
        }
        function fun4(){
            $('#Follow').css({
                display:'none',
            });
        }
        function fun5(){
            $('#call_all').css('display','none');
        }
        function showContent(e){
//        e.preventDefault();
            //var thisTiem= this.dataItem($(e.currentTarget).closest("tr"));
//        if(thisTiem==undefined){
//            alert('系统错误 请刷新再试~');
//        }
            var pid=$(e).attr('data-pid');
            //        var pid=thisTiem.pid;
            var mb_phone=$(e).attr('data-mb');
            var name=$(e).attr('data-name');
            var csrf_token=$('meta[name="csrf-token"]').attr("content");
            $('#cwk_info .userinfo').empty();
            var userinfo='<p style="color: #0b0b0b;">姓名:'+name+'&nbsp;&nbsp;&nbsp;手机号:'+mb_phone+'</p>';
            $('.Mailto').attr('data-pid',pid);
            $('.Mailto').attr('data-name',name);
            $('.Mailto').attr('data-mb',mb_phone);
            $('.Mailto').attr('data-url','/?r=user/call');
//        $(".formaction").empty();
            $('#cwk_info .userinfo').append(userinfo);
            $.ajax({
                url:'/?r=user/usercwkapi',
                type:'POST',
                data:{
                    pid:pid,
                    mobile:mb_phone,
                    _csrf:csrf_token
                },
                dataType:'json',
                success:function(res){
                    $('#cwk_info .cwk_info_content').empty();
                    $('#cwk_info').css({
                        display:'block'
                    });
                    $('#cwk_info .cwk_info_content').kendoGrid({
                        dataSource: {
                            data: res,
                            pageSize: 10
                        },
                        pageable: {
                            refresh: true,
                            pageSizes: true,
                            buttonCount: 5
                        },
                        resizable: true,
                        columns:[{
                            field:'task_name',
                            title:'标题',
                            width:140,
                            template:function(item){
                                var str=item.task_name;
                                return str;
                            }

                        },{
                            field:'a',
                            title:'选项a',
                            width:140

                        },{
                            field:'b',
                            title:'选项b',
                            width:140

                        },{
                            field:'c',
                            title:'选项c',
                            width:140
                        },{
                            field:'d',
                            title:'选项d',
                            width:140
                        },{
                            field:'input',
                            title:'表单类型'
                        },{
                            field:'res',
                            title:'作答内容',
                            width:140
                        }]
                    });
                }
            });
        }
        //查看跟进
        function showFollow(e){
            var mb_phone=$(e).attr('data-mb');
            var name=$(e).attr('data-name');
            var pid=$(e).attr('data-pid');
            var csrf_token=$('meta[name="csrf-token"]').attr("content");
//        var content=$('#from_action_content').val();
            $.ajax({
                url:'/?r=user/callshow',
                type:'POST',
                data:{
                    cu_id:pid,
                    mailto:mb_phone,
                    _csrf:csrf_token
                },
                dataType:'json',
                success:function(res){
                    $('#Follow .Follow_content').html('');
                    $('#Follow').css('display','block');
                    $('#Follow .Follow_content').kendoGrid({
                        dataSource: {
                            data: res,
                            pageSize: 10
                        },
                        pageable: {
                            refresh: true,
                            pageSizes: true,
                            buttonCount: 5
                        },
                        resizable: true,
                        columns:[{
                            field:'call_time',
                            title:'发送时间',
                            width:140

                        },{
                            field:'status',
                            title:'状态',
                            width:140

                        },{
                            field:'create_name',
                            title:'操作人',
                            width:140

                        },{
                            field:'mailto',
                            title:'收件人',
                            width:140
                        },{
                            field:'call_content',
                            title:'发送内容',
                            width:140,
                            template:function(item){
                                var HTML=item.call_content;
                                return HTML;
                            }
                        }]
                    });
                }
            });
        }
        //发送邮件
        function Mailto(e){
            var pid=$('.Mailto').attr('data-pid');
//       var name=$('.Mailto').attr('data-name');
            var mb_phone=$('.Mailto').attr('data-mb');
            var csrf_token=$('meta[name="csrf-token"]').attr("content");
            var content=$('.formaction').val();
            var url=$('.Mailto').attr('data-url');
            if(url==undefined){
                return false;
            }
            $.ajax({
//                url:'/?r=user/call',
                url:url,
                type:'POST',
                data:{
                    cu_id:pid,
                    mailto:mb_phone,
                    content:content,
                    _csrf:csrf_token
                },
                dataType:'json',
                success:function(res){
                    if(res.code==200){
                        alert(res.message);
                        $('#Follow').css({
                            display:'none',
                        });
                    }else {
                        alert(res.message);
                    }
                }
            });
        }

        function Mailto_All(e){
            var pid=$('#top .select select').val();
            var mb_phone=$('.call_all_mailto').val();
            var csrf_token=$('meta[name="csrf-token"]').attr("content");
            var content=$('.call_all_textarea').val();
            var url='/?r=user/call-all';
            if(url==undefined){
                return false;
            }
            $.ajax({
//                url:'/?r=user/call',
                url:url,
                type:'POST',
                data:{
                    cu_id:pid,
                    mailto:mb_phone,
                    content:content,
                    _csrf:csrf_token
                },
                dataType:'json',
                success:function(res){
                    if(res.code==200){
                        alert(res.message);
                        $('#Follow').css({
                            display:'none',
                        });
                    }else {
                        alert(res.message);
                    }
                }
            });
        }


    </script>
    <style type="text/css">
        #zqzr {
            padding: 20px;
            background-color: #aaaaaa;
            width: 80%;
            height: 80%;
            position: absolute;
            z-index: 101;
            top: 30%;
            left:40%;
            margin-left: -300px;
            margin-top: -100px;
            border: 1px #ddd solid;
            overflow: auto;
        }
        #zqzr_botton{
            position: absolute;
            z-index: 102;
            left: 95%;
        }
        #cwk_info {
            padding: 20px;
            background-color: #aaaaaa;
            width: 80%;
            height: 80%;
            position: absolute;
            z-index: 101;
            top: 30%;
            left: 40%;
            margin-left: -300px;
            margin-top: -100px;
            border: 1px #ddd solid;
            overflow: auto;
        }
        #cwk_info_botton{
            position: absolute;
            z-index: 102;
            left: 95%;
        }
        #Follow{
            padding: 20px;
            background-color: #aaaaaa;
            width: 80%;
            height: 80%;
            position: absolute;
            z-index: 101;
            top: 30%;
            left: 40%;
            margin-left: -300px;
            margin-top: -100px;
            border: 1px #ddd solid;
            overflow: auto;
        }

        #Follow_info_botton{
            position: absolute;
            z-index: 102;
            left: 95%;
        }


        #call_all{
            padding: 20px;
            background-color: #aaaaaa;
            width: 80%;
            height: 80%;
            position: absolute;
            z-index: 101;
            top: 30%;
            left: 40%;
            margin-left: -300px;
            margin-top: -100px;
            border: 1px #ddd solid;
            overflow: auto;
        }

        #call_all_botton{
            position: absolute;
            z-index: 102;
            left: 95%;
        }
    </style>


