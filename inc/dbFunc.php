<?php 
	// normal information
	$db_host="localhost";
	$db_userName="root";
	$db_name="myblogDB";
	$db_password="wilson2005";
	// error_reporting(0);

	//operation functions
	function openConnection(){
		global $db_host,$db_password,$db_userName,$db_name;
		$conn=mysqli_connect($db_host,$db_userName,$db_password,$db_name);
		return $conn;
	}
	function databaseInit(){
		global $db_host,$db_password,$db_userName;
		$conn=mysqli_connect($db_host,$db_userName,$db_password);
		$sql="CREATE DATABASE `myblogdb`;
			USE `myblogdb`;
			CREATE TABLE `user` (
				`uid` INT(8) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
				`uname` TINYTEXT NOT NULL,
				`password` TEXT NOT NULL,
				`uimg` TINYTEXT NULL,
				`email` TINYTEXT NOT NULL,
				`favor` TEXT NULL,
				`level` TINYINT(1) NOT NULL DEFAULT '1',
				`likeArr` LONGTEXT NULL,
				`dislikeArr` LONGTEXT NULL,
				PRIMARY KEY (`uid`),
				UNIQUE INDEX `username` (`uname`(10)) USING BTREE,
				INDEX `email` (`email`(50))
			)
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB;
			CREATE TABLE `items` (
				`id` INT(8) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT,
				`name` TINYTEXT NOT NULL,
				`type` TINYINT(1) UNSIGNED NOT NULL,
				`contents` LONGTEXT NOT NULL,
				`extraInfo` TEXT NULL,
				`dateTime` DATE NOT NULL,
				`disLike` INT(11) NULL DEFAULT '0',
				`readCount` INT(8) UNSIGNED NOT NULL DEFAULT '0',
				`comment` LONGTEXT NULL,
				`like` INT(11) NULL DEFAULT '0',
				`tags` TEXT NOT NULL,
				`class` TEXT NOT NULL,
				`secret` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
				`uid` INT(8) UNSIGNED ZEROFILL NOT NULL,
				PRIMARY KEY (`id`),
				INDEX `name` (`name`(50), `type`, `tags`(100), `class`(20)),
				INDEX `userid` (`uid`),
				CONSTRAINT `userid` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`) ON UPDATE CASCADE ON DELETE CASCADE
			)
			COLLATE='utf8_general_ci'
			ENGINE=InnoDB;";
		mysqli_multi_query($conn, $sql);
	}

	// get part

	function getClasses($conn){
		$class1=Array();
		$class2=Array();
		$class3=Array();
		$sql="SELECT DISTINCT `class` from items where type=1;";
		$result=mysqli_query($conn,$sql);
		while($row=mysqli_fetch_array($result))array_push($class1,$row["class"]);
		$sql="SELECT DISTINCT `class` from items where type=2;";
		$result=mysqli_query($conn,$sql);
		while($row=mysqli_fetch_array($result))array_push($class2,$row["class"]);
		$sql="SELECT DISTINCT `class` from items where type=3;";
		$result=mysqli_query($conn,$sql);
		while($row=mysqli_fetch_array($result))array_push($class3,$row["class"]);
		$classAll=Array("blog"=>$class1,"book"=>$class2,"download"=>$class3);
		return $classAll;
	}	
	function getOrderedItems($conn){
		$oItem1=Array();
		$oItem2=Array();
		$oItem3=Array();
		$oItem4=Array();
		$oItem5=Array();
		$oItem6=Array();
		$sql="SELECT `name`,`dateTime` FROM `items` WHERE type=1 order by `dateTime` desc LIMIT 4";
		$result=mysqli_query($conn,$sql);
		while($row=mysqli_fetch_row($result))array_push($oItem1,$row);
		$sql="SELECT `name`,`dateTime` FROM `items` WHERE type=2 order by `dateTime` desc LIMIT 4";
		$result=mysqli_query($conn,$sql);
		while($row=mysqli_fetch_row($result))array_push($oItem2,$row);
		$sql="SELECT `name`,`dateTime` FROM `items` WHERE type=3 order by `dateTime` desc LIMIT 4";
		$result=mysqli_query($conn,$sql);
		while($row=mysqli_fetch_row($result))array_push($oItem3,$row);
		$sql="SELECT `name`,`readCount` FROM `items` WHERE type=1 order by `readCount` desc LIMIT 4";
		$result=mysqli_query($conn,$sql);
		while($row=mysqli_fetch_row($result))array_push($oItem4,$row);
		$sql="SELECT `name`,`readCount` FROM `items` WHERE type=2 order by `readCount` desc LIMIT 4";
		$result=mysqli_query($conn,$sql);
		while($row=mysqli_fetch_row($result))array_push($oItem5,$row);
		$sql="SELECT `name`,`readCount` FROM `items` WHERE type=3 order by `readCount` desc LIMIT 4";
		$result=mysqli_query($conn,$sql);
		while($row=mysqli_fetch_row($result))array_push($oItem6,$row);
		$oItem=Array("blog"=>Array($oItem4,$oItem1),"book"=>Array($oItem5,$oItem2),"download"=>Array($oItem6,$oItem3));
		return $oItem;
	}
	function getTags($conn){
		$sql="SELECT tags FROM `items`";
		$result=mysqli_query($conn,$sql);
		$tag='';
		while($row=mysqli_fetch_array($result))$tag.=$row["tags"].'|';
		$tag=explode('|',$tag);
		sort($tag);
		$tag=array_unique($tag);
		return $tag;
	}
	function getFriendLink($conn){
		$sql="SELECT `name`,`contents` FROM `items` WHERE `type`=5";
		$result=mysqli_query($conn,$sql);
		$friendLink=Array();
		while($row=mysqli_fetch_row($result))array_push($friendLink,$row);
		return $friendLink;
	}
	function getIndexIntro($conn){
		$sql="SELECT `contents`,`extraInfo` FROM `items` WHERE `type`=6";
		$result=mysqli_query($conn,$sql);
		while($row=mysqli_fetch_row($result))return $row;
	}
	function getUserInfo($conn,$uname){
		$sql="SELECT uid,uname,uimg,level,likeArr,dislikeArr,favor FROM `user` WHERE uname='".$uname."' OR email='".$uname."';";
		$result=mysqli_query($conn,$sql);
		while($row=mysqli_fetch_assoc($result))return $row;
	}
	function getUserAllInfo($conn,$uid){
		$sql="SELECT * FROM `user` WHERE uid='".$uid."';";
		$result=mysqli_query($conn,$sql);
		while($row=mysqli_fetch_assoc($result))return $row;
	}
	function checkUserLogin($conn,$uarr){
		$sql="SELECT `password` FROM `user` WHERE `uname`='".$uarr["uname"]."' OR `email`='".$uarr["uname"]."';";
		$result=mysqli_query($conn,$sql);
		if($result!=false and $result->num_rows==0)return '用户名或邮箱错误';
		$result=mysqli_fetch_assoc($result);
		// echo $result["password"]." ".sha1($uarr["password"]);
		if($result["password"]!==sha1($uarr["password"]))return '密码错误';
		return '';
	}
	function getItemInfo($conn,$id){
		$sql='';
		if($id!=-1)$sql.="SELECT * FROM `items` WHERE id='".$id."';";
		else $sql.="SELECT * FROM `items` WHERE type=4;";
		$result=mysqli_query($conn,$sql);
		while($row=mysqli_fetch_assoc($result))return $row;
	}
	function searchItem($conn,$filter){
		$sql="SELECT `id`,`type`,`name`,`uid`,`readCount`,`dateTime`,`tags`,`secret` FROM `items`";
		if(!empty($filter)){
			foreach($filter as $key=>$val){
				if(!is_array($val))$filter[$key]=$key." LIKE '%".$val."%'";
				else if($key!='order' and $key!='tags'){
					if($key!='type')
						foreach($val as &$value){
							$value='"'.$value.'"';
						}
					$tmpSql=$key.' IN ('.implode(',',$val).')';
					$filter[$key]=$tmpSql;
				}
				else if($key=='tags'){
					foreach($val as &$value){
						$value='tags LIKE "%'.$value.'%"';
					}
					$filter[$key]=' ('.implode(' OR ',$val).') ';
				}
			}
			$tmpSql='';
			if(!empty($filter['order'])){
				$tmpSql=' ORDER BY '.$filter['order'][0];
				array_pop($filter);
			}
			if(!empty($filter))$sql.=' WHERE '.implode(' AND ',$filter);
			$sql.=$tmpSql;
		}
		$res=mysqli_query($conn,$sql);
		$result=Array();
		while($row=mysqli_fetch_assoc($res))array_push($result,$row);
		return $result;
	}
	function getAuInfo($conn,$id){
		$sql="SELECT DISTINCT `class` FROM `items` WHERE uid=".$id." AND type IN (1,2,3)";
		$res=mysqli_query($conn,$sql);
		$class=Array();
		while($row=mysqli_fetch_assoc($res))array_push($class,$row["class"]);
		$sql="SELECT `tags` FROM `items` WHERE uid=".$id." AND type IN (1,2,3)";
		$res=mysqli_query($conn,$sql);
		$tag=Array();
		while($row=mysqli_fetch_assoc($res))array_push($tag,$row["tags"]);
		$sql="SELECT type,count(`id`) as count FROM `items` WHERE uid=".$id." and type GROUP BY type";
		$auCount=Array(1=>0,2=>0,3=>0);
		$res=mysqli_query($conn,$sql);
		while($row=mysqli_fetch_assoc($res))$auCount[$row["type"]]=$row["count"];
		return Array("class"=>$class,"tags"=>$tag,"auCount"=>$auCount);
	}

	// Edit Part

	function editUserInfo($conn,$id,$infoArr,$uimg){
		$sql="SELECT * FROM `user` WHERE (`name`='".$infoArr["uname"]."' OR `email`='".$infoArr["email"]."') AND `id`<>".$id.";";
		$result=mysqli_query($conn,$sql);
		if($result!=false and $result->num_rows!=0)return '用户名或邮箱重复';
		$tmpInfo=getUserAllInfo($conn,$id);
		if($infoArr["password"]!='' and sha1($infoArr["password"])!=$tmpInfo["password"])
			return '旧密码错误';
		if($infoArr["password"]!='' and $infoArr["npassword"]=='')return '请输入新密码';
		$sql="UPDATE `user` SET `uname`='".$infoArr["uname"]."',`password`='".(empty($infoArr["npassword"])? $tmpInfo["password"]:sha1($infoArr["npassword"]))."',`uimg`=".($uimg? "'".$id.".png'":'uimg').",`email`='".$infoArr["email"]."',`level`=".(empty($infoArr["level"])? 'level':$infoArr["level"])." WHERE `uid`='".$id."'";
		mysqli_query($conn,$sql);
		return '';
	}
	function addUser($conn,$infoArr,$uimg){
		$sql="SELECT * FROM `user` WHERE (`name`='".$infoArr["uname"]."' OR `email`='".$infoArr["email"]."');";
		$result=mysqli_query($conn,$sql);
		if($result!=false and $result->num_rows!=0)return '用户名或邮箱重复';
		$sql="INSERT INTO `user`(`uname`, `password`, `email`) VALUES ('".$infoArr['uname']."','".sha1($infoArr['password'])."','".$infoArr['email']."');UPDATE `user` SET `uimg`=".($uimg? 'CONCAT(LPAD(LAST_INSERT_ID(),8,0),\'.png\')':'"default.png"')." WHERE `uid`=LAST_INSERT_ID();SELECT LAST_INSERT_ID();";
		mysqli_multi_query($conn,$sql);
		mysqli_next_result($conn);
		mysqli_next_result($conn);
		$res=mysqli_store_result($conn);
		$res=mysqli_fetch_row($res);
		return (int)$res[0];
	}
	function addComment($conn,$id,$comm){
		$sql="UPDATE `items` SET `comment`=CONCAT_WS('',`comment`,'".$comm."') WHERE `id`=".$id.";";
		// echo $id;
		mysqli_query($conn,$sql);
	}
	function updateLike($conn,$info,$id,$like,$dis){
		$sql="UPDATE `user` SET `likeArr`='".$info["likeArr"]."' WHERE `uid`=".$info["uid"].";";
		mysqli_query($conn,$sql);
		if($like)$sql="UPDATE `items` SET `like`=`like`-1 WHERE `id`=".$id.";";
		else{
			$sql="UPDATE `items` SET `like`=`like`+1 WHERE `id`=".$id.";";
			if($dis)
				$sql.="UPDATE `items` SET `dislike`=`dislike`-1 WHERE `id`=".$id.";";
		}
		mysqli_multi_query($conn,$sql);
	}
	function updateDislike($conn,$info,$id,$like,$dis){
		$sql="UPDATE `user` SET `dislikeArr`='".$info["dislikeArr"]."' WHERE `uid`=".$info["uid"].";";
		mysqli_query($conn,$sql);
		if($dis)$sql="UPDATE `items` SET `dislike`=`dislike`-1 WHERE `id`=".$id.";";
		else{
			$sql="UPDATE `items` SET `dislike`=`dislike`+1 WHERE `id`=".$id.";";
			if($like)
				$sql.="UPDATE `items` SET `like`=`like`-1 WHERE `id`=".$id.";";
		}
		mysqli_multi_query($conn,$sql);
	}
	function updateFavor($conn,$info){
		$sql="UPDATE `user` SET `favor`='".$info["favor"]."' WHERE `uid`=".$info["uid"].";";
		mysqli_query($conn,$sql);
	}
?>