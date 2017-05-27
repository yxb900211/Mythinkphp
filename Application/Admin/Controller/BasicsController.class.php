<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 基础增删改查控制器
 */
class BasicsController extends AdminCommonController {
	public $model = 'Users';//设置模型名称
	public $del_field = array('status' => 'del');//设置逻辑删除字段,也可在config内统一设置
	public $success = array(
		'insert' => '欢迎加入~~',
		'update' => '为您更新了信息哦~~',
		'del'    => '信息已经扔掉了~~',
		);
	//设置操作成功提示文字
	
}
/*结语：基础的增删改查只需要这样简单的配置便可以达成*/