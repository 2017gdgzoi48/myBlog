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
?>
<head>
	<meta charset="UTF-8">
	<title>MyBlog</title>
	<script type="text/javascript" src="<?php echo $webRoot ?>/inc/codes/jquery-3.4.1.min.js"></script>
	<link rel="stylesheet" href="<?php echo $webRoot ?>/res/css/main.css">
	<script type="text/javascript" src="<?php echo $webRoot ?>/res/js/main.js"></script>
</head>
<body style="margin: 0px;padding: 0px;background-color: rgba(0, 0, 0, 0.06);">
	<div class="guide">
		<span class="guideTitle"><a href="<?php echo $webRoot; ?>"><?php echo $blogName; ?></a></span>
		<span class="guideNav">
			<a href="<?php echo $webRoot.'/search.php' ?>?type=1">博客</a>
			<ul class="guideDetials" hidden>
				<?php 
					for($i=0;$i<count($classAll["blog"]) and !empty($classAll["blog"]);$i++){
						echo '<li><a href="'.$webRoot.'/search.php'.'?class='.$classAll["blog"][$i].'">'.$classAll["blog"][$i].'</a></li>';
					}
				?>
			</ul>
		</span>
		<span class="guideNav">
			<a href="<?php echo $webRoot.'/search.php' ?>?type=2">专栏</a>
			<ul class="guideDetials" hidden>
				<?php 
					for($i=0;$i<count($classAll["book"]) and !empty($classAll["book"]);$i++){
						echo '<li><a href="'.$webRoot.'/search.php'.'?class='.$classAll["book"][$i].'">'.$classAll["book"][$i].'</a></li>';
					}
				?>
			</ul>
		</span>
		<span class="guideNav">
			<a href="<?php echo $webRoot.'/search.php' ?>?type=3">下载</a>
			<ul class="guideDetials" hidden>
				<?php 
					for($i=0;$i<count($classAll["download"]) and !empty($classAll["download"]);$i++){
						echo '<li><a href="'.$webRoot.'/search.php'.'?class='.$classAll["download"][$i].'">'.$classAll["download"][$i].'</a></li>';
					}
				?>
			</ul>
		</span>
		<span class="guideNav">
			<a href="<?php echo $webRoot.'/pages/contents/showIntro.php'; ?>">关于</a>
			<ul class="guideDetials" hidden>
			</ul>
		</span>
		<span class="guideNav1">
			<form action="<?php echo $webRoot.'/search.php'; ?>">
				<img src="<?php echo $webRoot ?>/res/img/ico/search.png" >
				<input type="text" name="name" />
			</form>
		</span>
		<div class="guide" style="justify-content: flex-end;">
			<span class="guideNav">
				<?php
					if(empty($_SESSION["user_info"])) echo '<a href="'.$webRoot.'/pages/user/userLogin.php">登录</a>';
					else {
						$userInfo=unserialize($_SESSION["user_info"]);
						echo '<img src="'.$webRoot.'/res/img/userImage/'.$userInfo['uimg'].'" class="uimg"><a href="'.$webRoot.'/pages/user/userInfo.php?uid='.$userInfo['uid'].'">'.$userInfo['uname'].'</a>';
					}
				?>
				<ul class="guideDetials" hidden>
					<?php  
						if(!empty($_SESSION["user_info"])){
							echo 
					'<li><a href="'.$webRoot.'/pages/user/userInfo.php?uid='.$userInfo['uid'].'">用户信息</a></li>
					<li><a href="'.$webRoot.'/pages/user/userEdit.php?uid='.$userInfo['uid'].'">修改信息</a></li>
					<li><a href="'.$webRoot.'/pages/user/userLogout.php">退出</a></li>';
						}
					?>
				</ul>
			</span>
			<?php 
				if(empty($_SESSION["user_info"])){
					echo '<span class="guideNav" >
							<a href="'.$webRoot.'/pages/user/userRegs.php">注册</a>
							<ul class="guideDetials"></ul>
						</span>';
				}
			?>
		</div>
	</div>
	<div class="footer">Copyright &copy 2019 <?php echo $blogUrl; ?> All rights reserved.</div>
</body>
</html>