<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MyDemo</title>
<link rel="stylesheet" type="text/css" href="/Public/Admin/layui/css/layui.css" /> 
<link rel="stylesheet" type="text/css" href="/Public/Admin/css/css.css" /> 
</head>
 
<link rel="stylesheet" href="/Public/zyupload/skins/zyupload-1.0.0.min.css " type="text/css">
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
<legend>layui前端框架自带ajax上传</legend>
</fieldset>
<div class="bomol">
<input type="file" name="file" id="demo1">
</div>

                   
</div>

                 <div class="boytern">
                    <fieldset class="layui-elem-field layui-field-title" style="margin-top: 50px;">
  <legend>zyupload插件ajax上传</legend>
</fieldset>
<div id="zyupload" class="zyupload"></div>                 
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
var url = "<?php echo U('AdminCommon/ajax_upload');?>";
  // demo1
  layui.use('upload', function(){
    layui.upload({
      url:url,              //上传处理地址
      title: '上传DEMO1',   //设置上传标题
      elem:'#demo1',        //选择器，对应input的id或者class
      unwrap:false,         //样式风格，如需要原始input框填写true
      ext: 'jpg|png|gif',   //设置文件上传格式限制
      before:function(input) {
        // 上传之前的回调函数
      },
      success:function(data){
        //上传成功后回调，josn
        alert('返回值为JSON：'+JSON.stringify(data));
      }

    });
  });
</script>
<script type="text/javascript" src="/Public/zyupload/jquery-1.7.2.js"></script>
<script type="text/javascript" src="/Public/zyupload/zyupload-1.0.0.min.js"></script>
<script type="text/javascript">

      $(function(){
        var url = "/index.php/Admin/Upload/zyupload";
        // 初始化插件
        $("#zyupload").zyUpload({
          width            :   "750px",                 // 宽度
          height           :   "400px",                 // 宽度
          itemWidth        :   "140px",                 // 文件项的宽度
          itemHeight       :   "115px",                 // 文件项的高度
          url              :   url,                     // 上传文件的路径
          fileType         :   ["jpg","png","gif"],     // 上传文件的类型
          fileSize         :   51200000,                // 上传文件的大小
          multiple         :   true,                    // 是否可以多个文件上传
          dragDrop         :   true,                    // 是否可以拖动上传文件
          tailor           :   true,                    // 是否可以裁剪图片
          del              :   true,                    // 是否可以删除文件
          finishDel        :   false,           // 是否在上传文件完成后删除预览
          /* 外部获得的回调接口 */
          onSelect: function(selectFiles, allFiles){    // 选择文件的回调方法  selectFile:当前选中的文件  allFiles:还没上传的全部文件
            console.info("当前选择了以下文件：");
            console.info(selectFiles);
          },
          onDelete: function(file, files){              // 删除一个文件的回调方法 file:当前删除的文件  files:删除之后的文件
            alert(file.name);
            console.info("当前删除了此文件：");
            console.info(file.name);
          },
          onSuccess: function(file, response){          // 文件上传成功的回调方法
            alert('上传完成的回调为JSON：'+JSON.stringify(file));
            alert('上传完成的回调为JSON：'+JSON.stringify(response));

          },
          onFailure: function(file, response){          // 文件上传失败的回调方法
            console.info("此文件上传失败：");
            console.info(file.name);
          },
          onComplete: function(response){               // 上传完成的回调方法
            alert('上传完成的回调为JSON：'+JSON.stringify(response));
          }
        });
        
      });
    
    </script> 
</body>
</html>