<table id="view">
	<tr height="20">
		<td class="subject" style='word-break:break-all;padding:10'><img src=<?=$dir?>/notice.gif border="0">&nbsp;<?=$subject?>	</td>
	</tr>
	<tr>
		<td>
			<span class=sly style="float:right"><?=$date?>&nbsp;&nbsp;&nbsp; Hit:<?=$hit?>&nbsp;&nbsp;&nbsp; Vote:<?=$vote?> </span>
		</td>
	</tr>
	<? if($data[homepage]){?>
	<tr>		
		<td align=left>
				<a href="<?=$data[homepage]?>" target="_blank" onfocus='this.blur()'>
				<span style="font-family:verdana;font-size:7pt;"><img src=<?=$dir?>/home.gif border=0></a></span>
		</td>
	</tr>
	<? }?>
	<tr>
		<td style='word-break:break-all;'>
				<?=$hide_sitelink1_start?><font class=list_eng>- <b>SiteLink #1</b> : <?=$sitelink1?></font><br><?=$hide_sitelink1_end?>
				<?=$hide_sitelink2_start?><font class=list_eng>- <b>SiteLink #2</b> : <?=$sitelink2?></font><br><?=$hide_sitelink2_end?>
				<?=$hide_download1_start?><font class=list_eng>- <b>Download #1</b> : <?=$a_file_link1?><?=$file_name1?> (<?=$file_size1?>)</a>, Download : <?=$file_download1?></font><br><?=$upload_image1?><?=$hide_download1_end?>
				<?=$hide_download2_start?><font class=list_eng>- <b>Download #2</b> : <?=$a_file_link2?><?=$file_name2?> (<?=$file_size2?>)</a>, Download : <?=$file_download2?></font><br><?=$upload_image2?><?=$hide_download2_end?>
		</td>
	</tr>
</table>

<div id="commentContainer">
<?
$c_face ="";
if($data['ismember']){
	$commentWriter =mysql_fetch_array(mysql_query("select * from $member_table where no='".$data['ismember']."'"));
	if(@file_exists($commentWriter['picture'])) $c_face = "<img width='100' height='100' src='".$commentWriter['picture']."' border=0>";
}

// confirm whether this comment is normal or notice
$c_normal_flag = ($data[headnum] > -2000000000) ? true : false;
?>
<? if($c_normal_flag) { ?>
	<div class="commentBodyContents">
		<div class="comment normal">
				<div class="c_image"><?=$c_face?></div>
			<div class="c_info">
				<span class="c_Name "><?=$name?></span>
				<span class="reg_date"><?=$date?></span>
			</div>
			<div class="ct" ></div>
			<div class="message" ><?=$memo?></div>
		</div>
	</div>
<? } else { ?>
	<!-- hide member photo, member name and ct when it is posted for notice -->
	<div class="commentBodyContents">
		<div class="comment notice">
			<div class="c_info">
				<span class="reg_date"><?=$date?></span>
			</div>
			<div class="message" ><?=$memo?></div>
		</div>
	</div>
<? } ?>


<!-- 간단한 답글 시작하는 부분 -->
<?=$hide_comment_start?> 

