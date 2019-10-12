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
	// echo $_GET["id"];
	$itemInfo=getItemInfo($conn,-1);
	$ui=getUserAllInfo($conn,$itemInfo["uid"]);
	// print_r($ui);
	$auInfo=getAuInfo($conn,$ui["uid"]);
	$_GET["id"]=$itemInfo["id"];
	$flink=getFriendLink($conn);
	if($itemInfo["secret"]==1 and (empty($userInfo)||$userInfo["level"]<3) and $userInfo["uname"]!=$ui["uname"]){
		header("location: /../../showMsg.php?msg=此文章不存在"); 
	}
	$favor=0;
	$like=0;
	$dislike=0;
	if(!empty($userInfo)){
		if(strpos($userInfo["favor"],ltrim($_GET["id"],'0'))!==false)$favor=1;
		if(strpos($userInfo["likeArr"],ltrim($_GET["id"],'0'))!==false)$like=1;
		if(strpos($userInfo["dislikeArr"],ltrim($_GET["id"],'0'))!==false)$dislike=1;
	}
	if(!empty($_POST)){
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
	<link rel="stylesheet" href="<?php echo $webRoot ?>/res/css/index.css">
	<script type="text/javascript" src="<?php echo $webRoot ?>/res/js/main.js"></script>
	<script type="text/javascript" src="<?php echo $webRoot ?>/res/js/show.js"></script>
	<script type="text/javascript" src="<?php echo $webRoot ?>/res/js/intro.js"></script>
	<link rel="stylesheet" href="<?php echo $webRoot ?>/res/css/intro.css">
</head>
<body style="margin: 0px;padding: 0px;">
	<?php require($_SERVER['DOCUMENT_ROOT']."/inc/tmpFiles/guide.php") ?>
	<div class="header">
		<img src="/../../res/img/userImage/<?php echo $ui["uimg"]; ?>" class="biguimg">
		<span class="title" style="color:#307dc8;font-size: 40px;">
			关于我-<?php echo $ui["uname"]; ?>
		</span>
	</div>
	<span class="line" style="margin: auto;width: calc(100% - 180px)"></span>
	<div>
		<span class="title" style="display: block;margin-left: 90px;">个人介绍</span>
		<div class="content" style="margin-left: 90px;text-indent: 30px;margin-bottom: 20px;">
			<?php echo $itemInfo["contents"]; ?>
		</div>
	</div>
	<span class="line" style="margin: auto;width: calc(100% - 180px)"></span>
	<div>
		<span class="title" style="display: block;margin-left: 90px;">我的相册</span>
		<div id="album" class="album">
			<span id="photoCount" hidden>
				<?php 
					$now=1;
					while(file_exists($_SERVER["DOCUMENT_ROOT"]."/res/img/album/".$now.'.jpg')){
						$now+=1;
					} 
					echo $now-1;
				?>
			</span>
			<img src="<?php echo $webRoot ?>/res/img/album/3.jpg" id="pre" class="photo" alt="fuck" style="left:-500px;">
			<img src="<?php echo $webRoot ?>/res/img/album/1.jpg" id="mid" class="photo" alt="fork">
			<img src="<?php echo $webRoot ?>/res/img/album/2.jpg" id="nxt" class="photo" alt="fock" style="left:500px;">
			<span class="control1" id="goPre">&lt</span>
			<span class="control1" id="goNxt">&gt</span>
		</div>
	</div>
	<span class="line" style="margin: auto;width: calc(100% - 180px)"></span>
	<div>
		<span class="title" style="display: block;margin-left: 90px;">联系方式</span>
		<div class="content" style="margin-left: 110px;margin-bottom: 20px;">
			<?php 
				$link=explode('<',$itemInfo["extraInfo"]);
				array_shift($link);
				foreach($link as $val){
					$linkArr=explode('>',$val);
					echo '<span>'.$linkArr[0].' : '.$linkArr[1].'</span><br>';
				}
			?>
		</div>
	</div>
	<span class="line" style="margin: auto;width: calc(100% - 180px)"></span>
	<div>
		<span class="title" style="display: block;margin-left: 90px;">友链</span>
		<div class="content" style="margin-left: 90px;margin-bottom: 20px;">
			<?php 
				if(empty($flink))echo '暂时没有友链哦';
				for($i=0;$i<count($flink) and !empty($flink);$i++)
					echo '<span class="tag"><a href="'.$flink[$i][1].'">'.(mb_strlen($flink[$i][0],'utf8')>10? mb_substr($flink[$i][0],0,8,'utf-8')."..." : $flink[$i][0]).'</a></span>';
			?>
		</div>
	</div>
	<?php require($_SERVER['DOCUMENT_ROOT']."/inc/tmpFiles/feedback.php") ?>
	<?php require($_SERVER['DOCUMENT_ROOT']."/inc/tmpFiles/foot.php") ?>
</body>
</html>