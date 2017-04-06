<table id="view">
	<tr height="20">
		<td class="subject" style='word-break:break-all;padding:10'><img src=<?=$dir?>/notice.gif border="0">&nbsp;<?=$subject?>	</td>
	</tr>
	<tr>
		<td>
			<span style="float:left"><?=$face_image?><?=$name?></span>
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

				<?//=substr($memo,0,strlen($memo)-17)?>
				<?=$memo?>
		</td>
	</tr>
</table>


<!-- 간단한 답글 시작하는 부분 -->
<?=$hide_comment_start?> 
