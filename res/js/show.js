var tmp_like,tmp_dislike,tmp_favor,pos=[];
function cmp(p){
	return function(m,n){
		return m[p]-n[p];
	}
}
function treeWalk(node){
	var nxtPos=node+1;
	while(nxtPos<pos.length&&pos[nxtPos].fr<pos[node].to){
		pos[node].child.push(nxtPos);
		nxtPos=treeWalk(nxtPos);
	}
	return nxtPos;
}
function treeMake(str,node){
	var content=str.slice(pos[node].fr+1,(str.indexOf('<',pos[node].fr+1)==-1? str.length-1:str.indexOf('<',pos[node].fr+1))).replace(/>/gi,'').split('　');
	console.log(content);
	var nodeCode='<a href="showBooks.php?id='+content[0]+'">'+content[1]+'</a>';
	if(pos[node].child.length==0)return '<li>'+nodeCode+'</li>';
	var code='<ol class=bookcontent>';
	for(var ch of pos[node].child){
		var childCode=treeMake(str,ch);
		code+=childCode;
	}
	code+='</ol>';
	return '<li>'+nodeCode+code+'</li>';
}
function showContent(str){
	if(str==undefined)return '暂时没有目录哦';
	var stack=[];
	for(var i=0;i<str.length;i++){
		var ch=str[i];
		if(ch=='<')stack.push(i);
		else if(ch=='>'){
			var fr=stack.pop(),to=i;
			pos.push({'fr':fr,'to':to,'child':[]});
		}
	}
	pos.sort(cmp("fr"));
	treeWalk(0);
	console.log(str);
	tree=treeMake(str,0);
	return tree;
}
$("document").ready(function(){
	tmp_like=$(".chLike").text();
	tmp_dislike=$(".chDislike").text();
	tmp_favor=$(".contr:eq(0)").find("img").attr("src");
	$("input[hidden]").attr("disabled",true);
	$(".contr:eq(0)").click(function(){
		if($(this).find("img").attr("src")=="/../../res/img/ico/favor-empty.png"){
			$(this).find("img").attr("src","/../../res/img/ico/favor-full.png");
		}
		else $(this).find("img").attr("src","/../../res/img/ico/favor-empty.png");
	});
	$(".chLike").click(function(){
		if(!$(this).hasClass("fulllike")){
			var like=Number($(this).text());
			$(this).text(like+1);
			if($(this).next().hasClass("fulldislike")){
				$(this).next().toggleClass("fulldislike");
				var like=Number($(this).next().text());
				$(this).next().text(like-1);
			}
		}
		else{
			var like=Number($(this).text());
			$(this).text(like-1);
		}
		$(this).toggleClass("fulllike");
	});
	$(".chDislike").click(function(){
		if(!$(this).hasClass("fulldislike")){
			var like=Number($(this).text());
			$(this).text(like+1);
			if($(this).prev().hasClass("fulllike")){
				$(this).prev().toggleClass("fulllike");
				var like=Number($(this).prev().text());
				$(this).prev().text(like-1);
			}
		}
		else{
			var like=Number($(this).text());
			$(this).text(like-1);
		}
		$(this).toggleClass("fulldislike");
	});
	$("form:eq(1)").submit(function(){
		$(window).bind("beforeunload",null);
		$("input[hidden]").removeAttr("disabled");
		if($(".contr:eq(0)").find("img").attr("src")!=tmp_favor)
			$("input:eq(3)").val(1);
		if($(".chDislike").text()!=tmp_dislike)
			$("input:eq(2)").val(1);
		if($(".chLike").text()!=tmp_like)
			$("input:eq(1)").val(1);
		if($("input:eq(1)").val()==$("input:eq(2)").val()&&$("input:eq(1)").val()!=''){
			if($(".chLike").hasClass("fulllike"))$("input:eq(2)").val(null);
			else $("input:eq(1)").val(null);
		}
		var formdata=$("form:eq(1)").serialize();
		$.ajax({
			url:document.baseURI,
			type:"POST",
			data:formdata
		});
		setTimeout(window.location.reload(),500);
		return false;
	});
	$(window).bind("beforeunload",function(){
		$("input[hidden]").removeAttr("disabled");
		if($(".contr:eq(0)").find("img").attr("src")!=tmp_favor)
			$("input:eq(3)").val(1);
		if($(".chDislike").text()!=tmp_dislike)
			$("input:eq(2)").val(1);
		if($(".chLike").text()!=tmp_like)
			$("input:eq(1)").val(1);
		if($("input:eq(1)").val()==$("input:eq(2)").val()&&$("input:eq(1)").val()!=''){
			if($(".chLike").hasClass("fulllike"))$("input:eq(2)").val(null);
			else $("input:eq(1)").val(null);
		}
		$("textarea").attr("disabled","true");
		var formdata=$("form:eq(1)").serialize();
		$.ajax({
			url:document.baseURI,
			type:"POST",
			data:formdata
		});
	});
	if($(".bookcontent").length>0)
		$(".bookcontent").prop('innerHTML',showContent($(".bookcontent").text()));
});