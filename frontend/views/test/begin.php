<h2><?php echo $model['title']['task_name'];?></h2>
<div class="label_content">
    <form action="" method="" id="w_form">
    <?php foreach ($model['arr'] as $key => $value): ?>
        <div class="label_w_tilte">
            <p><?php echo $value['task_name']; ?></p>
            <?php if($value['input']==1){?>
            <textarea name="<?php echo sha1($value['id']);?>" style="width: 200px;height: 100px;"></textarea>
<!--            <p><input type="radio" name="--><?php //echo sha1($value['id']);?><!--" value="b">--><?php //echo $value['b']; ?><!--</p>-->
<!--            <p><input type="radio" name="--><?php //echo sha1($value['id']);?><!--" value="c">--><?php //echo $value['c']; ?><!--</p>-->
<!--            <p><input type="radio" name="--><?php //echo sha1($value['id']);?><!--" value="d">--><?php //echo $value['d']; ?><!--</p>-->
            <?php }elseif($value['input']==2){?>
                <p><input type="radio" name="<?php echo sha1($value['id']);?>" value="a"><?php echo $value['a']; ?></p>
                <p><input type="radio" name="<?php echo sha1($value['id']);?>" value="b"><?php echo $value['b']; ?></p>
                <p><input type="radio" name="<?php echo sha1($value['id']);?>" value="c"><?php echo $value['c']; ?></p>
                <p><input type="radio" name="<?php echo sha1($value['id']);?>" value="d"><?php echo $value['d']; ?></p>
            <?php }else{?>
                <p><input type="checkbox" name="<?php echo sha1($value['id']);?>[]" value="a" ><?php echo $value['a']; ?></p>
                <p><input type="checkbox" name="<?php echo sha1($value['id']);?>[]" value="b"><?php echo $value['b']; ?></p>
                <p><input type="checkbox" name="<?php echo sha1($value['id']);?>[]" value="c" ><?php echo $value['c']; ?></p>
                <p><input type="checkbox" name="<?php echo sha1($value['id']);?>[]" value="d"><?php echo $value['d']; ?></p>
            <?php };?>
<!--        <option value="--><?php //echo $type['action']; ?><!--">--><?php //echo $type['name'];?><!--</option>-->
            </div>
    <?php endforeach;?>
    <div class="label_mb">
        <p style="color: #00aa00">为了我们方便联系你 请如实填写</p>
        <p><input type="text" name="name" value="" placeholder="请输入姓名"></p>
        <p><input type="text" name="mb_phone" value="" onchange="this.value=this.value.replace(/\D/g,'')" placeholder="请输入手机号码"></p>
    </div>
        <input name="_csrf" type="hidden" id="_csrf" value="<?= Yii::$app->request->csrfToken ?>">
        <input name="task_pid" type="hidden" value="<?php echo  $model['title']['id'];?>">
<!--        <p><input type="submit" id="button" value="提交"></p>-->
        </form>
    <p><button id="button" style="width: 60px;height: 50px;background-color: #00aa00;font-size: 15px;">提交</button></p>
    <div id="error" style="display: none;">
    </div>
</div>
<script src="/assets/b8f13ea9/jquery.js"></script>
<script language="JavaScript">
$(function(){
    $('#button').on('click',function(){
        var url=window.location.href;
        var str=url.substr(-1);
       if(str==undefined){
           return false;
       }
         var name=$('input[name=name]').val();
         var mb=$('input[name=mb_phone]').val();
        var reg = /(1[3-9]\d{9}$)/;
        var str='';
        var error=0;
        if(mb==''){
            str+=' 手机号不能为空';
            error=error+1;
        }

        if(name=''){
            str+=' 名字不能为空';
            error=error+1;
        }
        if(!reg.test(mb)){
            str+=' 手机号格式不正确';
            error=error+1;
        }
        if(error>0){
            $('#error').css('display','none');
            $('#error').html('<span style="color: red">'+str+'</span>');
            $('#error').css('display','block');
            return false;
        }
        $.ajax({
              url:'/?r=test/begin&w_id='+str,
              type:'POST',
              data:$('#w_form').serialize(),
              dataType:'json',
              async:false,
              success:function(e){
              console.log(e);
             if(e.code==200){
                alert(e.message);
              window.location.href='/?r=test/end&str=感谢你的访问';
              }else {
                alert(e.message);
             }
        }
    });
});
});


</script>
<style type="text/css">
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