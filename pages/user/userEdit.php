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
	if(empty($_SESSION["user_info"]))header("Location:".$webRoot."/showMsg.php?msg=您还未登录！");
	$ui=getUserAllInfo($conn,$_GET["uid"]);
	$userInfo=unserialize($_SESSION["user_info"]);
	if(empty($ui))header("Location:".$webRoot."/showMsg.php?msg=不存在的用户！");
	if($ui["uid"]!=$userInfo["uid"] and $userInfo["level"]!=3)header("Location:".$webRoot."/showMsg.php?msg=您没有权限修改此用户的个人信息！");
	if(!empty($_POST)){
		$res='';
		if($_FILES["uimg"]["error"]!=4 and $_FILES['uimg']['size']>30000)$res='请上传小于30kb的图片！';
		if($userInfo["level"]!=3 and empty($_POST["password"]) and !empty($_POST["npassword"]))$res="请输入旧密码";
		if($_FILES["uimg"]["error"]!=4 and substr($_FILES["uimg"]["type"],0,6)!='image/')$res='头像格式错误！';
		if($userInfo["level"]!=3 and !empty($_POST["keylevel"]))$res='请不要冒充管理员，谢谢！';
		if(empty($_POST["vcode"]) or  strtolower($_POST["vcode"])!=strtolower($_SESSION["vcode"]))$res='验证码错误！';
		if($res=='')$res=editUserInfo($conn,$_GET["uid"],$_POST,$_FILES["uimg"]["error"]!=4); 
		if($res==''){
			if($_FILES["uimg"]["error"]!=4)move_uploaded_file($_FILES['uimg']["tmp_name"],$_SERVER['DOCUMENT_ROOT'].'/res/img/userImage/'.$_GET['uid'].'.png');
			if($ui["uid"]==$userInfo["uid"])$_SESSION["user_info"]=serialize(getUserInfo($conn,$_POST['uname']));
			header("Location:".$webRoot."/showMsg.php?msg=修改成功！");
		}
	}
?>
<head>
	<meta charset="UTF-8">
	<title>MyBlog</title>
	<script type="text/javascript" src="<?php echo $webRoot ?>/inc/codes/jquery-3.4.1.min.js"></script>
	<link rel="stylesheet" href="<?php echo $webRoot ?>/res/css/main.css">
	<script type="text/javascript" src="<?php echo $webRoot ?>/res/js/main.js"></script>
	<script type="text/javascript" src="<?php echo $webRoot ?>/inc/codes/jqueryMD5.js"></script>
	<script type="text/javascript" src="<?php echo $webRoot ?>/res/js/checkForm.js"></script>
	<link rel="stylesheet" href="<?php echo $webRoot ?>/res/css/index.css">
	<link rel="stylesheet" href="<?php echo $webRoot ?>/res/css/form.css">
</head>
<body style="margin: 0px;padding: 0px;background-color: rgba(0, 0, 0, 0.06);">
	<?php require($_SERVER['DOCUMENT_ROOT']."/inc/tmpFiles/guide.php") ?>
	<?php 
		global $res;
		if($res!='')echo '<script>alert("'.$res.'");</script>';
	?>
	<div class="formpart main">
		<p class="title formtitle">用户信息修改</span>
		<br>
		<span class="line" style="margin: 10px auto;width: 60%;"></span>
		<span id="info" hidden=""></span>
		<form class="form  content" method="post" onsubmit="return checkAndMd5_2();" enctype="multipart/form-data">
			<div class="formitem"><span class="formdesc">用户名</span><input name="uname" type="text" value="<?php echo $ui['uname']; ?>" /></div> 
			<br>
			<div class="formitem"><span class="formdesc">邮箱</span><input name="email" type="text" value="<?php echo $ui['email']; ?>" /></div> 
			<br>
			<div class="formitem"><span class="formdesc">旧密码（不改就不用填）</span><input name="password" type="password"/></div>
			<br>
			<div class="formitem"><span class="formdesc">新密码</span><input name="npassword" type="password"/></div>
			<br>
			<div><span class="formdesc">头像(不改不用上传)</span><input name="uimg" type="file"/></div>
			<br>
			<?php if($userInfo["level"]==3)echo '<div class="formitem"><span class="formdesc">权限等级</span><input name="level" type="text"/></div>
			<br>'; ?>
			<div class="formitem"><span class="formdesc">验证码</span><input name="vcode" type="text" />&nbsp&nbsp&nbsp<img src="../vcode.php" onclick="this.src='../vcode.php?'+Math.random();"></div> 
			<br>
			<input type="submit" class="linkButton submitButton" value="提交" />
			<button class="linkButton submitButton" id="reset" type="button">重置</button>
		</form>
	</div>
	<?php require($_SERVER['DOCUMENT_ROOT']."/inc/tmpFiles/foot.php") ?>
</body>
</html>