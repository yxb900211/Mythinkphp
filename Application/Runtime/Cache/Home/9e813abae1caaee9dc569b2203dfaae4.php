<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>

	<title><<?php echo ($title); ?>></title>
	<script type="text/javascript" src="/Public/Admin/js/jquery-1.10.1.min.js"></script>
	<script type="text/javascript" src="/Public/Admin/layer/layer.js"></script>
	<script type="text/javascript" src="/Public/Admin/common/common.js"></script>
</head>
<body>
<div style="width: 960px;margin: auto;">
<h2>增加一条新的数据</h2>
<hr />
<!--
弹窗引用方式form：
设置class
class="任意不冲突的class名（唯一）"
 -->
<form action="/index.php/Home/Index/insert" method="post" class="form-horizontal" enctype="multipart/form-data">
<input type="hidden" name="__GO__" value="/index.php/Home/Index/index" />
<!--增加成功跳转指令的页面地址-->
姓名：<input type="text" name="username" style="width: 200px;" />

<br /><br />
图片：<input type="hidden" name="_token" value="<?php echo ($token); ?>" />

<br /><br />
<input id="submit" type="submit" class="ajax-post" target-form="form-horizontal" value="添加" />
<!--
弹窗引用方式form：
class="ajax-post" 提交表单的弹窗
target-form="form设置的class"
file-url="设置图片上传的路径信息，没有上传则不填写"
 -->
<a href="">
<input type="button" value="返回" />
</a>
</form>
</div>
</body>
</html>