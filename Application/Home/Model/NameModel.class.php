<?php
namespace Home\Model;
use Think\Model;
class NameModel extends Model {
	protected $_validate = array(     
		array('name','require','姓名必须！'), 
		array('name','','用户名已经存在！',0,'unique',1), 
		);
}