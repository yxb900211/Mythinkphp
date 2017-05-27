<?php
namespace Admin\Model;
use Think\Model;
class UsersInformationModel extends Model {
	protected $_validate = array(
		array('users_id','require','请选择用户！'),
		array('users_id','','用户已经录入信息！',0,'unique',1), 
		array('name','require','请输入姓名',0,'',1), 
		array('sex','require','请选择性别',0,'',1), 
		array('birthday','require','请输入生日',0,'',1), 
	);
	protected $_auto = array(
	);
	public function get_all()
	{
		$map['status'] = 'none';
		return $this->where($map)->select();
	}
}