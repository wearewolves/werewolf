<!--해결된 버그-->
<table border=0  width=100% cellpadding="5" cellspacing="0" >
	<tr height=30 ><td colspan=7 class=title   valign=top style="font:15px;"><b>해결된 버그</b></td></tr>
</table>

<table border=0 cellspacing=0 cellpadding=0 width=100%>
<col width=10%></col>
<col width=50%></col>
<col width=10%></col>
<col width=10%></col>
<col width=10%></col>
<col width=10%></col>
<tr align=left>
<td background=<?=$dir?>/images/h_bg.gif height=25>
	<table cellspacing=0 cellpadding=0>
		<tr>
			<td><img src=<?=$dir?>/images/h_left.gif WIDTH="3" HEIGHT="25" BORDER=0></td>
			<td align=center width=100%>처리 시간</td>
		</tr>
	</table>
</td>
<td align=center background=<?=$dir?>/images/h_bg.gif height=25>제목</td>
<td align=center background=<?=$dir?>/images/h_bg.gif height=25>담당자</td>
<td align=center background=<?=$dir?>/images/h_bg.gif height=25>서버</td>
<td align=center background=<?=$dir?>/images/h_bg.gif height=25>타입</td>
<td align=center background=<?=$dir?>/images/h_bg.gif height=25 align=right>
	<table cellspacing=0 cellpadding=0>
		<tr align=center>
			<td width=100%>심각도</td>
			<td><img src=<?=$dir?>/images/h_right.gif WIDTH="3" HEIGHT="25" BORDER=0></td>
		</tr>
	</table>
</td>
</tr>
</table>



<table cellpadding=4 cellspacing=0  width='100%' border=0 bordercolor=#E6E6E6 style='border-collapse:collapse;'  >
<col width=10%></col>
<col width=50%></col>
<col width=10%></col>
<col width=10%></col>
<col width=10%></col>
<col width=10%></col>

<?	
	// 버그 읽어오기
	$_dbTimeStart = getmicrotime();
	$temp_result=mysql_query("select * from $DB_brief where status=5 order by report_date desc");
	$_dbTime += getmicrotime()-$_dbTimeStart;	

	while($bug=mysql_fetch_array($temp_result)){?>
<tr>
<td align=center>&nbsp;<?=date("Y-m-d",$bug['deal_date'])?></td>
<td align=left>&nbsp;<a href=<?="view.php?id=$id&no=$bug[bug]"?> onfocus=blur()>
	<?
		$temp=mysql_fetch_array(mysql_query("select * from $t_board"."_".$id." where no =$bug[bug]"));
		echo $temp['subject'];
	?></a>
</td>
<td align=center>&nbsp;
<?
		$repairman=mysql_fetch_array(mysql_query("select name from zetyx_member_table where no= $bug[repairman]"));
		echo $repairman[0];	
?>
</td>
<td align=center>&nbsp;<?=$server[$bug['server']]?></td>
<td align=center>&nbsp;<?=$type[$bug['type']]?></td>
<td align=center>&nbsp;<?=$serverity[$bug['serverity']]?></td>
</tr>
<?
		}
?>
<tr><td height=10 colspan=2 ></td></tr>
</table>
