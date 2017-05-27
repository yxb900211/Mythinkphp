<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<title><<?php echo ($title); ?>></title>
<script type="text/javascript" src="/Public/Admin/js/jquery-1.10.1.min.js"></script>
<link rel="stylesheet" type="text/css" href="/Public/Admin/layui/css/layui.css">
<script type="text/javascript" src="/Public/Admin/layui/layui.js"></script>
</head>
<body>
<div style="width: 960px;margin: auto;">

<h2>数据列表</h2>
<hr />
<a href="/index.php/Home/Index/add"><button>添加</button></a>
<a href="/index.php/Home/Index/recycle"><button>回收站</button></a>
<div style="float: right;margin-bottom:5px;">
<form action="/index.php/Home/Index/index" method="get">
	<input type="text" name="name" /><input type="submit" value="查询" />
</form>
</div>
<table border="1px" width="960px;" cellspacing="0">
token:<?php echo session('token');?>
<thead>
	<tr>
		<th style="width: 10%">序号</th>
		<th style="width: 30%">姓名</th>
		<th style="width: 10%">年龄</th>
		<th style="width: 15%">生日</th>
		<th style="width: 15%">添加日期</th>
		<th style="width: 20%">操作</th>
	</tr>
</thead>
<tbody>
<?php if(is_array($db)): $k = 0; $__LIST__ = $db;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$db): $mod = ($k % 2 );++$k;?><tr>
		<td></td>
		<td><?php echo ($db["user_id"]); ?></td>
		<td></td>
		<td></td>
		<td><?php echo ($db["add_time"]); ?></td>
		<td><a href="/index.php/Home/Index/edit/id/<?php echo ($db["id"]); ?>"><button>修改</button></a> <a class="ajax-get" href="/index.php/Home/Index/delete/id/<?php echo ($db["id"]); ?>/_token/<?php echo ($token); ?>"><button>删除</button></a></td>
	</tr><?php endforeach; endif; else: echo "" ;endif; ?>
</tbody>
</table>
<div id="pageDemo"></div>
<script>
layui.use(['layer', 'laypage', 'element'], function(){
  var layer = layui.layer
  // ,$ = layui.query
  ,laypage = layui.laypage
  ,element = layui.element();
  //向世界问个好
  // layer.msg('Hello World');
  //监听Tab切换
  element.on('tab(demo)', function(data){
    layer.msg('切换了：'+ this.innerHTML);
    console.log(data);
  });
  //分页
  laypage({
    cont: 'pageDemo' //分页容器的id
    ,pages: 100 //总页数
    ,skin: '#5FB878' //自定义选中色值
    //,skip: true //开启跳页
    ,jump: function(obj, first){
      if(!first){
        $.get('/index.php/Home/Index/index?p='+obj.curr,function(data){
        	$('tbody').html('');
        	$.each(data.db,function(i,item){
        		$('tbody').append('<tr><td></td><td>'+item.user_id+'</td><td></td><td></td><td>'+item.add_time+'</td><td><a href="/index.php/Home/Index/edit/id/<?php echo ($db["id"]); ?>"><button>修改</button></a> <a class="ajax-get" href="/index.php/Home/Index/delete/id/<?php echo ($db["id"]); ?>/_token/<?php echo ($token); ?>"><button>删除</button></a></td></tr>');
        	});
        });
      }
    }
  });
});
</script>
</div>
</div>
</body>
</html>