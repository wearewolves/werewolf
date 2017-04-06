<?
	$bug=mysql_fetch_array(mysql_query("select * from ".$t_board."_".$id."_brief where bug=$no"));
?>
<img src=<?=$dir?>/t.gif border=0 height=5><br>

<div width=<?=$width?> align=right>Hit : <b><?=$hit?></div>

<table border=0 cellspacing=0 cellpadding=0 width=<?=$width?>>
<col width=80></col><col width=></col>

<tr bgcolor=#222222>
	<td height=22>
		<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
		<tr >
			<td align=center  width=100%>보고</td>
		</tr>
		</table>
	</td>
	<td>
		<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
		<tr >
			<td align=center width=100%>&nbsp;<?=$subject?></td>
		</tr>
		</table>		
	</td>
</tr>

<tr ><td height=22 align=center bgcolor=#111111><b>보고일</td><td class=red_8>&nbsp;&nbsp;<?=$date?></b></td></tr>
<tr ><td height=22 align=center bgcolor=#111111><b>보고자</td><td>&nbsp;<?=$face_image?>&nbsp;<?=$name?></td></tr>
<tr><td bgcolor=#111111 align=center height=22><b>타입</td><td>&nbsp;&nbsp;<?=$type[$bug['type']]?></td></tr>
<tr><td bgcolor=#111111 align=center height=22><b>심각도</td><td>&nbsp;&nbsp;<?=$serverity[$bug['serverity']]?>	</tr>			
<tr><td bgcolor=#111111 align=center height=22><b>서버</td><td>&nbsp;&nbsp;<?=$server[$bug['server']]?></td></tr>

<tr><td height=1 colspan=2 bgcolor=#151515></td></tr>
<tr><td height=10 colspan=2 ></td></tr>

<tr bgcolor=#222222>
	<td height=22>
		<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
		<tr >
			<td align=center  width=100>버그</td>
		</tr>
		</table>
	</td>
	<td>
		<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
		<tr >
			<td align=center width=100%>&nbsp;</td>
		</tr>
		</table>		
	</td>
</tr>
<tr><td bgcolor=#111111 align=center height=22><b>처리 시간</td><td>&nbsp;&nbsp;<?=date("Y-m-d h:i:s",$bug['deal_date'])?></td></tr>
<!--<tr><td bgcolor=#eeeeee align=center height=22><b>처리 기간</td><td>&nbsp;&nbsp;<?=date("Y-m-d h:i:s",$bug['repair_date']-$bug['report_date'])?></td></tr>-->
<tr><td bgcolor=#111111 width='100' align=center  height=22><b>담당자</b></td><td>&nbsp;&nbsp;
<?if($bug['repairman']){
		$repairman=mysql_fetch_array(mysql_query("select name from zetyx_member_table where no= $bug[repairman]"));
		echo $repairman[0];
	}
	else echo "담당자 없음";
?></td></tr>
<tr><td bgcolor=#111111 align=center height=22><b>처리 상태</td><td>&nbsp;&nbsp;<?=$status[$bug['status']]?></td></tr>
<tr><td bgcolor=#111111 align=center height=22><b>처리 결과</td><td>&nbsp;&nbsp;
			<?
			echo $dealResult[$bug['dealResult']];
				if($bug['status']==3){
				
					if($bug['dealResult']==4){
						echo "(처리 예정: ".date("Y년 m월", $bug['reservation']).")";
					}
				}
			?>
</td></tr>
<tr><td height=1 colspan=2 bgcolor=#151515></td></tr>
<tr><td height=10 colspan=2 ></td></tr> 
<tr bgcolor=#222222>
	<td height=22>
		<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
		<tr >
			<td align=center  width=100>세부 내용</td>
		</tr>
		</table>
	</td>
	<td>
		<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
		<tr >
			<td align=center  width=100%>&nbsp;</td>
		</tr>
		</table>		
	</td>
</tr>
<?=$hide_homepage_start?>
<tr bgcolor=#222222>
	<td height=22>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr>
	<td align=center><font class=red_7>HOMEPAGES</font></td>
	</tr></table>
	</td>
	<td>&nbsp;<?=$homepage?></td>
</tr>
<tr><td height=1 colspan=2 bgcolor=#999999></td></tr>
<?=$hide_homepage_end?>

<?=$hide_download1_start?>
<tr bgcolor=#222222>
	<td height=22>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr><td align=center bgcolor=#eeeeee><font class=red_7>FILE1</font></td>
	</tr></table>
	</td>
	<td>&nbsp;<?=$a_file_link1?><?=$file_name1?> (<?=$file_size1?>)</a><font class=red_7>, DOWNLOAD: <?=$file_download1?></font></td>
</tr>
<tr><td height=1 colspan=2 bgcolor=#151515></td></tr>
<?=$hide_download1_end?>

<?=$hide_download2_start?>
<tr bgcolor=#222222>
	<td height=22>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr><td align=center bgcolor=#eeeeee><font class=red_7>FILE2</font></td></tr></table></td>
	<td>&nbsp;<?=$a_file_link2?><?=$file_name2?> (<?=$file_size2?>)</a><font class=red_7>, DOWNLOAD: <?=$file_download2?></font></td>
</tr>
<tr><td height=1 colspan=2 bgcolor=#151515></td></tr>
<?=$hide_download2_end?>

<?=$hide_sitelink1_start?>
<tr bgcolor=#222222>
	<td height=22>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr><td align=center bgcolor=#eeeeee><font class=red_7>LINK1</font></td></tr></table></td>
	<td>&nbsp;<?=$sitelink1?></td>
</tr>
<tr><td height=1 colspan=2 bgcolor=#151515></td></tr>
<?=$hide_sitelink1_end?>

<?=$hide_sitelink2_start?>
<tr bgcolor=#222222>
	<td height=22>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr><td align=center bgcolor=#eeeeee><font class=red_7>LINK2</font></td></tr></table></td>
	<td>&nbsp;<?=$sitelink2?></td>
</tr>
<tr><td height=1 colspan=2 bgcolor=#151515></td></tr>
<?=$hide_sitelink2_end?>
</table>


<table border=0 cellspacing=0 cellpadding=0 width=<?=$width?> height=<?=$height?> style="table-layout:fixed;">
<tr>
	<td style='word-break:break-all;padding:10'>
		<?=$upload_image1?>
		<?=$upload_image2?>
		<?=$memo?>
		<br>
		<br>
		<div align=right style=font-family:tahoma;font-size=7pt><?=$ip?></div>
	</td>
</tr>
</table>
<img src=<?=$dir?>/t.gif border=0 height=2><br>

<table border=0 width=<?=$width?> cellspacing=0 cellpadding=0>
<?
	// 버그를 처리한 기록 불러오기
		$view_AddNote_result=mysql_query("select * from $DB_addnote where parent='$no' order by no asc");
		$max_no=mysql_fetch_array(mysql_query("select max(no) from $DB_addnote where parent='$no'"));

		while($bug_add=mysql_fetch_array($view_AddNote_result)) {
			$comment_name=stripslashes($c_data[name]);
			$repairman=mysql_fetch_array(mysql_query("select name from zetyx_member_table where no= $bug_add[repairman]"));

			$c_memo=trim(stripslashes($bug_add[memo2]));
			$c_reg_date="<span title='".date("Y년 m월 d일 H시 i분 s초",$bug_add[deal_date])."'>".date("Y/m/d",$bug_add[reg_date])."</span>";
			
			if($bug_add[repairman]) {
				if($bug_add[repairman]==$member[no]||$is_admin||$member[level]<=$setup[grant_delete]) $a_del="<a onfocus=blur() href='$dir/del_addNote_ok.php?$href$sort&no=$no&c_no=$bug_add[no]'>";
				else $a_del="&nbsp;<Zeroboard ";
			} else $a_del="<a onfocus=blur() href='$dir/del_addNote_ok.php?$href$sort&no=$no&c_no=$bug_add[no]'>";

			
			if($setup[use_formmail]&&check_zbLayer($c_data)) {
				$comment_name = "<span $show_ip onMousedown=\"ZB_layerAction('zbLayer$_zbCheckNum','visible')\" style=cursor:hand>$comment_name</span>";
			} else {
				if($c_data[ismember]) $comment_name="<a onfocus=blur() href=\"javascript:void(window.open('view_info.php?id=$id&member_no=$c_data[ismember]','mailform','width=400,height=510,statusbar=no,scrollbars=yes,toolbar=no'))\" $show_ip>$comment_name</a>";
				else $comment_name="<div $show_ip>$comment_name</div>";
			}

				$_skinTimeStart = getmicrotime();
				include $dir."/view_addNote.php";
				$_skinTime += getmicrotime()-$_skinTimeStart;
				flush();
		}
//		if($member[level]<=$setup[grant_comment]) {
		if($member[no] == 1) {
			$_skinTimeStart = getmicrotime();
			include "$dir/view_write_addNote.php";
			$_skinTime += getmicrotime()-$_skinTimeStart;
		}	
?>
<tr>
    <td bgcolor=151515></td>
</tr>
</table>
<br>
<!-- 간단한 답글 시작하는 부분 -->
<?=$hide_comment_start?> 
<table border=0 width=<?=$width?> cellspacing=0 cellpadding=0>
