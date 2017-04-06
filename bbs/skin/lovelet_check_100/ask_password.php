<?
if(eregi(":\/\/",$dir)||eregi("\.\.",$dir)) $dir ="./";
?>


<br><br><br>

<table border=0 width=300 cellspacing=0 cellpadding=0 align=center>
<form method=post name=delete action=<?=$target?>>
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=no value=<?=$no?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=category value="<?=$category?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<input type=hidden name=mode value="<?=$mode?>">
<input type=hidden name=c_no value=<?=$c_no?>>
	<tr height=4>
		<td bgcolor=660325></td>
	</tr>
	<tr>
		<td align=center style='padding-top:10px;padding-bottom:10px;line-height:150%'><?=$title?></td>
	</tr>
	<tr height=1>
		<td bgcolor=dddddd></td>
	</tr>
<?	if(!$member[no]) {?>
	<tr height=60>
		<td align=center valign=center>

	<table border=0 cellspacing=0 cellpadding=0>
		<tr height=15>
			<td><img src="<?=$dir?>/pass.gif"></td>
		</tr>
		<tr height=30>
			<td><?=$input_password?></td>
		</tr>
	</table></td>
	</tr>
	<tr height=2>
		<td bgcolor=660325></td>
	</tr>
<?	}?>
	<tr height=5>
		<td></td>
	</tr>
	<tr height=25>
		<td align=right><input type=image src=<?=$dir?>/submit.gif border=0 accesskey="s" onfocus='this.blur()' alt=확인><a href=javascript:void(history.back()) onfocus='this.blur()'><img src=<?=$dir?>/cancel.gif border=0 alt=취소></a></td>
	</tr></form>
</table>

<br><br>