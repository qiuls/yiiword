<div class="content-header" style="border: 2px solid #f2dede;height: 50px;">
 <span style="color:green"><?php echo $str;?></span>
</div>
<div class="content-content" style="height: 500px;">
<div style="width: 25%;height: auto;background-color: #00a1e8">你可能感兴趣</div>
        <ul style="list-style: none;">
            <?php foreach($arr as $v):?>
                <li style="height: 20px;width: 25%;"><a href="/?r=test/begin&w_id=<?php echo $v['id'];?>"><?php echo $v['task_name'];?></a></li>
            <?php endforeach;?>
        </ul>
</div>
<style type="text/css">
    a{
        text-decoration: none;
    }
ul li:hover{
    background-color: #f2dede;
}
</style>