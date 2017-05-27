<?php
require_once('tcpdf.php');
/**
 * 继承tcpdf插件子类 定义页眉页脚
 */
class MYPDF extends TCPDF {
    public function Header() {
        $this->SetY(12);
        $this->SetFont('simfang', '', 10);
        $this->SetX(20);
        if ($this->page != 1) {
            $this->Cell(140, 1, $this->company, 'B', false, 'L', 0, '', 0, false, 'M', 'M');
            $this->Cell(30, 1, $this->number, 'B', false, 'R', 0, '', 0, false, 'M', 'M');
        }
        
        $this->endTemplate();
    }
    public function Footer() {
        $this->SetY(-12);
        $this->SetX(20);
        $this->SetFont('simfang', '', 8);
        if ($this->page != 1) {
            $this->Cell(0, 10, '第 '.$this->getAliasNumPage().' 页  共 '.$this->getAliasNbPages().' 页', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        }
    }
    public function setHeaderData_user($company,$number)
    {
        $this->company = $company;
        $this->number  = $number;
    }
}
?>