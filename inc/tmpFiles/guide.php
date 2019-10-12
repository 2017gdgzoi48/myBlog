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
						if($userInfo['level']>=2)echo '<li><a href="'.$webRoot.'/pages/edit/manage.php">管理作品</a></li>';
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