<!-- 내 페이지 버그 -->
	<? if($target=="My")	{include "$dir/myBug.php";}?>

<!-- 새로운 버그 -->
	<? if($target=="New")	{ include "$dir/newBug.php";}?>

<!-- 담당자가 접수한 버그 -->
	<? if($target=="Assigned")	{ include "$dir/assignedBug.php";}?>

<!-- 처리된 버그 -->
	<? if($target=="Resolved")	{  include "$dir/resolvedBug.php";}?>

<!-- 처리를 검증한 버그 -->
	<? if($target=="Verified")	{   include "$dir/verifiedBug.php";}?>


<!-- 전체 버그 리스트 -->
<table id='sub006' style="display" cellspacing=0 cellpadding=0 width=100% border=0 >
	<tr id=tr006>
		<td valign=top align=center style=padding-top:5px>

<table border=0  width=100% cellpadding="5" cellspacing="0" >
	<tr height=30  ><td colspan=7   valign=top style="font:15px;">전체 버그 목록</td></tr>
</table>
<table border=0 cellspacing=0 cellpadding=0 width=<?=$width?> >
<form method=post name=list action=list_all.php><input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>><input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=selected>
<input type=hidden name=exec>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<col width=5%></col>
<col width=40%></col>
<col width=10%></col>
<col width=16%></col>
<col width=18%></col>
<col width=10%></col>

<tr align=left bgcolor="#151515">
<td height=25>
<table cellspacing=0 cellpadding=0>
<tr>
<td></td>
<td align=center width=100%><?=$a_no?>번호</a></td>
</tr>
</table>
</td>

<td  height=25>
<table cellspacing=0 cellpadding=0 width=100%>
<tr><td align=center><?=$a_subject?>제 목</a></td>
</tr>
</table>
</td>

<td align=center height=25>서버</td>
<td align=center height=25>심각도</td>
<td align=center height=25>진행상황</td>

<td height=25>
<table width=100%>
<tr><td align=center><?=$a_date?>등록일</a></td>
</tr>
</table>
</td>

<td height=25 align=right>
<table cellspacing=0 cellpadding=0>
<td></td>
</tr>
</table>
</td>
</tr>

