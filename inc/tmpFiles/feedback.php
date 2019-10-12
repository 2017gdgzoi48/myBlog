<div class="control">
	<span class="contr"><img src="/../../res/img/ico/favor<?php echo ($favor? '-full':'-empty') ?>.png" title="收藏"、></span>
	<span class="contr"><a href="javascript:scrollTo({left:$('.line:eq(2)').offset().left,top:$('.line:eq(2)').offset().top,behavior: 'smooth'});"><img src="/../../res/img/ico/comment.png"  title="评论"/></a></span>
	<span class="contr"><a href="javascript:scrollTo({left:0,top:0,behavior: 'smooth'});"><img src="/../../res/img/ico/top.png" title="回到顶部" /></a></span>
</div>
<span class="choose" style="margin-right: 0;">
	<span class="chLike <?php if($like)echo 'fulllike'; ?>"><?php echo $itemInfo["like"]; ?></span>
	<span class="chDislike <?php if($dislike)echo 'fulldislike'; ?>"><?php echo $itemInfo["disLike"]; ?></span>
</span>
<span class="line" style="width: 90%;margin:20px auto;"></span>
<div id="comment">
	<span class="title" style="margin: 10px 5%;font-size: 30px;font-weight: bold;display: block;" >评论</span>
	<?php 
	// hello|world|
		if(empty($itemInfo["comment"]))echo '<span class="comment"><span>还没有评论哦，快来评论吧。</span></span>';
		else{
			$comm=explode('<',$itemInfo["comment"]);
			array_shift($comm);
			foreach($comm as $val){
				$comArr=explode('>',$val);
				echo '<span class="comment"><img src="/../../res/img/userImage/'.$comArr[0].'" /><span>'.$comArr[1].'　:　'.$comArr[2].'</span></span>';
			}
		}
		if(!empty($userInfo)){
			echo '<span class="comment">
			<img src="/../../res/img/userImage/'.$userInfo['uimg'].'" />
			<span>添加评论</span>
			<form method="post">
				<textarea name="comment" value=""></textarea>
				<input type="text" name="like" hidden>
				<input type="text" name="dislike" hidden>
				<input type="text" name="favor" hidden>
				<button>提交</button>
			</form>
			</span>';
		}
		else{
			echo '<span class="comment"><span>请先登录再评论，谢谢</span></span>';
		}
	?>
</div>