<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 首页控制器
 */
class AutoController extends AdminCommonController {
	public function auto()
	{
		ignore_user_abort();//即使Client断开(如关掉浏览器)，PHP脚本也可以继续执行.
		set_time_limit(60); //执行时间为无限制，php默认的执行时间是30秒，通过set_time_limit(0)可以让程序无限制的执行下去
		$interval=10;//每隔2秒运行
		do{
			M('users')->data(array('username' => 1111))->add();
			sleep($interval);
		}while (true);
	}
}