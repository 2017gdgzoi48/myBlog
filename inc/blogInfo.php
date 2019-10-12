<?php 
	$webRoot='http://'.$_SERVER["HTTP_HOST"];

	//normal information 
	static $blogTitle="Myblog";
	static $blogLogo="";
	$blogName=($blogLogo==''? $blogTitle:'<img src="'.$webRoot.$blogLogo.'"/>');
	static $blogInited=true;
	static $blogUrl="yuckxi.com";

	// $webRoot=$_SERVER['DOCUMENT_ROOT'];
?>  