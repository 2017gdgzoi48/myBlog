function check(na,pass){
	if(na===''||pass==='')return Boolean($("#info").html("用户名或密码不能为空！"));
	if((na.length>10||na.length<3)&&na.match(/^[\w-]+@\w+.com$/)===null)return Boolean($("#info").html("用户名长度错误！(4-10位)"));
	if(na.match(/[^\u4E00-\u9FA5A\w]$/)!==null)return Boolean($("#info").html("用户名含有非法字符！"));
	return 0;
}
function checkAndMd5(){
	$("input.submitButton").attr("disabled", true);
	var uname=$("input:eq(1)").val();
	var password=$("input:eq(2)").val();
	if(!check(uname,password)){
		$("input:eq(2)").val($.md5($("input:eq(2)").val()));
		return true;
	}
	else alert($("#info").html());
	$("input.submitButton").attr("disabled",false);
	return false;
}
function check_2(na,email,p2){
	if(na===''||email==='')return Boolean($("#info").html("用户名或邮箱不能为空！"));
	if((na.length>10||na.length<3)||(email.length<5))return Boolean($("#info").html("用户名或邮箱长度错误！"));
	if(na.match(/[^\u4E00-\u9FA5A\w]$/)!==null)return Boolean($("#info").html("用户名含有非法字符！"));
	if(email.match(/^[\w-]+@\w+.com$/)===null)return Boolean($("#info").html("请输入正确的邮箱！"));
	if((p2.length<5||p2.length>15)&&p2!=='')return Boolean($("#info").html("请输入5-15位的新密码!"));
	if(p2.match(/[^\S]/)!==null)return Boolean($("#info").html("新密码含有非法字符！"));
	return 0;
}
function checkAndMd5_2(){
	$("input.submitButton").attr("disabled", true);
	var uname=$("input:eq(1)").val();
	var email=$("input:eq(2)").val();
	var pass2=$("input:eq(4)").val();
	if(!check_2(uname,email,pass2)){
		$("input:eq(3)").val(($("input:eq(3)").val()==''? '':$.md5($("input:eq(2)").val())));
		$("input:eq(4)").val(($("input:eq(4)").val()==''? '':$.md5($("input:eq(3)").val())));
		return true;
	}
	else alert($("#info").html());
	$("input.submitButton").attr("disabled",false);
	return false;
}
function checkAndMd5_3(){
	$("input.submitButton").attr("disabled", true);
	var uname=$("input:eq(1)").val();
	var email=$("input:eq(2)").val();
	var pass1=$("input:eq(3)").val();
	var pass2=$("input:eq(4)").val();
	if(!check_2(uname,email,pass2)&&pass1==pass2){
		$("input:eq(3)").val(($("input:eq(3)").val()==''? '':$.md5($("input:eq(3)").val())));
		$("input:eq(4)").val(($("input:eq(4)").val()==''? '':$.md5($("input:eq(4)").val())));
		return true;
	}
	else alert((pass1!=pass2? '请输入相同的密码！':$("#info").html()));
	$("input.submitButton").attr("disabled",false);
	return false;
}
$("document").ready(function(){
	$("#reset").click(function(){
		$("input:gt(0):last-child").val('');
	});
});