<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2016/6/27
 * Time: 20:09
 */
?>
  <div class="error" style="display: none">
    </div>
    <form action="" method="post" id="inputfrom">
    <textarea name="task_name" id="task_name" placeholder="请输入标题内容"><?php echo $arr['task_name'];?>
</textarea>
        <?php if ($arr['type']==2):?>
        <div class="fun1">
            <p><input type="text" name="a" placeholder="请输入a选项内容" value="<?php echo $arr['a'];?>"></p>
            <p><input type="text" name="b" placeholder="请输入b选项内容" value="<?php echo $arr['b'];?>"></p>
            <p><input type="text" name="c" placeholder="请输入c选项内容" value="<?php echo $arr['c'];?>"></p>
            <p><input type="text" name="d" placeholder="请输入d选项内容" value="<?php echo $arr['d'];?>"></p>
            <p><select name="pid" id="pid"  style="width: 200px; height: 34px;">
                    <!--                <option value="-1">请选择所属分类标题</option>-->
                </select></p>
<!--            <p><input type="text" name="pid" placeholder="请输入问卷id" value="--><?php //echo $arr['pid'];?><!--"></p>-->
            <select name="input" id="select"  style="width: 200px; height: 34px;">
                <option value="-1">请选择表单框</option>
                <?php if($arr['input']==1){?>
                <option value="1" selected="selected">文本框</option>
                <option value="2">单选框</option>
                <option value="3">复选框</option>
                <?php }elseif($arr['input']==2){?>
                    <option value="1" >文本框</option>
                    <option value="2" selected="selected">单选框</option>
                    <option value="3">复选框</option>
                <?php}else{?>
                    <option value="1" >文本框</option>
                    <option value="2">单选框</option>
                    <option value="3" selected="selected">复选框</option>
                <?php };?>
            </select>
        </div>
        <?php endif;?>
        <input type="hidden" name="id" id="hidden" value="<?= Yii::$app->request->get('id'); ?>">
        <input type="hidden" name="type" id="hidden" value="<?= Yii::$app->request->get('type'); ?>">
        <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <textarea name="meta" placeholder="描述" width="300px;" height="200px"><?php echo $arr['meta'];?></textarea>
        <p><input type="button" id="button" value="query"></p>
    </form>

<script language="JavaScript">
      $(function(){
          <?php if ($arr['type']==2):?>
          $.ajax({
              url:'/?r=user/api',
              type:'GET',
              data:{type:1},
              dataType:'json',
              success:function(res){
                  $('#pid').empty();
                  $('#pid').append('<option value="-1">请选择所属分类</option>');
                  var pid=<?php echo $arr['pid'];?>;
                  for(var i in res){
                      var id=res[i].id;
                      var title=res[i].task_name;
                      if(pid==id){
                      $('#pid').append('<option value="'+id+'" selected="selected">'+title+'</option>');
                      }else {
                          $('#pid').append('<option value="'+id+'">'+title+'</option>');
                      }
                  }
              }
          });
          <?php endif;?>


          $('#button').on('click',function(){
              var type=$('input[name=type]').val();
              if(type==2 && $('#select').val()==-1){
                  $('.error').css('display','none');
                  $('.error').html('<span style="color: red">请选择至少一类框</span>');
                  $('.error').css('display','block');
                  return false;
                  //$('#select').val()
              }
              if($('task_name').val()==''){
                  $('.error').css('display','none');
                  $('.error').html('<span style="color: red">标题不能为空</span>');
                  $('.error').css('display','block');
                  return false;
              }
              $.ajax({
                 url:'/?r=user/update',
                 type:'POST',
                 data:$('#inputfrom').serialize(),
                  dataType:'json',
                  success:function(res){
                   if(res.code==200){
                       var message=res.message;
                       if(confirm(message+' 关闭页面请刷新列表')){
                           window.opener = null;
                           window.open('', '_self');
                           window.close()
                       }
                    }else{
                       var message=res.message;
                       if(confirm(message+' 关闭页面请列表重试')){
                           window.opener = null;
                           window.open('', '_self');
                           window.close()
                       }
                    }
                  }
              });
          });
      });
</script>
<style>
input[type=text],input[type=password]{
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
textarea{
width: 200px;
height: 100px;
}
</style>
