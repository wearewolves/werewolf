<form>
<br><br><br><br>
<table border=0 width=250>
<tr>
    <td align=center height=30>
      <br><font color=#FF8080><?echo $message;?></font><br><br>
		<?if(!$url){?>
			<input type=button value="back"  onclick=history.back() onfocus=blur() class=submit>
		<?} else {?>
			<input type=submit value="back" class=submit onclick=location.href="<?echo $url;?>" onfocus=blur()>
		<?}?>
   <br><br>
 </td>
</tr>
</table>
</form>
