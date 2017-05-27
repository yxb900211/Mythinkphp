<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<title><<?php echo ($title); ?>></title>
</head>
<body>
<div style="width: 960px;margin: auto;">
<h2>修改数据</h2>
<hr />
<form action="/index.php/Home/Index/update" method="post"   enctype="multipart/form-data">
<input type="hidden" name="__GO__" value="/index.php/Home/Index/index" />
<!--增加成功跳转指令的页面地址-->

姓名：<input type="text" name="username" value="<?php echo ($db["username"]); ?>" style="width: 200px;" />
<br /><br />
<input type="hidden" name="_token" value="<?php echo ($token); ?>">
<!-- 生日：<input  value="<<?php echo (date('Y-m-d',$db["born"])); ?>>" type="date" name="born" style="width: 200px;" /> -->
<br /><br />
<!-- 图片：<input type="file" name="file"/> -->
<br /><br />
<input type="hidden" name="id" value="<?php echo ($db["id"]); ?>" />
<input type="submit" value="修改" />
<a href="">
<input type="button" value="返回" />
</a>
</form>
</div>
</body>
</html>