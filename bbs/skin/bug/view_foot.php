<?=$hide_comment_end?>

<table width=<?=$width?> cellspacing=0 cellpadding=0>
<tr>
    <td bgcolor=151515 colspan=2><img src=<?=$dir?>/t.gif height=1></td>
</tr>
<tr>
 <td height=30>
    <?=$a_reply?>&nbsp;<img src=<?=$dir?>/images/b_reply.gif border=0></a>
    <?=$a_modify?>&nbsp;<img src=<?=$dir?>/images/b_modify.gif border=0></a>
	<!--
    <?//=$a_delete?>&nbsp;<img src=<?//=$dir?>/images/b_delete.gif border=0 ></a>
	-->
	<a onfocus=blur() href='<?="delete_bug.php?".$href.$sort?>&no=<?=$no?>'><img src=<?=$dir?>/images/b_delete.gif border=0 onclick=></a>
 </td>
 <td align=right>
    <?=$a_list?><img src=<?=$dir?>/images/b_list.gif border=0></a>
    <?=$a_write?>&nbsp;<img src=<?=$dir?>/images/b_write.gif border=0></a>
 </td>
</tr>
</table>

<br>

<?=$hide_prev_start?>
<table width=<?=$width?>>
<tr>
  <td style='word-break:break-all;'> <?=$a_prev?>¢· <?=$prev_subject?></a></td>
</tr>
</table>
<?=$hide_prev_end?>

<?=$hide_next_start?>
<table width=<?=$width?>>
<tr>
  <td style='word-break:break-all;'> <?=$a_next?>¢¹ <?=$next_subject?></a></td>
</tr>
</table>
<?=$hide_next_end?>

<br>
