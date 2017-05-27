<?php
header('Content-Type:text/html;Charset=utf-8;');

include "xcrypt.php";

echo '<pre>';
//////////////////////////////////////
$a = isset($_GET['a']) ? $_GET['a'] : '测试123';

//密钥
$key = '12345678123456781234567812345678'; //256 bit
$key = '1234567812345678'; //128 bit
$key = '12345678'; //64 bit

//设置模式和IV
$m = new Xcrypt($key, 'cbc', 'auto');

//获取向量值
echo '向量：';
var_dump($m->getIV());

//加密
$b = $m->encrypt($a, 'base64');
//解密
$c = $m->decrypt($b, 'base64');

echo '加密后：';
var_dump($b);
echo '解密后：';
var_dump($c);


/////////////////////////////////////////
echo '</pre>';