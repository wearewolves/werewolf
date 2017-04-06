<tr>
    <td bgcolor=151515></td>
</tr>
<tr valign=top>
	<td style='word-break:break-all;'>
		<table border=0 cellspacing=0 cellpadding=0 width=100%>
		<tr>
			<td valign=top width=80>
				<?=$repairman[0]?> <br>
				<font class=red_7><?=date("Y-m-d",$bug_add[deal_date])?><br><?=date("h:i:s",$bug_add[deal_date])?></font>&nbsp;&nbsp; 
				<? if($max_no[0]==$bug_add[no]){?>
					<?=$a_del?><font class=red_7>[X]</font></a>
				<?}?>
			</td>
			<td width=1 bgcolor=151515></td>
			<td class=red_8 style='word-break:break-all;padding:2px'><b>
<?if($bug_add['status'] == 2){//재 처리 요청or 해결?>
			버그를 접수했습니다.<br> 담당자: <?=$repairman[0]?>
<?}?>	
<?if($bug_add['status'] == 3){//?>
			버그를 처리했습니다. <br>처리 결과: <?=$dealResult[$bug_add['dealResult']]?>
			<?
				if($bug_add['dealResult']==4){
					echo "(처리 예정: ".date("Y년 m월", $bug_add['reservation']).")";
				}
			?>
<?}?>
<?if($bug_add['status'] == 4){//?>
			재 수정을 요청합니다. 
<?}?>	
<?if($bug_add['status'] == 5){//?>
			수정을 확인했습니다. 
<?}?>	
<?if($bug_add['status'] == 6){//?>
			버그가 재발했습니다. 
<?}?>	</b><br><br>
		    <?=nl2br($c_memo)?>
	        </td>

		</tr>
		</table>
	</td>
</tr>

