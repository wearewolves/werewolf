<table border=0 cellspacing=0 cellpadding=0 width=<?=$width?>>
<tr><td width=1>
<form method=post name=list action=list_all.php>
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=selected>
<input type=hidden name=exec>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
</td><td width=100%>



<?=$hide_cart_start?>
<td><table border=0 cellspacing=0 cellpadding=0  width=100%><tr>
<td align=center class=bg><?=$a_cart?></td>
</tr></table>
</td>
<?=$hide_cart_end?>

<td width=100%><table border=0 cellspacing=0 cellpadding=0 width=100%><tr>
<td align=left class=bg><?=$a_subject?></td>
</tr></table>
</td>


</tr>
