<?php
namespace Home\Controller;
use Think\Controller;
use Think\Faster;
use Think\D;
use Think\Db;
class IndexController extends HomeCommonController {
	public $model = 'Demo';
	public function index()
	{
		$this->display();
	}
	public function add()
	{
		C('LAYOUT_ON',false);
		$this->display();
	}
	public function insert()
	{
		parent::insert(true,[
			'create_time' => time(),
			'update_time' => time(),
			],function($id,&$error){
				$flag = D::add('Yin',['name' => $id,'true' => '223']);
				if (!$flag) {
					$error = D::error('Yin');
					return false;
				}
			});
	}
}
