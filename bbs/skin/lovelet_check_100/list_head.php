<?php
if ('' != $keyword) {
    $year = substr($keyword, 0, 4);
    $month = substr($keyword, 4, 2);
}
	$year = ('' == $year) ? date('Y') : $year;
	$month = ('' == $month) ? date('n') : $month;

	$totalDays = date('t', mktime(0, 0, 1, $month, 1, $year)); // 해당 연월의 일수를 계산한다.
	$firstDay = date('w', mktime(0, 0, 0, $month, 1, $year));
	$col = 0;

	$member=member_info();
	$nno = stripslashes($member[no]);
	if($member[no]==1 and $player)$nno = stripslashes($player);

	if($member[no])$suddenDeathCount = mysql_fetch_array(mysql_query("select count(*)  from `zetyx_board_werewolf_suddenDeath` where player = $member[no]"));
	$suddenDeathCount[0];				

	switch($suddenDeathCount[0]){
		case 0: $checkDaysSuddenDeathPlayer = 1;// 신규 가입자
					break;
		case 1: $checkDaysSuddenDeathPlayer = 2;// 돌연사 1회
					break;
		case 2: $checkDaysSuddenDeathPlayer = 3;// 돌연사 2회
					break;
		case 3: $checkDaysSuddenDeathPlayer = 4;// 돌연사 3회
					break;
		case 4: // 멤버 정보 삭제
				@mysql_query("delete from $member_table where no='$member[no]'") or error(mysql_error());

				// 쪽지 테이블에서 멤버 정보 삭제
				@mysql_query("delete from $get_memo_table where member_no='$member[no]'") or error(mysql_error());
				@mysql_query("delete from $send_memo_table where member_no='$member[no]'") or error(mysql_error());

				// 그룹테이블에서 회원수 -1
				@mysql_query("update $group_table set member_num=member_num-1 where no = '$group_no'") or error(mysql_error());

				// 로그아웃 시킴
				destroyZBSessionID($member[no]);

				// 4.0x 용 세션 처리
				$zb_logged_no='';
				$zb_logged_time='';
				$zb_logged_ip='';
				$zb_secret='';
				$zb_last_connect_check = '0';
				session_register("zb_logged_no");
				session_register("zb_logged_time");
				session_register("zb_logged_ip");
				session_register("zb_secret");
				session_register("zb_last_connect_check");
				break;
	}

	$noMannerPlayers = array();
	require_once("noMannerPlayers.php");
	
	if(array_key_exists($member[no],$noMannerPlayers))
		$checkDaysSuddenDeathPlayer = $noMannerPlayers[$member[no]];

	$checkDays = "(";
	
	for($index = $checkDaysSuddenDeathPlayer -1 ; $index >= 0 ;$index--){
		$checkTime = time() - 86400 * $index;

		$checkYear = date('Y',$checkTime ) ;
		$checkMonth = date('m',$checkTime );
		$checkDay = date('d',$checkTime );

		$checkDays .= $checkYear.$checkMonth.$checkDay;

		if($index) $checkDays .=",";
	}
	$checkDays .=")";

	$totalcheck=mysql_num_rows(mysql_query("select no from zetyx_board_$id where subject  in $checkDays and ismember='$nno'"));
?>

<table width="<?=$width?>" border="0" cellspacing="0" cellpadding="0">
<form action="" method="post" name="form_schedule">
<input type="hidden" name="ss" value="on">
<input type="hidden" name="keyword" value="<?=$keyword?>">
	<tr height=40>
		<td align=center>
		<select name="year" onchange="itdSearch();">
		<?php for ($i = date('Y'); $i >= 2007; $i--) { ?>
		<option value="<?=$i?>"<?=($i == $year) ? ' selected' : ''?>><?=$i?></option>
		<?php } ?>
		</select>&nbsp;년&nbsp;
		<select name="month" onchange="itdSearch();">
		<?php for ($i = 1; $i < 13; $i++) { ?>
		<option value="<?=(1 == strlen($i) ? '0' . $i : $i)?>"<?=($i == $month) ? ' selected' : ''?>><?=$i?></option>
		<?php } ?>
		</select>&nbsp;월</td>
	</tr>
	<tr height=25>
		<?if($member[level]==8 or $member[level]==9 ){?>
			<?if($totalcheck==$checkDaysSuddenDeathPlayer){
				$sql = "update zetyx_member_table set `level`= 7 where no = $member[no]";
				@mysql_query($sql) or error(mysql_error());						
				?>			
				<td align=center>
					<b><font color=red>축하합니다. 등급이 올라갔습니다!!!</font></b><br>
					그럼 인랑을 재밌게 즐겨주세요. <br>			
				</td>
			<?}else{?>
				<td align=center> 게임에 참여하기 위해서는 <b><font color=660325><?=$checkDaysSuddenDeathPlayer?></font></b>일 동안 연속으로 도장을 찍어야 합니다.<br> 현재 <b><font color=660325><?=$totalcheck?></font></b>회 출석했음!</h3></td>
			<?}?>
		<?}?>
	</tr></form>
</table>

<script language="javascript">
<!--
if ("" == "<?=$keyword?>") {
    itdSearch();
}

function itdSearch() {
    document.form_schedule.keyword.value = document.form_schedule.year.value + document.form_schedule.month.value
    document.form_schedule.submit();
}
//-->
</script>


<table width=<?=$width?> border="0" cellspacing="0" cellpadding="0">
	<tr height="30" align=center>
		<td width=<?=$width/7?> class="ver8" bgcolor=#151515><font class=smallno><b>Sun</b></font></td>
		<td width=<?=$width/7?> class="ver8" bgcolor=#151515><b>Mon</b></td>
		<td width=<?=$width/7?> class="ver8" bgcolor=#151515><b>Tue</b></td>
		<td width=<?=$width/7?> class="ver8" bgcolor=#151515><b>Wed</b></td>
		<td width=<?=$width/7?> class="ver8" bgcolor=#151515><b>Thu</b></td>
		<td width=<?=$width/7?> class="ver8" bgcolor=#151515><b>Fri</b></td>
		<td width=<?=$width/7?> class="ver8" bgcolor=#151515><b>Sat</b></td>
	</tr>
</table>

<table width="<?=$width?>" border="0" cellspacing="1" cellpadding="0" bgcolor="#111111">
	<col width=<?=$width/7?>></col><col width=<?=$width/7?>></col><col width=<?=$width/7?>></col><col width=<?=$width/7?>></col><col width=<?=$width/7?>></col><col width=<?=$width/7?>></col><col width=<?=$width/7?>></col>
	<tr bgcolor=black>
  <?php
  for ($i = 0; $i < $firstDay; $i++) {
  ?>
		<td></td>
  <?php
      $col++;
  }
  for ($j = 1; $j <= $totalDays; $j++) {
      $month1 = ((1 == strlen($month)) ? '0' . $month : $month);
      $j1 = ((1 == strlen($j)) ? '0' . $j : $j);

	$idn=$year.$month1.$j1;
	$result1=mysql_query("select * from zetyx_board_$id where subject='$idn' and ismember='$nno'");
	$data_a=mysql_fetch_array($result1);
	$icon_no=$data_a[sitelink1];
	$checkt=mysql_num_rows(mysql_query("select no from zetyx_board_$id where subject='$idn'"));
	$check2=mysql_num_rows($result1);
      if ($check2==1) {
          $today = "<img src=$dir/icon/$icon_no.png border=0>";
      } else {
          $today = "";
      }
?>

		<td>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr height=20>
			<td width=20>&nbsp;<?=$j?></td>
			<td align=right class=smallno>( <b><?=$checkt?></b> )</td>
		</tr>
		<tr height=50>
			<td align=center colspan=2><?=$today?></td>
		</tr>
	</table></td>
  <?php
      $col++;
      if (7 == $col) {
  ?>
  </tr>
  <?php
          if ($totalDays != $j) {
  ?>
	<tr bgcolor=black>
  <?php
          }
          $col = 0;
      }
  }
  while (($col > 0) && ($col < 7)) {
  ?>
		<td></td>
  <?php
      $col++;
  }
  ?>
	</tr>
</table>

<?
if($member[no]==1) {
?>

<table width=<?=$width?> border="0" cellspacing="0" cellpadding="0">
	<tr height=20>
		<td colspan=2></td>
	</tr>
	<tr height=20>
		<td class= width=50%>&nbsp;<b>For Administrator</b></td>
		<td class= width=50% align=right><b><?=$year?>. <?=$month?></b>&nbsp;</td>
	</tr>
</table>

<table border=0 cellspacing=1 cellpadding=0 width=<?=$width?> style=table-layout:fixed bgcolor=#151515>
<form method=post name=list action=list_all.php>
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=selected>
<input type=hidden name=exec>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
	<tr>
		<td bgcolor=black valign=top>
	<table border=0 cellspacing=0 cellpadding=0 width=100%>
		<col width=40></col><col width=50></col><col width=></col><col width=110></col><col width=110></col>
		<tr height=25>
			<td align=center bgcolor=#151515></td>
			<td align=center bgcolor=#151515><b>No</b></td>
			<td align=center bgcolor=#151515><b>Date</b></td>
			<td align=center bgcolor=#151515><b>IName</b></td>
			<td align=center bgcolor=#151515><b>LV</b></td>
			<td align=center bgcolor=#151515><b>Check</b></td>
			<td align=center bgcolor=#151515><b>Edit</b></td>
		</tr>
		<tr height=1>
			<td colspan=5 bgcolor=#151515></td>
		</tr>
<? } ?>