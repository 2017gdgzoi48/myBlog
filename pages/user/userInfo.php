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
	if(empty($ui))header("Location:".$webRoot."/showMsg.php?msg=不存在的用户！");
?>
<head>
	<meta charset="UTF-8">
	<title>MyBlog</title>
	<script type="text/javascript" src="<?php echo $webRoot ?>/inc/codes/jquery-3.4.1.min.js"></script>
	<link rel="stylesheet" href="<?php echo $webRoot ?>/res/css/main.css">
	<link rel="stylesheet" href="<?php echo $webRoot ?>/res/css/index.css">
	<link rel="stylesheet" href="<?php echo $webRoot ?>/res/css/uinfo.css">
	<script type="text/javascript" src="<?php echo $webRoot ?>/res/js/main.js"></script>
</head>
<body style="margin: 0px;padding: 0px;background-color: rgba(0, 0, 0, 0.06);">
	<?php require($_SERVER['DOCUMENT_ROOT']."/inc/tmpFiles/guide.php") ?>
	<div class="header">
		<img src="<?php echo $webRoot; ?>/res/img/userImage/<?php echo $ui['uimg']; ?>" class="biguimg">
		<span class="title">用户<?php echo $ui['uname']; ?>的个人信息</span>
		<img src="<?php echo $webRoot; ?>/res/img/ico/person.png">
		<span class="line"></span>
	</div>
	<div class="main">
		<div class="part" style="margin-left:20px;flex:35;">
			<span class="title" style="display: block;">基本信息</span>
			<ul class="infolist content">
				<li><?php echo $ui["uid"]; ?></li>
				<li><?php echo $ui["uname"]; ?></li>
				<li><?php echo $ui["email"]; ?></li>
				<li><img src="<?php echo $webRoot; ?>/res/img/userImage/<?php echo $ui['uimg']; ?>" class="smalluimg"></li>
				<li><?php echo $ui["level"]; ?></li>
			</ul>
		</div>
		<div class="anotherpart">
			<span class="title">收藏夹</span>
			<ul class="infolist content">
				<?php 
					$favor=explode('|',$ui["favor"]);
					array_pop($favor);
					for($i=0;$i<count($favor);$i++){
						if(!empty($favor[$i])){
							$info=getItemInfo($conn,$favor[$i]);
							$name=$info["name"];
							$name=(mb_strlen($name,'utf8')>15? mb_substr($name,0,8,'utf-8')."...":$name);
							echo '<li class="tag"><a href="'.$webRoot.'/search.php?name='.$info["name"].'&type='.$info["type"].'" >'.$name.'</a></li>';
						}
					}
				?>
			</ul>
		</div>
	</div>
	<?php require($_SERVER['DOCUMENT_ROOT']."/inc/tmpFiles/foot.php") ?>
</body>
</html>