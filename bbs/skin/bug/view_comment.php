<tr>
    <td bgcolor=151515><img src=<?=$dir?>/t.gif height=1></td>
</tr>
<tr>
	<td height=4><img src=<?=$dir?>/t.gif border=0 height=3></td>
</tr>
<tr valign=top>
	<td style='word-break:break-all;'>
		<table border=0 cellspacing=0 cellpadding=0 width=100%>
		<tr>
<td valign=top width=80><?=$c_face_image?> <?=$comment_name?> 
<br><font class=red_7><?=date("Y-m-d",$c_data[reg_date])?></font> <?=$a_del?><font class=red_7>-</font></a></td>
			<td width=1 bgcolor=151515></td>
			<td class=red_8 style='word-break:break-all;padding:2px'>
		    <?=nl2br($c_memo)?>
	        </td>

		</tr>
		</table>
	</td>
</tr>
