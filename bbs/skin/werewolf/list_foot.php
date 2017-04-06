<!-- 마무리 부분입니다 -->
	</tbody>
</table>

<table border=0 cellpadding=0 cellspacing=0 width=100%>
	<tr>
		<!-- 리스트,글쓰기,넘어가기 버튼부분 -->
		<td align=left><?=$a_prev_page?>prev</a><?=$print_page?> <?=$a_next_page?>next</a></td>
		<td align=right><span class=hit><?=$a_delete_all?>&nbsp;control<?=$a_list?>&nbsp;[마을 목록]<?=$a_write?>&nbsp;[마을 만들기]</td>
	</tr>
</table>
</form>

<!-- 검색테그 부분입니다. ---------------------->
<!-- 검색시 이름 내용 제목 다 검사하게 소스가 수정 되어있습니다 -->
<form method=post name=search action=<?=$PHP_SELF?>>
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=selected><input type=hidden name=exec>
<input type=hidden name=sn value="on">
<input type=hidden name=ss value="on">
<input type=hidden name=sc value="on">
<input type=hidden name=category value="<?=$category?>">
<!-- 검색창 나오는곳 입니다 -->
<table border=0 width=100% cellspcing=0 cellpadding=0>
	<tr>
		<td align=right valign=middle>
			<input type=text name=keyword value="<?=$keyword?>" style="width:90;height:18;" class="input">
			<input type=submit class="submit" value="Search" border=0 align=absmiddle src=<?=$dir?>/search.gif onfocus=blur()>
		</td>
	</tr>
<!-- 페이지 출력 ---------------------->
</table>
</form>