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
?>
<head>
	<meta charset="UTF-8">
	<title>MyBlog</title>
	<script type="text/javascript" src="<?php echo $webRoot ?>/inc/codes/jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="<?php echo $webRoot ?>/inc/codes/latex.js"></script>
	<script type="text/javascript" src="<?php echo $webRoot ?>/inc/codes/editer.js"></script>
	<script type="text/javascript" src="<?php echo $webRoot ?>/inc/codes/highcharts.js"></script>
	<link rel="stylesheet" href="<?php echo $webRoot ?>/res/css/main.css">
	<link rel="stylesheet" href="<?php echo $webRoot ?>/res/css/form.css">
	<link rel="stylesheet" href="<?php echo $webRoot ?>/res/css/index.css">
	<link rel="stylesheet" href="<?php echo $webRoot ?>/res/css/markdown.css">
	<script type="text/javascript" src="<?php echo $webRoot ?>/res/js/main.js"></script>
	<style>
		.toolbar{
			display: inline-block;
			vertical-align: top;
		}
		.toolbar span{
			
		}
	</style>
	<script>
		function lang_highlight(str,lang){
			if(typeof(lastLang)==='undefined')lastLang=lang;
			if(typeof(comm)==='undefined')comm=false;
			if(lang!=lastLang)comm=false;
			var support_lang=['css'];
			if(lang.indexOf('=')!=-1){
				return '<span style="'+lang+'">'+str+'</span>';
			}
			else if(support_lang.some(lan => lan==lang)){
				switch (lang) {
					case 'css':
						var pos=str.search(/\/\*/g);
						var pos2=str.search(/\*\//g);
						if((pos!=-1&&pos2!=-1))
							str=str.slice(0,pos)+'<span class="comment">'+str.slice(pos,pos2+2)+'</span>'+str.slice(pos2+2);
						if(pos==-1&&pos2!=-1){
							comm=false;
							str='<span class="comment">'+str+'</span>';
							break;
						}
						if(pos!=-1&&pos2==-1||comm){
							lastLang=lang;
							comm=true;
							str='<span class="comment">'+str+'</span>';
							break;
						}
						str=str.replace(/!important|@media|@keyframes/g,'<span class="keywords">$&</span>');
						str=str.replace(/([^\n:<>'"]+)((?:.+)?(?:{))/g,'<span class="other">$1</span>$2');
						str=str.replace(/([^\w\s<>'"=//\-:#;&]|&gt;|&lt;|&#47;|&#61;| \- ){1}/g,'<span class="op">$&</span>');
						str=str.replace(/(&quot;|&apos;)[^\1]*\1/g,'<span class="string">$&</span>');
						str=str.replace(/(?<!&amp;#)#?[0-9]+[\w;]*(|;\n)/g,'<span class="num">$&</span>');
						str=str.replace(/([^\n:<>]+):([^;\n]*;\n?)/g,'$`<span class="name">$1:</span>$2');
						break;
				}
			}
			return str;
		}
		function markdown_replace(str){			
			var markReg={
				reg_block:/^((?!\\)&gt;(.+\n)+\n)|^((?!\\)&gt;+.+\n?)+/mg,
				reg_title:/#{1,6} .*/mg,
				reg_block_code:/```(.+)\n[\s\S]*```/mg,
				reg_inline_code:/``(.+)\n[\s\S]*``/mg,
				reg_itac_bold:/(?!<code>[\s\S]*)((?![^\\]?)\*{3}.*(?![^\\]?)\*{3}|(?![^\\]?)_{3}.*(?![^\\]?)_{3})(?![\s\S]*<\/code>)/mg,
				reg_bold:/(?!<code>[\s\S]*)((?![^\\]?)\*{2}.*(?![^\\]?)\*{2}|(?![^\\]?)_{2}.*(?![^\\]?)_{2})(?![\s\S]*<\/code>)/mg,
				reg_itac:/(?!<code>[\s\S]*)((?![^\\]?)\*{1}.*(?![^\\]?)\*{1}|(?![^\\]?)_{1}.*(?![^\\]?)_{1})(?![\s\S]*<\/code>)/mg,
				reg_img:/!\[.+\]\(.+(\s+".+")?\)/mg,
				reg_link:/\[.+\] ?\(.+(\s+".+")?\)/mg,
				reg_h1:/^.+\n(&#61;)+$/mg,
				reg_h2:/^.+\n\-+$/mg,
				reg_line:/^(\* *|\- *|_ *){3,}$/g,
				reg_order_list:/([0-9](?!\\)\..+\n?)+(\t*[0-9](?!\\)\..+)?/mg,
				reg_list:/(((?!\\)[*\-+]).+\n?)+/mg,
				reg_latex:/(\$|\${2})[^\$]+\1/mg,
				reg_keyword:/\\[\\`\*_{}\[\]\(\)#\+\-\.!>]{1}/mg,
				reg_code:/^(( {4}|\t).+\n?)+/mg,
				reg_br:/( {2}|\n)/mg
			};
			str=str.replace(/(?!\\)&/g,'&amp;');
			str=str.replace(/(?!\\)>/g,'&gt;');
			str=str.replace(/(?!\\)</g,'&lt;');
			str=str.replace(/(?!\\)"/g,'&quot;');
			str=str.replace(/(?!\\)'/g,'&apos;');
			str=str.replace(/(?!\\)\//g,'&#47;');
			str=str.replace(/(?!\\)=/g,'&#61;');
			for(var key in markReg){
				switch (key) {
					case 'reg_block':
						str=str.replace(markReg[key],function(match){
							var contentArr=match.trim().split('\n');
							var newContentArr=[];
							for(var line of contentArr){
								if(line.search(/^&gt;/)==0)line=line.slice(4).trim();
								newContentArr.push(line);
							}
							var content=newContentArr.join('\n');
							return '<span class="block">'+content+'</span>';
						});
						break;
					case 'reg_title':
						str=str.replace(markReg[key],function(match){
							var cou=match.split('').filter(ch => ch=='#').length;
							return '<h'+cou+'>'+match.slice(cou+1)+'</h'+cou+'>';
						});
						break;
					case 'reg_itac':
						str=str.replace(markReg[key],function(match){
							var content=match.slice(1,match.length-1).trim();
							return '<em>'+content+'</em>';
						});
						break;
					case 'reg_bold':
						str=str.replace(markReg[key],function(match){
							var content=match.slice(2,match.length-2).trim();
							return '<strong>'+content+'</strong>';
						});
						break;
					case 'reg_itac_bold':
						str=str.replace(markReg[key],function(match){
							var content=match.slice(3,match.length-3).trim();
							return '<strong><em>'+content+'</em></strong>';
						});
						break;
					case 'reg_order_list':
						str=str.replace(markReg[key],function(match){
							var contentArr=match.trim().split('\n');
							var newContentArr=[];
							for(var line of contentArr){
								var pos=line.indexOf('.');
								if(line.trimLeft()==line)line=line.trimLeft().slice(pos+1);
								else line=line.slice(1);
								line='<li>'+line+'</li>';
								newContentArr.push(line);
							}
							var content=newContentArr.join('');
							return '<ol>'+content+'</ol>';
						});
						break;
					case 'reg_list':
						str=str.replace(markReg[key],function(match){
							var contentArr=match.trim().split('\n');
							var newContentArr=[];
							for(var line of contentArr){
								line=line.slice(1).trimLeft();
								line='<li>'+line+'</li>';
								newContentArr.push(line);
							}
							var content=newContentArr.join('');
							return '<ul>'+content+'</ul>';
						});
						break;
					case 'reg_line':
						str=str.replace(markReg[key],function(match){
							return '<hr />';
						});
						break;
					case 'reg_h1':
						str=str.replace(markReg[key],function(match){
							return '<h1>'+match.slice(0,match.indexOf('\n'))+'</h1>';
						});
						break;
					case 'reg_h2':
						str=str.replace(markReg[key],function(match){
							return '<h2>'+match.slice(0,match.indexOf('\n'))+'</h2>';
						});
						break;					
					case 'reg_br':
						str=str.replace(markReg[key],function(match){
							return '<br />';
						});
						break;
					case 'reg_block_code':
						str=str.replace(markReg[key],function(match){
							var content=match.slice(match.indexOf('\n')+1,match.length-3);
							var lang=match.slice(3,match.indexOf('\n'));
							var contentArr=content.split('\n');
							var newContent='';
							for(var line of contentArr){
								line=lang_highlight(line,lang);
								newContent+=line.replace('\t','&nbsp;&nbsp;')+'\n';
							}
							return '<p><code>'+newContent+'</code></p>';
						});
						break;
					case 'reg_inline_code':
						str=str.replace(markReg[key],function(match){
							var content=match.slice(2,match.length-2);
							var lang=match.slice(3,match.indexOf('\n'));
							if(lang.indexOf('=')==-1)return '<p><code>'+lang_highlight(content,lang)+'</code></p>';
							else return lang_highlight(content,lang);
						});
						break;
					case 'reg_code':
						str=str.replace(markReg[key],function(match){
							var content=match.trim();
							return '<pre><code>'+content+'</code></pre>';
						});
						break;
					case 'reg_link':
						str=str.replace(markReg[key],function(match){
							var name=match.slice(match.indexOf('[')+1,match.indexOf(']'));
							var link=match.slice(match.indexOf('(')+1,match.indexOf('"')).trim();
							var title=match.slice(match.indexOf('"')+1,match.lastIndexOf('"')).trim();
							if(title.indexOf('"')==-1)title='';
							return '<a href="'+link+'" title="'+title+'">'+name+'</a>';
						});
						break;
					case 'reg_img':
						str=str.replace(markReg[key],function(match){
							match=match.slice(1);
							var name=match.slice(match.indexOf('[')+1,match.indexOf(']'));
							var link=match.slice(match.indexOf('(')+1,match.length).trim();
							return '<img src="'+link+'" alt="'+name+'" />';
						});
						break;
					case 'reg_keyword':
						str=str.replace(markReg[key],function(match){
							return match.slice(1);
						});
						break;
					case 'reg_latex':
						str=str.replace(markReg[key],function(match){
							return '<p>'+match.slice(1,match.length-1)+'</p>';
						});
						break;
				}
			}
			str=str.replace(/&gt;/g,'>');
			str=str.replace(/&lt;/g,'<');
			str=str.replace(/&quot;/g,'"');
			str=str.replace(/&apos;/g,"'");
			str=str.replace(/&#47;/g,'/');
			str=str.replace(/&#61;/g,'=');
			str=str.replace(/&amp;/g,'&');
			return str;
		}
	</script>
</head>
<body style="margin: 0px;padding: 0px;background-color: rgba(0, 0, 0, 0.06);">
	<?php require($_SERVER['DOCUMENT_ROOT']."/inc/tmpFiles/guide.php") ?>
	<div class="formpart main">
		<span class="title formtitle">创建新博客</span>
		<br>
		<span class="line" style="width: 80%;margin: auto;"></span>
		<form method="post" class="form content">
			<div class="formitem">
				<span class="formdesc">博客名称</span>
				<input type="text" name="name" style="display: inline-block;width: 700px">
			</div>
			<br>
			<div class="formitem">
				<span class="formdesc" style="vertical-align: top;">博客内容</span>
				<textarea name="contents" placeholder="markdown编辑器，如需输入数学公式直接输入即可(不用黏贴图片)" style="text-indent: 0px;"></textarea>
			</div>
			<div class="formitem toolbar" >
				<span>hello</span>
			</div>
		</form>
	</div>
	<?php require($_SERVER['DOCUMENT_ROOT']."/inc/tmpFiles/foot.php") ?>
	<!-- OpenLatexEditor('examplebox','latex','zh-cn',true,'1+sin(x)','mini'); -->
</body>
</html>