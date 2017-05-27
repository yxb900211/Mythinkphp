<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 首页控制器
 */
class UploadController extends AdminCommonController {
	public function zyupload()
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
        	if (I('post.tailor') != 'false') {
        		$tailor = I('post.tailor');
        		$tailor = str_replace('\'', '"', $tailor);
        		$size = json_decode($tailor,true);
        		$img_url = './Uploads'.$info['file']['savepath'].$info['file']['savename'];
        		$image = new \Think\Image();
        		$image ->open($img_url);
        		$image ->crop($size['width'],$size['height'],$size['leftX'],$size['leftY'])->save($img_url);
        	}
            $info['status'] = 'true';
        }
        $this->ajaxReturn($info);
    }

}