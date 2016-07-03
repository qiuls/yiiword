<?php
use yii\helpers\Html;
?>
<div class="title">
    <p>问卷列表</p>
</div>
<div id="top">
    <div class="select">
    <select style="width:200px;height:35px;"><option value="-1">请选择问卷标题</option></select>
    </div>
    <div class="button">
    <input type='button' class='k-button k-button-icontext roll' id='roll' onclick="roleclick();" value='统计详情'>
<!--        <input type="button" value="点击我看看效果" onclick="window.open('http://www.baidu.com')" />-->
    </div>
<!--    <div id="roll" style="width:100px;height: 25px;background-color: #00aa00;display: none;"><a class='btn btn-success'>+添加工单</a></div>-->
</div>
<div style="width: 100%;height: 50px;">

</div>
<div id="wei"></div>
<?= Html::csrfMetaTags() ?>

<div id="zqzr" style="display:none; border: 2px solid #aaa; border-radius: 10px !important;background: #eee; height: 400px; overflow-y: scroll; width:auto;">
    <div class="zqzr_titie" style="height: 35px;"><span style="font-weight: bold;">问卷答题统计</span><button onclick="fun2();" id="zqzr_botton">X</button></div>
    <div id="h1"></div>
    <div class="zqzr_content">
    </div>


</div>

<div id="cwk_info" style="display:none; border: 2px solid #aaa; border-radius: 10px !important;background: #eee; height: 400px; overflow-y: scroll; width:auto;">
    <div class="cwk_info_titie" style="height: 35px;"><span style="font-weight: bold;">问卷答题详情</span><button onclick="fun3();" id="cwk_info_botton">X</button></div>
    <div class="userinfo"></div>
    <div class="cwk_info_content">
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
                            width: 140
                        },{
                            field:"name",
                            title:"姓名",
                            width:140
                        },{
                            field:"task_ptitle",
                            title:"文件标题",
                            width:140,
                        },{
//                            command: [{text: "详细", click: showDetails}, {text: "内容", click: showContent}],
                            command: [{text: "详细", click: showContent}],
                           title:'操作',
                            width:140
                        }]
                    });
                }
            });
        });
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
                            width:140
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
    function showContent(e){
        e.preventDefault();
        var thisTiem= this.dataItem($(e.currentTarget).closest("tr"));
        var pid=thisTiem.pid;
        var csrf_token=$('meta[name="csrf-token"]').attr("content");
        $('#cwk_info .userinfo').empty();
        var userinfo='<p style="color: #0b0b0b;">姓名:'+thisTiem.name+'&nbsp;&nbsp;&nbsp;手机号:'+thisTiem.mb_phone+'</p>';
        $('#cwk_info .userinfo').append(userinfo);
        $.ajax({
           url:'/?r=user/usercwkapi',
           type:'POST',
            data:{
                pid:pid,
                mobile:thisTiem.mb_phone,
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
                       width:140

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



</script>
<style type="text/css">
    #zqzr {
        padding: 30px;
        background-color: #aaaaaa;
        width: 75%;
        height: 55%;
        position: absolute;
        z-index: 101;
        top: 50%;
        left: 50%;
        margin-left: -400px;
        margin-top: -150px;
        border: 1px #ddd solid;
        overflow: auto;
    }
    #zqzr_botton{
        position: absolute;
        z-index: 102;
        left: 95%;
    }
    #cwk_info {
        padding: 30px;
        background-color: #aaaaaa;
        width: 75%;
        height: 55%;
        position: absolute;
        z-index: 101;
        top: 50%;
        left: 50%;
        margin-left: -400px;
        margin-top: -150px;
        border: 1px #ddd solid;
        overflow: auto;
    }
    #cwk_info_botton{
        position: absolute;
        z-index: 102;
        left: 95%;
    }
</style>

