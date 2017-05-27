<?php
/**
 * 获取客户端IP
 * @return [string] [description]
 */
function getClientIp() {
 $ip = NULL;
 if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
  $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
  $pos = array_search('unknown',$arr);
  if(false !== $pos) unset($arr[$pos]);
  $ip = trim($arr[0]);
 }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
  $ip = $_SERVER['HTTP_CLIENT_IP'];
 }elseif (isset($_SERVER['REMOTE_ADDR'])) {
  $ip = $_SERVER['REMOTE_ADDR'];
 }
 // IP地址合法验证
 $ip = (false !== ip2long($ip)) ? $ip : '0.0.0.0';
 return $ip;
}
 
/**
 * 获取在线IP
 * @return String
 */
function getOnlineIp($format=0) {
 global $S_GLOBAL;
 if(empty($S_GLOBAL['onlineip'])) {
  if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
   $onlineip = getenv('HTTP_CLIENT_IP');
  } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
   $onlineip = getenv('HTTP_X_FORWARDED_FOR');
  } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
   $onlineip = getenv('REMOTE_ADDR');
  } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
   $onlineip = $_SERVER['REMOTE_ADDR'];
  }
  preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
  $S_GLOBAL['onlineip'] = $onlineipmatches[0] ? $onlineipmatches[0] : 'unknown';
 }
 
 if($format) {
  $ips = explode('.', $S_GLOBAL['onlineip']);
  for($i=0;$i<3;$i++) {
   $ips[$i] = intval($ips[$i]);
  }
  return sprintf('%03d%03d%03d', $ips[0], $ips[1], $ips[2]);
 } else {
  return $S_GLOBAL['onlineip'];
 }
}
 
 
 
/**
 * 获取url
 * @return [type] [description]
 */
function getUrl(){
 $pageURL = 'http';
 if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
 $pageURL .= "s";
 }
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
 $pageURL .= $_SERVER["HTTP_HOST"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
 } else {
 $pageURL .= $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}
 
/**
 * 获取当前站点的访问路径根目录
 * @return [type] [description]
 */
function getSiteUrl() {
 $uri = $_SERVER['REQUEST_URI']?$_SERVER['REQUEST_URI']:($_SERVER['PHP_SELF']?$_SERVER['PHP_SELF']:$_SERVER['SCRIPT_NAME']);
 return 'http://'.$_SERVER['HTTP_HOST'].substr($uri, 0, strrpos($uri, '/')+1);
}
 
 
 
/**
 * 字符串截取，支持中文和其他编码
 * @param [string] $str  [字符串]
 * @param integer $start [起始位置]
 * @param integer $length [截取长度]
 * @param string $charset [字符串编码]
 * @param boolean $suffix [是否有省略号]
 * @return [type]   [description]
 */
function msubstr($str, $start=0, $length=15, $charset="utf-8", $suffix=true) {
 if(function_exists("mb_substr")) {
  return mb_substr($str, $start, $length, $charset);
 } elseif(function_exists('iconv_substr')) {
  return iconv_substr($str,$start,$length,$charset);
 }
 $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
 $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
 $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
 $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
 preg_match_all($re[$charset], $str, $match);
 $slice = join("",array_slice($match[0], $start, $length));
 if($suffix) {
  return $slice."…";
 }
 return $slice;
}
 
/**
 * php 实现js escape 函数
 * @param [type] $string [description]
 * @param string $encoding [description]
 * @return [type]   [description]
 */
function escape($string, $encoding = 'UTF-8'){
 $return = null;
 for ($x = 0; $x < mb_strlen($string, $encoding);$x ++)
 {
 $str = mb_substr($string, $x, 1, $encoding);
 if (strlen($str) > 1) { // 多字节字符
  $return .= "%u" . strtoupper(bin2hex(mb_convert_encoding($str, 'UCS-2', $encoding)));
 } else {
  $return .= "%" . strtoupper(bin2hex($str));
 }
 }
 return $return;
}
/**
 * php 实现 js unescape函数
 * @param [type] $str [description]
 * @return [type]  [description]
 */
function unescape($str) {
 $str = rawurldecode($str);
 preg_match_all("/(?:%u.{4})|.{4};|&#\d+;|.+/U",$str,$r);
 $ar = $r[0];
 foreach($ar as $k=>$v) {
  if(substr($v,0,2) == "%u"){
   $ar[$k] = iconv("UCS-2","utf-8//IGNORE",pack("H4",substr($v,-4)));
  } elseif(substr($v,0,3) == "") {
   $ar[$k] = iconv("UCS-2","utf-8",pack("H4",substr($v,3,-1)));
  } elseif(substr($v,0,2) == "&#") {
   echo substr($v,2,-1)."";
   $ar[$k] = iconv("UCS-2","utf-8",pack("n",substr($v,2,-1)));
  }
 }
 return join("",$ar);
}
 
/**
 * 数字转人名币
 * @param [type] $num [description]
 * @return [type]  [description]
 */
function num2rmb ($num) {
 $c1 = "零壹贰叁肆伍陆柒捌玖";
 $c2 = "分角元拾佰仟万拾佰仟亿";
 $num = round($num, 2);
 $num = $num * 100;
 if (strlen($num) > 10) {
  return "oh,sorry,the number is too long!";
 }
 $i = 0;
 $c = "";
 while (1) {
  if ($i == 0) {
   $n = substr($num, strlen($num)-1, 1);
  } else {
   $n = $num % 10;
  }
  $p1 = substr($c1, 3 * $n, 3);
  $p2 = substr($c2, 3 * $i, 3);
  if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元'))) {
   $c = $p1 . $p2 . $c;
  } else {
   $c = $p1 . $c;
  }
  $i = $i + 1;
  $num = $num / 10;
  $num = (int)$num;
  if ($num == 0) {
   break;
  }
 }
 $j = 0;
 $slen = strlen($c);
 while ($j < $slen) {
  $m = substr($c, $j, 6);
  if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
   $left = substr($c, 0, $j);
   $right = substr($c, $j + 3);
   $c = $left . $right;
   $j = $j-3;
   $slen = $slen-3;
  }
  $j = $j + 3;
 }
 if (substr($c, strlen($c)-3, 3) == '零') {
  $c = substr($c, 0, strlen($c)-3);
 } // if there is a '0' on the end , chop it out
 return $c . "整";
}
 
/**
 * 特殊的字符
 * @param [type] $str [description]
 * @return [type]  [description]
 */
function makeSemiangle($str) {
 $arr = array(
  '０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4',
  '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
  'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E',
  'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',
  'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O',
  'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',
  'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y',
  'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
  'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i',
  'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',
  'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's',
  'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
  'ｙ' => 'y', 'ｚ' => 'z',
  '（' => '(', '）' => ')', '〔' => '[', '〕' => ']', '【' => '[',
  '】' => ']', '〖' => '[', '〗' => ']', '｛' => '{', '｝' => '}', '《' => '<',
  '》' => '>',
  '％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-',
  '：' => ':', '。' => '.', '、' => ',', '，' => '.', '、' => '.',
  '；' => ';', '？' => '?', '！' => '!', '…' => '-', '‖' => '|',
  '”' => '"', '“' => '"', '‘' => '`', '｜' => '|', '〃' => '"',
  '　' => ' ','．' => '.');
 return strtr($str, $arr);
}
 
/**
 * 下载
 * @param [type] $filename [description]
 * @param string $dir  [description]
 * @return [type]   [description]
 */
function downloads($filename,$dir='./'){
 $filepath = $dir.$filename;
 if (!file_exists($filepath)){
  header("Content-type: text/html; charset=utf-8");
  echo "File not found!";
  exit;
 } else {
  $file = fopen($filepath,"r");
  Header("Content-type: application/octet-stream");
  Header("Accept-Ranges: bytes");
  Header("Accept-Length: ".filesize($filepath));
  Header("Content-Disposition: attachment; filename=".$filename);
  echo fread($file, filesize($filepath));
  fclose($file);
 }
}
 
/**
 * 创建一个目录树
 * @param [type] $dir [description]
 * @param integer $mode [description]
 * @return [type]  [description]
 */
function mkdirs($dir, $mode = 0777) {
 if (!is_dir($dir)) {
  mkdirs(dirname($dir), $mode);
  return mkdir($dir, $mode);
 }
 return true;
}