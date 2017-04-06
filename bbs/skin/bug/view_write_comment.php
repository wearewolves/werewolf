</table>

<form method=post name=write action=comment_ok.php>
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

<div align=center>
<table border=0 bgcolor=151515 cellspacing=1 cellpadding=0 width=<?=$width?>>
<tr bgcolor=000000>
  <td>
	<table border=0 bgcolor=151515 cellspacing=1 cellpadding=2 width=100%>
	<col width=80></col><col width=></col><col width=70></col>
	<tr align=center bgcolor=222222> 
	  <td height=20>
	  <img src=images/t.gif border=0 width=80 height=1><br><font class=red_7>NAME</font></td>
	  <td colspan=2><table width=100%><tr>
	  <td style='word-break:break-all;'><?=$c_name?></td></tr></table></td>
	  </tr>


		 <?=$hide_c_password_start?>
	  <tr align=center bgcolor=222222> 
	  <td height=20>
	     <font class=red_7>PASSWORD</font></td>
	    <td colspan=2>
		<table width=100%><tr><td>
		<input type=password name=password <?=size(8)?> maxlength=20 class=red_input>
		</td></tr></table></td>
	   </td>
	  </tr>
		 <?=$hide_c_password_end?>
	  <tr bgcolor=111111>
	  <td valign=middle align=center>
	  <font class=red_7>COMMENT</font></td>
	  <td><textarea name=memo <?=size(40)?> rows=5 class=red_commentw style= width=400></textarea></td>
	  <td><input type=submit rows=5 <?if($browser){?>class=red_submit<?}?> value='SUBMIT' accesskey="s"></td>
	</tr>
	</table>
  </td>
</tr>
</table>
</form>
</div>
