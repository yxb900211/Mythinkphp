<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * 首页控制器
 */
class TcpdfController extends AdminCommonController {
	public function newpdf()
	{
		$post = I('post.');
		vendor('Tcpdf.mypdf');
	    $pdf = new \mypdf();
	    $pdf->SetMargins(0,0,0);
	    $pdf->setHeaderData_user($post['left'],$post['right']);
	    $pdf->setPrintHeader(true);
	    $pdf->setPrintFooter(true);
	    for ($i=0; $i < $post['page'] ; $i++) { 
	    	$pdf->AddPage();
	    	// $pdf->Image('url, 0,0, 210, 0,'','','C');
	    	// 可以设置背景图
	    	$pdf->setFont('simfang','',18);
	    	$pdf->writeHTMLCell(0, 0, 18, 80, $post['content']);
	    	//支持部分简单html语言输出，writeHTMLCell(宽度, 高度, X轴坐标, Y轴坐标, $post['content']);
	    	//坐标以mm为单位，A4纸张标准大小为210mm*279mm;

	    }
	    $pdf->Output();
	    //$pdf->Output('filename.pdf','D'); 为直接下载
	}
}