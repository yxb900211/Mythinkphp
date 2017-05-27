<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * +--------------------------------------------------------------------------+
 * | Common公共控制器 
 * +--------------------------------------------------------------------------+
 * | 引用条件，需要引用新的page类一起连用，page类文件放置在\Org\Util下
 * +--------------------------------------------------------------------------+
 * | _initialize()方法为引用时自动执行，内含跳转到登陆页方法，不用可以删掉
 * +--------------------------------------------------------------------------+
 * | 制作人：蜂蝶旋舞 版本号：V9.30
 * +--------------------------------------------------------------------------+
 */
class CommonController extends Controller {
    public $model = CONTROLLER_NAME;//模型名称
    public $data = array();

	/**
	 * 用户跳转到登陆页面操作
	 * 不需要可以屏蔽掉_initialize()方法代码 
	 */
    
    
	protected function _initialize()
	{
		if(!session('admin'))
		{
			
        }
        $this->_getStatus(C('OPEN_STATUS'));      
    }

    public function index()
    {
        $this->data = $this->_list();
    }
    public function _after_index()
    {
        $this->display();
    }
    /**
     *                                                                         
     * 查询操作
     * @param  array  $list ['model'  => 模型名称,
     *                       'assign' => 传输到页面的名称,
     *                       'order'  => 排序字段,
     *                       'sel'    => 查询信息,
     *                       'join'   => 多表查询关系,
     *                       'sort'   => 排序方式,
     *                       'return' => 布尔真为回传数据到控制器否则直接到页面，默认false，
     *                        ]
     * @return [type]       [description]
     */
    private function _list()
    {
        if (method_exists($this,'_select')){
            $this->_select($list);
        }
    	$model   = $this->model;
        $assign  = $list['assign']?$list['assign']:'db';
    	$join    = $list['join']?$list['join']:null;
    	$field   = $list['field']?$list['field']:'*';
        $return  = $list['return']?$list['return']:false;
    	$Model   = M($model);
        if (!is_object($Model)) {
                    $this->error('模型错误');
                }
    	$order   = $list['order']?$list['order']:$Model->getPk().' desc';
    	$request = array_merge(I('get.'),I('post.'));
    	//取得排序方式
    	$sort = ($request['order'])?$request['order']:$order;
        $sel2 = array();
    	//获取查询条件
        if ($this->status) {
            $status = $this->status;
            $sel[$status[0]] = array('neq',$status[1]);
        }
        foreach ($list['sel'] as $key => $value) {
            $sel[$key] = $value;
        }   
        if ($sel) {
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
            $sel = array_filter($sel,array($this,'delEmpty'));
        }
    	//查询数据
        //查询语句生成使用
        if (empty($return)||!isset($return)||$return == true) {
            if (empty($join)) {
                $db = $Model->field($field)->order($order)->where($sel)->select();
            } else {
                $db = $Model->field($field)->order($order)->join($join)->where($sel)->select();
            }
        }else{
            if (empty($join)) {
                $db = $Model->field($field)->order($order)->limit($return)->where($sel)->select();
            } else {
                $db = $Model->field($field)->order($order)->join($join)->limit($return)->where($sel)->select();
            }
        }
       //echo $Model->_sql();
       //dump($db);die;
        //查询语句生成使用
    	//分页调取
        $this->_echopage($db,$assign='db');
        //数据生成并传输掉页面，可以更改成
        //就可以在后台控制器继续对数据进行操作更改     
    }
    /*查询结束*/
    /**
     * 替换数据中的某个位置
     * @param  [type] $replace [description]
     * @return [type]          [description]
     */
    public function _replace($replace){
        $model  = $replace['model'];//模型
        $data   = $replace['data'];//数据
        $field  = $replace['field'];//要替代的
        $need   = $replace['need'];//输出
        $retain = ($replace['retain'])?$replace['retain']:false;//是否保留原始数据
        $Model = M($model);
        $Pkey  = ($replace['Pkey'])?$replace['Pkey']:$Model->getPk();//被查询的主键
        if(is_array($data[0])){//2为数组
            foreach ($data as $key => $value) {
                $sel[$Pkey] = $value[$field];
                $db = $Model->where($sel)->find();
                if ($db) {
                    if ($retain == true) {
                        $data[$key][$field.'_re'] = $db[$need];
                    }else{
                        $data[$key][$field] = $db[$need];
                    }
                }
            }
        return $data;     
        }else{//非2为数组
            if(is_array($data)){
                $sel[$Pkey] = $data[$field];
                $db = $Model->where($sel)->find();
                if ($db) {
                    if ($retain == true) {
                        $data[$field.'_re'] = $db[$need];
                    }else{
                        $data[$field] = $db[$need];
                    }
                }   
                return $data;
            }else{
                return '传入数组格式不正确，支持一维或者二维数组';
            }
        }
    }
    /**
     * 快速添加算法
     */
    public function add()
    {
        $this->display();
    }
    public function insert()
    {
        $this->_into();
    }
    private function _into()
    {
        $Model = D($this->model);
        if (!is_object($Model)) {
            $this->error('模型错误');
        }
        if (method_exists($this,'_insert')){
            $this->_insert($data);
        }
        $data = array();
        $InData = array_merge(I('post.'),$data);

        foreach ($_FILES as $f) {
            if ($f['name']) {
                $openup[] = true;
            }
        }
        if (in_array(true,$openup)) {
            $info = $this->_upload();
        }
        //获取文件原始文件名，通过C('FILE_REALNAME')配置
        //配置支持数组以及字符串格式。具体可查看config文件内部注释
        if ($info) {
            foreach ($info as $key => $file) {
                $InData[$key] = $file['savepath'].$file['savename'];
                if (C('FILE_REALNAME')) {
                    $realname = C('FILE_REALNAME');
                    if (is_array($realname)) {
                        if (isset($realname[$key])) {
                            $file_real = (substr($realname[$key],0,1) == '_')?$key.$realname[$key]:$realname[$key];
                        }else if (isset($realname['ALL'])) {
                            $file_real = (substr($realname['ALL'],0,1) == '_')?$key.$realname['ALL']:$realname['ALL'];
                        }
                    }else{
                        $file_real = (substr($realname,0,1) == '_')?$key.$realname:$realname;
                    }
                    if ($file_real) {
                        $InData[$file_real] = $file['name'];
                    }      
                }
            }
        }
        //原始文件名结束
        //dump($InData);die;
        if ($Model->create($InData)) {           
            $NewId = $Model->add();
            if ($NewId) {
                $this->assign('NewId',$NewId);
                $success = $this->success;
                $echo = $success['insert']?$success['insert']:'添加数据成功!';
                if (I('param.__GO__')) {
                    //echo "<script>alert('添加数据失败！');</script>";
                    $this->success($echo,I('param.__GO__'));
                }else{
                    $this->success($echo,$_SERVER["HTTP_REFERER"]);
                }
            }else{
                $this->error('未知错误，添加失败。');
            }
        }else{
            $this->error($Model->getError());
        }
    }
    /*添加结束*/
    /**
     * 数据更新
     * @return [type] [description]
     */
    public function edit()
    {
        $Model = D($this->model);
        if (!is_object($Model)) {
            $this->error('模型错误');
        }
        $fields = $Model->getDbFields();
        foreach (I('param.') as $key => $value) {
            if (in_array($key,$fields)) {
                $map[$key] = $value;
            }
        }
        if (!$map) {
            $this->error('没有有效的查询条件，请检查传递的参数');
        }
        $db = $Model->where($map)->find();
        if ($db) {
            $this->assign('db',$db);
        }else{
            $this->error('查询不到任何数据,请检查查询条件');
        }      
        //dump($db);die;
        $this->display();
    }
    public function update()
    {
        $this->_update();
    }
    private function _update()
    {
        $Model = D($this->model);
        if (!is_object($Model)) {
            $this->error('模型错误');
        }
        if (method_exists($this,'_save')){
            $this->_save($data);
        }
        $data = $data?$data:array();
        $InData = array_merge(I('post.'),$data);
        foreach ($_FILES as $f) {
            if ($f['name']) {
                $openup[] = true;
            }
        }
        if (in_array(true,$openup)) {
            $info = $this->_upload();
        }
        //获取文件原始文件名，通过C('FILE_REALNAME')配置
        //配置支持数组以及字符串格式。具体可查看config文件内部注释
        if ($info) {
            foreach ($info as $key => $file) {
                $InData[$key] = $file['savepath'].$file['savename'];
                if (C('FILE_REALNAME')) {
                    $realname = C('FILE_REALNAME');
                    if (is_array($realname)) {
                        if (isset($realname[$key])) {
                            $file_real = (substr($realname[$key],0,1) == '_')?$key.$realname[$key]:$realname[$key];
                        }else if (isset($realname['ALL'])) {
                            $file_real = (substr($realname['ALL'],0,1) == '_')?$key.$realname['ALL']:$realname['ALL'];
                        }
                    }else{
                        $file_real = (substr($realname,0,1) == '_')?$key.$realname:$realname;
                    }
                    if ($file_real) {
                        $InData[$file_real] = $file['name'];
                    }      
                }
            }
        }
        //原始文件名结束
        if ($Model->create($InData)) {
            //上传算法位置，等待之后更新       
            //dump($Model->getPk());die;
            if (!in_array($Model->getPk(),array_keys(I('post.')))) {
                    $this->error('请检查是否传入主键参数！');
                }
            $NewId = $Model->save();
            if ($NewId) {
                $this->assign('NewId',$NewId);
                $success = $this->success;
                $echo = $success['update']?$success['update']:'更新数据成功!';
                if (I('param.__GO__')) {
                    $this->success($echo,I('param.__GO__'));
                }else{
                    $this->success($echo,$_SERVER["HTTP_REFERER"]);
                }
            }else{
                $this->error('未作出修改');
            }
        }else{
            $this->error($Model->getError());
        }

    }
    /*更新结束*/
    /**
     * 数据删除
     * 页面引用del为逻辑删除，不删除数据库数据,逻辑删除请开启逻辑删除模式配置，以及设置相应字段
     * 页面引用del_data为数据删除，彻底删除数据，慎用
     * 支持get,post传值方式，需按照主键传值
     * 传值格式可以多重形势：
     * 例如：id = 1; 或 id = 1,2,3,4,5; 或者 id = array(1,2,3,4,5);
     */
    public function del()
    {
        $this->_delete(false);
    }
    public function del_data()
    {
        $this->_delete(true);
    }
    private function _delete($type)
    {
        if (!is_bool($type)) {
            $this->error('非法操作');
        }
        $Model = D($this->model);
        if (!is_object($Model)) {
            $this->error('模型错误');
        }
        if (is_array(I('param.'.$Model->getPk()))) {
            $map[$Model->getPk()] = array('in',implode(',',I('param.'.$Model->getPk())));
        }else{
            $map[$Model->getPk()] = array('in',I('param.'.$Model->getPk()));
        }
        switch ($type) {
            case false:
                if (method_exists($this,'_status')){//外部设置删除字段的值
                    $this->_status($data);
                }else{
                    $status = $this->status;
                    $data[$status[0]] = $status[1];//默认数据库删除格式字段status = 1为已经删除
                }
                $flag = $Model->where($map)->setField($data);
                break;
            case true:
                $flag = $Model->where($map)->delete();
                break;    
        }
        if ($flag) {
            $success = $this->success;
            $echo = $success['del']?$success['del']:'删除成功！';
            $this->success($echo);
        }else{
            $this->error('未知错误');
        }
    }
    private function _upload()
    {
        $config = C('UPLOAD_CONFIG')?C('UPLOAD_CONFIG')
                  :array(
                    'maxSize'  => 5242880,
                    'savePath' => './Uploads/',
                    'saveName' => array('uniqid',''),
                    'exts'     => array('jpg', 'gif', 'png', 'jpeg'),
                    'autoSub'  => true,
                    'subName'  => array('date','Ymd'),
                    );//默认上传配置
        $upload = new \Think\Upload($config);
        $info   = $upload->upload();
        if(!$info) {// 上传错误提示错误信息 
            $this->error($upload->getError());
        }else{// 上传成功 获取上传文件信息\
            return $info;
        }
    }
    public function ajax_upload()
    {
        $config = C('UPLOAD_CONFIG')?C('UPLOAD_CONFIG')
                  :array(
                    'maxSize'  => 5242880,
                    'savePath' => './Uploads/',
                    'saveName' => array('uniqid',''),
                    'exts'     => array('jpg', 'gif', 'png', 'jpeg'),
                    'autoSub'  => true,
                    'subName'  => array('date','Ymd'),
                    );//默认上传配置
        $upload = new \Think\Upload($config);
        $info   = $upload->upload();
        if(!$info) {// 上传错误提示错误信息 
            echo 'alert("'.$upload->getError().'");';
        }else{// 上传成功 获取上传文件信息\
            foreach ($info as $key => $file) {
                if (C('FILE_REALNAME')) {
                    $realname = C('FILE_REALNAME');
                    if (is_array($realname)) {
                        if (isset($realname[$key])) {
                            $file_real = (substr($realname[$key],0,1) == '_')?$key.$realname[$key]:$realname[$key];
                        }else if (isset($realname['ALL'])) {
                            $file_real = (substr($realname['ALL'],0,1) == '_')?$key.$realname['ALL']:$realname['ALL'];
                        }
                    }else{
                        $file_real = (substr($realname,0,1) == '_')?$key.$realname:$realname;
                    }
                    if ($file_real) {
                        $info[$key]['field'] = $file_real;
                    }      
                }
            }
            $this->ajaxReturn($info);
        }
    }
    /**
     * [_getStatus description]
     * 获取不显示的状态条件，逻辑删除的删除条件
     */
    private function _getStatus($switch = true)
    {
        if ($switch == true) {
            $Model = $this->model;
           // dump($Model);
            if (C('DEL_STATUS')) {
                if(is_array(C('DEL_STATUS'))){
                    $config = C('DEL_STATUS');
                    if (isset($config[$Model])) {
                        $status = explode(',',$config[$Model]); 
                    }else if (isset($config['ALL'])) {
                        $status = explode(',',$config['ALL']); 
                    }
                }else{
                    $status = explode(',',C('DEL_STATUS'));   
                }
            }
        }
        $this->status = $status;
    }
    /**
     * [_echopage description]
     * 单独分页调取
     * 传入参数(需要分页的数据包[,页面调取变量名默认'db')
     */
    public function _echopage($data,$assign='db',$return=false)
    {
        $page   = new \Org\Util\Page($data,C('PAGE_NUMBER'));//实例化分页
        //调取config配置‘PAGE_CONFIG’对page显示方式进行更改
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
        //分页调取结束  
        $this->assign('page',$page->show());
        if ($return) {
            return $page->data();
        }else{
            $this->assign($assign,$page->data());
        }
        
    }
    public function check_rules()
    {
        $Model = M('AdminGroup');
        $all = $Model->where('id=1')->find();
        $all = explode(',',$all['rules']);
        $sess = session('admin');
        $now = $Model->where('id='.$sess['group_id'])->find();
        foreach ($all as $key => $value) {
             if (in_array($value,explode(',',$now['rules']))) {
                 unset($all[$key]);
             }
        }
       // dump($all);die;
        if (in_array(strtolower(CONTROLLER_NAME),$all)) {
            $this->error('抱歉,您无权访问此页面！');
        }
        
    }
    /**
     * 数据导入导出
     * @param  文件名
     * @param  数据库标题字段名
     * @param  数据
     */
    public function exportExcel($expTitle="",$expCellName,$expTableData){
        $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
        $fileName = $xlsTitle?$xlsTitle:$_SESSION['account'].date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
        $cellNum = count($expCellName);
        $dataNum = count($expTableData);
        vendor("PHPExcel.PHPExcel");
        $objPHPExcel = new \PHPExcel();
        $cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');
        $objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
        for($i=0;$i<$cellNum;$i++){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]); 
        }  
        for($i=0;$i<$dataNum;$i++){
          for($j=0;$j<$cellNum;$j++){
            $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
          }             
        }  
        header('pragma:public');
        header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
        header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  
        $objWriter->save('php://output'); 
        exit;   
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
