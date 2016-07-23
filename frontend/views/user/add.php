<!--<script src="/assets/b8f13ea9/jquery.js"></script>-->


<div style="height: 50px;">
    <input type="button" class="k-button k-button-icontext" id="fun1" onclick="fun1();" value="添加问卷标题">
    <input type="button" class="k-button k-button-icontext" id="fun2" onclick="fun2();" value="添加问卷题目">
</div>


    <div class="error" style="display: none">
    </div>
    <form action="" method="post" id="inputfrom">
    <textarea name="task_name" id="task_name" style="width: 75%;height: 300px;" placeholder="请输入标题内容">
</textarea>
        <div class="fun1" style="display: none;">
    <p><input type="text" name="a" placeholder="请输入a选项内容"></p>
    <p><input type="text" name="b" placeholder="请输入b选项内容"></p>
    <p><input type="text" name="c" placeholder="请输入c选项内容"></p>
    <p><input type="text" name="d" placeholder="请输入d选项内容"></p>
            <p>&nbsp;<select name="pid" id="pid"  style="width: 310px; height: 35px;">
<!--                <option value="-1">请选择所属分类标题</option>-->
            </select></p>
<!--            <p><input type="text" name="pid" placeholder="请输入问卷id"></p>-->
            <p>&nbsp;<select name="input" id="select"  style="width: 310px; height: 35px;">
                <option value="-1">请选择表单框</option>
                <option value="1">文本框</option>
                <option value="2">单选框</option>
                <option value="3">复选框</option>
            </select></p>
            </div>
        <input type="hidden" name="type" id="hidden" value="1">
        <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
<!--    <p><input type="text" name="key" placeholder="请输入题目选项"></p>-->
    <textarea name="meta" placeholder="描述" style="width: 75%;height: 200px;"></textarea>
        <p><input type="button" id="button" class='k-button k-button-icontext' value="确定"></p>
    </form>
</div>
<script>
    $(function () {
        $("#task_name").kendoEditor({
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

        $('#button').on('click',function(){
            var task_name=$('#task_name').val();
            if(task_name==undefined || task_name==''){
                $('.error').css({
                    'display':'none'
                });
                $('.error').text('标题不能为空');
                $('.error').css({
                    'display':'block',
                    'color':'red',
                });
                return false;
            }
            if($('#hidden').val()==2 && $("#select").val()==-1){
                $('.error').css({
                    'display':'none'
                });
                $('.error').text('必须选择一种表单框');
                $('.error').css({
                    'display':'block',
                    'color':'red',
                });
                return false;
            }
            $.ajax({
               url:'/?r=user/add',
                type:'POST',
                data:$("#inputfrom").serialize(),
                dataType:'json',
                success:function(e){
                    console.log(e);
                    if(e.code==200){
                    alert(e.message);
                    window.location.href='/?r=user/index';
                    }else {
                        alert(e.message);
                    }
                }
            });


        });
    });
    function fun1(){
       $('.fun1').css('display','none');
        $('#hidden').val('1');
    }

   function fun2(){
       $('.fun1').css('display','block');
       $('#hidden').val('2');
       $.ajax({
           url:'/?r=user/api',
           type:'GET',
           data:{type:1},
           dataType:'json',
           success:function(res){
               $('#pid').empty();
               $('#pid').append('<option value="-1">请选择所属分类</option>');
               for(var i in res){
                   var id=res[i].id;
                   var title=res[i].task_name;
                   $('#pid').append('<option value="'+id+'">'+title+'</option>');
               }
           }
       });
   }


</script>
<style>
.ul li:hover{
    cursor: pointer;
    background-color: #00aa00;
}
.ul li{

    list-style: none;
    background-color: #00b3ee;
    width: 100px;
    height: 35px;

}

input[type=text],input[type=password]{
    margin: 5px;
    padding: 0 10px;
    width: 300px;
    height: 34px;
    color: #404040;
    font-size: 18px;
    background: white;
    border: 1px solid;
    border-color: #c4c4c4 #d1d1d1 #d4d4d4;
}

input[type=text]:focus,
input[type=password]:focus{
    outline: none;
    border-color: #51a7e8;
    box-shadow: inset 0 1px 2px rgba(0,0,0,.075), 0 0 5px rgba(81,167,232,.5);
}
input[type=text]:hover,
input[type=password]:hover{
    outline: none;
    border-color: #51a7e8;
    box-shadow: inset 0 1px 2px rgba(0,0,0,.075), 0 0 5px rgba(81,167,232,.5);
}
input[type=text]:active,
input[type=password]:active{
    outline: none;
    border-color: #51a7e8;
    box-shadow: inset 0 1px 2px rgba(0,0,0,.075), 0 0 5px rgba(81,167,232,.5);
}

</style>
