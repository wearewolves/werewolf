<?
if(eregi(":\/\/",$dir)||eregi("\.\.",$dir)) $dir ="./";
?>


<br><br><br>
<table border=0 width=300 cellspacing=0 cellpadding=0 align=center>
<form>
	<tr height=4>
		<td bgcolor=660325></td>
	</tr>
	<tr>
		<td align=center height=30><b>Error</b></td>
	</tr>
	<tr height=1>
		<td bgcolor=dddddd></td>
	</tr>
	<tr style='padding-top:10px;padding-bottom:10px;line-height:150%'>
		<td align=center><?echo $message;?></td>
	</tr>

<? if(!$url) { ?>
	<tr height=2>
		<td bgcolor=660325></td>
	</tr>
	<tr height=5>
		<td></td>
	</tr>
	<tr>
		<td align=right><a href=javascript:void(history.back()) onfocus='this.blur()'><img src=<?=$dir?>/cancel.gif border=0 alt=Ãë¼Ò></a></td>
	</tr>

<? } else { ?>
	<tr height=2>
		<td bgcolor=660325></td>
	</tr>
	<tr height=5>
		<td></td>
	</tr>
	<tr height=25>
		<td align=center><a href=# onclick=location.href="<?echo $url;?>" onfocus=blur()><img src=<?=$dir?>/submit.gif></a></td>
	</tr>
<? } ?>
</form>
</table>
<br><br>