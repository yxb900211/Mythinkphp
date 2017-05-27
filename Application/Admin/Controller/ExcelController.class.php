<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 首页控制器
 */
class ExcelController extends AdminCommonController {
	public $model = 'Users';//设置模型名称
	public $del_field = array('status' => 'del');//设置逻辑删除字段,也可在config内统一设置
	public $success = array(
		'insert' => '欢迎加入~~',
		'update' => '为您更新了信息哦~~',
		'del'    => '信息已经扔掉了~~',
		);

	/**
	 * [export description]
	 * 导出excel文件操作
	 */
	public function export()
	{
		$db = D('Users')->get_all();
		ob_end_clean();
        $xlsName  = date('Y-m-d_H:i:s',time());//文件名称
        $xlsCell  = array(//文件第一排注释
	        array('id','ID'),
	        array('username','用户名'),
	        array('password','密码（md5）'),
	        array('add_time','创建时间'),
	        );
        	foreach ($db as $key => $value) {
        		$db[$key]['add_time'] = date('Y-m-d',$value['add_time']);
        	}
    	exportExcel($xlsName,$xlsCell,$db);
	}
	public function import(){
		echo '文件为例子文件，应上传文件获取地址后，以importExcel($src)函数打开即可获得数据';
        $data=importExcel('./2016-12-27_16-40-08.xls');//上传的需要导入的文件，即可获得数据
        dump($data);die;
    }
}