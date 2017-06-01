layui.use('element', function(){
  var element = layui.element();
});
layui.use(['layer', 'laypage', 'element'], function(){
  var layer = layui.layer
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
    ,skip: true //开启跳页
    ,jump: function(obj, first){
      if(!first){
        layer.msg('第'+ obj.curr +'页');
      }
    }
  });
});
layui.use('code', function(){ //加载code模块
  layui.code(); //引用code方法
});