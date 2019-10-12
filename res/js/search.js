$("document").ready(function(){
	$(".turner").click(function(){
		$(".highSearch").slideToggle("1000");
		$(".turner").html(($(".turner").text()=="-收起过滤器"? "+展开过滤器":"-收起过滤器")+'<img src="/res/img/ico/filter.png">');
	});
	$(".wayitem").click(function(){
		var faChoice=$(this).prevUntil("br").last();
		var broChoice=faChoice.nextUntil("br").not($(this));
		if(faChoice.attr("name")==='order')
			broChoice.removeClass("waySelect");
		$(this).toggleClass("waySelect");
	});
	$("form#search").find("img").click(function(){
		var inputs=$(this).nextAll().slice(1);
		inputs.each(function(index,ele){
			var sele=$(".highSearch").find(".waytitle").eq(index).nextUntil("br").filter(".waySelect");
			var resStr='';
			sele.each(function(){
				if($(this).data("code")!=null)resStr+=$(this).data("code")+'|';
				else resStr+=$(this).text()+'|';
			});
			$(this).val(resStr);
		});
		$(this).parent().submit();
	});
});	