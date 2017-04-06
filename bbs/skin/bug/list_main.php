<?
//echo $data[no];
$bug=mysql_fetch_array(mysql_query("select * from $DB_brief where no = $data[no] "));
?>

<tr align=center >
<td class=red_7 height=22><?=$number?></td>
<td height=22 align=left style='word-break:break-all;'>
&nbsp;<?=$insert?><?=$hide_category_start?>[<?=$category_name?>] <?=$hide_category_end?><?=$subject?>&nbsp;
<font class=red_comment><?=$comment_num?></font>
</td> 

<td align=center>&nbsp;<?=$server[$bug['server']]?></td>
<td align=center>&nbsp;<?=$serverity[$bug['serverity']]?></td>
<td align=center>&nbsp;<?=$status[$bug['status']]?></td>

<td height=22 nowrap class=red_7><?=$reg_date?></td>
</tr>
<tr>
<td colspan=5 height=1 bgcolor=151515></td>
</tr>
