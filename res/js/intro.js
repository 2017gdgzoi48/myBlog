$("document").ready(function(){
	var num=Number($("#photoCount").text()),countNow=1;
	var imgPath='/res/img/album/';
	function goNxt(){
		$("#mid").animate({"left":"500px"},"500");
		$("#pre").animate({"left":"0px"},"500");
		setTimeout(function(){
			$("#nxt").attr("src",$("#mid").attr("src"));
			$("#mid").attr("src",$("#pre").attr("src"));
		},500);
		setTimeout(function(){
			$("#mid").attr("display","none");
			$("#mid").css("left","0px");
			$("#mid").attr("display","block");
			$("#pre").attr("display","none");
			$("#pre").css("left","-500px");
			$("#pre").attr("display","block");
			countNow=(countNow==1? num:countNow-1);
			$("#pre").attr("src",imgPath+(countNow==1? num:countNow-1)+".jpg");
		},600);
	}
	function goPre(){
		$("#mid").animate({"left":"-500px"},"500");
		$("#nxt").animate({"left":"0px"},"500");
		setTimeout(function(){
			$("#pre").attr("src",$("#mid").attr("src"));
			$("#mid").attr("src",$("#nxt").attr("src"));
		},500);
		setTimeout(function(){
			$("#mid").attr("display","none");
			$("#mid").css("left","0px");
			$("#mid").attr("display","block");
			$("#nxt").attr("display","none");
			$("#nxt").css("left","500px");
			$("#nxt").attr("display","block");
			countNow=(countNow==num? 1:countNow+1);
			$("#nxt").attr("src",imgPath+(countNow==num? 1:countNow+1)+".jpg");
		},600);
	}
	$("#goPre").click(function(){
		goPre();
	});
	$("#goNxt").click(function(){
		goNxt();
	});
	$("#album").mouseover(function(){
		clearInterval(intver);
	});
	$("#album").mouseleave(function(){
		intver=setInterval(goNxt,3000);
	});
	var intver=setInterval(goNxt,3000);
});