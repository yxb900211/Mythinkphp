<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | 蜂蝶旋舞: 492663515 <492663515@qq.com>
// +----------------------------------------------------------------------
namespace Org\Util;
class K {
    protected $model;
    //主模型
    protected $children_model = array();
    //子模型存储位置
    protected $parent_model = array();
    //父模型存储位置
    protected $select_map = array();
    //查询条件生成
    protected $field = array();
    //关联数据字段名称存储位置
    protected $data = array();
    //data()方法缓存数据
    protected $parent = array();
    //
    public function __construct($model)
    {
        $this->model = $this->check_model($model);
        //设置主模型
    }

    public function before()
    {
        return $this->model->where($this->select_map);
    }

    public function select()
    {
        $db = $this->before()->select();
        dump($db);die;
    }

    public function __call($function_name,$args=array())
    {
        $callback = call_user_func(array($this->before(),$function_name),$args[0]);
        if (is_object($callback)) {
            $this->model = $callback;
            return $this;
        }else{
            return $callback;
        }
    }

    public function where($sel = array())
    {
        foreach ($sel as $key => $value) {
            if (is_array($value)) {//过滤无用的条件查询
                $middle    = str_replace(array(',','%'),'',$value[1]);
                $sel[$key] = (empty($middle)&&$key != '_complex'&&$middle != 0) ? null : $value ;
            }
            if (strtolower($value[0]) == 'between') {//between算法，传过来之前就不用在判断between条件的2个值都是否存在了
                $between = explode(',', $value[1]);
                if(count(array_filter($between)) < 2){
                    $sel[$key] = ($between[0])?array('egt',$between[0]):null;
                    $sel[$key] = ($between[1])?array('elt',$between[1]):$sel[$key];
                }
            }
        }
        $this->select_map = array_filter($sel,array($this,'delEmpty'));
        return $this;
    }

    protected function check_model($model)
    {
        $Model = D($model);
        if (!is_object($Model)) {
            E($model.'模型错误或模型不存在！'.L('_METHOD_NOT_EXIST_'));
            return false;
        }else{
            if ($Model->getDbError()) {
                E($model.'模型错误或模型不存在！'.L('_METHOD_NOT_EXIST_'));
            }else{
                return $Model;
            }
        }
    }

    // public function add($model_data=array())
    // {
    //     if (I('post.')) {
    //         $form_data = I('post.');
    //     }
    //     foreach ($this->children_model as $key => $value) {
    //         $data = $this->chickFields($value,$form_data);
    //         $user_data = is_array($this->data[$key])?$this->data[$key]:array();
    //         $model[$key] = D($value);
    //         $flag[$key] = $model[$key]->create(array_merge($data,$user_data));
    //         if (!$flag[$key]) {
    //             $this->error = $model[$key]->getError();
    //         }
    //     }
    //     $date = $this->chickFields($this->model,$form_data);
    //     $f = $this->model->create(array_merge($date,$model_data));
    //     if ($this->model->getError()) {
    //         $this->error = $this->model->getError();
    //     }
    //     if (empty($this->error)) {
    //         foreach ($model as $k => $table) {
    //             $table->create(array_merge($flag[$k],$this->chickFields($table,$this->parent)));
    //             $this->parent[$this->field[$k]] = $table->add();
    //         }
    //         $this->model->create(array_merge($f,$this->chickFields($this->model,$this->parent)));
    //         $re = $this->model->add();
    //         return $re;
    //     }else{
    //         return false;
    //     }
    // }
    // public function chickFields($model,$form_data="")
    // {
    //     $m = is_object($model)?$model:D($model);
    //     $fields = $m->getDbFields();
    //     foreach ($form_data as $k => $post) {
    //         if (in_array($k,$fields)) {
    //             $data[$k] = $post;
    //         }else{
    //             $k = explode('>',$k);
    //             if (strtolower($k[0]) == strtolower($model)) {
    //                 if (in_array($k[1],$fields)) {
    //                     $data[$k[1]] = $post;
    //                 }
    //             }
    //         }
    //     }
    //     return $data;
    // }

    public function getError()
    {
        return $this->error;
    }

    public function delEmpty($value='')
    {
        if ($value != null || $value == '0')
        {
            return true;
        }
        return false;
    }



}
?>