<script type="text/javascript" src="skin/<?=$id?>/js/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="skin/<?=$id?>/js/jquery.floatbox.js"></script>
<script type="text/javascript" src="skin/<?=$id?>/js/image-picker.min.js"></script>
<script type="text/javascript" src="skin/<?=$id?>/js/were-090114.js?ver=<?php echo filemtime('skin/'.$id.'/js/were-090114.js'); ?>"></script>
<?
	//---------------------------------------------------------------------- 
	//데이터 초기화
	$gameinfo=mysql_fetch_array(mysql_query("select * from $DB_gameinfo where game=$no"));
	$rule=mysql_fetch_array(mysql_query("select * from $DB_rule where no=$gameinfo[rule]"));
	if($member[no])	$entry = mysql_fetch_array(mysql_query("select * from $DB_entry where game=$no and player = $member[no]"));
	
	$character_list = DB_array("no","character","$DB_character where `set` = '$gameinfo[characterSet]'");
	
	// Check subrules
	$CheckSecretVote = checkSubRule($gameinfo['subRule'], 4);
	//----------------------------------------------------------------------
	//세팅
	//마을 사람 이미지가 있는 주소
	$characterImageFolder = "skin/".$id."/character/".$gameinfo['characterSet']."/";

	//전체 순위 기록
	$writeRecord = true;
	//----------------------------------------------------------------------
	// 비밀글
	if(!$data[is_secret]) $password = "";

	if(!$is_admin and $gameinfo['startingTime'] > time()) error("예약된 마을입니다.");
	if ($viewDay=="" or $viewDay > $gameinfo['day'] or $viewDay < 0) $viewDay = $gameinfo['day'];

	if($gameinfo['state']=="게임끝" or $gameinfo['state']=="버그"or $gameinfo['state']=="테스트" ){?>
		<div id="notice">
			<h1> 게임이 종료되었습니다. 모든 로그를 읽으시려면 아래의 [전체]를 눌러주세요. </h1>
			</div>
	<?}

	if($entry and ($gameinfo['state'] == "게임중" or $gameinfo['state'] == "버그" or $gameinfo['state'] == "테스트")){
		$truecharacter =mysql_fetch_array(mysql_query("select * from $DB_truecharacter where no=$entry[truecharacter]"));
		$vote =mysql_fetch_array(mysql_query("select * from $DB_vote where game=$no and voter = $entry[character] and day = $gameinfo[day]"));

		if($truecharacter['forecast'])$forecast =mysql_fetch_array(mysql_query("select * from $DB_revelation where game='$no' and prophet='$entry[character]' and day='$gameinfo[day]' and type = '점'"));
		if($truecharacter['forecast-odd'])$forecastOdd =mysql_fetch_array(mysql_query("select * from $DB_revelation where game='$no' and prophet='$entry[character]' and day='$gameinfo[day]' and type = '점'"));
		if($truecharacter['mediumism'])$mediumism =mysql_fetch_array(mysql_query("SELECT * FROM `$DB_entry` WHERE `game` = $no AND `deathday` = $viewDay -1 AND `deathtype` LIKE '심판' "));

		if($truecharacter['assault'])$assault =mysql_fetch_array(mysql_query("select * from $DB_deathNote where game='$no' and werewolf = '$entry[character]' and day='$gameinfo[day]'"));

		if($truecharacter['guard'])$guard =mysql_fetch_array(mysql_query("select * from $DB_guard where game='$no' and day='$gameinfo[day]'"));

		if($truecharacter['detect'])$detect =mysql_fetch_array(mysql_query("select * from $DB_detect where game='$no' and day='$gameinfo[day]'"));
		if($truecharacter['revenge'])$revenge =mysql_fetch_array(mysql_query("select * from $DB_revenge where game='$no'"));

		if($truecharacter['half-assault'])$halfassault =mysql_fetch_array(mysql_query("select * from $DB_deathNoteHalf where game='$no' and werewolf = '$entry[character]' and day='$gameinfo[day]'"));
		if($truecharacter['assault-con'])$assaultCon =mysql_fetch_array(mysql_query("select * from $DB_deathNote where game='$no' and werewolf = '$entry[character]' and day='$gameinfo[day]'"));

		if($truecharacter['mustkill'])$mustkill =mysql_fetch_array(mysql_query("select * from $DB_mustkill where game='$no'"));
	}

	if($gameinfo['state'] == "준비중") {
		if($is_admin and $viewMode) {
			if($viewMode == "all") $viewMode = "all";
			elseif($viewMode == "del") $viewMode = "del";
			else $viewMode = "일반";
		}
		else $viewMode = "일반";
	}
	elseif($gameinfo['state'] == "게임중") {
		if($entry) {
			if($entry['alive'] == "사망") $viewMode = "death";
			else {
				if($truecharacter['telepathy']) $viewMode = "tele";
				elseif($truecharacter['secretchat']) $viewMode = "sec";
				elseif($truecharacter['secretletter']) $viewMode = "letter";
				else $viewMode = "일반";
			}
		}
		elseif($is_admin and $viewMode) {
			if($viewMode == "all") $viewMode = "all";
			elseif($viewMode == "death") $viewMode = "death";
			elseif($viewMode == "tele") $viewMode = "tele";
			elseif($viewMode == "sec") $viewMode = "sec";
			elseif($viewMode == "memo") $viewMode = "memo";
			elseif($viewMode == "del") $viewMode = "del";
			elseif($viewMode == "test") $viewMode = "test";
			elseif($viewMode == "letter") $viewMode = "letter";
			else $viewMode = "일반";
		}
		else $viewMode = "일반";
	}
	elseif($gameinfo['state'] == "게임끝" and !$viewMode) $viewMode = "일반";

	if($viewMode == "all") $commentType = "('일반','알림','봉인제안','비밀','사망','텔레','메모','편지','답변')";
	elseif($viewMode == "death") $commentType = "('일반','알림','봉인제안','사망')";
	elseif($viewMode == "tele") $commentType = "('일반','알림','봉인제안','텔레')";
	elseif($viewMode == "letter") $commentType = "('일반','알림','봉인제안','편지','답변')";
	elseif($viewMode == "sec") $commentType = "('일반','알림','봉인제안','비밀')";
	elseif($viewMode == "memo") $commentType = "('일반','알림','봉인제안','메모')";
	elseif($viewMode == "del") $commentType = "('일반','알림','봉인제안','비밀','사망','텔레','메모','편지','답변')";
	else $commentType = "('일반','알림','봉인제안')";
	
//[일정에 따른 이벤트 시작]////////////////////////////////////////////////////////////////////////////	
	// -----------------------------------------------------------------------------------//
	// 사건 발생 시간 체크
	//
	//발생 조건: 사건 발생 시간이 지났다면
	//발생 분기: 
	//			          1. 게임 진행 1일째
	//			          2. 게임 진행 2일째
	//			          3. 게임 진행 3일째
	// -----------------------------------------------------------------------------------//
	flush(); 

	require_once("lib/functionForPlayer.php");
	if($is_admin and $useAdminTool)require_once("lib/functionForAdmin.php");

	$dayList ="<div class='viewDay'>";//<a href=$PHP_SELF?id=$id&no=$no&viewDay=0>프롤로그 </a>";

	for($indx=0;$indx<=$gameinfo['day'];$indx++){
		if($indx == $gameinfo['day'] and( $gameinfo['state']=="게임끝" or  $gameinfo['state']=="테스트")) $printDay = "에필로그";
		elseif($indx ==0) $printDay= "프롤로그 ";
		else $printDay= $indx."일째";

		if($viewDay == $indx) $active = "class ='selectedMode'";
		else  $active ="class =''";

		$dayList .="<a href='$PHP_SELF?id=$id&no=$no&viewDay=$indx&viewMode=$viewMode&viewChar=$viewChar'  $active>$printDay </a>";
	}
	$dayList .="</div>";

	echo $dayList;

	$modeList= "<div class='viewMode' ><a href=$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewChar=$viewChar&viewMode=일반> 일반 </a>";
	$modeList .="<a href=$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewChar=$viewChar&viewMode=death>사망 </a>";
	$modeList .="<a href=$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewChar=$viewChar&viewMode=sec>비밀 대화 </a>";
	$modeList .="<a href=$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewChar=$viewChar&viewMode=tele>텔레파시 </a>";
	$modeList .="<a href=$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewChar=$viewChar&viewMode=letter>비밀 편지 </a>";
	$modeList .="<a href=$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewChar=$viewChar&viewMode=memo>메모 </a>";
	$modeList .="<a href=$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewChar=$viewChar&viewMode=all> 전체 </a>";
	if($is_admin)$modeList .="<a href=$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewChar=$viewChar&viewMode=del>삭제 </a>";
	if($is_admin)$modeList .="<a href='log/".$gameinfo['game']."-log.txt' target='_blank'>로그 </a>";
	$modeList .="</div>";

	if($gameinfo['state']=="게임끝" or $gameinfo['state']=="봉인" or $gameinfo['state']=="버그"  or $gameinfo['state']=="테스트" or ($is_admin)) echo $modeList;
?>

<div id="viewStateAll">
	<div class="viewState">
		<div class="state">마을 이름</div>
		<div class="content"><?=$subject?></div>
	</div>

	<?if($data['x'] == 1 ){?>
	<div class="viewState">
		<div class="state">마을 소개</div>
		<div class="content">
			<?=$memo?>
		</div>
	</div>
	<?}?>
	
	<?	// 게임이 시작되면 게임이 시작한 날짜가 보인다.
		if ($gameinfo['state'] == "준비중" and $gameinfo['players']<>$rule[max_player]) {?>
	<div class="viewState">
		<div class="state">인원 모집</div>
		<div class="content">
			<?echo date("m",$gameinfo['deathtime'])."월 ".date("d",$gameinfo['deathtime'])."일 ".date("H",$gameinfo['deathtime'])."시 ".date("i",$gameinfo['deathtime'])."분";?> 이후로 인원이 모이면 게임이 시작됩니다.
		</div>
	</div>
	<?}?>
	<?	// 게임이 시작되면 게임이 시작한 날짜가 보인다.
		if ($gameinfo['state'] == "준비중" and $gameinfo['players']==$rule[max_player] ) {?>
	<div class="viewState">
		<div class="state">인원 모집</div>
		<div class="content">
			마을 사람들이 모였습니다.<br /> 
			<?echo date("m",$gameinfo['deathtime'])."월 ".date("d",$gameinfo['deathtime'])." 일 ".date("H",$gameinfo['deathtime'])."시 ".date("i",$gameinfo['deathtime'])." 분";?> 부터 게임이 시작됩니다.
		</div>
	</div>
	<?}?>
	<?	// 게임이 시작되면 게임이 시작한 날짜가 보인다.
		if ($gameinfo['state'] <> "준비중") {?>
	<div class="viewState">
		<div class="state">사건이 시작된 날</div>
		<div class="content">
			<?echo date("m",$gameinfo['deathtime'])."월 ".date("d",$gameinfo['deathtime'])."일";?>
		</div>
	</div>
	<?}?>
	<div class="viewState">
		<div class="state">사건이 발생하는 시각</div>
		<div class="content">
		<?
			if($viewDay == 0) {
				$accidentTiem = $gameinfo['deathtime'];
			}
			elseif($gameinfo['state'] == "준비중" or $gameinfo['useTimetable'] == 0) {
				$accidentTiem = $gameinfo['deathtime'] + $gameinfo['termOfDay']*$viewDay;
			}
			elseif($gameinfo['useTimetable'] == 1) {
				$timetable = mysql_fetch_array(mysql_query("select * from `zetyx_board_werewolf_timetable` where `game` = $gameinfo[game] and `day` = $viewDay-1"));
				$accidentTiem = $timetable['reg_date'] + $gameinfo['termOfDay'];
			}
			echo date("H",$accidentTiem)."시 ".date("i",$accidentTiem)."분";
		?>
		</div>
	</div>
	<div class="viewState">
		<div class="state">발언 제한 시간</div>
		<div class="content">
			<!--
			<span class="align-right">
			-->
				<? echo "마을 시작 직후 ".($gameinfo['delayAfter'] / 60)."분<br>"; ?>
				<? echo "사건 발생 직전 ".($gameinfo['delayBefore'] / 60)."분"; ?>
			<!--
			</span>
			-->
		</div>
	</div>
	<div class="viewState">
		<div class="state">마을 사람</div>
		<div class="content">
			<?echo $gameinfo['players']."명";?>
		</div>
	</div>
	<div class="viewState">
		<div class="state">룰</div>
		<div class="content">
			<?echo $rule['name'];?>
		</div>
	</div>
	<div class="viewState">
		<div class="state">서브룰</div>
		<div class="content">
			<?
				if($gameinfo['subRule'] == 0) echo "없음";
				else {
					$subrule_result = mysql_query("select * from `zetyx_board_werewolf_subrule`");
					
					while($subrule_temp = mysql_fetch_array($subrule_result)) {
						if(checkSubRule($gameinfo['subRule'], $subrule_temp[no])) {
							echo $subrule_temp[name]."<br>";
						}
					}
				}
			?>
		</div>
	</div>
	<div class="viewState">
		<div class="state">롤 플레잉 세트</div>
		<div class="content">
			<?
				$ruleplayingSet = mysql_fetch_array(mysql_query("select * from `".$db->characterSet."` where `no`= '".$gameinfo['characterSet']."'"));
				echo "<a href='skin/".$id."/view_role-playing.php?id=".$id."&set=".$ruleplayingSet['no']."'>". $ruleplayingSet['name']."</a>";
			?>
		</div>
	</div>
	<div class="viewState">
		<div class="state">진행 상황</div>
		<div class="content">
		<?
			if($gameinfo['state']=="준비중"){
				if($gameinfo['players']==16) echo "참여 인원이 모두 모였습니다.";
				else echo "참여 인원 모집 중";				
			}
			else{
				if (	$viewDay == 0)				echo "참여 인원 모집 중";
				elseif ($viewDay == $gameinfo['day'] and $gameinfo['state']=="게임끝"  )		echo "모든 상황이 종료되었습니다.";
				else	echo $viewDay."일째 날입니다.";
			}
		?>
		</div>
	</div>
	<?	// 준비 중일때
		if ($viewDay =="0") {?>
	<div class="viewState">
		<div class="state"></div>
		<div class="content">
			낮은 인간의 행세를 하고, 밤에 정체를 나타낸다고 하는 인랑.<br />
			그 인랑이, 이 마을에 섞여 있다는 소문이 퍼졌다.<br />
			마을 사람들은 반신반의 하면서도, 마을 숙소에 모이게 되었다.
		</div>
	</div>
	<?	}?>
	<?	// 1일
		if ($viewDay =="1") {?>
	<div class="viewState">
		<div class="state"></div>
		<div class="content">
			이제 당신은 당신의 본성을 깨닫게 된다.<br />
			당신은 인간인가 아니면 인랑인가!<br />
			<br />
			<? 
			// 실제 직업과 표시가 다르게 나타내는 부분
			$markjob = "";
			if($entry)
			{
				$markjob = $truecharacter_list[$entry[truecharacter]];
				if($markjob == "은거 귀족") $markjob = "마을사람";
			}
			if($markjob != "")echo "당신은 <span style='border:solid 1;border-color:#333333;width:60px' align=center><font color =#000000> ".$markjob." </font></span>입니다.";
			?>
		</div>
	</div>
	<?	}?>	
	<?//2일 부터
		if (1 < $viewDay) {?>
			<?
				$death_player_list;
				$death_list = mysql_query("select * from $DB_entry where game=$no and deathday = $viewDay-1 and deathtype ='돌연'");	
	
				while($death=mysql_fetch_array($death_list)){
					$death_player_list .= "<div style='width:120px;text-align:left'>".$character_list[$death['character']]."</div>";
				}
	
				if($death_player_list){?>
	<div class="viewState">
		<div class="state">돌연사</div>
		<div class="content">
					<?echo "마을에 돌연사가 발생했습니다.<br> 돌연사한 마을 사람은 아래와 같습니다.<br><br>".$death_player_list;?>
		</div>
	</div>
			<?}?>
			

			<?if($rule['no'] == 1){?>
				<?$death = mysql_fetch_array(mysql_query("select * from $DB_entry where game=$no and deathday = $viewDay-1 and deathtype ='심판'"));				
				if($death){?>
	<div class="viewState">
		<div class="state">투표</div>
		<div class="content">				
				<?echo $character_list[$death['character']]." 씨가 투표 결과로 목 매달아졌습니다.<br>" ;?>
		</div>
	</div>	
				<?}?>
				
				<?$death = mysql_fetch_array(mysql_query("select * from $DB_entry where game=$no and deathday = $viewDay-1 and deathtype ='습격'"));	?>
	<div class="viewState">
		<div class="state">습격</div>
		<div class="content">				
				<?if($death){ echo $character_list[$death['character']]."의 시체가 발견되었습니다.<br>지난밤 인랑에게 습격받은 것으로 보입니다.<br>";}
				else{echo "지난밤에는 습격이 없었다. 인랑이 습격에 실패한 것일까...?<br>";}?>
		</div>
	</div>
			<?}?>

			<?if($rule['no'] == 2 ){?>
				<?$death = mysql_fetch_array(mysql_query("select * from $DB_entry where game=$no and deathday = $viewDay-1 and deathtype ='심판'"));				
				if($death){?>
	<div class="viewState">
		<div class="state">투표</div>
		<div class="content">				
				<?echo $character_list[$death['character']]." 씨가 투표 결과에 의해 생매장당했습니다.<br>" ;?>
		</div>
	</div>	
				<?}?>

			
			<?	$death_list = mysql_query("select * from $DB_entry where game=$no and deathday = $viewDay-1 and deathtype ='습격'");	
				$death_player_list ="";

					while($death=mysql_fetch_array($death_list)){
						$death_player_list .= "<div style='width:120px;text-align:left'>".$character_list[$death['character']]."</div>";
					}
				if($death_player_list){?>
	
	<div class="viewState">
		<div class="state">습격</div>
		<div class="content">
				<?echo "지난밤은 왠지 소란스러웠다. 불길한 생각에 밖을 내다보니<br />무참히 살해된 시체가 있었다… 죽은 자의 이름은…<br /><br />".$death_player_list;?>
		</div>
	</div>
				<?}
				else{?>
	<div class="viewState">
		<div class="state">습격</div>
		<div class="content">
				<?echo "지난밤은 너무나도 조용했다. 눈을 뜨고 밖을 내다보았지만,<br />아무 일도 없었던 것 같다. 이건 어찌 된 일일까…?";?>
		</div>
	</div>	
	<?}}?>


			<?if($rule['no'] == 3 or $rule['no'] == 5 or $rule['no'] == 6 ){?>
				<?$death = mysql_fetch_array(mysql_query("select * from $DB_entry where game=$no and deathday = $viewDay-1 and deathtype ='심판'"));				
				if($death){?>
					<div class="viewState">
						<div class="state">투표</div>
							<div class="content">				
								<?echo $character_list[$death['character']]." 씨가 투표 결과로 목 매달아졌습니다.<br>" ;?>
							</div>
						</div>	
				<?}
				else if($viewDay>2){?>
					<div class="viewState">
						<div class="state">투표</div>
						<div class="content">
								<?echo "투표 결과에 따라 형을 집행하려는 순간..<br>";
									if($gameinfo['state']=="게임끝" and $viewDay == $gameinfo['day']) echo "마을은 어둠에 뒤덮였다.";
									else echo "귀족임이 밝혀졌다.";
								?>
						</div>
					</div>	
				<?}?>

			
			<?	$death_list = mysql_query("select * from $DB_entry where game=$no and deathday = $viewDay-1 and deathtype ='습격'");	
				$death_player_list ="";

					while($death=mysql_fetch_array($death_list)){
						$death_player_list .= "<div style='width:120px;text-align:left'>".$character_list[$death['character']]."</div>";
					}
				if($death_player_list){?>
	
	<div class="viewState">
		<div class="state">습격</div>
		<div class="content">
				<?echo "지난밤은 왠지 소란스러웠다. 불길한 생각에 밖을 내다보니<br />무참히 살해된 시체가 있었다… 죽은 자의 이름은…<br /><br />".$death_player_list;?>
		</div>
	</div>
				<?}
				else{?>
	<div class="viewState">
		<div class="state">습격</div>
		<div class="content">
				<?echo "지난밤은 너무나도 조용했다. 눈을 뜨고 밖을 내다보았지만,<br />아무 일도 없었던 것 같다. 이건 어찌 된 일일까…?";?>
		</div>
	</div>	
	<?}}?>



	<?	}?>	
	<?	if ($viewDay ==$gameinfo['day'] and $gameinfo['state']=="게임끝") {?>
		<div class="viewState">
			<div class="state"></div>
			<div class="content">게임이 종료되었습니다. <br />	<br />	
		<?
			if($gameinfo['win'] == 1)
				echo "더 이상 인랑에게 대항할 수 있을 정도의 마을 사람은 남아있지 않다... <br />인랑은 남은 마을 사람을 전부 잡아먹은 후, 다른 희생자를 찾아 이 마을을 떠났다.";
			elseif($gameinfo['win'] == 0) 
				echo "모든 인랑을 퇴치하여 마을에 평화가 찾아왔다.<br />이제 인랑을 두려워할 필요는 없어졌다!";
			elseif($gameinfo['win']==2)
				echo "...모든 것이 끝났다고 생각했다. 하지만 살아남은 자들은 보았다.<br />마을 어귀에서부터 몰려드는 수많은 햄스터의 무리를... <br /><br />.....살아남은 자들은 모두 햄스터에게 잡아먹혔다.";
			elseif($gameinfo['win']==3)
				echo "침울한 분위기 가운데 투표 결과를 집행하려는 순간..<br>
						<br>
						디아블로가 각성했다.<br>
						괴성을 지르며 디아블로는 마을을 안드로메다로 보내버렸다.. bye~";
		?>
			</div>
		</div>
	<?	}?>	
	<?if($entry['alive']== "생존" and $forecast_result){?>
		<div class="viewState">
			<div class="state">점괘 결과</div>
			<div class="content">
				<?="점괘가 나왔다. ".$character_list[$forecast_result['mystery']]?>
				<?	
					if($forecast_result[result] == 0 or $forecast_result[result] == 2) echo " 씨는 인간이다.";
					else echo " 씨는 인랑이다.";
					?>

			
			</div>
	</div>
	<?}?>
	<?if($viewDay == 1 and ($gameinfo['rule']==1 or $gameinfo['rule']==2)){ ?>
		<div class="viewState">
			<div class="state"></div>
			<div class="content">
			<?
				if ($gameinfo['players']==11) echo "아무래도 이 안에는, 마을사람이 5명, 인랑이 2명, 점쟁이가 1명,<br />영매자가 1명, 광인이 1명, 사냥꾼이 1명 있는 것 같다.";
				if ($gameinfo['players']==12) echo "아무래도 이 안에는, 마을사람이 6명, 인랑이 2명, 점쟁이가 1명,<br />영매자가 1명, 광인이 1명, 사냥꾼이 1명 있는 것 같다."; 
				if ($gameinfo['players']==13) echo "아무래도 이 안에는, 마을사람이 7명, 인랑이 2명, 점쟁이가 1명,<br />영매자가 1명, 광인이 1명, 사냥꾼이 1명 있는 것 같다.";
				if ($gameinfo['players']==14) echo "아무래도 이 안에는, 마을사람이 8명, 인랑이 2명, 점쟁이가 1명,<br />영매자가 1명, 광인이 1명, 사냥꾼이 1명 있는 것 같다.";
				if ($gameinfo['players']==15) echo "아무래도 이 안에는, 마을사람이 8명, 인랑이 3명, 점쟁이가 1명,<br />영매자가 1명, 광인이 1명, 사냥꾼이 1명 있는 것 같다.";
				if ($gameinfo['players']==16) echo "아무래도 이 안에는, 마을사람이 7명, 인랑이 3명, 점쟁이가 1명,<br />영매자가 1명, 광인이 1명, 사냥꾼이 1명, 초능력자가 2명 있는 것 같다.";
				if ($gameinfo['players']==17) echo "아무래도 이 안에는, 마을사람이 7명, 인랑이 3명, 점쟁이가 1명, 영매자가 1명,<br />광인이 1명, 사냥꾼이 1명, 초능력자가 2명, 햄스터가 1마리 있는 것 같다.";
			?>
			</div>
		</div>
	<?}?>
	<?if($viewDay == 1 and ($gameinfo['rule']==3)){ ?>
		<div class="viewState">
			<div class="state"></div>
			<div class="content">
			<?
				if ($gameinfo['players']==9) echo "아무래도 이 안에는 인랑 1명, 외로운 늑대 1명, 광인 1명, 점쟁이 1명, 영매자 1명,<br>사냥꾼 1명, 복수자 1명, 보안관 1명, 마을사람 1명이 있는 것 같다.";
				if ($gameinfo['players']==10) echo "아무래도 이 안에는 인랑 1명, 외로운 늑대 1명, 광인 1명, 점쟁이 1명, 영매자 1명,<br>사냥꾼 1명, 복수자 1명, 귀족 1명, 마을사람 2명이 있는 것 같다.";
				if ($gameinfo['players']==11) echo "아무래도 이 안에는 인랑 1명, 인랑 리더 1명, 광인 1명, 점쟁이 1명, 영매자 1명,<br>사냥꾼 1명, 촌장 1명,보안관 1명, 마을사람 3명이 있는 것 같다.";
				if ($gameinfo['players']==12) echo "아무래도 이 안에는 인랑 1명, 인랑 리더 1명, 광인 1명,<br>점쟁이 1명, 영매자 1명, 사냥꾼 1명, 촌장 1명, 마을사람 5명이 있는 것 같다.";
				if ($gameinfo['players']==13) echo "아무래도 이 안에는 인랑 1명, 인랑 리더 1명, 외로운 늑대 1명, 광인 1명,<br>점쟁이 1명, 영매자 1명, 사냥꾼 1명, 복수자 1명, 촌장 1명, 귀족 1명, 보안관 1명, 마을사람 2명이 있는 것 같다.";
				if ($gameinfo['players']==14) echo "아무래도 이 안에는 인랑 1명, 인랑 리더 1명, 외로운 늑대 1명, 광인 1명,<br>점쟁이 1명, 영매자 1명, 사냥꾼 1명, 복수자 1명, 촌장 1명, 귀족 1명, 마을사람 4명이 있는 것 같다.";
				if ($gameinfo['players']==15) echo "아무래도 이 안에는 인랑 2명, 인랑 리더 1명, 외로운 늑대 1명, 광인 1명,<br>점쟁이 1명, 영매자 1명, 사냥꾼 1명, 복수자 1명, 촌장 1명, 귀족 1명, 보안관 1명, 마을사람 3명이 있는 것 같다.";
				if ($gameinfo['players']==16) echo "아무래도 이 안에는 인랑 2명, 인랑 리더 1명, 외로운 늑대 1명, 광인 1명,<br>점쟁이 1명, 영매자 1명, 사냥꾼 1명, 복수자 1명, 촌장 1명, 귀족 1명, 마을사람 5명이 있는 것 같다.";
				if ($gameinfo['players']==17) echo "아무래도 이 안에는 인랑 2명, 인랑 리더 1명, 외로운 늑대 1명, 광인 1명,<br>점쟁이 1명, 영매자 1명, 사냥꾼 1명, 복수자 1명, 촌장 1명, 귀족 1명, 마을사람 5명, 그리고 디아블로가 있는 것 같다.";
			?>
			</div>
		</div>
	<?}?>
	<?if($viewDay == 1 and ($gameinfo['rule']==5)){ ?>
		<div class="viewState">
			<div class="state"></div>
			<div class="content">
			<?
				if ($gameinfo['players']==7) echo "아무래도 이 안에는 인랑 2명, 점쟁이 1명,<br>사냥꾼 1명, 복수자 1명, 마을사람 2명이 있는 것 같다.";
				if ($gameinfo['players']==8) echo "아무래도 이 안에는 인랑 2명, 점쟁이 1명,<br>사냥꾼 1명, 복수자 1명, 마을사람 3명이 있는 것 같다.";
			?>
			</div>
		</div>
	<?}?>
	<?if($viewDay == 1 and ($gameinfo['rule']==6)){ ?>
		<div class="viewState">
			<div class="state"></div>
			<div class="content">
			<?
				if ($gameinfo['players']==11) echo "아무래도 이 안에는, 마을사람이 4명, 인랑이 1명, 점쟁이가 1명,<br />영매자가 1명, 광인이 1명, 사냥꾼이 1명,<br />잔혹한 인랑이 1명, 은거 귀족이 1명이 있는 것 같다.";
				if ($gameinfo['players']==12) echo "아무래도 이 안에는, 마을사람이 5명, 인랑이 1명, 점쟁이가 1명,<br />영매자가 1명, 광인이 1명, 사냥꾼이 1명,<br />잔혹한 인랑이 1명, 은거 귀족이 1명이 있는 것 같다."; 
				if ($gameinfo['players']==13) echo "아무래도 이 안에는, 마을사람이 6명, 인랑이 1명, 점쟁이가 1명,<br />영매자가 1명, 광인이 1명, 사냥꾼이 1명,<br />잔혹한 인랑이 1명, 은거 귀족이 1명이 있는 것 같다.";
				if ($gameinfo['players']==14) echo "아무래도 이 안에는, 마을사람이 7명, 인랑이 1명, 점쟁이가 1명,<br />영매자가 1명, 광인이 1명, 사냥꾼이 1명,<br />잔혹한 인랑이 1명, 은거 귀족이 1명이 있는 것 같다.";
				if ($gameinfo['players']==15) echo "아무래도 이 안에는, 마을사람이 6명, 인랑이 2명, 점쟁이가 1명,<br />영매자가 1명, 광인이 1명, 사냥꾼이 1명,<br />잔혹한 인랑이 1명, 은거 귀족이 1명, 촌장 1명이 있는 것 같다.";
				if ($gameinfo['players']==16) echo "아무래도 이 안에는, 마을사람이 7명, 인랑이 2명, 점쟁이가 1명,<br />영매자가 1명, 광인이 1명, 사냥꾼이 1명,<br />잔혹한 인랑이 1명, 은거 귀족이 1명, 촌장 1명이 있는 것 같다.";
			?>
			</div>
		</div>
	<?}?>
</div>


<link rel="stylesheet" href="skin/<?=$id?>/css/image-picker.css?ver=<?php echo filemtime('skin/'.$id.'/css/image-picker.css'); ?>" type="text/css" />
<link rel="stylesheet" href="skin/<?=$id?>/css/table.werewolfStyle.css?ver=<?php echo filemtime('skin/'.$id.'/css/table.werewolfStyle.css'); ?>" type="text/css" />
<?
if($is_admin and 0){
	require_once("class/TableMaker.php");
	$tableMaker = new TableMaker;
	$tableMaker->setTableStyle("werewolfStyle");

	$tableHead = array("No","<a href='$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewMode=$viewMode' >마을 사람</a>","상태","투표 대상","&nbsp;","&nbsp;");
	$tableCol ="<col width=17><col width=120></col><col width=100></col><col width=125></col><col width=120></col><col width=></col>";
	$tableBody= array();

	$i=1;
	$temp_result=mysql_query("select * from $DB_entry where game = $no and (alive= '생존' or $viewDay <= deathday) order by alive desc,deathday desc,victim");
		while($data=mysql_fetch_array($temp_result)){

			$t=$data[character];
		
			if($data['deathtype'] == "심판"){
				$deathType = "투표";
			}
			else $deathType = $data['deathtype'];

			if ($data['alive']=="사망"){
				if ($viewDay <= $data['deathday']){$alive  = "생존";}
				else {	$alive = $data['deathday']."일째 사망-".$deathType;}
			}
			else $alive ="생존";

			$temp_vote=mysql_fetch_array(mysql_query("select * from `$DB_vote` where `game` = $no and `day` = $viewDay-1 and `voter` = $t;"));
			if($temp_vote['candidacy'])
				$candidacy =$character_list[$temp_vote['candidacy']];
			else 
				$candidacy = "&nbsp;";

			if($viewMode=="all"){
				$playerName ="<a href=skin/".$id."/view_private_record.php?id=".$id."&player=$data[player]>".$data[name]."</a>";
				if($is_admin) 	$playerName .= "<a href=skin/".$id."/view_ip_overlap.php?id=".$id."&player=$data[player]>-IP</a>";
				
				if($data['truecharacter'])
					$job =  $truecharacter_list[$data['truecharacter']];
				else
					$job ="&nbsp;";
			}
			else{
				$playerName ="&nbsp;";
				$job = "&nbsp;";
			}

			if($data[victim]==1)	
				$playerName ="NPC";
			else 
				$playerName ="&nbsp;";

			$tableBody[] = array($i,"<a href='$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewMode=$viewMode&viewChar=$t' >$character_list[$t]</a>",$alive,$candidacy,$playerName,$job);
			++$i;		
		}
	$tableMaker->printTable($tableHead,$tableBody,$tableCol);
	$tableBody ="";
}
?>

<table id="playerList">
	<col width=17><col width=140></col><col width=100></col><col width=125></col><col width=120></col><col width=></col>
	<thead>
		<tr><td>No</td><td><?="<a href='$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewMode=$viewMode' >"?>마을 사람</a></td><td>상태</td><td>투표 대상</td><td>&nbsp;</td><td>&nbsp;</td></tr>
	</thead>
	<tbody>
<?	// 플레이어 출력
	$i=1;
	$temp_result=mysql_query("select * from $DB_entry where game = $no and (alive= '생존' or $viewDay <= deathday) order by alive desc,deathday desc,victim");
	
		while($data=mysql_fetch_array($temp_result)){
			 $playerMember=mysql_fetch_array(mysql_query("select * from zetyx_member_table where no = $data[player]"));

			$t=$data[character];

			echo "<tr onMouseOver=this.style.backgroundColor='#090909' onMouseOut=this.style.backgroundColor=''>";
//1
			echo "<td align=center class='red_8'>$i</td>";	++$i;
//1
			echo "<td><a href='$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewMode=$viewMode&viewChar=$t'><img src='skin/$id/image/filter.png' border='0' title='클릭 - $character_list[$t]님의 로그만 봅니다.'></a>".
					"<input type='checkbox' id='$t' class='characterButton' value='$t' checked='checked'/>".
					"<label for='$t' title='클릭 - 필터링\n더블 클릭 - $character_list[$t]님의 로그만 봅니다.'>$character_list[$t]</label></td>";			
		
//2

			if($data['deathtype'] == "심판"){
				$deathType = "투표";
			}
			else $deathType = $data['deathtype'];

			if ($data['alive']=="사망"){
				if ($viewDay <= $data['deathday']){echo "<td>생존</td>";}
				else {	echo "<td>".$data['deathday']."일째 사망-".$deathType."</td>";}
			}
			else echo "<td>생존 </td>";
//3
			echo "<td>&nbsp;";
			// subrule : secret vote
			if($CheckSecretVote) {
				if($viewMode == "all") {
					$temp_vote=mysql_fetch_array(mysql_query("select * from `$DB_vote` where `game` = $no and `day` = $viewDay-1 and `voter` = $t;"));
					echo $character_list[$temp_vote['candidacy']];
					if($data['truecharacter'] == 15 and $temp_vote['candidacy']) echo "x2";
				}
			}
			else {
				$temp_vote=mysql_fetch_array(mysql_query("select * from `$DB_vote` where `game` = $no and `day` = $viewDay-1 and `voter` = $t;"));
				echo $character_list[$temp_vote['candidacy']];
				if($data['truecharacter'] == 15 and $temp_vote['candidacy'] and $viewMode == "all") echo "x2";
			}
			echo "</td>";



//4		
			if($data[victim]==0){
				$playerName ="<a href=skin/".$id."/view_private_record.php?id=".$id."&player=$data[player]>".$data[name]."</a>";
				if($is_admin) 	$playerName .= "<a href=skin/".$id."/view_ip_overlap.php?id=".$id."&player=$data[player]>-IP</a>";
			}
			else $playerName ="NPC";

			if($viewMode=="all"){ echo "<td>$playerName </td><td>".$truecharacter_list[$data['truecharacter']]."</td>";}
//			elseif($is_admin){ echo "<td><font color=#000000'>  $playerName $data[player]</font></td><td><font color=#000000'>".$truecharacter_list[$data['truecharacter']]."  $data[character] </font></td>";}
			elseif($data[player] == 0){ echo "<td>$playerName </td><td>&nbsp;</td>";}
			else	echo "<td>&nbsp;</td><td>&nbsp;</td>";
//5
			echo "</tr>";
		}
?>	
	</tbody>
</table>

<table id="deathPlayerList">
	<col width=17><col width=140></col><col width=100></col><col width=125></col><col width=120></col><col width=></col>
	<tbody>
<?	// 플레이어 출력
	$i=1;
	$temp_result=mysql_query("select * from $DB_entry where game = $no and (alive <> '생존'  and $viewDay > deathday) order by alive desc,deathday desc,victim");
	
		while($data=mysql_fetch_array($temp_result)){
			 $playerMember=mysql_fetch_array(mysql_query("select * from zetyx_member_table where no = $data[player]"));

			$t=$data[character];

			echo "<tr onMouseOver=this.style.backgroundColor='#090909' onMouseOut=this.style.backgroundColor=''>";
//1
			echo "<td align=center class='red_8'>$i</td>";	++$i;		
//1
			echo "<td><a href='$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewMode=$viewMode&viewChar=$t'><img src='skin/$id/image/filter.png' border='0' title='클릭 - $character_list[$t]님의 로그만 봅니다.'></a>".
					"<input type='checkbox' id='$t' class='characterButton' value='$t' checked='checked'/>".
					"<label for='$t'>$character_list[$t]</label></td>";			
		
//2

			if($data['deathtype'] == "심판"){
				$deathType = "투표";
			}
			else $deathType = $data['deathtype'];

			if ($data['alive']=="사망"){
				if ($viewDay <= $data['deathday']){echo "<td>생존</td>";}
				else {	echo "<td>".$data['deathday']."일째 사망-".$deathType."</td>";}
			}
			else echo "<td>생존 </td>";
//3
			echo "<td>&nbsp;";
			// subrule : secret vote
			if($CheckSecretVote) {
				if($viewMode == "all") {
					$temp_vote=mysql_fetch_array(mysql_query("select * from `$DB_vote` where `game` = $no and `day` = $viewDay-1 and `voter` = $t;"));
					echo $character_list[$temp_vote['candidacy']];
					if($data['truecharacter'] == 15 and $temp_vote['candidacy']) echo "x2";
				}
			}
			else {
				$temp_vote=mysql_fetch_array(mysql_query("select * from `$DB_vote` where `game` = $no and `day` = $viewDay-1 and `voter` = $t;"));
				echo $character_list[$temp_vote['candidacy']];
				if($data['truecharacter'] == 15 and $temp_vote['candidacy'] and $viewMode == "all") echo "x2";
			}
			echo "</td>";

//4		
			if($data[victim] == 0){
				$playerName ="<a href=skin/".$id."/view_private_record.php?id=".$id."&player=$data[player]>".$data[name]."</a>";
				if($is_admin) 	$playerName .= "<a href=skin/".$id."/view_ip_overlap.php?id=".$id."&player=$data[player]>-IP</a>";
			}
			else $playerName ="NPC";

			if($viewMode=="all"){ echo "<td>$playerName </td><td>".$truecharacter_list[$data['truecharacter']]."</td>";}
			elseif($data[player] ==0){ echo "<td>$playerName </td><td>&nbsp;</td>";}
//			elseif($is_admin){ echo "<td><font color=#000000'>  $playerName $data[player]</font></td><td><font color=#000000'>".$truecharacter_list[$data['truecharacter']]."  $data[character] </font></td>";}
			else	echo "<td>&nbsp;</td><td>&nbsp;</td>";
//5
			echo "</tr>";
		}
?>	
	</tbody>
</table>


<?if(true or $viewDay == $gameinfo['day']){
if($entry['character']) $character = $entry['character'];
else $character = 0;

	$sql = "select max(comment) from $DB_comment_type where `game`='$no' and (`type` in $commentType or `character` = '".$character."')";
	$lastComment = mysql_fetch_array(mysql_query($sql));	

	if($member['no']){
		$login_info=mysql_fetch_array(mysql_query("SELECT * from zetyx_board_werewolf_loginlog WHERE ismember = ".$member['no']." ORDER BY NO DESC LIMIT 1"));
		$login_ip = $login_info['ip'];
	}
	
	$SID = $SessionID->getSID($gameinfo['game'],$viewDay,$lastComment['0'],$member['no'],$viewMode,$login_ip, $_zb_path);
	//echo $sql."<br>";
	//echo $gameinfo['game']."".$viewDay." ".$lastComment['0']." ".$member['no']."".$viewMode." ".$login_ip;
	$test = urlencode($SID);
?>
<bgsound src="#" id="soundeffect" loop=1 autostart="true" ></bgsound>
<SCRIPT LANGUAGE="JavaScript">
<!--
var SID = "<?=$SID?>"; 
var test = "<?=$test?>";
var viewChar =<?=$viewChar?$viewChar:0?>;

var gameNo  = <?=$gameinfo['game']?>; 
var gameDay  = <?=$viewDay?>; 
var gameLink  ="<?=$_zb_url?>";
var viewMode ="<?=$viewMode?>";

var characterImageFolder="<?=$characterImageFolder?>";
var loadingInterval = 30000;
//var timer = setTimeout(load,loadingInterval);
var commentLoader = window.setInterval("load()",loadingInterval);
//load();
var viewImage="<?=$viewImage?>";

//window.onload =load;
//-->
</SCRIPT>
<?}?>


<?//트랙백
	if($gameinfo['state'] == "게임끝" or $gameinfo['state'] == "버그"){	?>
<script>
function toClip(memo) {
        window.clipboardData.setData('Text',memo);
        alert('주소가 복사되었습니다');
}
</script>

<?	include "print_trackback.php";}?>



<!-- 페이지 모두 열기 버튼 -->
<button type="button" id="buttonOpenCommentPagesAll" style="border: 2px solid #666666; background-color: black; color: #666666; padding: 10px 110px; margin: 4px 2px; text-align: center; text-decoration: none; font-size: 14px; display: inline-block;">페이지 모두 열기</button>



<?
//덧글을 viewDay에 따라 뽑아낸다.
	//echo"select * from $t_comment"."_$id where parent='$no' and reg_date  between ".($gameinfo[deathtime] + (86400 * ( $viewDay -1)))." and   ".($gameinfo[deathtime] +  86400 * ($viewDay))."   order by no asc";
	if($viewChar and is_numeric($viewChar)) $checkChar = " AND `character` = $viewChar ";

	// Hide seal logs until the end of game except for myself and admin
	if($checkChar)
		// game in progress && viewChar != playing character && not admin
		if($gameinfo['state'] == "게임중" && $viewChar != $character && !$is_admin) $checkChar .= "AND type != '봉인제안' ";

	if(!$member[no]) $member[no] =0;

	$readLatest = $HTTP_COOKIE_VARS['readLatest'];	
	if(!$readLatest or $readLatest <0 or 20 < $readLatest or !is_numeric($readLatest)) $readLatest = 10;

	if($gameinfo['useTimetable'] == 0){
		if($gameinfo['state']== "준비중" ){
			$logCount = mysql_fetch_array(mysql_query("select count(*) from $t_comment"."_$id, $t_comment"."_$id"."_commentType where parent='$no'  AND game='$no' AND no = `comment`  AND (type in ".$commentType." or ismember = $member[no] and type like '메모')".$checkChar."order by no asc"));
			
			$totalCommentPage = ceil( $logCount[0]/ $readLatest) ;

			if(!$cPage or $cPage <0 or $totalCommentPage < $cPage or !is_numeric($cPage)) $cPage = $totalCommentPage;

			$logCount= $logCount[0] > $readLatest  ?  $readLatest * ($cPage -1) :0;
			$l = " limit ".($logCount).", ".$readLatest ;

			$sql="select * from $t_comment"."_$id, $t_comment"."_$id"."_commentType where parent='$no'  AND game='$no' AND no = `comment` AND (type in ".$commentType." or ismember = $member[no] and type like '메모')".$checkChar."order by no asc ".$l;
		}
		elseif($viewDay == 0){
			$logCount = mysql_fetch_array(mysql_query("select count(*) from $t_comment"."_$id, $t_comment"."_$id"."_commentType where parent='$no' AND game='$no' AND no = `comment` AND (type in ".$commentType." or ismember = $member[no] and type like '메모')".$checkChar." and reg_date  < $gameinfo[deathtime]  order by no asc"));			

			$totalCommentPage = ceil( $logCount[0]/ $readLatest) ;

			if(!$cPage or $cPage <0 or $totalCommentPage < $cPage or !is_numeric($cPage)) $cPage = $totalCommentPage;

			$logCount= $logCount[0] > $readLatest  ?  $readLatest * ($cPage -1) :0;
			$l = " limit ".($logCount).", ".$readLatest ;

			$sql="select * from $t_comment"."_$id, $t_comment"."_$id"."_commentType where parent='$no' AND game='$no' AND no = `comment` AND (type in ".$commentType." or ismember = $member[no] and type like '메모')".$checkChar." and reg_date  < $gameinfo[deathtime]  order by no asc ".$l;
		}
		elseif($viewDay == $gameinfo['day'] and $gameinfo['state']=="게임끝"){
			$logCount = mysql_fetch_array(mysql_query("select count(*) from $t_comment"."_$id, $t_comment"."_$id"."_commentType where parent='$no'  AND game='$no' AND no = `comment` ".$checkChar." and reg_date  > ".($gameinfo[deathtime] + ($gameinfo['termOfDay'] * ( $viewDay -1)))."  order by no asc "));			

			$totalCommentPage = ceil( $logCount[0]/ $readLatest) ;

			if(!$cPage or $cPage <0 or $totalCommentPage < $cPage or !is_numeric($cPage)) $cPage = $totalCommentPage;

			$logCount= $logCount[0] > $readLatest  ?  $readLatest * ($cPage -1) :0;
			$l = " limit ".($logCount).", ".$readLatest ;

			$sql="select * from $t_comment"."_$id, $t_comment"."_$id"."_commentType where parent='$no'  AND game='$no' AND no = `comment` ".$checkChar." and reg_date  > ".($gameinfo[deathtime] + ($gameinfo['termOfDay'] * ( $viewDay -1)))."  order by no asc ".$l;
		}
		else{
			$logCount = mysql_fetch_array(mysql_query("SELECT count(*) FROM $t_comment"."_$id, $t_comment"."_$id"."_commentType WHERE parent='$no' AND game='$no' AND no = `comment` ".$checkChar." AND  reg_date  BETWEEN ".($gameinfo[deathtime] + ($gameinfo['termOfDay'] * ( $viewDay -1)))." and   ".($gameinfo[deathtime] +  $gameinfo['termOfDay'] * ($viewDay))." and (type in ".$commentType." or ismember = $member[no]) order by no asc "));			

			$totalCommentPage = ceil( $logCount[0]/ $readLatest) ;

			if(!$cPage or $cPage <0 or $totalCommentPage < $cPage or !is_numeric($cPage)) $cPage = $totalCommentPage;

			$logCount= $logCount[0] > $readLatest  ?  $readLatest * ($cPage -1) :0;
			$l = " limit ".($logCount).", ".$readLatest ;

			$sql="SELECT * FROM $t_comment"."_$id, $t_comment"."_$id"."_commentType WHERE parent='$no' AND game='$no' AND no = `comment` ".$checkChar." AND  reg_date  BETWEEN ".($gameinfo[deathtime] + ($gameinfo['termOfDay'] * ( $viewDay -1)))." and   ".($gameinfo[deathtime] +  $gameinfo['termOfDay'] * ($viewDay))." and (type in ".$commentType." or ismember = $member[no] ) order by no asc ".$l;

			//$sql="select * from $t_comment"."_$id where parent='$no' and reg_date  between ".($gameinfo[deathtime] + ($gameinfo['termOfDay'] * ( $viewDay -1)))." and   ".($gameinfo[deathtime] +  $gameinfo['termOfDay'] * ($viewDay))."   order by no asc";
		}
	}
	elseif($gameinfo['useTimetable'] == 1){
		if($viewDay==1)
			$starttime = $gameinfo[deathtime];
		else{
			$starttime=mysql_fetch_array(mysql_query("select * from `zetyx_board_werewolf_timetable` where `game` = $gameinfo[game] and   `day` = $viewDay -1"));
			$starttime = $starttime['reg_date'];
		}
			$endtime  =mysql_fetch_array(mysql_query("select * from `zetyx_board_werewolf_timetable` where `game` = $gameinfo[game] and   `day` = $viewDay"));
			if($endtime['reg_date'])$endtime = $endtime['reg_date'];
			else $endtime = $starttime + $gameinfo['termOfDay'];


		if($gameinfo['state']== "준비중" ){
			$logCount = mysql_fetch_array(mysql_query("select count(*) from $t_comment"."_$id, $t_comment"."_$id"."_commentType where parent='$no'  AND game='$no' AND no = `comment`  AND (type in ".$commentType." or ismember = $member[no] and type like '메모')".$checkChar."order by no asc"));
			
			$totalCommentPage = ceil( $logCount[0]/ $readLatest) ;

			if(!$cPage or $cPage <0 or $totalCommentPage < $cPage or !is_numeric($cPage)) $cPage = $totalCommentPage;

			$logCount= $logCount[0] > $readLatest  ?  $readLatest * ($cPage -1) :0;
			$l = " limit ".($logCount).", ".$readLatest ;

			$sql="select * from $t_comment"."_$id, $t_comment"."_$id"."_commentType where parent='$no'  AND game='$no' AND no = `comment` AND (type in ".$commentType." or ismember = $member[no] and type like '메모')".$checkChar."order by no asc ".$l;
		}
		elseif($viewDay == 0){
			$logCount = mysql_fetch_array(mysql_query("select count(*) from $t_comment"."_$id, $t_comment"."_$id"."_commentType where parent='$no' AND game='$no' AND no = `comment` AND (type in ".$commentType." or ismember = $member[no] and type like '메모')".$checkChar." and reg_date  < $gameinfo[deathtime]  order by no asc"));			

			$totalCommentPage = ceil( $logCount[0]/ $readLatest) ;

			if(!$cPage or $cPage <0 or $totalCommentPage < $cPage or !is_numeric($cPage)) $cPage = $totalCommentPage;

			$logCount= $logCount[0] > $readLatest  ?  $readLatest * ($cPage -1) :0;
			$l = " limit ".($logCount).", ".$readLatest ;

			$sql="select * from $t_comment"."_$id, $t_comment"."_$id"."_commentType where parent='$no' AND game='$no' AND no = `comment` AND (type in ".$commentType." or ismember = $member[no] and type like '메모')".$checkChar." and reg_date  < $gameinfo[deathtime]  order by no asc ".$l;
		}
		elseif($viewDay == $gameinfo['day'] and ($gameinfo['state']=="게임끝" or $gameinfo['state']=="테스트")){
			$sql = "select count(*) from $t_comment"."_$id, $t_comment"."_$id"."_commentType where parent='$no'  AND game='$no' AND no = `comment` ".$checkChar." and reg_date  > ".($starttime)."  order by no asc ";
			$logCount = mysql_fetch_array(mysql_query($sql));			

			if($is_admin)		print $sql;

			$totalCommentPage = ceil( $logCount[0]/ $readLatest) ;

			if(!$cPage or $cPage <0 or $totalCommentPage < $cPage or !is_numeric($cPage)) $cPage = $totalCommentPage;

			$logCount= $logCount[0] > $readLatest  ?  $readLatest * ($cPage -1) :0;
			$l = " limit ".($logCount).", ".$readLatest ;

			$sql="select * from $t_comment"."_$id, $t_comment"."_$id"."_commentType where parent='$no'  AND game='$no' AND no = `comment` ".$checkChar." and reg_date  > ".($starttime)."  order by no asc ".$l;
		}
		else{
			$sql = "SELECT count(*) FROM $t_comment"."_$id, $t_comment"."_$id"."_commentType WHERE parent='$no' AND game='$no' AND no = `comment` ".$checkChar." AND  reg_date  BETWEEN ".($starttime)." and   ".($endtime)." and (type in ".$commentType." or ismember = $member[no]) order by no asc ";

			$logCount = mysql_fetch_array(mysql_query($sql));			
			if($is_admin)		print $sql;

			$totalCommentPage = ceil( $logCount[0]/ $readLatest) ;

			if(!$cPage or $cPage <0 or $totalCommentPage < $cPage or !is_numeric($cPage)) $cPage = $totalCommentPage;

			$logCount= $logCount[0] > $readLatest  ?  $readLatest * ($cPage -1) :0;
			$l = " limit ".($logCount).", ".$readLatest ;

			$sql="SELECT * FROM $t_comment"."_$id, $t_comment"."_$id"."_commentType WHERE parent='$no' AND game='$no' AND no = `comment` ".$checkChar." AND  reg_date  BETWEEN ".($starttime)." and   ".($endtime)." and (type in ".$commentType." or ismember = $member[no]) order by no asc ".$l;

			//$sql="select * from $t_comment"."_$id where parent='$no' and reg_date  between ".($gameinfo[deathtime] + ($gameinfo['termOfDay'] * ( $viewDay -1)))." and   ".($gameinfo[deathtime] +  $gameinfo['termOfDay'] * ($viewDay))."   order by no asc";
		}
	}


	$view_comment_result=mysql_query($sql);
?>


<!-- 간단한 답글 시작하는 부분 -->
<?=$hide_comment_start?> 
<div id="commentContainer">

<?for($index = 1;$index<=$totalCommentPage;$index++){
	if($index <> $totalCommentPage){
			echo "<div class='buttonCommentPage close'><input type='hidden' value='".$index."' id='value'/>".$index."<span></span></div>";
			echo "<div id='commentPage".$index."' value='".$index."'>";
			echo "</div>\n";
	}
	else{
			echo "<div class='buttonCommentPage open'><input type='hidden' value='".$index."' id='value'/>".$index."<span></span></div>";
			echo "<div id='commentPage".$index."' value='".$index."'>";
	}
}
?>