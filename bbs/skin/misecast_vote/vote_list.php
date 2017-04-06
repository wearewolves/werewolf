<?
/* //////////////////////
<?=$bar_size?> : %로 나타난 설문의 결과치입니다
/////////////////////// */
?>

<table border=0 cellspacing=0 cellpadding=2 width=100%>

<tr>
  <td height=22 style='word-break:break-all; padding-left:6px;padding-right:6px;'>
  <?=$a_vote?><?=$subject?></a>&nbsp;&nbsp;<font class=rini_ver3>(<?=$vote?>)</font>&nbsp;&nbsp;&nbsp;<?=$a_modify?>+</a>&nbsp;&nbsp;<?=$a_delete?>-</a>
  </td>
</tr>
<tr>
  <td width=100% style='padding-left:6px;padding-right:6px;'>
    <table border=0 width=100% height=6 cellspacing=0 cellpadding=0>
    <tr>
     <td width=1%>
        <table border=0 width=100% height=6 bgcolor=#555555 cellspacing=0 cellpadding=0><tr><td></td></tr></table>
     </td>
     <td width=90%>
        <table border=0 width=<?=$bar_size?>% height=6 bgcolor=#555555 cellspacing=0 cellpadding=0><tr><td></td></tr></table>
     </td>
     <td align=right width=9% class=rini_ver3><?=$vote_rate?>%</td>
    </tr>
    </table>
  </td>
</tr>
<tr>
<td height=10></td>
</tr>

</table>
