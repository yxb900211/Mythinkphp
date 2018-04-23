<?php
namespace Think;
/**
 * +--------------------------------------------------------------------------+
 * | D模型方法封装类
 * +--------------------------------------------------------------------------+
 * | 引用方法：在控制器命名空间下 引用该类'use Think/D;'即可使用
 * +--------------------------------------------------------------------------+
 * | 引用环境：此类基于TP3.2 Model类 DB类 function D()方法有效性执行
 * +--------------------------------------------------------------------------+
 * | 制作人：蜂蝶旋舞 QQ:492663515 版本号：V04.06
 * +--------------------------------------------------------------------------+
 */
class D {
	//用户当前模型
	private $model;
	//方法大全
	public static $func = ['model','get','find','lists','count','query','add','field','save','set','inc','dec','delete'];
	/**
	 * [__construct description]
	 * @Author   yin~
	 * @DateTime 2017-03-16
	 * @Function [构造函数]
	 * @param    string     $name  [需要实例化的模型名称]
	 * @param    string     $alias [别名]不是必须变量
	 */
	private function __construct($name='',$alias='')
	{
		if (empty($alias)) {
			$this->model = D($name);
		}else{
			$this->model = D($name)->alias($alias);
		}
	}
	/**
	 * [__call description]
	 * @Author   yin~
	 * @DateTime 2017-03-16
	 * @Function [重载魔术函数]
	 * @param    string     $name  [需要实例化的模型名称]
	 * @param  [type] $function_name [description]
	 * @param  [type] $argments      [description]
	 * @return [type]                [description]
	 */
	public function __call($function_name,$argments)
	{
		return call_user_func_array([$this->model,$function_name],$argments);
		// dump($a);
	}
	/**
	 * [__callStatic description]
	 * @param  [type] $function_name [description]
	 * @param  [type] $argments      [description]
	 * @return [type]                [description]
	 */
	public static function __callStatic($function_name,$argments)
	{
		if ($function_name === 'sql') {
			$model  = self::table($argments[0]);
			return $model->model->_sql();
		}else if ($function_name === 'add') {
			return self::create($argments[0],$argments[1],true);
		}else if ($function_name === 'count') {
			return self::model($argments[0],$argments[1])->model->count();
		}else if ($function_name === 'query') {
			return self::model('',$argments[1])->model->query($argments[0],true);
		}else if ($function_name === 'inc') {
			return self::inc_dec('increase',$argments);
		}else if ($function_name === 'dec') {
			return self::inc_dec('decrease',$argments);
		}
	}
	/**
	 * [get description]
	 * @Author   yin~
	 * @DateTime 2017-03-16
	 * @Function [查询单一数据库内所有数据，或者按照where条件运行]
	 * @param    string     $name [description]
	 * @return   [type]           [description]
	 */
	public static function get($name='',$others='',$sql=true)
	{
		$model  = self::table($name);
		if ($others) $others = $model->set_others($others);
		if ($sql === true) {
			$data = $model->model->select();
		}else if ($sql === false) {
			$data = $model->model->select(false);
			if (substr($data,0,1) != '(' ) {
				$data = '('.$data.')';
			}
		}else if (is_string($sql)) {
			$fields = $model->model->getDbFields();
			if (in_array($sql, $fields)) {
				$data = $model->model->select(['index' => $sql]);
			}else{
				E('非法的数据格式');
			}
		}else{
			E('非法的数据格式');
		}
		return $data;
	}
	/**
	 * [find description]
	 * @Author   yin~
	 * @DateTime 2017-03-16
	 * @Function [查询单一数据库内所有数据，或者按照where条件运行]
	 * @param    string     $name [description]
	 * @return   [type]           [description]
	 */
	public static function find($name='',$others='')
	{
		$model  = self::table($name);
		if ($others) $others = $model->set_others($others);
		$data   = $model->model->find();
		return $data;
	}
	/**
	 * [model description]
	 * @Author   yin~
	 * @DateTime 2017-03-17
	 * @Function [返回数据库对象模型，执行后续操作]
	 * @param  string $name   [description]
	 * @param  string $others [description]
	 * @return [type]         [description]
	 */
	public static function model($name='',$others='')
	{
		$model  = self::table($name);
		if ($others) $others = $model->set_others($others);
		return $model;
	}
	/**
	 * [lists description]
	 * @Author   yin~
	 * @DateTime 2017-03-17
	 * @Function [查询数据库并且以设置字段作为索引返回]
	 * @param    string array     $name   [定义数据库名称以及别名]
	 * @param    string     $key    [查询数据字段，并以,分割开的第一个字段名为数组索引返回
     *								 查询2个字段返回1维数组，查询更多字段返回二维数组]
	 * @param    string     $others [查询表达式]
	 * @return   [type]             [description]
	 */
	public static function lists($name='',$key='',$others='')
	{
		$model  = self::table($name);
		$return = $model->set_keys($key);
		if (isset($return[1])) {
			$index = ['index' => $return[1]];
		}
		if ($others){
			if (is_array($others) && isset($others['field'])) {
				unset($others['field']);
			}
			$model->set_others($others);
		}
		if (isset($return[1])) {
			$data = $model->model->field($key)->select($index);
		}else{
			$data = $model->model->field($key)->select();
		}
		if ($data !== false && strpos($return[0],',') === false) {
			$data = array_map(function($data) use ($return){
				return $data[$return[0]];
			}, $data);
		}else if ($data !== false && !empty($return[1])) {
			$data = array_map(function($data) use ($return){
				unset($data[$return[1]]);
				return $data;
			},$data);
		}
		return $data;
	}
	/**
	 * [field description]
	 * @Author   yin~
	 * @DateTime 2017-04-01
	 * @Function [快速查找字段]
	 * @param    string     $name_field [传入格式 '表名.表内字段']
	 * @param    string     $others     [查询条件插入]
	 * @param    boolean    $bool       [布尔值，默认false，设置为真 类似lists方法的单字段查找]
	 * @return   [type]                 [description]
	 */
	public static function field($name_field='',$others='',$bool=false)
	{
		$position = strpos($name_field,'.');
		if (!$position) {
			return self::find($name_field,$others);
		}else{
			$name = substr($name_field,0,$position);
			$field = substr($name_field,($position + 1));
			$model  = self::table($name);
			if ($others) $others = $model->set_others($others);
			return $model->getField($field,$bool);
		}

	}
	/**
	 * [table description]
	 * @Author   yin~
	 * @DateTime 2017-03-16
	 * @Function [设置模型]
	 * @param    string     $name [description]
	 */
	public static function table($name='')
	{
		if (is_string($name)) {
			return new self($name);
		}else if (is_array($name)) {
			return new self($name[0],$name[1]);
		}else{
			return false;
		}
	}
	/**
	 * [set_others description]
	 * @Author   yin~
	 * @DateTime 2017-03-16
	 * @Function [找到用户的传进变量，并解析]
	 * @param    string     $others [description]
	 */
	private function set_others($others='')
	{
		$type = gettype($others);
		switch ($type) {
			case 'integer':
			    $this->_integer($others);
				break;
			case 'string':
				$this->_string($others);
				break;
			case 'array':
			    $this->_array($others);
				break;
			default:
				E('非法的数据类型');
				break;
		}
	}
	/**
	 * [set_keys description]
	 * @Author   yin~
	 * @DateTime 2017-03-17
	 * @Function [检查变量$key是否合理]
	 * @param    string     $key [description]
	 */
	private function set_keys($key = '')
	{
		$type = gettype($key);
		switch ($type) {
			case 'string':
				$flag = strpos($key,',');
				if ($flag) {
					$index = substr($key,0,$flag);
					$field = substr($key,($flag + 1));
				}else{
					$field = $key;
				}
				break;
			default:
				E('非法的数据类型');
				break;
		}
		return [$field,$index];
	}
	/**
	 * [_integer description]
	 * @Author   yin~
	 * @DateTime 2017-03-16
	 * @Function [整数类型变量的执行方法]
	 * @param    [type]     $int [description]
	 * @return   [type]          [description]
	 */
	private function _integer($int)
	{
		$key = $this->model->getPk();
		$where[$key] = $int;
		$sqls['where'] = $where;
		$this->_sqls($sqls);
	}
	/**
	 * [_string description]
	 * @Author   yin~
	 * @DateTime 2017-03-16
	 * @Function [字符串类型的执行方法]
	 * @param    [type]     $string [description]
	 * @return   [type]             [description]
	 */
	private function _string($string)
	{
		if (is_numeric($string)) {
			$this->_integer($string);
		}else{
			$sqls['where'] = $string;
			$this->_sqls($sqls);
		}
	}
	/**
	 * [_array description]
	 * @Author   yin~
	 * @DateTime 2017-03-16
	 * @Function [数组类型的执行方法，解析数组并取得进一步操作]
	 * @param    [type]     $array [description]
	 * @return   [type]            [description]
	 */
	private function _array($array)
	{
		$keys   = array_keys($array);
		$sqls = ['where','alias','field','join','limit','order','group','table','data','having','union','distinct'];
		$fields = $this->model->getDbFields();
		$flags = array_map(function($data) use ($sqls,$fields){
			$return = 'fields';
			$check = strpos($data,'.');
			if ($check) $data = substr($data,($check + 1));
			if (!in_array($data, $fields)) {
				if (!in_array($data, $sqls)) {
					$return = false;
				}else{
					$return = 'sqls';
				}
			}
			return $return;
		}, $keys);
		if (in_array(false,$flags)) {
			E('错误的附加条件');
		}else if (in_array('sqls',$flags)) {
			$this->_sqls($array);
		}else if (in_array('fields',$flags)) {
			$sqls['where'] = $array;
			$this->_sqls($sqls);
		}else{
			E('错误的附加条件');
		}
	}
	/**
	 * [create description]
	 * @Author   yin~
	 * @DateTime 2017-03-16
	 * @Function [数据的快速写入方法]
	 * @param  string  $table [写入表名称]
	 * @param  array   $data  [支持1维2维数组，2维数组可多条插入]
	 * @param  boolean $type  [description]
	 * @return [type]         [description]
	 */
	public static function create($table = '',$data = array(),$type = false)
	{
		$model = self::table($table);
		if (count($data) === count($data,1)) {
			$flag = $model->model->create($data);
			if ($flag !== false && $type === true) {
				return $model->model->add();
			}else if ($flag !== false && $type === false) {
				return $model->model;
			}else{
				return false;
			}
		}else{
			$data = array_map(function($data) use ($model) {
				return $model->model->create($data);
			},$data);
			if (in_array(false,$data)) {
				return false;
			}else{
				return $model->model->addAll(array_values($data));
			}
		}
	}
	/**
	 * [create description]
	 * @Author   yin~
	 * @DateTime 2017-04-01
	 * @Function []
	 * @param    string     $table [description]
	 * @param    array      $data  [description]
	 * @param    boolean    $type  [description]
	 * @return   [type]            [description]
	 */
	public static function save($table = '',$others='',$data = array())
	{
		$model = self::table($table);
		$flag = $model->model->create($data,2);
		if ($flag) {
			if (!empty($others)) {
				$others = $model->set_others($others);
				return $model->model->save();
			}else{
				$key = $model->model->getPk();
				if ($flag[$key]) {
					return $model->model->save();
				}else{
					return E('请传入更新查询条件，或者主键');
				}
			}
		}else{
			return false;
		}
	}
	/**
	 * [set description]
	 * @Author   yin~
	 * @DateTime 2017-05-03
	 * @Function [快速更新字段]
	 * @param    string     $table  [description]
	 * @param    string     $others [description]
	 * @param    array      $data   [description]
	 */
	public static function set($table = '',$others='',$data = '')
	{
		$position = strpos($table,'.');
		if (!$position) {
			E('未设置需要更新字段');
		}else{
			$name = substr($table,0,$position);
			$field = substr($table,($position + 1));
			$model  = self::table($name);
			if ($others) $others = $model->set_others($others);
			return $model->setField($field,$data);
		}
	}
	/**
	 * [delete description]
	 * @Author   yin~
	 * @DateTime 2017-04-06
	 * @Function [数据删除]
	 * @param    string     $table  [description]
	 * @param    string     $others [description]
	 * @param    array      $data   [description]
	 * @return   [type]             [description]
	 */
	public static function delete($table = '',$others='')
	{
		$model = self::table($table);
		$others = $model->set_others($others);
		return $model->model->delete();
	}
	/**
	 * [inc_dec description]
	 * @Author   yin~
	 * @DateTime 2017-04-06 04-12更新
	 * @Function [快速增加修改操作]
	 * @param    string     $type     [description]
	 * @param    [type]     $argments [description]
	 * @return   [type]               [description]
	 * increase decrease方法 传值$table = $argments.0,$others=$argments.1,$num =$argments.2,$data = $argments.3
	 */
	public static function inc_dec($type = '',$argments)
	{
		$position = strpos($argments[0],'.');
		if (!$position) {
			E('未设置需要更新字段');
		}else{
			$name = substr($argments[0],0,$position);
			$field = substr($argments[0],($position + 1));
			$model = self::table($name);
			$flag = true;
			$num = 1;
			if (!empty($argments[1])) $others = $model->set_others($argments[1]);
			if (is_numeric($argments[2])) $num  = $argments[2];
			if (is_array($argments[3])) $flag = self::save($name,$argments[1],$argments[3]);
			if ($flag) {
				switch ($type) {
					case 'increase':return $model->model->setInc($field,$num);break;
					case 'decrease':return $model->model->setDec($field,$num);break;
					default:return false;break;
				}
			}else{
				return false;
			}
		}
	}
	/**
	 * [error description]
	 * @Author   yin~
	 * @DateTime 2017-03-16
	 * @Function [数据的错误信息报出]
	 * @param [type] $argments [description]
	 */
	public static function error($table)
	{
		$model = self::table($table);
		return $model->model->getError();
	}
	/**
	 * [_sqls description]
	 * @Author   yin~
	 * @DateTime 2017-03-16
	 * @Function [数组解析结果不为查询条件结果执行语句]
	 * @param    [type]     $array [description]
	 * @return   [type]            [description]
	 */
	private function _sqls($array)
	{
		$model = $this->model;
		foreach ($array as $func => $value) {
			call_user_func_array([&$model,$func], [$value]);
		}
		$this->model = $model;
	}
}