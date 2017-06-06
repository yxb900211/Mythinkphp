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
        <li class="layui-nav-item"><a href="<?php echo U('Index/index');?>">欢迎页面</a></li>
        <li class="layui-nav-item">
            <a href="javascript:;">开始使用</a>
            <dl class="layui-nav-child">
                <dd><a href="<?php echo U('Ready/index');?>"><i class="layui-icon">&#xe623;</i> 准备工作</a></dd>
                <dd><a href="<?php echo U('Ready/funD');?>"><i class="layui-icon">&#xe623;</i> D方法介绍</a></dd>
            </dl>
        </li>
        <li class="layui-nav-item">
            <a href="javascript:;">Faster类介绍</a>
            <dl class="layui-nav-child">
                <dd><a href="<?php echo U('Faster/index');?>"><i class="layui-icon">&#xe623;</i> 查询数据</a></dd>
                <dd><a href=""><i class="layui-icon">&#xe623;</i> 写入数据</a></dd>
                <dd><a href=""><i class="layui-icon">&#xe623;</i> 修改数据</a></dd>
                <dd><a href=""><i class="layui-icon">&#xe623;</i> 删除数据</a></dd>
            </dl>
        </li>
        <li class="layui-nav-item"><a href="">产品</a></li>
        <li class="layui-nav-item"><a href="">大数据</a></li>
    </ul>
</div>
<!-- 侧边导航栏目 -->
<div class="layui-body">
    <fieldset class="layui-elem-field">
        <legend>数据库</legend>
        <div class="layui-field-box">
        <blockquote class="layui-elem-quote">首先我们需要建立数据表</blockquote>
        <pre class="layui-code">
##建立数据表
CREATE TABLE IF NOT EXISTS `db_demo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` char(32) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
        </pre>
        </div>
    </fieldset>
    <fieldset class="layui-elem-field">
        <legend>控制器</legend>
        <div class="layui-field-box">
        <blockquote class="layui-elem-quote">控制器需要设置新建一个主控制器，让其他控制器继承于该控制器</blockquote>
        <pre class="layui-code">
namespace Home\Controller; //注意命名空间
use Think\Controller;
use Think\Faster; //引用Faster类
class HomeCommonController extends Controller {
    /**
     * [__call 魔术方法]
     * @param    $function_name [方法名称]
     * @param    $argments      [传递参数]
     * @return   数据信息       []
     */
    public function __call($function_name,$argments)
    {
        $model = Faster::start($this->model);
        if (method_exists($model,$function_name)) {
            $data = call_user_func_array([$model,$function_name],[$this,$argments]);
            switch ($data['type']) {
                case 'return': return $data['data']; break;
                case 'display':
                    foreach ($data['assign'] as $key => $value) {
                        $this->assign($key,$value);
                    }
                    $this->display();
                    break;
                case 'error': $this->error($data['msg']); break;
                case 'success': $this->success($data['msg'],$data['url']); break;
                case 'ajax': $this->ajaxReturn($data['assign']); break;
            }
        }else{
            parent::__call($function_name,$argments);
        }
    }
}
        </pre>
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