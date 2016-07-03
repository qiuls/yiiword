<?php
use yii\helpers\Html;
?>
<div id="api">
    <div style="height: 30px;"><span>首页</span></div>
<div id="select">
    <select style="width:200px;height:35px;">
        <option value="-1">请选择问卷标题</option>

    </select>
</div>
    <div style="width: auto;height: 100px;"></div>
<?= Html::csrfMetaTags(); ?>
<div id="p_title" style="display:none">

</div>

<div id="title" style="display:none">

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
             }, {
                 field: "meta",
                 title: "url",
                 template:function(item){
                     var url='http://192.168.117.10:8083/?r=test/begin&w_id='+item.id;
                     var input='<input type="text"  size="50" value="'+ url+'">';
                     return input;
                 }
             },{
                 command: [{text: "更新", click: showContent}],
                 field:'a',
                 title: "操作",
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
                     command: [{text: "更新", click: showContent}],
                     field: "a",
                     title:"操作"
                 }]
             });
         }else {
             return;
         }
     });



});

    function showContent(e){
        e.preventDefault();
        var thisTiem= this.dataItem($(e.currentTarget).closest("tr"));
        //alert(thisTiem.id);
        var  url='/?r=user/update&id='+thisTiem.id+'&type='+thisTiem.type;
        window.open(url);
//alert(1);
    }
</script>