<?
	$show_comment_ip = substr($c_data['ip'],0,strrpos($c_data['ip'],".")).".***";
	$show_comment_ip = "<font class=list_eng>".$show_comment_ip."</font>";

$c_face ="";
if($c_data['ismember']){
	$commentWriter =mysql_fetch_array(mysql_query("select * from $member_table where no='".$c_data['ismember']."'"));
	if(@file_exists($commentWriter['picture'])) $c_face = "<img width='100' height='100' src='".$commentWriter['picture']."' border=0>";
}?>


<div class="comment normal">
		<div class="c_image"><?=$c_face?></div>
	<div class="c_info">		
		<span class="c_Name "><?=$comment_name?></span>
		<span class="reg_date"><?=date("Y-m-d H:i:s",$c_data[reg_date])?></span>
		<?=$show_comment_ip?>
		<span class="commentDel"><?=$a_del?>X</a></span>
	</div>
	<div class="ct" ></div>
	<div class="message" ><?=nl2br($c_memo)?></div>
</div>