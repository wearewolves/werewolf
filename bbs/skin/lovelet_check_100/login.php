<?
if(eregi(":\/\/",$dir)||eregi("\.\.",$dir)) $dir ="./";
?>



<br><br><br>

<table border=0 width=300 cellspacing=0 cellpadding=0 style="table-layout:fixed">
	<tr height=4>
		<td bgcolor=660325></td>
	</tr>
	<tr>
		<td align=center height=30><b>Login</b></td>
	</tr>
	<tr height=1>
		<td bgcolor=dddddd></td>
	</tr>
	<tr style='padding-top:10px;padding-bottom:10px;line-height:150%'>
		<td align=center>

	<table border=0 cellspacing=0 cellpadding=0>
		<tr height=15>
			<td><img src="<?=$dir?>/id.gif"></td>
			<td width=10></td>
			<td><img src="<?=$dir?>/pass.gif"></td>
		</tr>
		<tr height=30>
			<td><input type=text name=user_id size=20 maxlength=20 class=input style='width:100px'></td>
			<td></td>
			<td><input type=password name=password size=20 maxlength=20 class=input style='width:100px'></td>
		</tr>
	</table></td>
	</tr>
	<tr height=2>
		<td bgcolor=660325></td>
	</tr>
	<tr>
		<td height=5></td>
	</tr>
	<tr>
		<td align=right><input type=image src=<?=$dir?>/submit.gif border=0 accesskey="s" onfocus='this.blur()' alt=로그인><a href=javascript:void(history.back()) onfocus='this.blur()'><img src=<?=$dir?>/cancel.gif border=0 alt=취소></a></td>
	</tr>
</table>
<br><br>