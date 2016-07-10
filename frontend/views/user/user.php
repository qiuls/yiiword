<?php
use yii\helpers\Html;
?>
<div id="api">
    <div style="height: 30px;"><span>问卷列表</span></div>
<div id="select">
    <select style="width:200px;height:35px;">
        <option value="-1">请选择问卷标题</option>

    </select>
</div>
    <div style="width: auto;height: 100px;">


    </div>
<input type="hidden"  name="_csrf" value="<?php echo Yii::$app->request->csrfToken;?>">
<div id="p_title" style="display:none;overflow: scroll;">

</div>

<div id="title" style="display:none;overflow: scroll;">

</div>
    </div>
<!--<input id="datepicker" />-->
<!--<script>-->
<!--    $(function () {-->
<!--        $("#datepicker").kendoDatePicker();-->
<!--    });-->
<!--</script>-->
<script>
 $(function(){
     $('#api #select select').append('<option value="1">问卷列表</option><option value="2">题目列表</option>');
     $('#api #select select').on('change',function() {
         var type=$('#select select').val();
         if(type==-1){
             return;
         }
         if(type==1){
             $('#p_title').empty();
             $('#p_title').css('display','block');
             $('#title').css('display','none');
             $('#p_title').html('');
         var creditorDataSource = new kendo.data.DataSource({
             type: 'json',
             transport: {
                 read: {
                     url: '/?r=user/indexapi',
                     dataType: "json",
                     type: 'GET',
                     data: {
                         type:type
                     }
                 }
             },
             schema: {
                 data: "recordList",
                 total: 'totalCount'
             },
             serverPaging: true,
             serverSorting: false,
             pageSize: 10
         });
         $('#p_title').kendoGrid({
             dataSource: creditorDataSource,
             pageable: {
                 refresh: true,
                 pageSizes: true,
                 buttonCount: 5
             },
             resizable: true,
             columns: [{
                 field: "id",
                 title: "序号",
             }, {
                 field: "task_name",
                 title: "标题",
             }, {
                 field: "meta",
                 title: "描述",
             },{
                 field: "create_time",
                 title: "创建时间",
             },{
                 field: "last_update_time",
                 title: "最后更新时间",
             }, {
                 field: "meta",
                 title: "url",
                 template:function(item){
                        var url='<?php echo $_SERVER['HTTP_HOST'];?>'+'/?r=test/begin&w_id='+item.id;
                     var input='<input type="text"  style="width:180px;height: 50px;font-size: 17px;"  value="'+ url+'">';
                     return input;
                 }
             },{
//                 command: [{text: "更新", click: showContent}],
                 field:'a',
                 title: "操作",
                 template:function(item){
                     var str='<input type="button" class="k-button k-button-icontext" onclick="showContent('+item.id+','+item.type+');" value="更新">';
                     if(item.status==1){
                         str+=' <input type="button" class="k-button k-button-icontext" onclick="updateStatus('+ item.id+','+0+');" value="下线">';
                        }else {
                         str+=' <input type="button" class="k-button k-button-icontext" onclick="updateStatus('+ item.id+','+1+');" value="发布">';
                     }
                     return str;
                 }
             }]
         });
         }else if(type==2){
             $('#title').empty();
             $('#title').css('display','block');
             $('#p_title').css('display','none');
             $('#title').html('');
             var creditorDataSource = new kendo.data.DataSource({
                 type: 'json',
                 transport: {
                     read: {
                         url: '/?r=user/indexapi',
                         dataType: "json",
                         type: 'GET',
                         data: {
                             type:type
                         }
                     }
                 },
                 schema: {
                     data: "recordList",
                     total: 'totalCount'
                 },
                 serverPaging: true,
                 serverSorting: false,
                 pageSize: 10
             });
             $('#title').kendoGrid({
                 dataSource: creditorDataSource,
                 pageable: {
                     refresh: true,
                     pageSizes: true,
                     buttonCount: 5
                 },
                 resizable: true,
                 columns: [{
                     field: "id",
                     title: "序号",
                 }, {
                     field: "task_name",
                     title: "标题",
                 }, {
                     field: "meta",
                     title: "描述",
                 }, {
                     field: "a",
                     title: "选项a",
                 },{
                     field: "b",
                     title: "选项b",
                 },{
                     field: "c",
                     title: "选项c",
                 }, {
                     field: "d",
                     title: "选项d"
                 },{
                     field: "input",
                     title: "表单类型"
                 },{
                     field: "p_title_name",
                     title: "所属问卷标题"
                 },{
                     field: "create_time",
                     title: "创建时间",
                 },{
                     field: "last_update_time",
                     title: "最后更新时间",
                 },{
//                     command: [{text: "更新", click: showContent}],
                     field:'a',
                     title: "操作",
                     template:function(item){
                         var str='<input type="button" class="k-button k-button-icontext" onclick="showContent('+item.id+','+item.type+');" value="更新">';
                         if(item.status==1){
                             str+=' <input type="button" class="k-button k-button-icontext" onclick="updateStatus('+ item.id+','+0+');" value="下线">';
                         }else {
                             str+=' <input type="button" class="k-button k-button-icontext" onclick="updateStatus('+ item.id+','+1+');" value="发布">';
                         }
                         return str;
                     }
                 }]
             });
         }else {
             return;
         }
     });



});

    function showContent(id,type){
////        e.preventDefault();
////        var thisTiem= this.dataItem($(e.currentTarget).closest("tr"));
//        var  url='/?r=user/update&id='+thisTiem.id+'&type='+thisTiem.type;
        var url='/?r=user/update&id='+id+'&type='+type;
        window.open(url);
    }

    function updateStatus(id,status){
//        var status=$(this).attr('data-status');
//          alert(status);
//        return;
        var _csrf=$('input[name=_csrf]').val();
        if(_csrf==undefined){
            return;
        }
        $.ajax({
           url:'/?r=user/updatestatus',
            type:'POST',
            data:{
                _csrf:_csrf,
                id:id,
                status:status
            },
            dataType:'json',
            success:function(res){
               alert(res.message);
            }
        });
    }
</script>