</td>
</tr>
</table>

<!-- 마무리 부분입니다 -->

<table height=26 border=0 cellpadding=0 cellspacing=0 width=<?=$width?>>

<tr>
  <td colspan=2 height=1 bgcolor=#E7E7E7>
</tr>
<tr>
  <td colspan=2 height=5>
</tr>

<tr>
<td align=left><span style="font-size:8pt;"><font face="Tahoma">
&nbsp;<?=$a_prev_page?>[prev]</a> <?=$print_page?> <?=$a_next_page?>[next]</font></span></a>
</td>
<td align=right class=rini_ver3>
<?=$a_list?><span style="font-size:8pt;"><font face="Tahoma">list&nbsp;&nbsp;</font></span></a>
<?=$a_write?><span style="font-size:8pt;"><font face="Tahoma">write</font></span></a>
</td>
</tr>
</form></table>

<table border=0 cellpadding=0 cellspacing=0 width=<?=$width?>>
<tr><td>
<!-- 폼태그 부분;; 수정하지 않는 것이 좋습니다 -->
<form method=post name=search action=<?=$PHP_SELF?>>
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=selected>
<input type=hidden name=exec>
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<input type=hidden name=category value="<?=$category?>">
<!----------------------------------------------->
</td></tr>

<!-- 검색창 지우기 주석
<tr>
<td align=right>
<input type=text name=keyword value="<?=$keyword?>" style="width:200px; height:18px" class=rini_input>
</td>
</tr>
여기까지 -->

<tr>
<td height=10></td></tr>
</form></table>
