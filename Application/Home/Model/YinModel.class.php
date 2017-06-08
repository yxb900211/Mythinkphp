<?php
namespace Home\Model;
use Think\Model;
class YinModel extends Model {
	protected $_validate = array(
		array('true','3,16','密码长度3-16位',2,'length',1),
	);

}