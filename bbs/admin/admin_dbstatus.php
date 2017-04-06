<table border=0 cellspacing=0 cellpadding=10 bgcolor=999999 width=100% height=100%>
<form name=showdb>
<tr>
	<td valign=top>
	<br>
	<table border=0 width=100%>
	<col width=50%></col><col width=50%></col>
	<tr>
		<td nowrap><font color=white size=4 face=tahoma><b>DB size</b> :</font> <input type=text name=size readonly style=border:0;font-size:11pt;background-color:999999;height:20px;width:100%;color:white;font-family:tahoma size=20 value=""></td>
	</tr>
	</table>
	<br>

	<table border=0 cellspacing=1 cellpadding=2 width=100% bgcolor=999999>
	<tr bgcolor=444444 align=center>
		<td style=color:white;font-size:8pt;font-family:tahoma>No</td>
		<td style=color:white;font-size:8pt;font-family:tahoma>테이블 이름</td>
		<td style=color:white;font-size:8pt;font-family:tahoma>형식</td>
		<td style=color:white;font-size:8pt;font-family:tahoma>줄(Rows)</td>
		<td style=color:white;font-size:8pt;font-family:tahoma>데이타 용량</td>
		<td style=color:white;font-size:8pt;font-family:tahoma>인덱스 용량</td>
		<td style=color:white;font-size:8pt;font-family:tahoma><b>전체 용량</b></td>
		<td style=color:white;font-size:8pt;font-family:tahoma>생성시간</td>
	</tr>
<?
	$dbData = file("config.php");
	$dbname = $dbData[4];

	$result = mysql_query("show table status from $dbname like 'zetyx%'",$connect);
	$size = 0;
	$num = 1;
	while($dbData=mysql_fetch_array($result)) {
		$size += $dbData[Data_length]+$dbData[Index_length];
?>
	<tr bgcolor=white align=center>
		<td><?=$num?></td>
		<td bgcolor=f4f4f4 align=left>&nbsp;<?=$dbData[Name]?></td>
		<td><?=$dbData[Type]?></td>
		<td align=right><?=number_format($dbData[Rows])?></td>
		<td align=right><?=getFileSize($dbData[Data_length])?></td>
		<td align=right><?=getFileSize($dbData[Index_length])?></td>
		<td bgcolor=#f1f1f1 align=right><?=getFileSize($dbData[Data_length]+$dbData[Index_length])?></td>
		<td><?=$dbData[Create_time]?></td>
	</tr>
<?
		$num++;
	}
?>
	</table>

	</td>
</tr>
</form>
</table>

<script>
document.showdb.size.value="<?=getFileSize($size)?> (<?=$num-1?>개)";
</script>
