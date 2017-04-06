<form>
<br><br><br>
<table border=0 width=250 height=30>
<tr>
    <td align=center height=30>
      <?echo $message;?>
	</td>
</tr>
</table>
<table border=0 width=250>
<tr><td align=center>
<?
  if(!$url)
  {
?>

  <br>
  <center><a href=# onclick=history.back() onfocus=blur()><img src=<?=$dir?>/images/ask_back.gif border=0></a>

<?
  }
  else
  {
?>
	<br>
  <div align=center><a href=# onclick=location.href="<?echo $url;?>" onfocus=blur()><img src=<?=$dir?>/images/asl_move.gif border=0></a>

<?
  }
?>
   <br><br>
</form>
<br><br>
</td></tr></table>