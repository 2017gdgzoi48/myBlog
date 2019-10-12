<?php
	session_start();
	header('Content-type:image/jpeg');
	$width=120;
	$height=40;
	$string='';
	$img=imagecreatetruecolor($width, $height);
	$arr=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','0','1','2','3','4','5','6','7','8','9');
	$colorBg=imagecolorallocate($img,rand(200,255),rand(200,255),rand(200,255));
	imagefill($img, 0, 0, $colorBg);
	for($i=1;$i<=200;$i++){
	    $pointcolor=imagecolorallocate($img,rand(0,255),rand(0,255),rand(0,255));
	    imagesetpixel($img,rand(0,$width-1),rand(0,$height-1),$pointcolor);
	}
	for ($i=0;$i<=6;$i++){
	    $linecolor=imagecolorallocate($img,rand(0,255),rand(0,255),rand(0,255));
	    imageline($img,rand(0,$width-1),rand(0,$height-1),rand(0,$width-1),rand(0,$height-1),$linecolor);
	}
	for($i=0;$i<4;$i++){
		$string.=$arr[rand(0,count($arr)-1)];
	}
	$_SESSION["vcode"]=$string;
	$colorString=imagecolorallocate($img,rand(10,100),rand(10,100),rand(10,100));
	// imagestring($img,5,rand(0,$width-36),rand(0,$height-15),'',$colorString);
	imagettftext($img,15,rand(-20,20),rand(5,50),rand(25,20),$colorString,'C:\Windows\Fonts\alger.ttf',$string);
	imagejpeg($img);
	imagedestroy($img);
?>
