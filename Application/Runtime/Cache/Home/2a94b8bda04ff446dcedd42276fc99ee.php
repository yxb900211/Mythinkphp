<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>demo</title>
    <link rel="stylesheet" type="text/css" href="/Public/demo/layui/css/layui.css">
    <link rel="stylesheet" type="text/css" href="/Public/demo/css/css.css">
    <script type="text/javascript" src="/Public/demo/layui/layui.js"></script>
    <script type="text/javascript" src="/Public/demo/js/jquery-1.10.1.min.js"></script>
    <script type="text/javascript" src="/Public/demo/js/js.js"></script>
</head>
<body>
<!--水平导航栏目-->
<div>
    <ul class="layui-nav">
        <li class="layui-nav-item">
            <div class="layui-demo"><img src="/Public/demo/img/logo.png" width="160" height="60"></div>
        </li>
        <li class="layui-nav-item"><a href="">开始使用</a></li>
        <li class="layui-nav-item layui-this">
            <a href="javascript:;">产品</a>
            <dl class="layui-nav-child">
                <dd><a href="">选项1</a></dd>
                <dd><a href="">选项2</a></dd>
                <dd><a href="">选项3</a></dd>
            </dl>
        </li>
        <li class="layui-nav-item"><a href="">大数据</a></li>
        <li class="layui-nav-item">
            <a href="javascript:;">解决方案</a>
            <dl class="layui-nav-child">
                <dd><a href="">移动模块</a></dd>
                <dd><a href="">后台模版</a></dd>
                <dd class="layui-this"><a href="">选中项</a></dd>
                <dd><a href="">电商平台</a></dd>
            </dl>
        </li>
        <li class="layui-nav-item"><a href="">社区</a></li>
    </ul>
</div>
<!--水平导航栏目-->
<!-- 侧边导航栏目 -->
<div class="layui-left layui-fixed">
    <ul class="layui-nav layui-nav-tree">
        <li class="layui-nav-item"><a href="<?php echo U('Index/index');?>"><i class="layui-icon">&#xe63c;</i> 欢迎页面</a></li>
        <li class="layui-nav-item">
            <a href="javascript:;">开始使用</a>
            <dl class="layui-nav-child">
                <dd><a href="<?php echo U('Ready/index');?>"><i class="layui-icon">&#xe623;</i> 准备工作</a></dd>
                <dd><a href="<?php echo U('Ready/funD');?>"><i class="layui-icon">&#xe623;</i> D方法介绍</a></dd>
                <dd><a href=""><i class="layui-icon">&#xe623;</i> 跳转</a></dd>
            </dl>
        </li>
        <li class="layui-nav-item">
            <a href="javascript:;">解决方案</a>
            <dl class="layui-nav-child">
                <dd><a href=""><i class="layui-icon">&#xe623;</i> 移动模块</a></dd>
                <dd><a href=""><i class="layui-icon">&#xe623;</i> 后台模版</a></dd>
                <dd><a href=""><i class="layui-icon">&#xe623;</i> 电商平台</a></dd>
            </dl>
        </li>
        <li class="layui-nav-item"><a href="">产品</a></li>
        <li class="layui-nav-item"><a href="">大数据</a></li>
    </ul>
</div>
<!-- 侧边导航栏目 -->
<div class="layui-body">
    <fieldset class="layui-elem-field">
        <legend>区块标题</legend>
        <div class="layui-field-box">

            <div class="layui-btn-group">
                <button class="layui-btn">增加</button>
                <button class="layui-btn">编辑</button>
                <button class="layui-btn">删除</button>
            </div>

            <table class="layui-table">
                <colgroup>
                    <col width="150">
                    <col width="200">
                    <col>
                </colgroup>
                <thead>
                <tr>
                    <th>昵称</th>
                    <th>加入时间</th>
                    <th>签名</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>人生就像是一场修行</td>
                </tr>
                <tr>
                    <td>贤心</td>
                    <td>2016-11-29</td>
                    <td>人生就像是一场修行</td>
                </tr>
                </tbody>
            </table>
            <div id="pageDemo"></div>
        </div>
    </fieldset>
</div>
<div class="layui-foot">
    492663515@qq.com
</div>
<script type="text/javascript">
	set_left("<?php echo U(CONTROLLER_NAME.'/'.ACTION_NAME);?>","<?php echo U(CONTROLLER_NAME.'/index');?>");
</script>
</body>
</html>