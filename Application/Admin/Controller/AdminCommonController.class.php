<?php
namespace Admin\Controller;//注意命名空间
use Think\Controller;
use Think\D;
/**
 * +--------------------------------------------------------------------------+
 * | Common公共控制器
 * +--------------------------------------------------------------------------+
 * | 引用条件，需要引用新的page类一起连用，page类文件放置在\Org\Util下
 * +--------------------------------------------------------------------------+
 * | _initialize()方法为引用时自动执行，内含跳转到登陆页方法，不用可以删掉
 * +--------------------------------------------------------------------------+
 * | 制作人：蜂蝶旋舞 QQ:492663515 版本号：V12.26
 * +--------------------------------------------------------------------------+
 */
class AdminCommonController extends Controller {
    public $model = CONTROLLER_NAME;//模型名称
    public $data = array();
    protected $list;
	/**
	 * 用户跳转到登陆页面操作
	 * 不需要可以屏蔽掉_initialize()方法代码
	 */
	protected function _initialize()
	{
		if(!session('users'))
		{
			// $this->redirect('/', '', 0, '页面跳转中...');
        }else{
            // $this->get_rules();
            // $this->check_rules();
        }
        $num = F('num')?F('num'):C('PAGE_NUMBER');
        C('PAGE_NUMBER',$num);
        $this->_getStatus(C('OPEN_STATUS'));
    }

    public function index($assign = 'db')
    {
        return $this->data = $this->_list()->_showSQL($assign);
    }
    /**
     * 过滤查询条件
     */
    private function _filter_maps($sel)
    {
         // dump($sel);die;
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
        return array_filter($sel,array($this,'delEmpty'));
    }
    /**
     * 查询操作
     * @param  array  $list ['model'  => 模型名称,
     *                       'assign' => 传输到页面的名称,
     *                       'order'  => 排序字段,
     *                       'sel'    => 查询信息,
     *                       'join'   => 多表查询关系,
     *                       'sort'   => 排序方式,
     *                       'return' => 布尔真为回传数据到控制器否则直接到页面，默认false，
     *                        ]
     */
    private function _list()
    {
        if (method_exists($this,'_select')){
            $this->_select($list);
        }
    	$this->list['join']    = $list['join']?$list['join']:null;
    	$this->list['field']   = $list['field']?$list['field']:'*';
    	$this->list['order']   = $list['order']?$list['order']:null;
        $sel2 = array();
    	//获取查询条件
        if($this->del_field){
            foreach ($this->del_field as $key => $value) {
                $sel[$key] = array('neq',$value);
            }
        }else if ($this->status) {
            $status = $this->status;
            $sel[$status[0]] = array('neq',$status[1]);
        }
        //
        foreach ($list['sel'] as $key => $value) {
            $sel[$key] = $value;
        }
        if ($sel) {
            $this->list['sel'] = $this->_filter_maps($sel);
        }
        // dump($list);die;
        return $this;
        //数据生成并传输掉页面，可以更改成
        //就可以在后台控制器继续对数据进行操作更改
    }
    /**
     * 组合sql语句并且输出
     */
    private function _showSQL($assign)
    {
        $Model = M($this->model);
        if (!is_object($Model)) {
            $this->error('模型错误');
        }
        $this->list['order'] = $this->list['order']?$this->list['order']:$Model->getPk().' desc';
        if ($this->list['join']) {
            $count = $Model->where($this->list['sel'])->join($this->list['join'])->count();
        }else{
            $count = $Model->where($this->list['sel'])->count();
        }
        $page   = new \Org\Util\Page($count,C('PAGE_NUMBER'));
        // dump($page);die;
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
        $limit = $page->firstRow.','.$page->listRows;
        if ($this->list['join']) {
            $db = D::get($this->model,[
                'field' => $this->list['field'],
                'order' => $this->list['order'],
                'limit' => $limit,
                'join'  => $this->list['join'],
                'where' => $this->list['sel'],
                ]);

        }else{
            $db = D::get($this->model,[
                'field' => $this->list['field'],
                'order' => $this->list['order'],
                'limit' => $limit,
                'where' => $this->list['sel'],
                ]);
        }
        // dump($Model -> _sql());die;
        $this->assign('page',$page->show());
        if ($assign === false) {
            return $db;
        }else{
            $this->assign($assign,$db);
            $this->display();
        }

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
                        $data[$key][$filed.'_re'] = $db[$need];
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
                        $data[$filed.'_re'] = $db[$need];
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
    public function upload_add()
    {
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
            return $InData;
        }else{
            return false;
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
        $data = array();
        if (method_exists($this,'_insert')){
            $this->_insert($data);
        }
        if ($_FILES) {
            $upData = $this->upload_add($_FILES);
        }else{
            $upData = array();
        }
        $InData = array_merge(I('post.'),$data,$upData);
        //原始文件名结束
        if ($Model->create($InData)) {
            $NewId = $Model->add();
            if ($NewId) {
                if (method_exists($this,'after_insert')){//增加后对此ID进行的操作
                    $this->after_insert($NewId);
                }
                $this->assign('NewId',$NewId);
                $success = $this->success;
                $echo = $success['insert']?$success['insert']:'添加数据成功!';
                if (I('param.__GO__')) {
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
            if (IS_AJAX) {
                $this->ajaxReturn($db);
            }else{
                $this->assign('db',$db);
                $this->display();
            }
        }else{
            $this->error('查询不到任何数据,请检查查询条件');
        }
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
        $data = array();
        if (method_exists($this,'_save')){
            $this->_save($data);
        }
        // $InData = array_merge(I('post.'),$data);
        if ($_FILES) {
            $upData = $this->upload_add($_FILES);
        }else{
            $upData = array();
        }
        $InData = array_merge(I('post.'),$data,$upData);
        //原始文件名结束
        if ($Model->create($InData)) {
            //上传算法位置，等待之后更新
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
                if ($this->del_field){//外部设置删除字段的值
                    $data = $this->del_field;
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
                    'rootPath' => './Uploads/',
                    'savePath' => '/Uploads/',
                    'saveName' => array('uniqid',''),
                    'exts'     => array('jpg', 'gif', 'png', 'jpeg'),
                    'autoSub'  => true,
                    'subName'  => array('date','Ymd'),
                    );//默认上传配置
        $upload = new \Think\Upload($config);
        $info   = $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $info['status'] = 'false';
            $info['error'] = $upload->getError();
        }else{// 上传成功 获取上传文件信息\
            $info['status'] = 'true';
        }
        $this->ajaxReturn($info);
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
    // public function check_rules()
    // {
    //     $Model = M('Group');
    //     $all = $Model->where('id=1')->find();
    //     $all = explode(',',$all['group_lv2']);
    //     $sess = session('users');
    //     $now = $Model->where('id='.$sess['group_id'])->find();
    //     foreach ($all as $key => $value) {
    //          if (in_array($value,explode(',',$now['group_lv2']))) {
    //              unset($all[$key]);
    //          }
    //     }
    //     if (in_array(strtolower(CONTROLLER_NAME),$all)) {
    //         $this->error('抱歉,您无权访问此页面！');
    //     }
    // }
    // public function get_rules()
    // {
    //     $Model = M('Group');
    //     $sess  = session('users');
    //     $rules = $Model->field('group_lv1,group_lv2')->where('id='.$sess['group_id'])->find();
    //     $this->assign('group',$rules);
    // }
    public function delEmpty($value='')
    {
        if ($value != null || $value == '0')
        {
            return true;
        }
        return false;

    }
    /**
     * 二维码生成
     */
    public function QRcode_url($rid)
    {
        vendor("phpqrcode.phpqrcode");
        $data = 'http://'.$_SERVER['SERVER_NAME'].U('ShowQrcode/showpage?rid='.$rid);
        $level = 'H';
        $size = 4;
        $QRcode = new \QRcode();
        $src = './Report/qrcode/'.$rid.time().'.png';
        // dump($src);
        $a = M('Report')->where('id='.$rid)->setField('qrcode',$src);
        // dump($a);die;
        $QRcode->png($data,$src,$level,$size);//变量$src为false直接生成
    }

}
