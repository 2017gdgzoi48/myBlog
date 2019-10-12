<?php
/* http://www.phpenv.cn */
require("http://gdgzoi.com/include/db_info.inc.php");
echo $DB_HOST;
// error_reporting(0);//关闭所有错误信息
// @header("content-Type: text/html; charset=utf-8");
// date_default_timezone_set('PRC');
// function _GET($n) { return isset($_GET[$n]) ? $_GET[$n] : NULL; }
// function _SERVER($n) { return isset($_SERVER[$n]) ? $_SERVER[$n] : '[undefine]'; }
// function memory_usage() { $memory  = ( ! function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2).'MB'; return $memory;}
// function micro_time_float() { $mtime = microtime(); $mtime = explode(' ', $mtime); return $mtime[1] + $mtime[0];}
// function get_hash() {
//   return sha1(uniqid());
// }
// @session_start();

// $currentTime = time();
// $changeTime = 86400;

// $rand = '';
// if(isset($_SESSION['time']) and ($currentTime - $_SESSION['time']) < $changeTime) {
//   $rand = $_SESSION['rand'];
// }else{
//   $_SESSION['time'] = $currentTime;
//   $_SESSION['rand'] = $rand = get_hash();
// }

// define('YES', '<span style="color: green; font-weight : bold;">√</span>');
// define('NO', '<span style="color: red; font-weight : bold;">×</span>');
// if ($_POST['mysqlPort']=="") {
// $host="127.0.0.1";
// } else {
// $host="127.0.0.1:".$_POST['mysqlPort'];
// }
// $Info = array();
// $Info['php_ini_file'] = function_exists('php_ini_loaded_file') ? php_ini_loaded_file() : '[undefine]';
// $mcrypt = get_extension_funcs('mcrypt') ? YES : NO;
// $ftp = get_extension_funcs('ftp') ? YES : NO;
// $link = @mysqli_connect($host, $_POST['mysqlUser'], $_POST['mysqlPassword']);
// $errno = mysqli_connect_errno();
// $infoKey = $rand;
// $up_start = micro_time_float();
// if (_GET($infoKey) == 'phpinfo') {
// if (function_exists('phpinfo')) phpinfo();
// else echo "phpinfo()函数已被禁用，无法正常显示详细信息!";
// exit;
// }
// ?>

<!-- // <html>
// <head>
// <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
// <title>phpEnv PHP探针</title>
// <meta name="keywords" content="php探针,phpEnv探针,phpEnv集成环境" />
// <meta name="description" content="phpEnv集成环境，专业的php集成环境" />

// <style type="text/css">
// <!--
// *{margin:0px;padding:0px;}
// body {background-color:#FFFFFF;color:#000000;margin:0px;font-family:"Microsoft Yahei",arial,sans-serif;}
// input {text-align:center;width:200px;height:30px;padding:5px;}
// a:link {color:green; text-decoration:none;}
// a:visited {color:green;text-decoration:none;}
// a:active {color:green;text-decoration:none;}
// a:hover {color:red;text-decoration:none;}
// table {border-collapse:collapse;margin:10px 0px;clear:both;}
// .tzt tr th, td {padding:8px 10px 8px 10px;vertical-align:center;text-align:center;height:30px; border:1px #FFFFFF solid;}
// .head1 {background:#248ee3;background:linear-gradient(to right, #0078D7,#748ee3 , #0078D7);font-size:20px;color:#fff; line-height:60px;text-align: center; font-weight: bold; }
// .l_name {text-align: center; background: #d3e1e5; }
// .lr_name {text-align: right; background: #d3e1e5; }
// .thead {line-height:25px;text-align: center; background:#248ee3;background:linear-gradient(to right, #0078D7,#748ee3 , #0078D7);font-weight: bold; color: #FFF;}
// .ll_val {text-align: left; background-color: #ecf0f1; color: #505050; }
// .l_check {text-align: center; background: #eee; color: #505050; }
// .warn {text-align:center;background-color: #D9F9DE;color:red;}
// a.arrow {font-family:webdings,sans-serif;font-size:10px;}
// a.arrow:hover {color:#ff0000;text-decoration:none;}
// -->
// <!-- </style>
// </head>
// <body>

// <div style="margin:0 auto;width:1140px;overflow:hidden;">

// <div class="head1"><a href="http://www.phpenv.cn" target="_blank" style="color:#fff" >phpEnv PHP 探针</a></div>

// <table width="100%" class="tzt">
// <tr>
// <th colspan="2" class="thead" width="50%">服务器信息</th>
// <th colspan="2" class="thead" width="50%">PHP功能组件开启状态</th>
// </tr>
// <tr>
// <td class="lr_name" width="12%">服务器域名</td>
// <td class="ll_val" width="38%"><?php echo  _SERVER('SERVER_NAME')?></td>
// <td class="lr_name" width="20%">MySQLi组件</td>
// <td class="l_check" width="30%"><?php echo  get_extension_funcs('mysqli') ? YES : NO ?></td>
// </tr>
// <tr>
// <td class="lr_name">IP地址和端口</td>
// <td class="ll_val">
// <?php echo  _SERVER('SERVER_ADDR').':'._SERVER('SERVER_PORT')?>
// </td>
// <td class="lr_name">cURL组件</td>
// <td class="l_check"><?php echo  get_extension_funcs('curl') ? YES : NO ?></td>
// </tr>
// <tr>
// <td class="lr_name">服务器环境</td>
// <td class="ll_val"><?php echo  stripos(_SERVER('SERVER_SOFTWARE'), 'PHP')?_SERVER('SERVER_SOFTWARE'):_SERVER('SERVER_SOFTWARE')?></td>
// <td class="lr_name">GD library组件</td>
// <td class="l_check"><?php echo  get_extension_funcs('gd') ? YES : NO ?></td>
// </tr>
// <tr>
// <td class="lr_name">PHP版本</td>
// <td class="ll_val"><?php echo  ' PHP/'.PHP_VERSION?></td>
// <td class="lr_name">EXIF信息查看组件</td>
// <td class="l_check"><?php echo  get_extension_funcs('exif') ? YES : NO ?></td>
// </tr>
// <tr>
// <td class="lr_name">PHP运行方式</td>
// <td class="ll_val"><?php echo  PHP_SAPI?></td>
// <td class="lr_name">OpenSSL协议组件</td>
// <td class="l_check"><?php echo  get_extension_funcs('openssl') ? YES : NO ?></td>
// </tr>
// <tr>
// <td class="lr_name">当前网站目录</td>
// <td class="ll_val" style="word-break:break-all;word-wrap:break-word">
// <?php echo  htmlentities(_SERVER('DOCUMENT_ROOT'))?>
// </td>
// <?php
// if(version_compare(PHP_VERSION,'7.0.0', '<')){
// 	echo "<td class='lr_name'>Mcrypt加密处理组件</td><td class='l_check'>".$mcrypt."</td>";
// }else{
// 	echo "<td class='lr_name'>FTP组件</td><td class='l_check'>".$ftp."</td>";
// }?>
// </tr>
// <tr>
// <td class="lr_name">服务器时间</td>
// <td class="ll_val">
// <?php echo  gmdate('Y-m-d H:i:s', time() + 3600 * 8)?>
// </td>
// <td class="lr_name" >IMAP电子邮件函数库</td>
// <td class="l_check"><?php echo  get_extension_funcs('imap') ? YES : NO ?></td>
// </tr>
// <tr>
// <td class="lr_name">PHPINFO</td>
// <td class="ll_val">
// <?php echo  "<a target='_blank' href='?$infoKey=phpinfo'>PHPINFO详细信息</a>"?>
// </td>
// <td class="lr_name">SendMail电子邮件支持</td>
// <td class="l_check"><?php echo  get_extension_funcs('standard') ? YES : NO ?></td>
// </tr>
// </table>

// <table width="100%" class="tzt">
// <tr>
// <td colspan="6" class="thead" width="100%">PHP重要参数检测</td>
// </tr>
// <tr>
// <td class="l_name">Memory限制</td>
// <td class="l_name">Upload限制</td>
// <td class="l_name">POST限制</td>
// <td class="l_name">Execution超时</td>
// <td class="l_name">Input超时</td>
// <td class="l_name">Socket超时</td>
// </tr>
// <tr>
// <td class="l_check"><?php echo  ini_get('memory_limit')?></td>
// <td class="l_check"><?php echo  ini_get('upload_max_filesize')?></td>
// <td class="l_check"><?php echo  ini_get('post_max_size')?></td>
// <td class="l_check"><?php echo  ini_get('max_execution_time').'s'?></td>
// <td class="l_check"><?php echo  ini_get('max_input_time').'s'?></td>
// <td class="l_check"><?php echo  ini_get('default_socket_timeout').'s'?></td>
// </tr>
// </table>
// <table width="100%" class="tzt">
// <tr>
// <th class="thead">PHP已编译模块检测</th>
// </tr>
// <tr>
// <td class="ll_val" style="text-align:center;">
// <?php
// $able=get_loaded_extensions();
// foreach ($able as $key=>$value) {
// if ($key!=0 && $key%13==0) {
// echo '<br />';
// }
// echo "$value&nbsp;&nbsp;&nbsp;&nbsp;";
// }
// ?>
// </td>
// </tr>
// </table>
// <form method="post" action="<?php echo  htmlentities($_SERVER['PHP_SELF'])?>">
// <table width="100%" id="data" class="tzt">
// <tr>
// <th colspan="4" class="thead">数据库连接测试</th>
// </tr>
// <tr>
// <td colspan="4" class="warn">如果你是线上（生产）环境，为了安全性，请及时修改MySQL密码！</td>
// </tr>
// <tr>
// <td width="25%" class="lr_name">数据库服务器</td>
// <td width="25%" class="ll_val"><input type="text" name="mysqlHost" value="127.0.0.1" disabled="true"/></td>
// <td width="25%" class="lr_name">数据库端口</td>
// <td width="25%" class="ll_val"><input type="text" name="mysqlPort" value="" /></td>
// </tr>
// <tr>
// <td class="lr_name">数据库用户名</td>
// <td class="ll_val"><input type="text" name="mysqlUser" value="" /></td>
// <td class="lr_name">数据库密码</td>
// <td class="ll_val"><input type="password" name="mysqlPassword" /></td>
// </tr>
// <tr>
// <td colspan="4" align="center" style="padding-top:15px"><input type="submit" value=" 连 接 " name="act" style="height:35px;width:150px" /></td>
// </tr> -->
/<!-- / </table>
// </form>
// <?php if(isset($_POST['act'])) {?>
// <table width="100%" class="tzt">
// <tr>
// <th colspan="4" class="thead">数据库测试结果</th>
// </tr>
// <?php
// if ($link) $str1 = '<span style="color: #008000; font-weight: bold;">连接正常 </span> (MySQL '.mysqli_get_server_info($link).')';
// else $str1 = '<span style="color: #ff0000; font-weight: bold;">连接错误</span><br />'.mysqli_connect_error();
// ?>
// <tr>
// <td colspan="2" class="lr_name" width="50%"><?php echo  $host?></td>
// <td colspan="2" class="ll_val" width="50%"><?php echo  $str1?></td>
// </tr>
// </table>
// <?php }?>
// <p style="color:#33384e;font-size:14px;text-align:center; margin-bottom:5px;">
// <?php $up_time = sprintf('%0.6f',micro_time_float() - $up_start);?>页面执行时间 <?php echo $up_time?> 秒，使用了 <?php echo memory_usage(); ?> 内存
// </p>
// <hr style="width:100%; color:#cdcdcd" noshade="noshade" size="1" />
// <p style="color:#505050; line-height:40px;font-size:14px; text-align:center;">&copy; <?php echo date("Y")?> <a href="http://www.phpenv.cn" target="_blank">phpEnv集成环境</a> </p>
// </div>
// </body>
// </html> --> -->
