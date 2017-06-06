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
                <dd><a href="<?php echo U('Faster/add');?>"><i class="layui-icon">&#xe623;</i> 写入数据</a></dd>
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
        <legend>方法设置</legend>
        <div class="layui-field-box">
        <blockquote class="layui-elem-quote">在我们的子级控制器中，如果不定义index方法则会默认访问Faster类快速查询方法</blockquote>
        <pre class="layui-code">
/*像是这样*/
namespace Home\Controller;
use Think\Controller;
/**
 * 控制器需要继承于我们准备工作中准备的主控制器
 */
class DemoController extends HomeCommonController {

}
        </pre>
        </div>
    </fieldset>
    <fieldset class="layui-elem-field">
        <legend>模型定义</legend>
        <div class="layui-field-box">
        <blockquote class="layui-elem-quote">在我们查询方法中，需要设置查询哪张数据模型</blockquote>
        <pre class="layui-code">
/*如果我们的模型名称‘Demo’ 与我们的控制器名称相同时，无须设置模型，会自动查询Demo模型中数据*/
//例如
class DemoController extends HomeCommonController {

}
/*当然控制器名称不会总和模型名称相同，这时我们需要设置模型变量 $model*/
class IndexController extends HomeCommonController {
public $model = 'Demo';
//或者我需要给模型名称增加一个别名
// public $model = ['Demo','D'];
}
        </pre>
        </div>
    </fieldset>
    <fieldset class="layui-elem-field">
        <legend>变量获取</legend>
        <div class="layui-field-box">
        <blockquote class="layui-elem-quote">上述方法完成后，我们就可以在view中获取一些变量</blockquote>
        <pre class="layui-code">
$db; //查询出来的数据（二维数组）
$page; //经过page类得到的分页html
$count; //查询出的数据条数
$token; //该页面的唯一标识符，用于重复提交验证等
        </pre>
        </div>
    </fieldset>
    <fieldset class="layui-elem-field">
        <legend>高级操作</legend>
        <div class="layui-field-box">
        <blockquote class="layui-elem-quote">当然，我们在实际使用当中，不可能所有的数据都无脑的去取得全部数据，会有条件、关联等操作，那么下面的操作会帮助你达成</blockquote>
        <pre class="layui-code">
/* 比如，我们现在需要加上一些查询条件以及表join连接 */
class DemoController extends HomeCommonController {
//那么我们可以在子控制器当中建立一个特殊的方法 _map
    public function _map(&$data){
    //这里$data前面的&符号是必须的
        $data = [
        'where' => ['id'=>1],
        'order' => 'create_time desc',
        'join'  => ['LEFT JOIN db_login L ON L.userid = db_demo.id'],
        ];
    }
}
//这个$data变量其实就是前面说到的D::get()方法中的map参数，支持所有的TP3.2的连贯操作方法
//这样我们再去view中取值就是上述条件查询出来的值了
        </pre>
        <pre class="layui-code">
/* 又或者我们需要一些前置操作，比如页面还需要自己手动查询一些变量传递到view视图中 */
class DemoController extends HomeCommonController {
//那么我们就可以用魔术函数 _before方法
    public function _before_index(){
    //在这里是在index方法中之前需要执行的方法，可以进行任何操作
    }
}
        </pre>
        <pre class="layui-code">
/* 最后，我们需要对查询出的数据做出一些处理，但是之前直接传送到页面去了。怎么办呢 */
class DemoController extends HomeCommonController {
//如果需要修改返回数据，那么我们就需要对父级方法进行覆盖处理了
    public function index(){
    //这里我们覆盖了父级的方法，页面就无法直接取到数据了，不过我们这边可以进行一些其他操作了
    /*前置操作写完之后，我们需要调用父类方法*/
        parent::index();//如果我们直接调用，跟上面的是一样的，那怎么才能修改数据呢？
        /*这里我们传参有几种方法*/
        /*第一种,传数字,这方法可以限制我们每页显示的数据量*/
        parent::index(10);
        /*第二种，传bool，true返回的是分页后的数据集，false则返回为分页的数据*/
        $db = parent::index(true); //取到数据后我们可以在方法中对后续数据进行操作
        /*第三种，传字符串，除了特殊字符串'sql' 我们将得到该方法所使用的SQL语句，主要用于测试
        其他任意字符串都代表此控制器内任意方法名，也就是通过循环数据集的每个value传入方法进行操作
        */
        parent::index('after_func');
        //通过这个方法，我们就可以通过我们下面设置的after_func方法去让数据集中的create_time字段变成日期格式
        /*第四种，匿名函数，类似上面的方法，只不过我们将匿名函数更方便的利用此方法中的其他变量*/
        $num = 3600;
        parent::index(function($data)use($num){
            $data['create_time'] = $data['create_time'] + $num;
            return $data;
        });
        //这样我们就得到了让数据集中create_time字段值每个都加上了3600秒的操作
    }
    /**
     * [after_func]
     * $data 为每条对应的数据
     */
    public function after_func($data){
        $data['create_time'] = date('Y-m-d',$data['create_time']);
        return $data;
    }

}
        </pre>
        </div>
    </fieldset>
   <fieldset class="layui-elem-field">
        <legend>关于分页</legend>
        <div class="layui-field-box">
        <blockquote class="layui-elem-quote">分页每页的页数是需要自定义的，需要对config中设置'PAGE_NUMBER'</blockquote>
        <pre class="layui-code">
return array(
  'PAGE_NUMBER' => 30,//设置每页显示30条数据。
  'PAGE_SHOW_FUNC' => 'show',//也许我们在page类里面设置了多个输出方法，对应多个端，那么我们可以在这里定义输出用的方法，默认show。
);
/*这样如果特殊页面可以用C()方法去设置*/
        </pre>
        </div>
    </fieldset>
   <fieldset class="layui-elem-field">
        <legend>关于AJAX</legend>
        <div class="layui-field-box">
        <blockquote class="layui-elem-quote">当然，对于现在AJAX的用途较为广泛，通过无刷新更改页面列表非常常见，这里如果是通过AJAX方法请求index方法的时候，也理所应当的获取到JSON返回值</blockquote>
        <pre class="layui-code">
JSON返回值如下
{db:数据,count:总数据量,page:总分页数量,token:唯一辨识符}
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