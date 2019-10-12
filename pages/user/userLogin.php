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
	if(!empty($_SESSION["user_info"]))header("Location:".$webRoot."/showMsg.php?msg=您已登录！");
	if(!empty($_POST)){
		if(empty($_POST["vcode"]) or strtolower($_POST["vcode"])!=strtolower($_SESSION["vcode"])){
			$res='验证码错误！';
		}
		if($res=='')$res=checkUserLogin($conn,$_POST);
		if($res==''){
			$_SESSION["user_info"]=serialize(getUserInfo($conn,$_POST['uname']));
			header("Location:".$webRoot."/showMsg.php?msg=登录成功！");
		}
	}
?>
<head>
	<meta charset="UTF-8">
	<title>MyBlog</title>
	<script type="text/javascript" src="<?php echo $webRoot ?>/inc/codes/jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="<?php echo $webRoot ?>/inc/codes/jqueryMD5.js"></script>
	<link rel="stylesheet" href="<?php echo $webRoot ?>/res/css/main.css">
	<link rel="stylesheet" href="<?php echo $webRoot ?>/res/css/index.css">
	<link rel="stylesheet" href="<?php echo $webRoot ?>/res/css/form.css">
	<script type="text/javascript" src="<?php echo $webRoot ?>/res/js/main.js"></script>
	<script type="text/javascript" src="<?php echo $webRoot ?>/res/js/checkForm.js"></script>
</head>
<body style="margin: 0px;padding: 0px;background-color: rgba(0, 0, 0, 0.06);">
	<?php require($_SERVER['DOCUMENT_ROOT']."/inc/tmpFiles/guide.php") ?>
	<?php 
		global $res;
		if($res!='')echo '<script>alert("'.$res.'");</script>';
	?>
	<div class="formpart main">
		<p class="title formtitle">用户登录</span>
		<br>
		<span class="line" style="margin: 10px auto;width: 60%;"></span>
		<span id="info" hidden=""></span>
		<form class="form  content" method="post" onsubmit="return checkAndMd5();">
			<div class="formitem"><span class="formdesc">邮箱/用户名</span><input name="uname" type="text" value="<?php if($res!='')echo $_POST['uname']; ?>" /></div> 
			<br>
			<div class="formitem"><span class="formdesc">密码</span><input name="password" type="password"/></div>
			<br>
			<div class="formitem"><span class="formdesc">验证码</span><input name="vcode" type="text" />&nbsp&nbsp&nbsp<img src="../vcode.php" onclick="this.src='../vcode.php?id='+Math.random();"></div> 
			<br>
			<input type="submit" class="linkButton submitButton" value="提交" />
			<button class="linkButton submitButton" id="reset" type="button">重置</button>
		</form>
	</div>
	<?php require($_SERVER['DOCUMENT_ROOT']."/inc/tmpFiles/foot.php") ?>
</body>
</html>