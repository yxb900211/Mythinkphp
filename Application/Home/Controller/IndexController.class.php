<?php
namespace Home\Controller;
use Think\Controller;
use Think\Faster;
use Think\D;
use Think\Db;
class IndexController extends HomeCommonController {
	public $model = 'Users';
	public function index()
	{
		dump(Faster::a());
	}
	public function return_data($data)
	{
		dump($data);
	}
}
