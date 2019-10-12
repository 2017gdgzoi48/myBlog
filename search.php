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
	$tag=getTags($conn);
	if(!empty($_GET)){
		if(!empty($_GET["name"]) and $_GET["name"]=="暂时没有哦~")unset($_GET["name"]);
		$filter=Array();
		foreach($_GET as $key=>$val){
			if($val!=''){					
				if($key=='name')$filter=array_merge($filter,Array("name"=>$val));
				else{
					if(strpos($val,'|')!=false)$val=explode('|',$val);
					if(is_array($val))array_pop($val);
					$filter=array_merge($filter,Array($key=>$val));
				}
			}
		}
		$res=searchItem($conn,$filter);
	}
?>
<head>
	<meta charset="UTF-8">
	<title>MyBlog</title>
	<script type="text/javascript" src="<?php echo $webRoot ?>/inc/codes/jquery-3.4.1.min.js"></script>
	<link rel="stylesheet" href="<?php echo $webRoot ?>/res/css/main.css">
	<link rel="stylesheet" href="<?php echo $webRoot ?>/res/css/search.css">
	<script type="text/javascript" src="<?php echo $webRoot ?>/res/js/main.js"></script>
	<script type="text/javascript" src="<?php echo $webRoot ?>/res/js/search.js"></script>
</head>
<body style="margin: 0px;padding: 0px;background-color: rgba(0, 0, 0, 0.06);">
	<?php require($_SERVER['DOCUMENT_ROOT']."/inc/tmpFiles/guide.php") ?>	
	<div class="mainSearch">
		<form id="search" >
			<img src="<?php echo $webRoot ?>/res/img/ico/bigSearch.png"/>
			<input type="text" placeholder="搜索..." name="name" value="<?php echo !empty($_GET["name"])?$_GET["name"]:''; ?>" />
			<input type="text"  name="class" value="<?php echo !empty($_GET["class"])?$_GET["class"]:''; ?>" hidden />
			<input type="text"  name="tags" value="<?php echo !empty($_GET["tag"])?$_GET["tag"]:''; ?>" hidden />
			<input type="text"  name="type" value="<?php echo !empty($_GET["type"])?$_GET["type"]:''; ?>" hidden />
			<input type="text"  name="order" value="<?php echo !empty($_GET["order"])?$_GET["order"]:''; ?>" hidden />
		</form>
		<span class="turner">+展开过滤器<img src="/res/img/ico/filter.png"></span>
		<div class="highSearch" hidden>
			<span class="waytitle" name="class">分类</span>
			<?php
				if(empty($classAll))echo '<span class="wayitem" style="pointer-events: none;">暂时没有分类哦</span>';
				for($i=0;$i<count($classAll["blog"]) and !empty($classAll["blog"]);$i++){
					echo '<span class="wayitem">'.(mb_strlen($classAll["blog"][$i],'utf8')>10? mb_substr($classAll["blog"][$i],0,8,'utf-8')."...":$classAll["blog"][$i]).'</span>';
				}
				for($i=0;$i<count($classAll["book"]) and !empty($classAll["book"]);$i++){
					echo '<span class="wayitem">'.(mb_strlen($classAll["book"][$i],'utf8')>10? mb_substr($classAll["book"][$i],0,8,'utf-8')."...":$classAll["book"][$i]).'</span>';
				}
				for($i=0;$i<count($classAll["download"]) and !empty($classAll["download"]);$i++){
					echo '<span class="wayitem">'.(mb_strlen($classAll["download"][$i],'utf8')>10? mb_substr($classAll["download"][$i],0,8,'utf-8')."...":$classAll["download"][$i]).'</span>';
				}
			?>
			<br>
			<span class="waytitle" name="tags">标签</span>
			<?php 
				if(empty($tag))echo '<span class="wayitem" style="pointer-events: none;">暂时没有标签哦</span>';
				foreach($tag as $val){
					if(!empty($val))echo '<span class="wayitem">'.(mb_strlen($val,'utf8')>10? mb_substr($val,0,8,'utf-8')."..." : $val).'</span>';
				}
			?>
			<br>
			<span class="waytitle" name="type">类型</span>
			<span class="wayitem" data-code="1">博客</span>
			<span class="wayitem" data-code="2">专栏</span>
			<span class="wayitem" data-code="3">资源</span>
			<br>
			<span class="waytitle" name="order">排序</span>
			<span class="wayitem" data-code="dateTime">按发布时间(正序)</span>
			<span class="wayitem" data-code="dateTime desc">按发布时间(倒序)</span>
			<span class="wayitem" data-code="readCount desc">按阅读数</span>
			<span class="wayitem" data-code="`like` desc">按点赞数</span>
			<br>
		</div>
	</div>
	<span class="line"></span>
	<br>
	<div class="main">
		<?php  
			$dest=Array(1=>"/pages/contents/showBlogs.php",2=>"/pages/contents/showBooks.php",3=>"/pages/contents/showResources.php");
			foreach ($res as $val) {
				if($val["type"]!=1 and $val["type"]!=2 and $val["type"]!=3)continue;
				$uinfo=getUserAllInfo($conn,$val["uid"]);
				if($val["secret"]!=0 and (empty($_SESSION["user_info"])||$userInfo["level"]<3) and $uinfo["uname"]!=$userInfo["uname"])continue;
				if($val["readCount"]>=1000 and $val["readCount"]<=100000)$val["readCount"]=round((float)$val["readCount"]/1000,1)."K";
				if($val["readCount"]>100000 and $val["readCount"]<=1000000)$val["readCount"]=round((float)$val["readCount"]/10000,1)."W";
				if($val["readCount"]>1000000)$val["readCount"]=round((float)$val["readCount"]/1000000,1)."M";
				$str='<a class="result" href='.$dest[$val["type"]].'?id='.$val["id"].'><span data-type='.$val['type'].' class="resTitle">'.$val['name'].'</span>';
				$tags=$val["tags"];
				$tags=explode('|',$tags);
				for($i=0;$i<count($tags) and !empty($tags);$i++)
					if($tags[$i]!='')$tags[$i]='<span class="tinytag">'.$tags[$i].'</span>';
				$str.=implode("",$tags);
				$str.='<ul class="info"><li>'.$uinfo["uname"].'</li><li>'.$val["readCount"].'</li><li>'.$val["dateTime"].'</li></ul></a>';
				echo $str;
			}
		?>
	</div>
	<?php if(empty($res))echo '<span class="empty"></span>'; ?>
	<?php require($_SERVER['DOCUMENT_ROOT']."/inc/tmpFiles/foot.php") ?>
</body>
</html>