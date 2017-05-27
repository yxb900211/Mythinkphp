<?php
namespace Home\Model;
use Think\Model\ViewModel;
class TestLinkModel extends ViewModel {
	public $viewFields = array(     
		'Test'=>array('id'),     
		'Name'=>array('name'=>'name', '_on'=>'Test.name_id=Name.id'),     
		'Class'=>array('class'=>'class', '_on'=>'Test.class_id=Class.id'),   
		);
}