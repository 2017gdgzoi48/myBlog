<!doctype html>
<?php
	session_start();
	require_once($_SERVER['DOCUMENT_ROOT']."/inc/dbFunc.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/inc/blogInfo.php");
	$conn=openConnection();
	if(empty($_SESSION["user_info"]))header("Location:".$webRoot."/showMsg.php?msg=您还未登录！");
	else{
		$_SESSION["user_info"]=NULL;
		header("Location:".$webRoot."/showMsg.php?msg=退出成功！");
	}
?>