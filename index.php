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
	$orderedItem=getOrderedItems($conn);
	$tag=getTags($conn);
	$flink=getFriendLink($conn);
	$indexIntro=getIndexIntro($conn);
?>
<head>
	<meta charset="UTF-8">
	<title>MyBlog</title>
	<script type="text/javascript" src="<?php echo $webRoot ?>/inc/codes/jquery-3.4.1.min.js"></script>
	<link rel="stylesheet" href="<?php echo $webRoot ?>/res/css/main.css">
	<link rel="stylesheet" href="<?php echo $webRoot ?>/res/css/index.css">
	<script type="text/javascript" src="<?php echo $webRoot ?>/res/js/main.js"></script>
</head>
<body style="margin: 0px;padding: 0px;background-color: rgba(0, 0, 0, 0.06);">
	<?php require($_SERVER['DOCUMENT_ROOT']."/inc/tmpFiles/guide.php") ?>
	<div class="main">
		<div class="part">
			<fieldset class="tinypart">
				<legend class="title">关于我</legend>
				<span class="line"></span>
				<p class="content">
					<?php 
						if(empty($indexIntro[0]))echo "暂时没有介绍哦";
						else echo $indexIntro[0];
					?>
				</p>
				<a href="/pages/contents/showIntro.php" class="linkButton">去看看</a>
			</fieldset> 
			<fieldset class="tinypart">
				<legend class="title">推荐博客</legend>
				<span class="line"></span>
				<div class="content">
					<span class="tinytitle">人气博客</span>
					<ol class="rank">
						<?php 
							$oItem=$orderedItem["blog"][0];
							for($i=0;$i<4;$i++){
								if(empty($oItem[$i]))array_push($oItem,Array("暂时没有哦~","0"));
								else{
									if($oItem[$i][1]>1000 and $oItem[$i][1]<=100000)$oItem[$i][1]=round((float)$oItem[$i][1]/1000,1)."K";
									if($oItem[$i][1]>100000 and $oItem[$i][1]<=1000000)$oItem[$i][1]=round((float)$oItem[$i][1]/10000,1)."W";
									if($oItem[$i][1]>1000000)$oItem[$i][1]=round((float)$oItem[$i][1]/1000000,1)."M";
								}
							}
						?>
						
						<li><a href="search.php?name=<?php echo $oItem[0][0] ?>&type=1"><?php echo (mb_strlen($oItem[0][0],'utf8')>10? mb_substr($oItem[0][0],0,8,'utf-8')."...":$oItem[0][0]); ?></a><span class="info"><?php echo $oItem[0][1]  ?></span></li>
						<li><a href="search.php?name=<?php echo $oItem[1][0] ?>&type=1"><?php echo (mb_strlen($oItem[1][0],'utf8')>10? mb_substr($oItem[1][0],0,8,'utf-8')."...":$oItem[1][0]); ?></a><span class="info"><?php echo $oItem[1][1]  ?></span></li>
						<li><a href="search.php?name=<?php echo $oItem[2][0] ?>&type=1"><?php echo (mb_strlen($oItem[2][0],'utf8')>10? mb_substr($oItem[2][0],0,8,'utf-8')."...":$oItem[2][0]); ?></a><span class="info"><?php echo $oItem[2][1]  ?></span></li>
						<li><a href="search.php?name=<?php echo $oItem[3][0] ?>&type=1"><?php echo (mb_strlen($oItem[3][0],'utf8')>10? mb_substr($oItem[3][0],0,8,'utf-8')."...":$oItem[3][0]); ?></a><span class="info"><?php echo $oItem[3][1]  ?></span></li>
					</ol>
				</div>
				<div class="content">
					<span class="tinytitle">最新博客</span>
					<ol class="rank">
						<?php 
							$oItem=$orderedItem["blog"][1];
							for($i=0;$i<4;$i++){
								if(empty($oItem[$i]))array_push($oItem,Array("暂时没有哦~","0-0-0"));
								else{
									if(mb_strlen($oItem[$i][0],'utf8')>10)$oItem[$i][0]=mb_substr($oItem[$i][0],0,8,'utf-8')."...";
								}
							}
						?>
						<li><a href="search.php?name=<?php echo $oItem[0][0] ?>&type=1"><?php echo (mb_strlen($oItem[0][0],'utf8')>10? mb_substr($oItem[0][0],0,8,'utf-8')."...":$oItem[0][0]); ?></a><span class="info"><?php echo $oItem[0][1]  ?></span></li>
						<li><a href="search.php?name=<?php echo $oItem[1][0] ?>&type=1"><?php echo (mb_strlen($oItem[1][0],'utf8')>10? mb_substr($oItem[1][0],0,8,'utf-8')."...":$oItem[1][0]); ?></a><span class="info"><?php echo $oItem[1][1]  ?></span></li>
						<li><a href="search.php?name=<?php echo $oItem[2][0] ?>&type=1"><?php echo (mb_strlen($oItem[2][0],'utf8')>10? mb_substr($oItem[2][0],0,8,'utf-8')."...":$oItem[2][0]); ?></a><span class="info"><?php echo $oItem[2][1]  ?></span></li>
						<li><a href="search.php?name=<?php echo $oItem[3][0] ?>&type=1"><?php echo (mb_strlen($oItem[3][0],'utf8')>10? mb_substr($oItem[3][0],0,8,'utf-8')."...":$oItem[3][0]); ?></a><span class="info"><?php echo $oItem[3][1]  ?></span></li>
					</ol>
				</div>
			</fieldset> 
			<fieldset class="tinypart">
				<legend class="title">推荐专栏</legend>
				<span class="line"></span>
				<div class="content">
					<span class="tinytitle">人气专栏</span>
					<ol class="rank">
						<?php 
							$oItem=$orderedItem["book"][0];
							for($i=0;$i<4;$i++){
								if(empty($oItem[$i]))array_push($oItem,Array("暂时没有哦~","0"));
								else{
									if(mb_strlen($oItem[$i][0],'utf8')>10)$oItem[$i][0]=mb_substr($oItem[$i][0],0,8,'utf-8')."...";
									if($oItem[$i][1]>1000 and $oItem[$i][1]<=100000)$oItem[$i][1]=round((float)$oItem[$i][1]/1000,1)."K";
									if($oItem[$i][1]>100000 and $oItem[$i][1]<=1000000)$oItem[$i][1]=round((float)$oItem[$i][1]/10000,1)."W";
									if($oItem[$i][1]>1000000)$oItem[$i][1]=round((float)$oItem[$i][1]/1000000,1)."M";
								}
							}
						?>
						<li><a href="search.php?name=<?php echo $oItem[0][0] ?>&type=2"><?php echo (mb_strlen($oItem[0][0],'utf8')>10? mb_substr($oItem[0][0],0,8,'utf-8')."...":$oItem[0][0]); ?></a><span class="info"><?php echo $oItem[0][1]  ?></span></li>
						<li><a href="search.php?name=<?php echo $oItem[1][0] ?>&type=2"><?php echo (mb_strlen($oItem[1][0],'utf8')>10? mb_substr($oItem[1][0],0,8,'utf-8')."...":$oItem[1][0]); ?></a><span class="info"><?php echo $oItem[1][1]  ?></span></li>
						<li><a href="search.php?name=<?php echo $oItem[2][0] ?>&type=2"><?php echo (mb_strlen($oItem[2][0],'utf8')>10? mb_substr($oItem[2][0],0,8,'utf-8')."...":$oItem[2][0]); ?></a><span class="info"><?php echo $oItem[2][1]  ?></span></li>
						<li><a href="search.php?name=<?php echo $oItem[3][0] ?>&type=2"><?php echo (mb_strlen($oItem[3][0],'utf8')>10? mb_substr($oItem[3][0],0,8,'utf-8')."...":$oItem[3][0]); ?></a><span class="info"><?php echo $oItem[3][1]  ?></span></li>
					</ol>
				</div>
				<div class="content">
					<span class="tinytitle">最新专栏</span>
					<ol class="rank">
						<?php 
							$oItem=$orderedItem["book"][1];
							for($i=0;$i<4;$i++){
								if(empty($oItem[$i]))array_push($oItem,Array("暂时没有哦~","0-0-0"));
								else{
									if(mb_strlen($oItem[$i][0],'utf8')>10)$oItem[$i][0]=mb_substr($oItem[$i][0],0,8,'utf-8')."...";
								}
							}
						?>
						<li><a href="search.php?name=<?php echo $oItem[0][0] ?>&type=2"><?php echo (mb_strlen($oItem[0][0],'utf8')>10? mb_substr($oItem[0][0],0,8,'utf-8')."...":$oItem[0][0]); ?></a><span class="info"><?php echo $oItem[0][1]  ?></span></li>
						<li><a href="search.php?name=<?php echo $oItem[1][0] ?>&type=2"><?php echo (mb_strlen($oItem[1][0],'utf8')>10? mb_substr($oItem[1][0],0,8,'utf-8')."...":$oItem[1][0]); ?></a><span class="info"><?php echo $oItem[1][1]  ?></span></li>
						<li><a href="search.php?name=<?php echo $oItem[2][0] ?>&type=2"><?php echo (mb_strlen($oItem[2][0],'utf8')>10? mb_substr($oItem[2][0],0,8,'utf-8')."...":$oItem[2][0]); ?></a><span class="info"><?php echo $oItem[2][1]  ?></span></li>
						<li><a href="search.php?name=<?php echo $oItem[3][0] ?>&type=2"><?php echo (mb_strlen($oItem[3][0],'utf8')>10? mb_substr($oItem[3][0],0,8,'utf-8')."...":$oItem[3][0]); ?></a><span class="info"><?php echo $oItem[3][1]  ?></span></li>
					</ol>
				</div>
			</fieldset> 
			<fieldset class="tinypart">
				<legend class="title">推荐资源</legend>
				<span class="line"></span>
				<div class="content">
					<span class="tinytitle">人气资源</span>
					<ol class="rank">
						<?php 
							$oItem=$orderedItem["download"][0];
							for($i=0;$i<4;$i++){
								if(empty($oItem[$i]))array_push($oItem,Array("暂时没有哦~","0"));
								else{
									if(mb_strlen($oItem[$i][0],'utf8')>10)$oItem[$i][0]=mb_substr($oItem[$i][0],0,8,'utf-8')."...";
									if($oItem[$i][1]>1000 and $oItem[$i][1]<=100000)$oItem[$i][1]=round((float)$oItem[$i][1]/1000,1)."K";
									if($oItem[$i][1]>100000 and $oItem[$i][1]<=1000000)$oItem[$i][1]=round((float)$oItem[$i][1]/10000,1)."W";
									if($oItem[$i][1]>1000000)$oItem[$i][1]=round((float)$oItem[$i][1]/1000000,1)."M";
								}
							}
						?>
						<li><a href="search.php?name=<?php echo $oItem[0][0] ?>&type=3"><?php echo (mb_strlen($oItem[0][0],'utf8')>10? mb_substr($oItem[0][0],0,8,'utf-8')."...":$oItem[0][0]); ?></a><span class="info"><?php echo $oItem[0][1]  ?></span></li>
						<li><a href="search.php?name=<?php echo $oItem[1][0] ?>&type=3"><?php echo (mb_strlen($oItem[1][0],'utf8')>10? mb_substr($oItem[1][0],0,8,'utf-8')."...":$oItem[1][0]); ?></a><span class="info"><?php echo $oItem[1][1]  ?></span></li>
						<li><a href="search.php?name=<?php echo $oItem[2][0] ?>&type=3"><?php echo (mb_strlen($oItem[2][0],'utf8')>10? mb_substr($oItem[2][0],0,8,'utf-8')."...":$oItem[2][0]); ?></a><span class="info"><?php echo $oItem[2][1]  ?></span></li>
						<li><a href="search.php?name=<?php echo $oItem[3][0] ?>&type=3"><?php echo (mb_strlen($oItem[3][0],'utf8')>10? mb_substr($oItem[3][0],0,8,'utf-8')."...":$oItem[3][0]); ?></a><span class="info"><?php echo $oItem[3][1]  ?></span></li>
					</ol>
				</div>
				<div class="content">
					<span class="tinytitle">最新资源</span>
					<ol class="rank">
						<?php 
							$oItem=$orderedItem["download"][0];
							for($i=0;$i<4;$i++){
								if(empty($oItem[$i]))array_push($oItem,Array("暂时没有哦~","0-0-0"));
								else{
									if(mb_strlen($oItem[$i][0],'utf8')>10)$oItem[$i][0]=mb_substr($oItem[$i][0],0,8,'utf-8')."...";
								}
							}
						?>
						<li><a href="search.php?name=<?php echo $oItem[0][0] ?>&type=3"><?php echo (mb_strlen($oItem[0][0],'utf8')>10? mb_substr($oItem[0][0],0,8,'utf-8')."...":$oItem[0][0]); ?></a><span class="info"><?php echo $oItem[0][1]  ?></span></li>
						<li><a href="search.php?name=<?php echo $oItem[1][0] ?>&type=3"><?php echo (mb_strlen($oItem[1][0],'utf8')>10? mb_substr($oItem[1][0],0,8,'utf-8')."...":$oItem[1][0]); ?></a><span class="info"><?php echo $oItem[1][1]  ?></span></li>
						<li><a href="search.php?name=<?php echo $oItem[2][0] ?>&type=3"><?php echo (mb_strlen($oItem[2][0],'utf8')>10? mb_substr($oItem[2][0],0,8,'utf-8')."...":$oItem[2][0]); ?></a><span class="info"><?php echo $oItem[2][1]  ?></span></li>
						<li><a href="search.php?name=<?php echo $oItem[3][0] ?>&type=3"><?php echo (mb_strlen($oItem[3][0],'utf8')>10? mb_substr($oItem[3][0],0,8,'utf-8')."...":$oItem[3][0]); ?></a><span class="info"><?php echo $oItem[3][1]  ?></span></li>
					</ol>
				</div>
			</fieldset> 
		</div>
		<div class="anotherpart">
			<div class="card">
				<span class="line"></span>
				<span class="title">博客公告</span>
				<img src="/res/img/ico/sound.png" />
				<br>
				<p class="content">
				<?php 
					if(empty($indexIntro[1]))echo "暂时没有公告哦";
					else echo $indexIntro[1];
				?></p>
			</div>
			<div class="card">
				<span class="line"></span>
				<span class="title">分类</span>
				<img src="/res/img/ico/class.png" />
				<br>
				<div class="content">
					<?php 
						if(empty($classAll))echo "暂时没有分类哦";
						for($i=0;$i<count($classAll["blog"]) and !empty($classAll["blog"]);$i++)
							echo '<span class="tag"><a href="/search.php?class='.$classAll["blog"][$i].'">'.(mb_strlen($classAll["blog"][$i],'utf8')>10? mb_substr($classAll["blog"][$i],0,8,'utf-8')."..." : $classAll["blog"][$i]).'</a></span>';
						for($i=0;$i<count($classAll["book"]) and !empty($classAll["book"]);$i++)
							echo '<span class="tag"><a href="/search.php?class='.$classAll["book"][$i].'">'.(mb_strlen($classAll["book"][$i],'utf8')>10? mb_substr($classAll["book"][$i],0,8,'utf-8')."..." : $classAll["book"][$i]).'</a></span>';
						for($i=0;$i<count($classAll["download"]) and !empty($classAll["download"]);$i++)
							echo '<span class="tag"><a href="/search.php?class='.$classAll["download"][$i].'">'.(mb_strlen($classAll["download"][$i],'utf8')>10? mb_substr($classAll["download"][$i],0,8,'utf-8')."..." : $classAll["download"][$i]).'</a></span>';
					?>
				</div>
			</div>
			<div class="card">
				<span class="line"></span>
				<span class="title">标签</span>
				<img src="/res/img/ico/tag.png" />
				<br>
				<div class="content">
					<?php 
						if(empty($tag)) echo '暂时没有标签哦';
						foreach($tag as $val){
							if(!empty($val))echo '<span class="tag"><a href="/search.php?tags='.$val.'">'.(mb_strlen($val,'utf8')>10? mb_substr($val,0,8,'utf-8')."..." : $val).'</a></span>';
						}
					?>
				</div>
			</div>
			<div class="card">
				<span class="line"></span>
				<span class="title">友链</span>
				<img src="/res/img/ico/link.png" />
				<br>
				<div class="content">
					<?php 
						if(empty($flink))echo '暂时没有友链哦';
						for($i=0;$i<count($flink) and !empty($flink);$i++)
							echo '<span class="tag"><a href="'.$flink[$i][1].'">'.(mb_strlen($flink[$i][0],'utf8')>10? mb_substr($flink[$i][0],0,8,'utf-8')."..." : $flink[$i][0]).'</a></span>';
					?>
				</div>
			</div>			
		</div>
	</div>
	<?php require($_SERVER['DOCUMENT_ROOT']."/inc/tmpFiles/foot.php") ?>
</body>
</html>