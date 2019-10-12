$("document").ready(function (){
	$(".guideNav").each(function(){
		if($(this).hasClass("nowPage"))$(this).data("now","1");
		$(this).mouseover(function(){
			$(this).children().last().slideDown();
			if($(this).data("now")===undefined)$(this).addClass("nowPage");
		});
		$(this).mouseleave(function(){
			if($(this).data("now")===undefined)$(this).removeClass("nowPage");
			$(this).children().last().slideUp();
		});
	});
	$("body").children().last().after('<div style="height:100vh;width:100%;position:fixed;top:0;left:0;z-index:-1;background-color:rgba(255,255,255,0.6);"></div>');
	if($(".footer").prev().offset().top<screen.availHeight-30){
		$(".footer").attr('style','position:absolute;top:'+(document.body.offsetHeight-30)+'px;');
	}
});