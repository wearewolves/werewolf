<table border=0 cellspacing=0 cellpadding=0 width=<?=$width?> height=28>
  <tr>
  <td nowrap><?=$memo_on_sound?></td>
<?
if($setup[use_category])
{
?>
  <td align=left><? include "include/print_category.php"; ?></td>
<?}?>

<td align=right class=rini_ver3>
<font face="Tahoma"><span style="font-size:8pt;"><?=$a_member_modify?>myinfo&nbsp;</a></span></font>
<font face="Tahoma"><span style="font-size:8pt;"><?=$a_member_memo?>memo&nbsp;</a></span></font>
<font face="Tahoma"><span style="font-size:8pt;"><?=$a_logout?>logout&nbsp;</a></span></font>
<font face="Tahoma"><span style="font-size:8pt;"><?=$a_setup?>admin</a></span></font>
<font face="Tahoma"><span style="font-size:8pt;"><?=$a_member_join?>join&nbsp;</a></span></font>
<font face="Tahoma"><span style="font-size:8pt;"><?=$a_login?>login</a></span></font>
</td>

</tr>
</table>