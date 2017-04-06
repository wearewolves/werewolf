<br><br><br>
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
<table border=0 width=250  bgcolor=eeeeee>
<tr  bgcolor=ffffff>
   <td align=center style=padding:2px><?=$title?></td>
</tr>
<?
	if(!$member[no]) {
?>
<tr height=40>
   <td align=center>
     <font class=red_7>PASSWORD</font>&nbsp;<?=$input_password?> 
   </td>
</tr>
<?
	}
?>
<tr bgcolor=white height=30>
	<td align=center>
	    <input type=image src=<?=$dir?>/images/ask_ok.gif border=0 accesskey="s" onfocus=blur()>
     	<?=$a_list?><img src=<?=$dir?>/images/ask_list.gif border=0></a>
     	<a href=javascript:void(history.back()) onfocus=blur()><img src=<?=$dir?>/images/ask_cancel.gif border=0></a>
   </td>
</tr>
</table>
</form>

