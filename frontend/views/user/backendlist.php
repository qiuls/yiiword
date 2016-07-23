<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2016/7/15
 * Time: 14:17
 */
?>
<h2>管理员审核</h2>
<input type="hidden"  name="_csrf" value="<?php echo Yii::$app->request->csrfToken;?>">
<div id="backend" style="">

</div>

<script language="JavaScript">
$(function(){
    var _csrf=$('input[name=_csrf]').val();
    var creditorDataSource = new kendo.data.DataSource({
        type: 'json',
        transport: {
            read: {
                url: '/?r=user/backendlist',
                dataType: "json",
                type: 'POST',
                data: {
                    _csrf:_csrf
                }
            }
        },
        schema: {
            data: "list",
            total: 'total'
        },
        serverPaging: true,
        serverSorting: false,
        pageSize: 10
    });
    $('#backend').kendoGrid({
        dataSource: creditorDataSource,
        pageable: {
            refresh: true,
            pageSizes: true,
            buttonCount: 5
        },
        resizable: true,
        columns:[{
            field: "id",
            title: "序号",
        },{
            field: "username",
            title: "用户名",
        },{
            field: "malibox",
            title: "邮箱",
        },{
            field: "status_message",
            title: "状态",
        },{
            field: "status",
            title: "操作",
            template:function(item){
           if(item.status==1){
               var str='<input type="button" class="k-button k-button-icontext" data-status="0" data-id="'+item.id+'" onclick="backendStatus(this);" value="禁用">';
           }else{
               var str='<input type="button" class="k-button k-button-icontext" data-status="1" data-id="'+item.id+'" onclick="backendStatus(this);" value="启用">';
           }
                return str;
            }
        }]
    });
});
    function backendStatus(e){
        var status=$(e).attr('data-status');
        var id=$(e).attr('data-id');
//        alert(status);
//        return;
    var _csrf=$('input[name=_csrf]').val();
    $.ajax({
       url:'/?r=user/backendupdate',
        type:'POST',
        data:{
            id:id,
            status:status,
            _csrf:_csrf
        },
        dataType:'json',
       success:function(res){
           alert(res.message);

       }
    });
    }


</script>
<style type="text/css">
    /*#backend{*/
        /*width: 100px*/
    /*}*/

</style>



