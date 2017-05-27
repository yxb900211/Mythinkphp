<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 首页控制器
 */
class QrcodeController extends AdminCommonController {
	private $url;
	public function index()
	{
		if (IS_POST) {
			$this->assign('flag','true');
		}
		$this->display();
	}
	public function qrcode($data,$type = false)
	{
		ob_end_clean();
		vendor("phpqrcode.phpqrcode");
		$data = urldecode($data);  //数据
		$level = 'H';                      //等级
        $size = 4;                         //大小
        $QRcode = new \QRcode();
        // dump($QRcode);die;
        $QRcode->png($data,$type,$level,$size);
	}
	public function qrcode_save($data)
	{
		$this->qrcode($data,'./Qrcode/'.$data.'.png');
		$this->success('保存成功');
	}

}