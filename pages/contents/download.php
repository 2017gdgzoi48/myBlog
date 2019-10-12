<!doctype html>
<?php
	session_start();
	require_once($_SERVER['DOCUMENT_ROOT']."/inc/dbFunc.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/inc/blogInfo.php");
	$conn=openConnection();
	if(empty($_COOKIE["classes"])){
		$classAll=getClasses($conn);
		setcookie('classes',serialize($classAll),time()+60*5);
	}else $classAll=unserialize($_COOKIE["classes"]);
	if(!empty($_POST)){
		$itemInfo=getItemInfo($conn,$_GET["id"]);
		$file='/../../res/download/'.$itemInfo["extraInfo"].'.zip';
		if(empty($_POST["vcode"])||$_POST["vcode"]!=$_SESSION["vcode"])$res="验证码错误";
		else if($itemInfo["type"]!=3){
			$res='文件不存在';
		}else{
			header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream;charset=utf-8');
		    header('Content-Disposition: attachment;filename="'.$itemInfo['name'].'.zip"');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length:'.filesize($file));
		    readfile($file); 
		    $res='正在下载';
		}
	}
?>
<head>
	<meta charset="UTF-8">
	<title>MyBlog</title>
	<script type="text/javascript" src="<?php echo $webRoot ?>/inc/codes/jquery-3.4.1.min.js"></script>
	<link rel="stylesheet" href="<?php echo $webRoot ?>/res/css/main.css">
	<link rel="stylesheet" href="<?php echo $webRoot ?>/res/css/index.css">
	<link rel="stylesheet" href="<?php echo $webRoot ?>/res/css/form.css">
	<script type="text/javascript" src="<?php echo $webRoot ?>/res/js/main.js"></script>
</head>
<body style="margin: 0px;padding: 0px;background-color: rgba(0, 0, 0, 0.06);">
	<?php require($_SERVER['DOCUMENT_ROOT']."/inc/tmpFiles/guide.php") ?>
	<?php 
		global $res;
		if($res!='')echo '<script>alert("'.$res.'");</script>';
	?>
	<div class="formpart main">
		<span class="formtitle title">下载资源</span>
		<span class="line" style="margin: 10px auto;width: 60%;"></span>
		<form method="post" class="form content">
			<div class="formitem">
				<span class="formdesc">验证码</span>
				<input type="text" name="vcode">
				<img src="/../../pages/vcode.php" onclick="javascript:this.src='/../../pages/vcode.php?'+Math.random();" />
				<br>
				<button type="submit" class="linkButton submitButton" >提交</button>
			</div>
		</form>
	</div>
	<?php require($_SERVER['DOCUMENT_ROOT']."/inc/tmpFiles/foot.php") ?>
</body>
</html>