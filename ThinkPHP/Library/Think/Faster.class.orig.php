<?php
namespace Think;
use Think\D;
use Think\Controller;
/**
 * +--------------------------------------------------------------------------+
 * | 底层控制器封装代码
 * +--------------------------------------------------------------------------+
 * | 引用方法：在控制器命名空间下 引用该类'use Think\Faster;'即可使用
 * +--------------------------------------------------------------------------+
 * | 引用环境：此类基于Think\D;类 以及\Org\Util\Page类同时使用
 * +--------------------------------------------------------------------------+
 * | 制作人：蜂蝶旋舞 QQ:492663515 版本号：V04.11（测试）
 * +--------------------------------------------------------------------------+
 */
/*
使用方法
首先要引用
use Think\Faster;
然后请复制下面代码到主控制器中
+-----------------------------------------------------------------------------+
public function __call($function_name,$argments)
{
    $model = Faster::start($this->model);
    if (method_exists($model,$function_name)) {
        $data = call_user_func_array([$model,$function_name],[$this,$argments]);
        switch ($data['type']) {
            case 'return': return $data['data']; break;
            case 'display':
                foreach ($data['assign'] as $key => $value) {
                    $this->assign($key,$value);
                }
                $this->display();
                break;
            case 'error': $this->error($data['msg']); break;
            case 'success': $this->success($data['msg'],$data['url']); break;
            case 'ajax': $this->ajaxReturn($data['assign']); break;
        }
    }else{
        parent::__call($function_name,$argments);
    }
}
+-----------------------------------------------------------------------------+
 */
class Faster
{
	const SHOW_VIEW      = 1;           //正常输出到页面
	const PAGE_NUMBER    = 2;           //输出到页面，但是指定每页显示数据量
	const RETURN_PAGES   = 3;           //作为数据返回本页数据
	const RETURN_NOPAGES = 4;           //作为数据返回全部数据
	const RETURN_SQL     = 5;           //返回此页面数据查询语句
	const AFTER_FUNC     = 6;           //返回数据需要的后续操作
	const STATE_RETURN   = 7;           //正常模式返回
	const TRANS_START    = 8;           //开启事务操作方式
	const APPEND_DATA    = 9;           //增加写入数据
	const COMPLEX_ADD    = 10;          //多表联合插入
	const COMPLEX_SAVE   = 11;          //多表联合升级
	const APPEND_SAVE    = 12;          //增加修改数据
	const DELETE_FALSE   = 13;          //伪删除，通过修改字段删除
	const AFTER_FUNC_OB  = 14;          //返回数据需要的后续操作

	public $model;

	private function __construct($Model)
	{
		$this->model = $Model;
	}

	/**
	 * [start description]
	 * @Author   yin~
	 * @DateTime 2017-03-03
	 * @Function [启动此类]
	 * @param    [type]     $Model_name [description]
	 * @return   [type]                 [description]
	 */
	public static function start($Model_name)
	{
		$Model = $Model_name?$Model_name:CONTROLLER_NAME;
		return new self($Model);
	}

	/**
	 * [check_args 数据返回输出方式读取设置]
	 * @param  [type] $func_name [description]
	 * @param  [type] $args      [description]
	 * @return [type]            [description]
	 */
	private function check_args($func_name,$args)
	{
		switch ($func_name) {
			case 'index'://查询
				if ($args) {
					if (is_bool($args[0])) {
						$config = ($args[0] == false)?self::RETURN_PAGES:self::RETURN_NOPAGES;
					}
					if (is_numeric($args[0])) {
						$config = self::PAGE_NUMBER;
					}
					if (is_string($args[0])) {
						switch ($args[0]) {
							case 'sql':$config = self::RETURN_SQL;break;
							default:$config = self::AFTER_FUNC;break;
						}
					}
					if (is_object($args[0])) {
						$config = self::AFTER_FUNC_OB;
					}
				}
				break;
			case 'insert'://增加
				if ($args) {
					$config = array_map(function($data){
						if (is_bool($data)) {
							$config = ($data == false)?self::COMPLEX_ADD:self::TRANS_START;
						}
						if (is_array($data)) {
							$config = self::APPEND_DATA;
						}
						if (is_string($data)) {
							$config = self::AFTER_FUNC;
						}
						if (is_object($data)) {
							$config = self::AFTER_FUNC_OB;
						}
						return $config;
					}, $args);
				}
				break;
			case 'update'://修改
				if ($args) {
					if (is_bool($args[0])) {
						$config = ($args[0] == false)?self::COMPLEX_SAVE:self::TRANS_START;
					}
					if (is_array($args[0])) {
						$config = self::APPEND_SAVE;
					}
				}
				break;
			case 'delete'://删除
				if ($args) {
					if (is_array($args[0])) {
						$config = self::DELETE_FALSE;
					}
				}
				break;
		}
		return $config;
	}
	public static function config_page($page)
	{
		if (C('PAGE_CONFIG')) {
            foreach (C('PAGE_CONFIG') as $key => $value) {
                $page->setConfig($key,$value);
            }
        }
        //调取config配置‘PAGE_CLASS’对page的CSS样式进行更改
        if (C('PAGE_CLASS')) {
            foreach (C('PAGE_CLASS') as $key => $value) {
                $page->setClass($key,$value);
            }
        }
        return $page;
	}
	/**
	 * 方法前置操作执行
	 * @param  [type] $Controller [description]
	 * @param  [type] $func_name  [description]
	 * @return [type]             [description]
	 */
	private static function before_func($Controller,$func_name)
	{
		if (!method_exists($Controller,$func_name)) {
			if (method_exists($Controller,'_before_'.$func_name)) {
				call_user_func([$Controller,'_before_'.$func_name]);
			}
		}
	}
	/**
	 * 04.08 yin
	 * [index description]
	 * @param  [type] $Controller [description]
	 * @param  [type] $args      [
	 * 传入格式 bool = false:作为数据集返回本页数据; true:作为数据集返回未分页数据;
	 *          int  = 设置每页返回的数据数量，默认为C('PAGE_NUMBER')
	 *          string = 设置返回数据需要的循环后续操作的操作名称，变量1为遍历的每条数据
	 * ]
	 * @return [type]            [description]
	 */
	public function index($Controller,$args)
	{
		$token = $this->set_token();
		self::before_func($Controller,'index');//前置方法执行
		if (method_exists($Controller,'_map')) $Controller->_map($data);
		if (!$data) $data = [];
		$config = ($args)?$this->check_args('index',$args):self::SHOW_VIEW;
		if ($config != 4) {
			$page_number = ($config != 2)?C('PAGE_NUMBER'):$args[0];
			$count	= D::count($this->model,$data);
			$page   = self::config_page(new \Org\Util\Page($count,$page_number));
			$data['limit'] = $page->firstRow.','.$page->listRows;
		}
		$sql = ($config === 5)?false:true;
		$data_set = D::get($this->model,$data,$sql);
		if ($config === 6) $data_set = array_map([$Controller,$args[0]],$data_set);
		if ($config === 14) $data_set = array_map(function($data) use($args){return $args[0]($data);},$data_set);
		if ($config != 4) {
			$page_show = C('PAGE_SHOW_FUNC')?C('PAGE_SHOW_FUNC'):'show';
			$show = call_user_func([$page,$page_show]);
		}
		if (in_array($config,[1,2,6,14])) {
			$return = [
				'assign' => ['count'=>$count,'db'=>$data_set,'page'=>$show,'token'=>$token],
				'type'	 => 'display',
				];
			if (IS_AJAX) {
				$return['type'] = 'ajax';
				$return['assign']['page'] = $page->totalPages;
			}
			return $return;
		}else{
			switch ($config) {
				case 5:return ['type' => 'return','data' => $data_set];break;
				case 4:return ['type' => 'return','data' => $data_set];break;
				case 3:return ['type' => 'return','data' => ['count' => $count,'db' => $data_set,'page' => $show]];break;
			}
		}
	}
	/**
	 * [add description]
	 * @Author   yin~
	 * @DateTime 2017-04-11
	 * @Function []
	 * @param    [type]     $Controller [description]
	 * @param    [type]     $args       [description]
	 */
	public function add($Controller,$args)
	{
		$token = $this->set_token();
		self::before_func($Controller,'add');//前置方法执行
		return [
				'assign' => ['token' => $token],
				'type'	 => 'display',
				];
	}
	/**
	 * [insert description]
	 * @Author   yin~
	 * @DateTime 2017-04-11
	 * @Function []
	 * @param    [type]     $Controller [description]
	 * @param    [type]     $args       [
	 * 传入格式 bool   = true:多表联合插入 false:开始事务操作
	 *          string = 传入后续操作方法名称，设定变量新增ID
	 *          array  = 需要一同写入的值['字段名' => '值']的格式
	 * ]
	 * @return   [type]                 [description]
	 */
	public function insert($Controller,$args)
	{
		$token = C('TOKEN_VALIDATE')?C('TOKEN_VALIDATE'):'off';
		if ($token != 'off') {
			if (!$this->validate_token()) return ['type' => 'error','msg' => '页面已过期'];
		}
		$config = ($args)?$this->check_args('insert',$args):[self::STATE_RETURN];
		self::before_func($Controller,'insert');//前置方法执行
		if (in_array(8, $config)) {D()->startTrans();}//开启事务模型
		if (in_array(9, $config)) {
			$add_id = D::add($this->model,array_merge(I('post.'),$args[0]));
		}elseif (in_array(10, $config)) {
			foreach (I('post.') as $key => $value) {
				$add_ids[] = D::add($key,$value);
			}
		}else{
			$add_id = D::add($this->model);
		}
		if ($add_id || $add_ids) {
			if (in_array(6, $config)) { call_user_func([$Controller,$args[0]],$add_id);}
			if (in_array(14, $config)) {$args[count($args) - 1]($add_id);}
			if (in_array(8, $config)) {D()->commit();}//开启事务模型
			$href_url = I('param.__GO__')?I('param.__GO__'):$_SERVER["HTTP_REFERER"];
			return ['type' => 'success','msg' => $Controller->success['insert']?$Controller->success['insert']:'添加成功','url' => $href_url];
		}else{
			if (in_array(8, $config)) {D()->rollback();}//开启事务模型
			session('token',I('_token'));
			$error = D::error($this->model);
			return ['type' => 'error','msg' => $error?$error:'写入错误'];
		}
	}
	/**
	 * [edit description]
	 * @Author   yin~
	 * @DateTime 2017-04-11
	 * @Function [修改查看页面]
	 * @param    [type]     $Controller [description]
	 * @param    [type]     $args       [description]
	 * @return   [type]                 [
	 * 传入格式 bool   = true:多表联合修改或插入 false:开始事务操作
	 *          string = 传入后续操作方法名称，设定变量新增ID
	 *          array  = 需要一同写入的值['字段名' => '值']的格式
	 * ]
	 */
	public function edit($Controller,$args)
	{
		$token = $this->set_token();
		self::before_func($Controller,'edit');//前置方法执行
		$data = D::find($this->model,($args[0]?$args[0]:I('id')));
		return [
				'assign' => ['db'=>$data,'token' => $token],
				'type'	 => 'display',
				];
	}
	public function update($Controller,$args)
	{
		$token = C('TOKEN_VALIDATE')?C('TOKEN_VALIDATE'):'off';
		if ($token != 'off') {
			if (!$this->validate_token()) return ['type' => 'error','msg' => '页面已过期'];
		}
		self::before_func($Controller,'update');//前置方法执行
		$config = ($args)?$this->check_args('update',$args):self::STATE_RETURN;
		if ($config == 8) {D()->startTrans();}//开启事务模型
		$pk = D::model($this->model)->getPk();
		if (I('param.'.$pk)) {
			if ($config == 12) {
				$add_id = D::save($this->model,[$pk => I('param.'.$pk)],array_merge(I('post.'),$args[0]));
			}else{
				$add_id = D::save($this->model,[$pk => I('param.'.$pk)]);
			}
			if ($add_id === false) {
				if ($config == 8) {D()->rollback();}//开启事务模型
				session('token',I('_token'));
				$error = D::error($this->model);
				return ['type' => 'error','msg' => $error?$error:'更新错误'];
			}elseif ($add_id == 0) {
				if ($config == 8) {D()->rollback();}//开启事务模型
				session('token',I('_token'));
				return ['type' => 'error','msg' => '未作出修改'];
			}else{
				if ($config == 8) {D()->commit();}//开启事务模型
				$href_url = I('param.__GO__')?I('param.__GO__'):$_SERVER["HTTP_REFERER"];
				return ['type' => 'success','msg' => $Controller->success['update']?$Controller->success['update']:'修改成功','url' => $href_url];
			}
		}elseif ($config == 11) {
			foreach (I('post.') as $key => $value) {
				$pk = D::model($key)->getPk();
				if (isset($value[$pk])) {
					$add_ids[] = D::save($key,[$pk => $value[$pk]],$value);
				}else{
					$add_ids[] = D::add($key,$value);
				}
			}
			if ($config == 8) {D()->commit();}//开启事务模型
			$href_url = I('param.__GO__')?I('param.__GO__'):$_SERVER["HTTP_REFERER"];
			$msg = in_array(false, $add_ids)?'部分修改成功':'修改成功';
			return ['type' => 'success','msg' => $Controller->success['update']?$Controller->success['update']:$msg,'url' => $href_url];
		}else{
			if ($config == 8) {D()->rollback();}//开启事务模型
			session('token',I('_token'));
			return ['type' => 'error','msg' => '未传入有效主键，无法修改'];
		}
	}
	/**
	 * [delete description]
	 * @Author   yin~
	 * @DateTime 2017-04-11
	 * @Function []
	 * @param    [type]     $Controller [description]
	 * @param    [type]     $args       [
	 * 传入格式  array  = 伪删除['字段名' => '值']的格式
	 * ]
	 * @return   [type]                 [description]
	 */
	public function delete($Controller,$args)
	{
		$token = C('TOKEN_VALIDATE')?C('TOKEN_VALIDATE'):'off';
		if ($token != 'off') {
			if (!$this->validate_token()) return ['type' => 'error','msg' => '页面已过期'];
		}
		self::before_func($Controller,'delete');//前置方法执行
		$config = ($args)?$this->check_args('delete',$args):self::STATE_RETURN;
		if ($config == 13) {
			session('token',I('_token'));
			$return = $this->update($Controller,$args);
			if ($return['type'] == 'success') {
				$return['msg'] = $Controller->success['delete']?$Controller->success['delete']:'删除成功';
			}
			return $return;
		}else{
			$pk = D::model($this->model)->getPk();
			$flag = D::delete($this->model,I('param.'.$pk));
			if ($flag) {
				$href_url = I('param.__GO__')?I('param.__GO__'):$_SERVER["HTTP_REFERER"];
				$msg = in_array(false, $add_ids)?'部分修改成功':'修改成功';
				return ['type' => 'success','msg' => $Controller->success['delete']?$Controller->success['delete']:'删除成功','url' => $href_url];
			}else{
				session('token',I('_token'));
				return ['type' => 'error','msg' => '删除失败'];
			}

		}
	}
	/**
	 * [set_token description]
	 * @Author   yin~
	 * @DateTime 2017-04-10
	 * @Function [token验证生成]
	 */
	public static function set_token()
	{
		session('token',md5(microtime(true)));
		return session('token');
	}
	/**
	 * [validate_token description]
	 * @Author   yin~
	 * @DateTime 2017-04-10
	 * @Function [token验证]
	 * @param    string     $value [description]
	 * @return   [type]            [description]
	 */
	private function validate_token()
	{
		$return = I('_token') == session('token')?true:false;
		$this->set_token();
		return $return;
	}
}