<script type="text/javascript" src="skin/<?=$id?>/js/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="skin/<?=$id?>/js/jquery.floatbox.js"></script>
<script type="text/javascript" src="skin/<?=$id?>/js/image-picker.min.js"></script>
<script type="text/javascript" src="skin/<?=$id?>/js/were-090114.js?ver=<?php echo filemtime('skin/'.$id.'/js/were-090114.js'); ?>"></script>
<?
	//----------------------------------------------------------------------
	//������ �ʱ�ȭ
	$gameinfo=mysql_fetch_array(mysql_query("select * from $DB_gameinfo where game=$no"));
	$rule=mysql_fetch_array(mysql_query("select * from $DB_rule where no=$gameinfo[rule]"));
	if($member[no])	$entry = mysql_fetch_array(mysql_query("select * from $DB_entry where game=$no and player = $member[no]"));
	
	$character_list = DB_array("no","character","$DB_character where `set` = '$gameinfo[characterSet]'");
	
	// Check subrules
	$CheckSecretVote = checkSubRule($gameinfo['subRule'], 4);
	//----------------------------------------------------------------------
	//����
	//���� ��� �̹����� �ִ� �ּ�
	$characterImageFolder = "skin/".$id."/character/".$gameinfo['characterSet']."/";

	//��ü ���� ���
	$writeRecord = true;
	//----------------------------------------------------------------------
	// ��б�
	if(!$data[is_secret]) $password = "";

	if(!$is_admin and $gameinfo['startingTime'] > time()) error("����� �����Դϴ�.");
	if ($viewDay=="" or $viewDay > $gameinfo['day'] or $viewDay < 0) $viewDay = $gameinfo['day'];

	if($gameinfo['state']=="���ӳ�" or $gameinfo['state']=="����"or $gameinfo['state']=="�׽�Ʈ" ){?>
		<div id="notice">
			<h1> ������ ����Ǿ����ϴ�. ��� �α׸� �����÷��� �Ʒ��� [��ü]�� �����ּ���. </h1>
			</div>
	<?}

	if($entry and ($gameinfo['state'] == "������" or $gameinfo['state'] == "����" or $gameinfo['state'] == "�׽�Ʈ")){
		$truecharacter =mysql_fetch_array(mysql_query("select * from $DB_truecharacter where no=$entry[truecharacter]"));
		$vote =mysql_fetch_array(mysql_query("select * from $DB_vote where game=$no and voter = $entry[character] and day = $gameinfo[day]"));

		if($truecharacter['forecast'])$forecast =mysql_fetch_array(mysql_query("select * from $DB_revelation where game='$no' and prophet='$entry[character]' and day='$gameinfo[day]' and type = '��'"));
		if($truecharacter['forecast-odd'])$forecastOdd =mysql_fetch_array(mysql_query("select * from $DB_revelation where game='$no' and prophet='$entry[character]' and day='$gameinfo[day]' and type = '��'"));
		if($truecharacter['mediumism'])$mediumism =mysql_fetch_array(mysql_query("SELECT * FROM `$DB_entry` WHERE `game` = $no AND `deathday` = $viewDay -1 AND `deathtype` LIKE '����' "));

		if($truecharacter['assault'])$assault =mysql_fetch_array(mysql_query("select * from $DB_deathNote where game='$no' and werewolf = '$entry[character]' and day='$gameinfo[day]'"));

		if($truecharacter['guard'])$guard =mysql_fetch_array(mysql_query("select * from $DB_guard where game='$no' and day='$gameinfo[day]'"));

		if($truecharacter['detect'])$detect =mysql_fetch_array(mysql_query("select * from $DB_detect where game='$no' and day='$gameinfo[day]'"));
		if($truecharacter['revenge'])$revenge =mysql_fetch_array(mysql_query("select * from $DB_revenge where game='$no'"));

		if($truecharacter['half-assault'])$halfassault =mysql_fetch_array(mysql_query("select * from $DB_deathNoteHalf where game='$no' and werewolf = '$entry[character]' and day='$gameinfo[day]'"));
		if($truecharacter['assault-con'])$assaultCon =mysql_fetch_array(mysql_query("select * from $DB_deathNote where game='$no' and werewolf = '$entry[character]' and day='$gameinfo[day]'"));
	}

	if($gameinfo['state'] == "�غ���") {
		if($is_admin and $viewMode) {
			if($viewMode == "all") $viewMode = "all";
			elseif($viewMode == "del") $viewMode = "del";
			else $viewMode = "�Ϲ�";
		}
		else $viewMode = "�Ϲ�";
	}
	elseif($gameinfo['state'] == "������") {
		if($entry) {
			if($entry['alive'] == "���") $viewMode = "death";
			else {
				if($truecharacter['telepathy']) $viewMode = "tele";
				elseif($truecharacter['secretchat']) $viewMode = "sec";
				elseif($truecharacter['secretletter']) $viewMode = "letter";
				else $viewMode = "�Ϲ�";
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
			else $viewMode = "�Ϲ�";
		}
		else $viewMode = "�Ϲ�";
	}
	elseif($gameinfo['state'] == "���ӳ�" and !$viewMode) $viewMode = "�Ϲ�";

	if($viewMode == "all") $commentType = "('�Ϲ�','�˸�','��������','���','���','�ڷ�','�޸�','����','�亯')";
	elseif($viewMode == "death") $commentType = "('�Ϲ�','�˸�','��������','���')";
	elseif($viewMode == "tele") $commentType = "('�Ϲ�','�˸�','��������','�ڷ�')";
	elseif($viewMode == "letter") $commentType = "('�Ϲ�','�˸�','��������','����','�亯')";
	elseif($viewMode == "sec") $commentType = "('�Ϲ�','�˸�','��������','���')";
	elseif($viewMode == "memo") $commentType = "('�Ϲ�','�˸�','��������','�޸�')";
	elseif($viewMode == "del") $commentType = "('�Ϲ�','�˸�','��������','���','���','�ڷ�','�޸�','����','�亯')";
	else $commentType = "('�Ϲ�','�˸�','��������')";
	
//[������ ���� �̺�Ʈ ����]////////////////////////////////////////////////////////////////////////////	
	// -----------------------------------------------------------------------------------//
	// ��� �߻� �ð� üũ
	//
	//�߻� ����: ��� �߻� �ð��� �����ٸ�
	//�߻� �б�: 
	//			          1. ���� ���� 1��°
	//			          2. ���� ���� 2��°
	//			          3. ���� ���� 3��°
	// -----------------------------------------------------------------------------------//
	flush(); 

	require_once("lib/functionForPlayer.php");
	if($is_admin and $useAdminTool)require_once("lib/functionForAdmin.php");

	$dayList ="<div class='viewDay'>";//<a href=$PHP_SELF?id=$id&no=$no&viewDay=0>���ѷα� </a>";

	for($indx=0;$indx<=$gameinfo['day'];$indx++){
		if($indx == $gameinfo['day'] and( $gameinfo['state']=="���ӳ�" or  $gameinfo['state']=="�׽�Ʈ")) $printDay = "���ʷα�";
		elseif($indx ==0) $printDay= "���ѷα� ";
		else $printDay= $indx."��°";

		if($viewDay == $indx) $active = "class ='selectedMode'";
		else  $active ="class =''";

		$dayList .="<a href='$PHP_SELF?id=$id&no=$no&viewDay=$indx&viewMode=$viewMode&viewChar=$viewChar'  $active>$printDay </a>";
	}
	$dayList .="</div>";

	echo $dayList;

	$modeList= "<div class='viewMode' ><a href=$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewChar=$viewChar&viewMode=�Ϲ�> �Ϲ� </a>";
	$modeList .="<a href=$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewChar=$viewChar&viewMode=death>��� </a>";
	$modeList .="<a href=$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewChar=$viewChar&viewMode=sec>��� ��ȭ </a>";
	$modeList .="<a href=$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewChar=$viewChar&viewMode=tele>�ڷ��Ľ� </a>";
	$modeList .="<a href=$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewChar=$viewChar&viewMode=letter>��� ���� </a>";
	$modeList .="<a href=$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewChar=$viewChar&viewMode=memo>�޸� </a>";
	$modeList .="<a href=$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewChar=$viewChar&viewMode=all> ��ü </a>";
	if($is_admin)$modeList .="<a href=$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewChar=$viewChar&viewMode=del>���� </a>";
	if($is_admin)$modeList .="<a href='log/".$gameinfo['game']."-log.txt' target='_blank'>�α� </a>";
	$modeList .="</div>";

	if($gameinfo['state']=="���ӳ�" or $gameinfo['state']=="����" or $gameinfo['state']=="����"  or $gameinfo['state']=="�׽�Ʈ" or ($is_admin)) echo $modeList;
?>

<div id="viewStateAll">
	<div class="viewState">
		<div class="state">���� �̸�</div>
		<div class="content"><?=$subject?></div>
	</div>

	<?if($data['x'] == 1 ){?>
	<div class="viewState">
		<div class="state">���� �Ұ�</div>
		<div class="content">
			<?=$memo?>
		</div>
	</div>
	<?}?>
	
	<?	// ������ ���۵Ǹ� ������ ������ ��¥�� ���δ�.
		if ($gameinfo['state'] == "�غ���" and $gameinfo['players']<>$rule[max_player]) {?>
	<div class="viewState">
		<div class="state">�ο� ����</div>
		<div class="content">
			<?echo date("m",$gameinfo['deathtime'])."�� ".date("d",$gameinfo['deathtime'])."�� ".date("H",$gameinfo['deathtime'])."�� ".date("i",$gameinfo['deathtime'])."��";?> ���ķ� �ο��� ���̸� ������ ���۵˴ϴ�.
		</div>
	</div>
	<?}?>
	<?	// ������ ���۵Ǹ� ������ ������ ��¥�� ���δ�.
		if ($gameinfo['state'] == "�غ���" and $gameinfo['players']==$rule[max_player] ) {?>
	<div class="viewState">
		<div class="state">�ο� ����</div>
		<div class="content">
			���� ������� �𿴽��ϴ�.<br /> 
			<?echo date("m",$gameinfo['deathtime'])."�� ".date("d",$gameinfo['deathtime'])." �� ".date("H",$gameinfo['deathtime'])."�� ".date("i",$gameinfo['deathtime'])." ��";?> ���� ������ ���۵˴ϴ�.
		</div>
	</div>
	<?}?>
	<?	// ������ ���۵Ǹ� ������ ������ ��¥�� ���δ�.
		if ($gameinfo['state'] <> "�غ���") {?>
	<div class="viewState">
		<div class="state">����� ���۵� ��</div>
		<div class="content">
			<?echo date("m",$gameinfo['deathtime'])."�� ".date("d",$gameinfo['deathtime'])."��";?>
		</div>
	</div>
	<?}?>
	<div class="viewState">
		<div class="state">����� �߻��ϴ� �ð�</div>
		<div class="content">
		<?
			if($viewDay == 0) {
				$accidentTiem = $gameinfo['deathtime'];
			}
			elseif($gameinfo['state'] == "�غ���" or $gameinfo['useTimetable'] == 0) {
				$accidentTiem = $gameinfo['deathtime'] + $gameinfo['termOfDay']*$viewDay;
			}
			elseif($gameinfo['useTimetable'] == 1) {
				$timetable = mysql_fetch_array(mysql_query("select * from `zetyx_board_werewolf_timetable` where `game` = $gameinfo[game] and `day` = $viewDay-1"));
				$accidentTiem = $timetable['reg_date'] + $gameinfo['termOfDay'];
			}
			echo date("H",$accidentTiem)."�� ".date("i",$accidentTiem)."��";
		?>
		</div>
	</div>
	<div class="viewState">
		<div class="state">�߾� ���� �ð�</div>
		<div class="content">
			<!--
			<span class="align-right">
			-->
				<? echo "��� �߻� ���� ".($gameinfo['delayBefore'] / 60)."��<br>"; ?>
				<? echo "���� ���� ���� ".($gameinfo['delayAfter'] / 60)."��"; ?>
			<!--
			</span>
			-->
		</div>
	</div>
	<div class="viewState">
		<div class="state">���� ���</div>
		<div class="content">
			<?echo $gameinfo['players']."��";?>
		</div>
	</div>
	<div class="viewState">
		<div class="state">��</div>
		<div class="content">
			<?echo $rule['name'];?>
		</div>
	</div>
	<div class="viewState">
		<div class="state">�����</div>
		<div class="content">
			<?
				if($gameinfo['subRule'] == 0) echo "����";
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
		<div class="state">�� �÷��� ��Ʈ</div>
		<div class="content">
			<?
				$ruleplayingSet = mysql_fetch_array(mysql_query("select * from `".$db->characterSet."` where `no`= '".$gameinfo['characterSet']."'"));
				echo "<a href='skin/".$id."/view_role-playing.php?id=".$id."&set=".$ruleplayingSet['no']."'>". $ruleplayingSet['name']."</a>";
			?>
		</div>
	</div>
	<div class="viewState">
		<div class="state">���� ��Ȳ</div>
		<div class="content">
		<?
			if($gameinfo['state']=="�غ���"){
				if($gameinfo['players']==16) echo "���� �ο��� ��� �𿴽��ϴ�.";
				else echo "���� �ο� ���� ��";				
			}
			else{
				if (	$viewDay == 0)				echo "���� �ο� ���� ��";
				elseif ($viewDay == $gameinfo['day'] and $gameinfo['state']=="���ӳ�"  )		echo "��� ��Ȳ�� ����Ǿ����ϴ�.";
				else	echo $viewDay."��° ���Դϴ�.";
			}
		?>
		</div>
	</div>
	<?	// �غ� ���϶�
		if ($viewDay =="0") {?>
	<div class="viewState">
		<div class="state"></div>
		<div class="content">
			���� �ΰ��� �༼�� �ϰ�, �㿡 ��ü�� ��Ÿ���ٰ� �ϴ� �ζ�.<br />
			�� �ζ���, �� ������ ���� �ִٴ� �ҹ��� ������.<br />
			���� ������� �ݽŹ��� �ϸ鼭��, ���� ���ҿ� ���̰� �Ǿ���.
		</div>
	</div>
	<?	}?>
	<?	// 1��
		if ($viewDay =="1") {?>
	<div class="viewState">
		<div class="state"></div>
		<div class="content">
			���� ����� ����� ������ ���ݰ� �ȴ�.<br />
			����� �ΰ��ΰ� �ƴϸ� �ζ��ΰ�!<br />
			<br />
			<? if($entry)echo "����� <span style='border:solid 1;border-color:#333333;width:60px' align=center><font color =#000000> ". $truecharacter_list[$entry[truecharacter]]." </font></span>�Դϴ�.";?>
		</div>
	</div>
	<?	}?>	
	<?//2�� ����
		if (1 < $viewDay) {?>
			<?
				$death_player_list;
				$death_list = mysql_query("select * from $DB_entry where game=$no and deathday = $viewDay-1 and deathtype ='����'");	
	
				while($death=mysql_fetch_array($death_list)){
					$death_player_list .= "<div style='width:120px;text-align:left'>".$character_list[$death['character']]."</div>";
				}
	
				if($death_player_list){?>
	<div class="viewState">
		<div class="state">������</div>
		<div class="content">
					<?echo "������ �����簡 �߻��߽��ϴ�.<br> �������� ���� ����� �Ʒ��� �����ϴ�.<br><br>".$death_player_list;?>
		</div>
	</div>
			<?}?>
			

			<?if($rule['no'] == 1 ){?>
				<?$death = mysql_fetch_array(mysql_query("select * from $DB_entry where game=$no and deathday = $viewDay-1 and deathtype ='����'"));				
				if($death){?>
	<div class="viewState">
		<div class="state">��ǥ</div>
		<div class="content">				
				<?echo $character_list[$death['character']]."���� ��ǥ ����� �� �Ŵ޾������ϴ�.<br>" ;?>
		</div>
	</div>	
				<?}?>
				
				<?$death = mysql_fetch_array(mysql_query("select * from $DB_entry where game=$no and deathday = $viewDay-1 and deathtype ='����'"));	?>
	<div class="viewState">
		<div class="state">����</div>
		<div class="content">				
				<?if($death){ echo $character_list[$death['character']]."�� ��ü�� �߰ߵǾ����ϴ�.<br>���� �� �ζ����� ���ݹ��� ������ ���Դϴ�.<br>";}
				else{echo "���� �㿡�� ������ ������. �ζ��� ���ݿ� ������ ���ϱ�...?<br>";}?>
		</div>
	</div>
			<?}?>

			<?if($rule['no'] == 2 ){?>
				<?$death = mysql_fetch_array(mysql_query("select * from $DB_entry where game=$no and deathday = $viewDay-1 and deathtype ='����'"));				
				if($death){?>
	<div class="viewState">
		<div class="state">��ǥ</div>
		<div class="content">				
				<?echo $character_list[$death['character']]."���� ��ǥ ����� ���� ��������߽��ϴ�.<br>" ;?>
		</div>
	</div>	
				<?}?>

			
			<?	$death_list = mysql_query("select * from $DB_entry where game=$no and deathday = $viewDay-1 and deathtype ='����'");	
				$death_player_list ="";

					while($death=mysql_fetch_array($death_list)){
						$death_player_list .= "<div style='width:120px;text-align:left'>".$character_list[$death['character']]."</div>";
					}
				if($death_player_list){?>
	
	<div class="viewState">
		<div class="state">����</div>
		<div class="content">
				<?echo "���� ���� ���� �Ҷ���������. �ұ��� ������ ���� ���ٺ���<br />������ ���ص� ��ü�� �־��١� ���� ���� �̸�����<br /><br />".$death_player_list;?>
		</div>
	</div>
				<?}
				else{?>
	<div class="viewState">
		<div class="state">����</div>
		<div class="content">
				<?echo "���� ���� �ʹ����� �����ߴ�. ���� �߰� ���� ���ٺ�������,<br />�ƹ� �ϵ� ������ �� ����. �̰� ���� �� ���ϱ?";?>
		</div>
	</div>	
	<?}}?>


			<?if($rule['no'] == 3 ){?>
				<?$death = mysql_fetch_array(mysql_query("select * from $DB_entry where game=$no and deathday = $viewDay-1 and deathtype ='����'"));				
				if($death){?>
					<div class="viewState">
						<div class="state">��ǥ</div>
							<div class="content">				
								<?echo $character_list[$death['character']]."���� ��ǥ ����� �� �Ŵ޾������ϴ�.<br>" ;?>
							</div>
						</div>	
				<?}
				else if($viewDay>2){?>
					<div class="viewState">
						<div class="state">��ǥ</div>
						<div class="content">
								<?echo "��ǥ ����� ���� ���� �����Ϸ��� ����..<br>";
									if($gameinfo['state']=="���ӳ�" and $viewDay == $gameinfo['day']) echo "������ ��ҿ� �ڵ�����.";
									else echo "�������� ��������.";
								?>
						</div>
					</div>	
				<?}?>

			
			<?	$death_list = mysql_query("select * from $DB_entry where game=$no and deathday = $viewDay-1 and deathtype ='����'");	
				$death_player_list ="";

					while($death=mysql_fetch_array($death_list)){
						$death_player_list .= "<div style='width:120px;text-align:left'>".$character_list[$death['character']]."</div>";
					}
				if($death_player_list){?>
	
	<div class="viewState">
		<div class="state">����</div>
		<div class="content">
				<?echo "���� ���� ���� �Ҷ���������. �ұ��� ������ ���� ���ٺ���<br />������ ���ص� ��ü�� �־��١� ���� ���� �̸�����<br /><br />".$death_player_list;?>
		</div>
	</div>
				<?}
				else{?>
	<div class="viewState">
		<div class="state">����</div>
		<div class="content">
				<?echo "���� ���� �ʹ����� �����ߴ�. ���� �߰� ���� ���ٺ�������,<br />�ƹ� �ϵ� ������ �� ����. �̰� ���� �� ���ϱ?";?>
		</div>
	</div>	
	<?}}?>



	<?	}?>	
	<?	if ($viewDay ==$gameinfo['day'] and $gameinfo['state']=="���ӳ�") {?>
		<div class="viewState">
			<div class="state"></div>
			<div class="content">������ ����Ǿ����ϴ�. <br />	<br />	
		<?
			if($gameinfo['win'] == 1)
				echo "�� �̻� �ζ����� ������ �� ���� ������ ���� ����� �������� �ʴ�... <br />�ζ��� ���� ���� ����� ���� ��Ƹ��� ��, �ٸ� ����ڸ� ã�� �� ������ ������.";
			elseif($gameinfo['win'] == 0) 
				echo "��� �ζ��� ��ġ�Ͽ� ������ ��ȭ�� ã�ƿԴ�.<br />���� �ζ��� �η����� �ʿ�� ��������!";
			elseif($gameinfo['win']==2)
				echo "...��� ���� �����ٰ� �����ߴ�. ������ ��Ƴ��� �ڵ��� ���Ҵ�.<br />���� ��Ϳ������� ������� ������ �ܽ����� ������... <br /><br />.....��Ƴ��� �ڵ��� ��� �ܽ��Ϳ��� ��Ƹ�����.";
			elseif($gameinfo['win']==3)
				echo "ħ���� ������ ��� ��ǥ ����� �����Ϸ��� ����..<br>
						<br>
						��ƺ�ΰ� �����ߴ�.<br>
						������ ������ ��ƺ�δ� ������ �ȵ�θ޴ٷ� �������ȴ�.. bye~";
		?>
			</div>
		</div>
	<?	}?>	
	<?if($entry['alive']== "����" and $forecast_result){?>
		<div class="viewState">
			<div class="state">���� ���</div>
			<div class="content">
				<?="������ ���Դ�. ".$character_list[$forecast_result['mystery']]?>
				<?	
					if($forecast_result[result] == 0 or $forecast_result[result] == 2) echo "���� �ΰ��̴�.";
					else echo "���� �ζ��̴�.";
					?>

			
			</div>
	</div>
	<?}?>
	<?if($viewDay == 1 and ($gameinfo['rule']==1 or $gameinfo['rule']==2)){ ?>
		<div class="viewState">
			<div class="state"></div>
			<div class="content">
			<?
				if ($gameinfo['players']==11) echo "�ƹ����� �� �ȿ���, ��������� 5��, �ζ��� 2��, �����̰� 1��,<br />�����ڰ� 1��, ������ 1��, ��ɲ��� 1�� �ִ� �� ����.";
				if ($gameinfo['players']==12) echo "�ƹ����� �� �ȿ���, ��������� 6��, �ζ��� 2��, �����̰� 1��,<br />�����ڰ� 1��, ������ 1��, ��ɲ��� 1�� �ִ� �� ����."; 
				if ($gameinfo['players']==13) echo "�ƹ����� �� �ȿ���, ��������� 7��, �ζ��� 2��, �����̰� 1��,<br />�����ڰ� 1��, ������ 1��, ��ɲ��� 1�� �ִ� �� ����.";
				if ($gameinfo['players']==14) echo "�ƹ����� �� �ȿ���, ��������� 8��, �ζ��� 2��, �����̰� 1��,<br />�����ڰ� 1��, ������ 1��, ��ɲ��� 1�� �ִ� �� ����.";
				if ($gameinfo['players']==15) echo "�ƹ����� �� �ȿ���, ��������� 8��, �ζ��� 3��, �����̰� 1��,<br />�����ڰ� 1��, ������ 1��, ��ɲ��� 1�� �ִ� �� ����.";
				if ($gameinfo['players']==16) echo "�ƹ����� �� �ȿ���, ��������� 7��, �ζ��� 3��, �����̰� 1��,<br />�����ڰ� 1��, ������ 1��, ��ɲ��� 1��, �ʴɷ��ڰ� 2�� �ִ� �� ����.";
				if ($gameinfo['players']==17) echo "�ƹ����� �� �ȿ���, ��������� 7��, �ζ��� 3��, �����̰� 1��, �����ڰ� 1��,<br />������ 1��, ��ɲ��� 1��, �ʴɷ��ڰ� 2��, �ܽ��Ͱ� 1���� �ִ� �� ����.";
			?>
			</div>
		</div>
	<?}?>
	<?if($viewDay == 1 and ($gameinfo['rule']==3)){ ?>
		<div class="viewState">
			<div class="state"></div>
			<div class="content">
			<?
				if ($gameinfo['players']==9) echo "�ƹ����� �� �ȿ��� �ζ� 1��, �ܷο� ���� 1��, ���� 1��, ������ 1��, ������ 1��,<br>��ɲ� 1��, ������ 1��, ���Ȱ� 1��, ������� 1���� �ִ� �� ����.";
				if ($gameinfo['players']==10) echo "�ƹ����� �� �ȿ��� �ζ� 1��, �ܷο� ���� 1��, ���� 1��, ������ 1��, ������ 1��,<br>��ɲ� 1��, ������ 1��, ���� 1��, ������� 2���� �ִ� �� ����.";
				if ($gameinfo['players']==11) echo "�ƹ����� �� �ȿ��� �ζ� 1��, �ζ� ���� 1��, ���� 1��, ������ 1��, ������ 1��,<br>��ɲ� 1��, ���� 1��,���Ȱ� 1��, ������� 3���� �ִ� �� ����.";
				if ($gameinfo['players']==12) echo "�ƹ����� �� �ȿ��� �ζ� 1��, �ζ� ���� 1��, ���� 1��,<br>������ 1��, ������ 1��, ��ɲ� 1��, ���� 1��, ������� 5���� �ִ� �� ����.";
				if ($gameinfo['players']==13) echo "�ƹ����� �� �ȿ��� �ζ� 1��, �ζ� ���� 1��, �ܷο� ���� 1��, ���� 1��,<br>������ 1��, ������ 1��, ��ɲ� 1��, ������ 1��, ���� 1��, ���� 1��, ���Ȱ� 1��, ������� 2���� �ִ� �� ����.";
				if ($gameinfo['players']==14) echo "�ƹ����� �� �ȿ��� �ζ� 1��, �ζ� ���� 1��, �ܷο� ���� 1��, ���� 1��,<br>������ 1��, ������ 1��, ��ɲ� 1��, ������ 1��, ���� 1��, ���� 1��, ������� 4���� �ִ� �� ����.";
				if ($gameinfo['players']==15) echo "�ƹ����� �� �ȿ��� �ζ� 2��, �ζ� ���� 1��, �ܷο� ���� 1��, ���� 1��,<br>������ 1��, ������ 1��, ��ɲ� 1��, ������ 1��, ���� 1��, ���� 1��, ���Ȱ� 1��, ������� 3���� �ִ� �� ����.";
				if ($gameinfo['players']==16) echo "�ƹ����� �� �ȿ��� �ζ� 2��, �ζ� ���� 1��, �ܷο� ���� 1��, ���� 1��,<br>������ 1��, ������ 1��, ��ɲ� 1��, ������ 1��, ���� 1��, ���� 1��, ������� 5���� �ִ� �� ����.";
				if ($gameinfo['players']==17) echo "�ƹ����� �� �ȿ��� �ζ� 2��, �ζ� ���� 1��, �ܷο� ���� 1��, ���� 1��,<br>������ 1��, ������ 1��, ��ɲ� 1��, ������ 1��, ���� 1��, ���� 1��, ������� 5��, �׸��� ��ƺ�ΰ� �ִ� �� ����.";
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

	$tableHead = array("No","<a href='$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewMode=$viewMode' >���� ���</a>","����","��ǥ ���","&nbsp;","&nbsp;");
	$tableCol ="<col width=17><col width=120></col><col width=100></col><col width=125></col><col width=120></col><col width=></col>";
	$tableBody= array();

	$i=1;
	$temp_result=mysql_query("select * from $DB_entry where game = $no and (alive= '����' or $viewDay <= deathday) order by alive desc,deathday desc,victim");
		while($data=mysql_fetch_array($temp_result)){

			$t=$data[character];
		
			if($data['deathtype'] == "����"){
				$deathType = "��ǥ";
			}
			else $deathType = $data['deathtype'];

			if ($data['alive']=="���"){
				if ($viewDay <= $data['deathday']){$alive  = "����";}
				else {	$alive = $data['deathday']."��° ���-".$deathType;}
			}
			else $alive ="����";

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
		<tr><td>No</td><td><?="<a href='$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewMode=$viewMode' >"?>���� ���</a></td><td>����</td><td>��ǥ ���</td><td>&nbsp;</td><td>&nbsp;</td></tr>
	</thead>
	<tbody>
<?	// �÷��̾� ���
	$i=1;
	$temp_result=mysql_query("select * from $DB_entry where game = $no and (alive= '����' or $viewDay <= deathday) order by alive desc,deathday desc,victim");
	
		while($data=mysql_fetch_array($temp_result)){
			 $playerMember=mysql_fetch_array(mysql_query("select * from zetyx_member_table where no = $data[player]"));

			$t=$data[character];

			echo "<tr onMouseOver=this.style.backgroundColor='#090909' onMouseOut=this.style.backgroundColor=''>";
//1
			echo "<td align=center class='red_8'>$i</td>";	++$i;
//1
			echo "<td><a href='$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewMode=$viewMode&viewChar=$t'><img src='skin/$id/image/filter.png' border='0' title='Ŭ�� - $character_list[$t]���� �α׸� ���ϴ�.'></a>".
					"<input type='checkbox' id='$t' class='characterButton' value='$t' checked='checked'/>".
					"<label for='$t' title='Ŭ�� - ���͸�\n���� Ŭ�� - $character_list[$t]���� �α׸� ���ϴ�.'>$character_list[$t]</label></td>";			
		
//2

			if($data['deathtype'] == "����"){
				$deathType = "��ǥ";
			}
			else $deathType = $data['deathtype'];

			if ($data['alive']=="���"){
				if ($viewDay <= $data['deathday']){echo "<td>����</td>";}
				else {	echo "<td>".$data['deathday']."��° ���-".$deathType."</td>";}
			}
			else echo "<td>���� </td>";
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
<?	// �÷��̾� ���
	$i=1;
	$temp_result=mysql_query("select * from $DB_entry where game = $no and (alive <> '����'  and $viewDay > deathday) order by alive desc,deathday desc,victim");
	
		while($data=mysql_fetch_array($temp_result)){
			 $playerMember=mysql_fetch_array(mysql_query("select * from zetyx_member_table where no = $data[player]"));

			$t=$data[character];

			echo "<tr onMouseOver=this.style.backgroundColor='#090909' onMouseOut=this.style.backgroundColor=''>";
//1
			echo "<td align=center class='red_8'>$i</td>";	++$i;		
//1
			echo "<td><a href='$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewMode=$viewMode&viewChar=$t'><img src='skin/$id/image/filter.png' border='0' title='Ŭ�� - $character_list[$t]���� �α׸� ���ϴ�.'></a>".
					"<input type='checkbox' id='$t' class='characterButton' value='$t' checked='checked'/>".
					"<label for='$t'>$character_list[$t]</label></td>";			
		
//2

			if($data['deathtype'] == "����"){
				$deathType = "��ǥ";
			}
			else $deathType = $data['deathtype'];

			if ($data['alive']=="���"){
				if ($viewDay <= $data['deathday']){echo "<td>����</td>";}
				else {	echo "<td>".$data['deathday']."��° ���-".$deathType."</td>";}
			}
			else echo "<td>���� </td>";
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

//	$lastComment = mysql_fetch_array(mysql_query("select max(reg_date) from $t_comment"."_$id where `parent`='$no'"));
	$sql = "select max(comment) from $DB_comment_type where `game`='$no' and (`type` in $commentType or `character` = '".$character."')";
	$lastComment = mysql_fetch_array(mysql_query($sql));
	
	$SID = $SessionID->getSID($gameinfo['game'],$viewDay,$lastComment['0'],$member['no'],$viewMode);
	//echo $sql."<br>";
	//echo $gameinfo['game']."".$viewDay." ".$lastComment['0']." ".$member['no']."".$viewMode;
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


<?//Ʈ����
	if($gameinfo['state'] == "���ӳ�" or $gameinfo['state'] == "����"){	?>
<script>
function toClip(memo) {
        window.clipboardData.setData('Text',memo);
        alert('�ּҰ� ����Ǿ����ϴ�');
}
</script>

<?	include "print_trackback.php";}?>



<!-- ������ ��� ���� ��ư -->
<button type="button" id="buttonOpenCommentPagesAll" style="border: 2px solid #666666; background-color: black; color: #666666; padding: 10px 110px; margin: 4px 2px; text-align: center; text-decoration: none; font-size: 14px; display: inline-block;">������ ��� ����</button>



<?
//������ viewDay�� ���� �̾Ƴ���.
	//echo"select * from $t_comment"."_$id where parent='$no' and reg_date  between ".($gameinfo[deathtime] + (86400 * ( $viewDay -1)))." and   ".($gameinfo[deathtime] +  86400 * ($viewDay))."   order by no asc";
	if($viewChar and is_numeric($viewChar)) $checkChar = " AND `character` = $viewChar ";

	// Hide seal logs until the end of game except for myself and admin
	if($checkChar)
		// game in progress && viewChar != playing character && not admin
		if($gameinfo['state'] == "������" && $viewChar != $character && !$is_admin) $checkChar .= "AND type != '��������' ";

	if(!$member[no]) $member[no] =0;

	$readLatest = $HTTP_COOKIE_VARS['readLatest'];	
	if(!$readLatest or $readLatest <0 or 20 < $readLatest or !is_numeric($readLatest)) $readLatest = 10;

	if($gameinfo['useTimetable'] == 0){
		if($gameinfo['state']== "�غ���" ){
			$logCount = mysql_fetch_array(mysql_query("select count(*) from $t_comment"."_$id, $t_comment"."_$id"."_commentType where parent='$no'  AND game='$no' AND no = `comment`  AND (type in ".$commentType." or ismember = $member[no] and type like '�޸�')".$checkChar."order by no asc"));
			
			$totalCommentPage = ceil( $logCount[0]/ $readLatest) ;

			if(!$cPage or $cPage <0 or $totalCommentPage < $cPage or !is_numeric($cPage)) $cPage = $totalCommentPage;

			$logCount= $logCount[0] > $readLatest  ?  $readLatest * ($cPage -1) :0;
			$l = " limit ".($logCount).", ".$readLatest ;

			$sql="select * from $t_comment"."_$id, $t_comment"."_$id"."_commentType where parent='$no'  AND game='$no' AND no = `comment` AND (type in ".$commentType." or ismember = $member[no] and type like '�޸�')".$checkChar."order by no asc ".$l;
		}
		elseif($viewDay == 0){
			$logCount = mysql_fetch_array(mysql_query("select count(*) from $t_comment"."_$id, $t_comment"."_$id"."_commentType where parent='$no' AND game='$no' AND no = `comment` AND (type in ".$commentType." or ismember = $member[no] and type like '�޸�')".$checkChar." and reg_date  < $gameinfo[deathtime]  order by no asc"));			

			$totalCommentPage = ceil( $logCount[0]/ $readLatest) ;

			if(!$cPage or $cPage <0 or $totalCommentPage < $cPage or !is_numeric($cPage)) $cPage = $totalCommentPage;

			$logCount= $logCount[0] > $readLatest  ?  $readLatest * ($cPage -1) :0;
			$l = " limit ".($logCount).", ".$readLatest ;

			$sql="select * from $t_comment"."_$id, $t_comment"."_$id"."_commentType where parent='$no' AND game='$no' AND no = `comment` AND (type in ".$commentType." or ismember = $member[no] and type like '�޸�')".$checkChar." and reg_date  < $gameinfo[deathtime]  order by no asc ".$l;
		}
		elseif($viewDay == $gameinfo['day'] and $gameinfo['state']=="���ӳ�"){
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


		if($gameinfo['state']== "�غ���" ){
			$logCount = mysql_fetch_array(mysql_query("select count(*) from $t_comment"."_$id, $t_comment"."_$id"."_commentType where parent='$no'  AND game='$no' AND no = `comment`  AND (type in ".$commentType." or ismember = $member[no] and type like '�޸�')".$checkChar."order by no asc"));
			
			$totalCommentPage = ceil( $logCount[0]/ $readLatest) ;

			if(!$cPage or $cPage <0 or $totalCommentPage < $cPage or !is_numeric($cPage)) $cPage = $totalCommentPage;

			$logCount= $logCount[0] > $readLatest  ?  $readLatest * ($cPage -1) :0;
			$l = " limit ".($logCount).", ".$readLatest ;

			$sql="select * from $t_comment"."_$id, $t_comment"."_$id"."_commentType where parent='$no'  AND game='$no' AND no = `comment` AND (type in ".$commentType." or ismember = $member[no] and type like '�޸�')".$checkChar."order by no asc ".$l;
		}
		elseif($viewDay == 0){
			$logCount = mysql_fetch_array(mysql_query("select count(*) from $t_comment"."_$id, $t_comment"."_$id"."_commentType where parent='$no' AND game='$no' AND no = `comment` AND (type in ".$commentType." or ismember = $member[no] and type like '�޸�')".$checkChar." and reg_date  < $gameinfo[deathtime]  order by no asc"));			

			$totalCommentPage = ceil( $logCount[0]/ $readLatest) ;

			if(!$cPage or $cPage <0 or $totalCommentPage < $cPage or !is_numeric($cPage)) $cPage = $totalCommentPage;

			$logCount= $logCount[0] > $readLatest  ?  $readLatest * ($cPage -1) :0;
			$l = " limit ".($logCount).", ".$readLatest ;

			$sql="select * from $t_comment"."_$id, $t_comment"."_$id"."_commentType where parent='$no' AND game='$no' AND no = `comment` AND (type in ".$commentType." or ismember = $member[no] and type like '�޸�')".$checkChar." and reg_date  < $gameinfo[deathtime]  order by no asc ".$l;
		}
		elseif($viewDay == $gameinfo['day'] and ($gameinfo['state']=="���ӳ�" or $gameinfo['state']=="�׽�Ʈ")){
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


<!-- ������ ��� �����ϴ� �κ� -->
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