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
	if(!empty($_SESSION["user_info"]))$userInfo=unserialize($_SESSION["user_info"]);
	$_GET["id"]=filter_var($_GET["id"],519);
	// echo $_GET["id"];
	$itemInfo=getItemInfo($conn,$_GET["id"]);
	$ui=getUserAllInfo($conn,$itemInfo["uid"]);
	$auInfo=getAuInfo($conn,$ui["uid"]);
	// print_r($auInfo);
	if($itemInfo["secret"]==1 and (empty($userInfo)||$userInfo["level"]<3) and $userInfo["uname"]!=$ui["uname"]){
		header("location: /../../showMsg.php?msg=此文章不存在"); 
	}
	if(empty($itemInfo)||$itemInfo["type"]!=1)header("location: /../../showMsg.php?msg=此博客不存在"); 
	$favor=0;
	$like=0;
	$dislike=0;
	if(!empty($userInfo)){
		if(strpos($userInfo["favor"],ltrim($_GET["id"],'0'))!==false)$favor=1;
		if(strpos($userInfo["likeArr"],ltrim($_GET["id"],'0'))!==false)$like=1;
		if(strpos($userInfo["dislikeArr"],ltrim($_GET["id"],'0'))!==false)$dislike=1;
	}
	if(!empty($_POST)){
		// print_r($_POST);
		foreach($_POST as $key=>$val){
			if($val!=''){
				if($key=='comment'){
					if(empty($userInfo))header("location: /../../showMsg.php?msg=请先登录再评论，谢谢！"); 
					$val=htmlspecialchars($val,ENT_HTML401);
					$val='<'.$userInfo["uimg"].'>'.$userInfo["uname"].'>'.$val;
					addComment($conn,$_GET["id"],$val);
				}
				else if(!empty($userInfo)){
					// $_GET["id"]=ltrim($_GET["id"],'0');
					switch($key){
						case 'like':
							if($like)
								$userInfo["likeArr"]=str_replace(ltrim($_GET["id"],'0').'|','',$userInfo["likeArr"]);
							else {
								$userInfo["likeArr"].=ltrim($_GET["id"],'0').'|';
								if($dislike)$userInfo["dislikeArr"]=str_replace(ltrim($_GET["id"],'0').'|','',$userInfo["dislikeArr"]);
							}
							updateLike($conn,$userInfo,ltrim($_GET["id"],'0'),$like,$dislike);
							break;
						case 'dislike':
							if($dislike)
								$userInfo["dislikeArr"]=str_replace(ltrim($_GET["id"],'0').'|','',$userInfo["dislikeArr"]);
							else {
								$userInfo["dislikeArr"].=ltrim($_GET["id"],'0').'|';
								if($like)$userInfo["likeArr"]=str_replace(ltrim($_GET["id"],'0').'|','',$userInfo["likeArr"]);
							}
							updateDislike($conn,$userInfo,ltrim($_GET["id"],'0'),$like,$dislike);
							break;
						case 'favor':
							if($favor)
								$userInfo["favor"]=str_replace(ltrim($_GET["id"],'0').'|','',$userInfo["favor"]);
							else $userInfo["favor"].=ltrim($_GET["id"],'0').'|';
							updateFavor($conn,$userInfo);
							break;
					}
				}
			}
		}
		$_SESSION["user_info"]=serialize($userInfo);
	}
?>
<head>
	<meta charset="UTF-8">
	<title>MyBlog</title>
	<script type="text/javascript" src="<?php echo $webRoot ?>/inc/codes/jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="<?php echo $webRoot ?>/inc/codes/latex.js"></script>
	<script type="text/javascript" src="<?php echo $webRoot ?>/inc/codes/highcharts.js"></script>
	<link rel="stylesheet" href="<?php echo $webRoot ?>/res/css/main.css">
	<link rel="stylesheet" href="<?php echo $webRoot ?>/res/css/show.css">
	<script type="text/javascript" src="<?php echo $webRoot ?>/res/js/main.js"></script>
	<script type="text/javascript" src="<?php echo $webRoot ?>/res/js/show.js"></script>
</head>
<body style="margin: 0px;padding: 0px;background-color: rgba(0, 0, 0, 0.06);">
	<?php require($_SERVER['DOCUMENT_ROOT']."/inc/tmpFiles/guide.php") ?>
	<div class="main">
		<div class="leftside">
			<span class="tinytag"><?php echo $itemInfo["class"]; ?></span>
			<span class="title"><?php echo $itemInfo["name"]; ?></span>
			<ul class="info">
				<li><?php echo $ui["uname"]; ?></li>
				<li>
				<?php 
					if($itemInfo["readCount"]>1000 and $itemInfo["readCount"]<=100000)$itemInfo["readCount"]=round((float)$itemInfo["readCount"]/1000,1)."K";
					if($itemInfo["readCount"]>100000 and $itemInfo["readCount"]<=1000000)$itemInfo["readCount"]=round((float)$itemInfo["readCount"]/10000,1)."W";
					if($itemInfo["readCount"]>1000000)$itemInfo["readCount"]=round((float)$itemInfo["readCount"]/1000000,1)."M";
					echo $itemInfo["readCount"];
				?>
				</li>
				<li><?php echo $itemInfo["dateTime"]; ?></li>
				<br>
				<li> </li>
				<?php  
					$itemInfo["tags"]=explode('|',$itemInfo["tags"]);
					foreach($itemInfo["tags"] as $val){
						if($val!=''){
							echo '<span class="wayitem">'.$val.'</span>';
						}
					}
				?>
			</ul>
			<span class="line"></span>
			<div class="content">
				<?php echo $itemInfo["contents"] ?>
			</div>
		</div>
		<div class="rightside">
			<div class="userInfo">
				<img src="/../../res/img/userImage/<?php echo $ui["uimg"] ?>" />
				<span class="author"><?php echo $ui["uname"] ?></span>
				<span class="line" style="width: 90%;margin: 10px auto;background-color: grey;"></span>
				<ul class="ui">
					<?php 
						foreach($auInfo["auCount"] as &$val){
							if(empty($val)){
								$val=0;
								continue;
							}
							if($val>1000 and $val<=100000)$val=round((float)$val/1000,1)."K";
							if($val>100000 and $val<=1000000)$val=round((float)$val/10000,1)."W";
							if($val>1000000)$val=round((float)$val/1000000,1)."M";
						}
					?>
					<li><?php echo $auInfo["auCount"][1] ?></li>
					<li><?php echo $auInfo["auCount"][2] ?></li>
					<li><?php echo $auInfo["auCount"][3] ?></li>
				</ul>
			</div>
			<div class="userInfo">
				<span class="author">博主分类</span>
				<span class="line" style="width: 90%;margin: 10px auto;background-color: #307dc8;"></span>
				<div>
					<?php 
						$class=$auInfo['class'];
						for($i=0;$i<count($class) and !empty($class);$i++)
							if($class[$i]!='')echo '<span class="tag" style="font-size: 20px;height: 20px;line-height: 20px;" ><a href="/../../search.php?tag='.$class[$i].'">'.(mb_strlen($class[$i],'utf8')>10? mb_substr($class[$i],0,8,'utf-8')."..." : $class[$i]).'</a></span>';
					?>
				</div>
			</div>
			<div class="userInfo">
				<span class="author">博主标签</span>
				<span class="line" style="width: 90%;margin: 10px auto;background-color: #307dc8;">
				</span>
				<div>
					<?php 
						$tag=explode('|', implode($auInfo['tags']));
						if(empty($tag)) echo '暂时没有标签哦';
						array_pop($tag);
						sort($tag);
						$tag=array_unique($tag);
						// print_r($tag);
						foreach($tag as $val )
							if(!empty($val))echo '<span class="tag" style="font-size: 20px;height: 20px;line-height: 20px;" ><a href="/../../search.php?tags='.$val.'">'.(mb_strlen($val,'utf8')>10? mb_substr($val,0,8,'utf-8')."..." : $val).'</a></span>';
					?>
				</div>
			</div>
		</div>
	</div>
	<?php require($_SERVER['DOCUMENT_ROOT']."/inc/tmpFiles/feedback.php") ?>
	<?php require($_SERVER['DOCUMENT_ROOT']."/inc/tmpFiles/foot.php") ?>
</body>
</html>