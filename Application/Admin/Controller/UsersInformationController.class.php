<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 进阶增删改查控制器
 *
 */
class UsersInformationController extends AdminCommonController {
	// public $model = 'UsersInformation';如果模型名称与控制器名称相同则不需要设置$model
	// public $del_field = array('status' => 'del');无逻辑删除字段也无需设置此项
	// public $success = array(
	// 	'insert' => '欢迎加入~~',
	// 	'update' => '为您更新了信息哦~~',
	// 	'del'    => '信息已经扔掉了~~',
	// 	);
	//设置操作成功提示文字，当然他们是有默认文字的，如果默认满足要求也无须进行设置
	
	/**
	 * [_select description]
	 * 查询的时候会有一些意想不到的查询条件或者排序或者join需要加入查询，我们就用到了下面的方法
	 * $list为数组，内对应为 array('sel'=>查询条件,'order'=>排序,'join'=>多表联查)等
	 */
	public function _select(&$list)
	{
		if (I('keyword')) {
			if (I('keyword') == '男') {
				$key = 'boy';
			}else if (I('keyword') == '女'){
				$key = 'girl';
			}
			$map['yin_users.username'] = I('keyword');
			$map['sex']                = $key;
			$map['name|age']           = array('like','%'.I('keyword').'%');
			$map['_logic']             = 'or';
		}
		//上面为查询条件
		$order = 'yin_users_information.add_time desc';
		//我们用修改时间倒叙排序
		$join  = 'yin_users ON yin_users_information.users_id = yin_users.id';
		//多表查询
		$field = 'yin_users_information.*,yin_users.username';
		//限定查询字段
		$list = array(
			'field' => $field,
			'sel'   => $map,
			'order' => $order,
			'join'  => $join,
			);
		// 最后组装到$list内，无须return
	}

	/**
	 * [index description]
	 * 有些时候你需要对主表查询出的数组做一些其他操作，当然是可以的
	 * 只需要引用父级的index方法传入false就可以得到返回值了
	 * 
	 */
	public function index()
	{
		$db = parent::index(false);
		foreach ($db as $key => $value) {
			$db[$key]['sex'] = $value['sex']=='boy'?'男':'女';
		}
		$this->assign('db',$db);
		$this->display();
	}

	/**
	 * [_before_add description]
	 * 可以用_before方法来做一些前置的准备工作比如传一些表单用到的数据
	 */
	public function _before_add()
	{
		$users = D('Users')->get_all();
		$this->assign('users',$users);
	}

	/**
	 * [_insert description]
	 * 比如model用的并不习惯，也提供了前置的方法，也可对原有字段做一些调整后写入
	 * 注意&符号以及变量名为data是必要的，请不要更改
	 */
	public function _insert(&$data)
	{
		$data['add_time'] = time();//这里只是举例说明，add_time本身是可以从model来完成计算并写入
	}

	/**
	 * [after_insert description]
	 * 某些时候需要在增加信息后对其有附加操作，这里可以获取到新加入的数据ID并且执行后续操作
	 */
	public function after_insert($id)
	{
		$birthday = D($this->model)->where('id='.$id)->getField('birthday');
		$age = date('Y',time()) - date('Y',strtotime($birthday));
		D($this->model)->where('id='.$id)->setField('age',$age);
		//这里只是举例说明，age本身是可以从model来完成计算并写入
	}
	
	/**
	 * [_before_edit description]
	 * 同样修改页面也可以前置操作，其他页面都是可以的
	 */
	public function _before_edit()
	{
		$users = D('Users')->get_all();
		$this->assign('users',$users);
	}

	/**
	 * [_save description]
	 * 比如model用的并不习惯，数据修改也提供了前置的方法，也可对原有字段做一些调整后写入
	 * 注意&符号以及变量名为data是必要的，请不要更改
	 */
	public function _save(&$data)
	{
		$age = date('Y',time()) - date('Y',strtotime(I('post.birthday')));
		$data['age'] = $age;
		$data['add_time'] = time();
	}
	
}
