<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MyDemo</title>
<link rel="stylesheet" type="text/css" href="/Public/Admin/layui/css/layui.css" /> 
<link rel="stylesheet" type="text/css" href="/Public/Admin/css/css.css" /> 
</head>
 
<body>
<div class="hend">
<div class="hend_left">
    <!-- <img src="/Public/Admin/images/logo.png" width="250" height="60" /> -->
</div>
<div class="hend_right">
<ul class="layui-nav" lay-filter="">
  <!-- <li class="layui-nav-item"><a href="">最新活动</a></li>
  <li class="layui-nav-item layui-this"><a href="">产品</a></li>
  <li class="layui-nav-item"><a href="">大数据</a></li>
  <li class="layui-nav-item">
    <a href="javascript:;">解决方案</a>
    <dl class="layui-nav-child">  -->
<!--       <dd><a href="">移动模块</a></dd>
      <dd><a href="">后台模版</a></dd>
      <dd><a href="">电商平台</a></dd>
    </dl>
  </li> -->
  <li class="layui-nav-item tc"><a>退出</a></li>
</ul>
</div>
</div>
<div class="conrer">
<div class="conleft">         
 <ul class="layui-nav layui-nav-tree layui-nav-side">
 <li class="layui-nav-item"><a href="<?php echo U('Index/index');?>">MyDemo</a></li>
  <li class="layui-nav-item layui-nav-itemed">
    <a href="javascript:;">基础增删改查</a>
    <dl class="layui-nav-child">
      <dd><a href="<?php echo U('Basics/index');?>"><i class="layui-icon">&#xe623;</i> 基础</a></dd>
      <dd><a href="<?php echo U('UsersInformation/index');?>"><i class="layui-icon">&#xe623;</i> 进阶</a></dd>
    </dl>
  </li>
  <li class="layui-nav-item layui-nav-itemed">
    <a href="javascript:;">其他插件引用</a>
    <dl class="layui-nav-child">
      <dd><a href="<?php echo U('Upload/index');?>"><i class="layui-icon">&#xe623;</i> 文件上传</a></dd>
      <dd><a href="<?php echo U('Qrcode/index');?>"><i class="layui-icon">&#xe623;</i> 二维码生成</a></dd>
      <dd><a href="<?php echo U('Excel/index');?>"><i class="layui-icon">&#xe623;</i> Excal导入导出</a></dd>
      <dd><a href="<?php echo U('Tcpdf/index');?>"><i class="layui-icon">&#xe623;</i> PDF文件</a></dd>
      <dd><a href="<?php echo U('Database/index');?>"><i class="layui-icon">&#xe623;</i> 数据库备份还原</a></dd>
    </dl>
  </li>
  <li class="layui-nav-item">
    <a href="javascript:;">解决方案</a>
    <dl class="layui-nav-child">
      <dd><a href="">移动模块</a></dd>
      <dd><a href="">后台模版</a></dd>
      <dd><a href="">电商平台</a></dd>
    </dl>
  </li>
  
  <li class="layui-nav-item"><a href="">大数据</a></li>
</ul>    
</div>

<!-- 页面内容 -->
<div class="conright" >
        <div class="qipor">
        <blockquote class="layui-elem-quote">插件</blockquote>
                 <div class="boytern">
                    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 50px;">
  <legend>数据库还原</legend>
</fieldset>
                    <div class="bomol">
                      <!--  <div class="timb">用户列表
                       </div> -->
                   <!--     <div class="layui-inline">
                            <input class="layui-input" placeholder="自定义日期格式" onclick="layui.laydate({elem: this, istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
                        </div> -->
                   </div>
                   <div class="btomt">
                       <!-- <button onclick="window.location.href='/index.php/Admin/Database/add'" class="layui-btn">
                                      <i class="layui-icon">&#xe608;</i> 添加
                       </button> -->
                       
        <a id="optimize" class="layui-btn layui-btn-small" href="<?php echo U('Database/index');?>">数据库备份</a>
        
                    </div>
                     
                   </div>
               
                 <table class="layui-table">
            <thead>
                <tr>
                    <th width="200">备份名称</th>
                    <th width="80">卷数</th>
                    <th width="80">压缩</th>
                    <th width="80">数据大小</th>
                    <th width="200">备份时间</th>
                    <th>状态</th>
                    <th width="120">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><tr>
                        <td><?php echo (date('Ymd-His',$data["time"])); ?></td>
                        <td><?php echo ($data["part"]); ?></td>
                        <td><?php echo ($data["compress"]); ?></td>
                        <td><?php echo (format_bytes($data["size"])); ?></td>
                        <td><?php echo ($key); ?></td>
                        <td>-</td>
                        <td class="action">
                            <a class="db-import" href="<?php echo U('import?time='.$data['time']);?>">还原</a>&nbsp;
                            <a class="ajax-get confirm" href="<?php echo U('del?time='.$data['time']);?>">删除</a>
                        </td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
           
                
                <!-- <div id="demo1"></div> -->
 

</div>
</div>

</div>
<!-- 页面内容 -->

<script type="text/javascript" src="/Public/Admin/js/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="/Public/Admin/layui/layui.js"></script>
<script type="text/javascript" src="/Public/Admin/layer/layer.js"></script>
<script type="text/javascript" src="/Public/Admin/common/common.js"></script>
<!--通用重要-->
<script>
layui.use('element', function(){
  var element = layui.element();
  
  //一些事件监听
  element.on('tab(demo)', function(data){
    console.log(data);
  });
});


</script>
<!--通用重要-->
<!--时间插件-->
<script>
layui.use('laydate', function(){
  var laydate = layui.laydate;
  
  var start = {
    min: laydate.now()
    ,max: '2099-06-16 23:59:59'
    ,istoday: false
    ,choose: function(datas){
      end.min = datas; //开始日选好后，重置结束日的最小日期
      end.start = datas //将结束日的初始值设定为开始日
    }
  };
  
  var end = {
    min: laydate.now()
    ,max: '2099-06-16 23:59:59'
    ,istoday: false
    ,choose: function(datas){
      start.max = datas; //结束日选好后，重置开始日的最大日期
    }
  };
  
  document.getElementById('LAY_demorange_s').onclick = function(){
    start.elem = this;
    laydate(start);
  }
  document.getElementById('LAY_demorange_e').onclick = function(){
    end.elem = this
    laydate(end);
  }
  
});
</script>
<!--时间插件-->


<!--分页-->
<script>
layui.use(['laypage', 'layer'], function(){
  var laypage = layui.laypage
  ,layer = layui.layer;
  
  laypage({
    cont: 'demo1'
    ,pages: 100 //总页数
    ,groups: 5 //连续显示分页数
  });

 
  
});
</script>
<!--分页-->



<!--询问框-->
<script>
$('.tc').click(function(){
	
	layui.use('layer', function(){
  var layer = layui.layer;            
layer.confirm('您确定退出吗？', {
  btn: ['确定','取消'] //按钮
}, function(){
  layer.msg('退出成功', {icon: 1});
}, function(){
  layer.msg('取消成功', {
    time: 20000, //20s后自动关闭
    
  });
});
});
	
	})

</script>
<!--询问框-->
<script type="text/javascript">
        $(".db-import").click(function(){
            var self = this, status = ".";
            $.get(self.href, success, "json");
            window.onbeforeunload = function(){ return "正在还原数据库，请不要关闭！" }
            return false;
        
            function success(data){
                if(data.status){
                    if(data.gz){
                        data.info += status;
                        if(status.length === 5){
                            status = ".";
                        } else {
                            status += ".";
                        }
                    }
                    $(self).parent().prev().text(data.info);
                    if(data.part){
                        $.get(self.href, 
                            {"part" : data.part, "start" : data.start}, 
                            success, 
                            "json"
                        );
                    }  else {
                        window.onbeforeunload = function(){ return null; }
                    }
                } else {
                    alert(data.info,'alert-error');
                }
            }
        });
    </script>
</body>
</html>