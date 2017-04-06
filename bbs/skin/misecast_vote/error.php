<div align=center>
<br><br><br><br>

<form>
<table border=0 cellspacing=0 cellpadding=0 width=250>
<tr>
  <td height=1 background=<?=$dir?>/images/dot.gif></td>
</tr>

<tr>
  <td height=20></td>
</tr>


<tr>
  <td align=center height=20><?echo $message;?></td>
</tr>

<tr>
  <td height=20></td>
</tr>

<tr>
  <td height=1 background=<?=$dir?>/images/dot.gif></td>
</tr>

<tr>
  <td height=30 align=center>
<?
  if(!$url)
  {
?>
  <input type=button value=' back ' onclick=history.back() border=0 align=absmiddle class=rini_submit>
<?
  }
  else
  {
?>
   <div align=center><input type=button value=' Move ' onclick=location.href="<?echo $url;?>" class=rini_submit>
<?
  }
?>

  </td>
</tr>
</table>
</form>
</div>
<br><br>