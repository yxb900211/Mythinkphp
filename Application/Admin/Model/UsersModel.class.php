<?php
namespace Admin\Model;
use Think\Model;
class UsersModel extends Model {
	protected $_validate = array(
		array('username','require','请填写用户名！'),
		array('username','','用户名已经存在！',0,'unique',1), 
		array('password','require','请输入密码',0,'',1), 
		array('repassword','password','确认密码不正确',0,'confirm'),
		array('password','3,16','密码长度3-16位',2,'length',1), 
	);
	protected $_auto = array (          
        array('password','getpassword',3,'callback'),
		array('add_time','time',1,'function'),
		array('status','none',1),
	);
	function getpassword(){
		if (I('post.password')) {
			return md5(I('post.password'));
		}else{
			return false;
		}
	}
	public function get_all()
	{
		$map['status'] = 'none';
		return $this->where($map)->select();
	}
}