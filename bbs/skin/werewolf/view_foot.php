<?=$hide_comment_end?>
</div>

<?//����////////////////////////////////////////////////
$sql = "select * from $DB_secretletter where `game`='".$no."' and `day`='".($viewDay-1)."'";
$secretmessage=mysql_fetch_array(mysql_query($sql));

if($secretmessage and (($secretmessage['to'] ==$entry['character'] and $entry['alive']=="����") or $viewMode =="all") and $viewDay>1){?>
<div class="letter">
	<h1>
		������ <u><?=$character_list[$secretmessage['from']]?></u> ���κ��� ������ �����߽��ϴ�.
	</h1>
	<div class="message">
			<?	
			$sql ="select * from `zetyx_board_comment_werewolf`  where no = ".$secretmessage['message'];
			$secretmessagem =mysql_fetch_array(mysql_query($sql));
			echo nl2br($secretmessagem['memo']);
			?>
	</div>
</div>
<?}?>


<?	
if(($gameinfo['state']=="������" and $truecharacter['forecast'] and $entry['alive']=="����")or $gameinfo['state']=="���ӳ�" and $viewDay > 1){
	$forecast_result =mysql_fetch_array(mysql_query("select * from $DB_revelation where game='$no' and day=$viewDay -1 and type = '��'"));

	if($forecast_result ){
	?>
	<div class="DisplayBoard alignleft">
		<span class="head">���� ���</span>
		<span>
			<? echo $character_list[$forecast_result['mystery']];	
			if($forecast_result[result] == 0 or $forecast_result[result] == 2) echo " ���� �ΰ�";
			else echo " ���� �ζ�";
			?>
		</span>
	</div>
<?}
}?>

<?	
if(($gameinfo['state']=="������" and $truecharacter['forecast-odd'] and $entry['alive']=="����") and $viewDay > 1  ){
	$forecast_result =mysql_fetch_array(mysql_query("select * from $DB_revelation where game='$no' and day=$viewDay -1 and type = '��'"));

	if($forecast_result ){
	?>
	<div class="DisplayBoard alignleft">
		<span class="head">���� ���</span>
		<span>
			<? echo $character_list[$forecast_result['mystery']];	
			if($forecast_result[result] == 0 or $forecast_result[result] == 2 ) echo " ���� �ΰ�";
			else echo " ���� �ζ�";
			?>
		</span>
	</div>
<?}
}?>



<?	if(($gameinfo['state']=="������" and $truecharacter['mediumism'] and $entry['alive']=="����")or $gameinfo['state']=="���ӳ�" and $viewDay > 2){
	$sql = "SELECT * FROM `$DB_entry` WHERE `game` = $no AND `deathday` = $viewDay -1 AND `deathtype` LIKE '����'";
	$mediumism =mysql_fetch_array(mysql_query($sql));

	if($mediumism){
?>
	<div class="DisplayBoard alignleft">
		<span class="head">���� ���</span>
		<span>
			<?
				echo $character_list[$mediumism[character]];
				if($mediumism[truecharacter]==5 or $mediumism[truecharacter]==9 or $mediumism[truecharacter]==10 or $mediumism[truecharacter]==14) echo " ���� �ζ�"; else echo " ���� �ΰ�";
			?>
		</span>
	</div>
<?}
}?>


<?	if(($gameinfo['state']=="������" and $truecharacter['guard'] and $entry['alive']=="����") or $gameinfo['state']=="���ӳ�" and $viewDay > 2) {
		$guard_result =mysql_fetch_array(mysql_query("select * from $DB_guard where game='$no' and day=$viewDay - 1"));
		$assault_result =mysql_fetch_array(mysql_query("select * from $DB_deathNote_result where game='$no' and day=$viewDay - 1"));
		
		if($guard_result and $assault_result ){?>
			<div class="DisplayBoard alignleft">
				<span class="head">��ȣ ���</span>
				<span>
					<?if($assault_result[injured] == $guard_result[purpose]) echo "������ ".$character_list[$guard_result[purpose]]." ���� ���� �ζ��� ������ ���Ҵ�!";
					else echo "������ ".$character_list[$guard_result[purpose]]." �� �ֺ������� �ƹ� �ϵ� �Ͼ�� �ʾҴ�.";?>
				</span>
			</div>
		<?}?>
<?	}?>

<?	if(($gameinfo['state']=="������" and $truecharacter['detect'] and $entry['alive']=="����") or $gameinfo['state']=="���ӳ�" and $viewDay > 1) {
	$sql="select * from $DB_detect where game='$no' and day=$viewDay -1 ";
	$detectResult =mysql_fetch_array(mysql_query($sql));

	if($detectResult){
		$sql = "select * from $DB_entry where `game`='$no' and `character` = '".$detectResult['target']."'";
		$target_entry = mysql_fetch_array(mysql_query($sql)) or die($sql);
	
		$resultText = $character_list[$detectResult['target']]." ���� " ;
		if($target_entry['truecharacter'] == 1) $resultText .= "����ϴ�";
		else $resultText .= "����ϴ�";
		?>
	<div class="DisplayBoard alignleft">
		<span class="head">���� ���</span><span><?= $resultText;?></span>
	</div>
	<?	}
}?>

<?
/*
	for($index = 1; $index <= $totalCommentPage; $index++){
		echo "[<span class='buttonCommentPage'><input type='hidden' value='".$index."' id='value'/>".$index."</span>] ";
	}
*/
?>

<?if(!($gameinfo['state'] == "���ӳ�" or $gameinfo['state'] == "�׽�Ʈ")) { 
	if($gameinfo['state'] == "�غ���" or $gameinfo['useTimetable']==0){
		$timer = $gameinfo['deathtime'] + $gameinfo['termOfDay']*$gameinfo['day'] - time();
	}
	elseif($gameinfo['useTimetable']==1) {
		$timetable=mysql_fetch_array(mysql_query("select * from `zetyx_board_werewolf_timetable` where `game` = $gameinfo[game] and `day` = $gameinfo[day]-1"));
		$timer = $timetable['reg_date'] + $gameinfo['termOfDay'] - time();
	}
	?>
<script type="text/javascript">
var hour=<?= floor($timer/3600 )?>;
var min=<?=($timer/60)%60?>;
var sec=<?=$timer% 60?>;
// add a zero in front of numbers<10
	hour=checkTime(hour);
	min=checkTime(min);
	sec=checkTime(sec);

function checkTime(i)
{
// if (i<10)   {i="0" + i}
  return i
}
 
 function startTime()
{
   if( sec>0 )
      sec--;
   else if( sec==0 && min>0 )
   {
      min--;
      sec = 59;
   }
   else if( sec==0 && min==0 && hour > 0)
   {
		hour--;
		min = 59;
		sec = 59;
   }
   else {
		hour = 0;
		min = 0;
		sec = 0;
		//window.location.reload();
   }
   
	document.getElementById('timer').innerHTML=zeroFill(hour)+":"+zeroFill(min)+":"+zeroFill(sec)
}

function zeroFill(value){
	if(0 <= value && value <= 9){
		return "0"+value;
	}
	else{
		return value;
	}
}

var timer = setInterval(startTime, 1000);

function formcheck(f){ 
	return true;
} 
</script>


<div class="DisplayBoard" id="displayTimer">
	<?if($gameinfo['day']){?>
		<span>������ <?=$gameinfo['day']?>��° ���Դϴ�.</span>
	<?}
		else{?>
		<span> ���� �ο� ���� ��</span>
	<?}?>
	<br />
	<?echo date("H",$accidentTiem)."�� ".date("i",$accidentTiem)."��";?> - <span id="timer"></span>
</div>
<?}?>

<!-- ȯ�� ���� -->
<!-- �α� �˸� �Ҹ� ���� -->
<span  id="playerpp"></span>

<div id="notice">
<form id="soundConfig" style="display:inline;">
<span id="icon"></span>
<input type="radio" name="sound" value="On" id="soundOn"> <label for="soundOn" title="���ο� ������ ����� �Ҹ��� �˸��ϴ�.">On</label>
<input type="radio" name="sound" value="Off" id="soundOff"> <label for="soundOff"  title="�˸� �Ҹ��� ���ϴ�.">Off</label>
</form> 

<form id="selectSound" style="display:inline;">
<span id="icon"></span>
<input type="radio" name="selectedSound" value="dog" id="dogSound"> <label for="dogSound" title="������ �Ҹ�">������</label>
<input type="radio" name="selectedSound" value="cat" id="catSound"> <label for="catSound"  title="����� �Ҹ�">�����</label>
</form> 
<!--
<embed name="myMusic" src="dog.wav" type="audio/x-wav" autostart="false" hidden="true" loop="false" mastersound width="0" height="0"></embed>
-->

<script>

</script>

	<!-- �ε��� �α� ���� ���� -->
	<div>
		<span id="limit"></span>
		<select id="readLimit"><option value="5">5</option><option value="10">10</option><option value="15">15</option><option value="20">20</option></select><label for="readLimit"  title="�������ּ���.">���� ����</label>
	</div>
	<!-- �α� ���� -->
	<div id="">
		<input type="checkbox" id="normalButton" class="commentButton" checked="checked" value="normal"/>
		<label for="normalButton" title="�Ϲ� �α׸� ���͸��մϴ�.">�Ϲ�</label>
		<input type="checkbox" id="memoButton" class="commentButton" checked="checked" value="memo"/>
		<label for="memoButton" title="�޸� �α׸� ���͸��մϴ�.">�޸�</label>
		<input type="checkbox" id="secretButton" class="commentButton" checked="checked" value="secret"/>
		<label for="secretButton" title="��� �α׸� ���͸��մϴ�.">��� ��ȭ</label>
	
	
		<input type="checkbox" id="telepathyButton" class="commentButton" checked="checked" value="telepathy"/>
		<label for="telepathyButton" title="�ڷ��Ľ� �α׸� ���͸��մϴ�.">�ڷ��Ľ�</label>
		<input type="checkbox" id="graveButton" class="commentButton" checked="checked" value="grave"/>
		<label for="graveButton" title="���� �α׸� ���͸��մϴ�.">����</label>
		<input type="checkbox" id="secretletterButton" class="commentButton" checked="checked" value="secretletter"/>
		<label for="secretletterButton" title="���� �α׸� ���͸��մϴ�.">��� ����</label>
		<input type="checkbox" id="secretanswerButton" class="commentButton" checked="checked" value="secretanswer"/>
		<label for="secretanswerButton" title="�亯 �α׸� ���͸��մϴ�.">�亯</label>
	</div>
</div>

<!-- ���� ��ħ�� ���� ��� -->
<div id="notice">
<h1>���ο� ������ ���� ���� ���� ��ħ�� ���� ������. ������ �ڵ����� ���ŵ˴ϴ�.</h1>
</div>

<?=$dayList?>
<?if($gameinfo['state']=="���ӳ�" or $gameinfo['state']=="����" or $gameinfo['state']=="����" or ($entry == "" and $is_admin)) echo $modeList;?>
<?	if ($viewDay =="0" and $gameinfo[players] <> $rule[max_player] and $gameinfo['state']=="�غ���"){?>
<div id="suddendeath">
		<?echo date("m",$gameinfo['deathtime'])."�� ".date("d",$gameinfo['deathtime'])." �� ".date("H",$gameinfo['deathtime'])."�� ".date("i",$gameinfo['deathtime'])." ��";?>���� ������ ���۵˴ϴ�.
</div>
<?	}?>

<?	if ($viewDay =="0" and $gameinfo[players] == $rule[max_player] and $gameinfo['state']=="�غ���"){?>
<div id="suddendeath">
			���� ������� �𿴽��ϴ�.<br> 
			<?echo date("A",$gameinfo['deathtime'])." ".date("h",$gameinfo['deathtime'])."�� ".date("i",$gameinfo['deathtime'])." ��";?>���� ������ ���۵˴ϴ�.
</div>
<?	}?>

<?	$noCommentPlayer_list = DB_array("no","character","$DB_entry where game = $no and alive='����' and victim = 0 and comment = 0 ");
	if ($gameinfo['state']=="������" and $noCommentPlayer_list and $viewDay == $gameinfo['day']  ){?>
<div class="DisplayBoard" id="NoCommentPlayerList">
		<h1>���ݱ��� �߾��� ���� ���� ����Դϴ�.</h1>
		<?if($gameinfo['termOfDay'] > 1800 ){?>
			<h1>�Ϸ� ���� �Ϲ� �α׸� ���� ������ �׽��ϴ�. (������)</h1>
		<?}else{?>
			<h1>�Ϸ� ���� �Ϲ� �α׸� ���� ���� Ƚ���� <?=$MaxSuddenCountUnder30M?>ȸ�� �Ǹ� �׽��ϴ�. (������)</h1>
			

			<?if($entry['suddenCount']){?>
				<h1><?=$entry['suddenCount']?>ȸ �߾��� �� �߽��ϴ�.</h1>
			<?}?>
		<?}?>

		<ol>
			<?
				foreach($noCommentPlayer_list  as $noCommentPlayer){
					echo "<li>".$character_list[$noCommentPlayer]."</li>";
				}
			?>
		</ol>
</div>
<?	}?>

<script>

/* �����ڵ�#56
var characterImage = Array();
	<?	$characterImage =DB_array("no","half_image","$DB_character where `set` = $gameinfo[characterSet]");
		while (list ($noCharacter, $half_image) = each ($characterImage)) {
			 echo "characterImage[$noCharacter] = '$half_image';\n";
		}
	?>

function changeCharacter(){
	addPlayer.previewCharacter.src=characterImageFolder+characterImage[addPlayer.selectCharacter.value];
}
*/

$(document).ready(function(){
	$("#role_select").imagepicker({
		show_label: true,
		hide_select: true
	});
});
</script>

<? if($gameinfo['state']=="�غ���"){?>
<div class="DisplayBoard">
<?
	if ($entry['player'] == ""){
		if($gameinfo['players'] < $rule['max_player']){

			if($member['level'] == 8){
				echo "��ų� �÷��̾�� ó�� ���Դϴ�. ���ӿ� �����Ϸ��� <a href=''>[��� ���]</a>�� �о��ּ���.";
			}	
			elseif($member['no']  <  1){
					echo "�α��� ���ּ���.";
			}			
			elseif($member[no]==1) $canPlay = true;
			elseif($gameinfo['termOfDay'] > 1800){
				if($member['level'] == 7){
					$canPlay = true;
				}
				elseif($member['level'] == 9){
					echo "�ű� ȸ���̽ñ���.<br>";
					echo "24�ð� ���ӿ� �����Ϸ��� <a href=''>[��� ���]</a>�� �о��ּ���.<br>";
					echo "30�� ������ �ٷ� ������ �� �ֽ��ϴ�.";

					$canPlay = false;
				}
			}
			elseif($gameinfo['termOfDay'] <= 1800) $canPlay = true;
		
		}
		elseif($gameinfo['players'] == $rule['max_player']) 
			echo "���� �ο��� ��� �𿴽��ϴ�. �� �̻� ������ �� �����ϴ�.";
	}
	elseif($entry){
		echo "<span><a href=$PHP_SELF?id=$id&no=$no&function=delPlayer&password=$password>���� ������</a></span></td>";
	}

	if($canPlay){
		if($playCount >= $fiducialPlayCount and $NowPlayingCount < $AttandMaxCountOver3 or $playCount < $fiducialPlayCount and $NowPlayingCount < $AttandMaxCountUnder3){
			echo "$NowPlayingCount �� ���ӿ� ���� ���Դϴ�.<br/>";
			$entryCharacter = DB_array("character","character","$DB_entry where game='$no' ");
			if($entryCharacter) $orderCondition = orderCondition($entryCharacter);
			else $orderCondition = "in (0)";
			//#56	$FirstCharacter=mysql_fetch_array(mysql_query("select * from $DB_character where `set` = $gameinfo[characterSet] and   `no`  not $orderCondition"));
			?>
			
			<!-- customize the confirm message for participation -->
			<? if($gameinfo['useTimetable'] == 1) { ?>
				<form method=post name=addPlayer action=<?="$PHP_SELF?id=$id&no=$no&function=addPlayer&password=$password"?>  enctype="multipart/form-datas" 
				onsubmit="return confirm('���ӿ� �����ϱ� ����!!\n\n1. �ζ��� ��ȭ�� ����Ǵ� �����Դϴ�. �ų� �ִ� ��ȭ�� ���ּ���.\n\n2. ���ӿ� �����ϸ� ���� ������ ������ Ȱ���� �ֽʽÿ�.\n(������ �����ؼ� ���� ������ 3~4�ð� ������ �ɸ��ϴ�. �߰��� �����ϴ� ���� ������ �սô�.)\n(�Ұ����� ��� ���� �÷����ϴ� �е鿡�� ���ظ� ���Ͻñ� �ٶ��ϴ�.)\n\n�����Ͻø� Ȯ���� �����ּ���.')">
			<? } else { ?>
				<form method=post name=addPlayer action=<?="$PHP_SELF?id=$id&no=$no&function=addPlayer&password=$password"?>  enctype="multipart/form-datas" 
				onsubmit="return confirm('���ӿ� �����ϱ� ����!!\n\n1. �ζ��� ��ȭ�� ����Ǵ� �����Դϴ�. �ų� �ִ� ��ȭ�� ���ּ���.\n\n2. ���ӿ� �����ϸ� ���� ������ ������ Ȱ���� �ֽʽÿ�.\n(������ �����ؼ� ���� ������ 1���� ������ �ɸ��ϴ�. �߰��� �����ϴ� ���� ������ �սô�.)\n(�Ұ����� ��� ���� �÷����ϴ� �е鿡�� ���ظ� ���Ͻñ� �ٶ��ϴ�.)\n\n�����Ͻø� Ȯ���� �����ּ���.')">
			<? } ?>
			
			<!--#56 old code
			<img name="previewCharacter" width='100' height='100' src="<?=$characterImageFolder.$FirstCharacter[half_image]?>"></img>
			<? // =DBselect("selectCharacter","","no",$character_list,"$DB_character where `set` = $gameinfo[characterSet] and   `no`  not $orderCondition","onkeyup=changeCharacter() onchange=changeCharacter()  font-size:9pt;width=100","","");?>
			-->
			
			<!-- #56 new code -->
			<div id="rolebox" style="max-height:400px; overflow:auto; overflow-x:hidden; margin:15px 0px;">
				<select name='selectCharacter' id="role_select">
				<?
					$characterQuery = mysql_query("select * from $DB_character where `set` = $gameinfo[characterSet] and `no` not $orderCondition order by 'no'");
					for($rc=0; $characterArray = mysql_fetch_array($characterQuery); $rc++) {
						echo "<option data-img-src='".$characterImageFolder.$characterArray['half_image']."' value='".$characterArray['no']."'>".$character_list[$characterArray['no']]."</option>\n";
					}
				?>
				</select>
			</div>
			<br>
			<!-- -->
			<input type="submit" name="temp" value="���� �����ϱ�" style="border: 2px solid #666666; background-color: #111111; color: #666666; padding: 5px 15px; margin: 4px 2px; text-align: center; font-family: '����', '���� ���', '�������'; text-decoration: none; font-size: 14px;">
			</form>
		<?}
		else echo "$NowPlayingCount �� ���ӿ� ���� ���Դϴ�.<br/><br/>�� �̻� ���ӿ� ������ �� �����ϴ�.";
	}
?>	
</div>
<?} ?>





<? if($is_admin and $useAdminTool){?>
<div class="DisplayBoard">
<h1>������ �޴�</h1>
<?
	// echo "<a href=$PHP_SELF?id=$id&no=$no&function=suddenDeathCheck title='�߾��� ���� �÷��̾ ������ üũ�Ѵ�.'>������ üũ</a></br>"; 
	//	echo "<a href=$PHP_SELF?id=$id&no=$no&function=delRecord>���� ��� ����</a></br>"; 
		echo "<a href=$PHP_SELF?id=$id&no=$no&function=2222>�����ϱ�</a></br>"; 
		echo "<a href=$PHP_SELF?id=$id&no=$no&function=1111>���׷�</a></br>"; 
		echo "<a href=$PHP_SELF?id=$id&no=$no&function=record>���� ����ϱ�</a></br>"; 
		echo "<a href=$PHP_SELF?id=$id&no=$no&function=preparation>�غ��ϱ�</a></br>"; 
		echo "<a href=$PHP_SELF?id=$id&no=$no&function=start>�����ϱ�</a></span></br>"; 
		echo "<a href=$PHP_SELF?id=$id&no=$no&function=forwardAday>�Ϸ� ������</a></br>"; 
		echo "<a href=$PHP_SELF?id=$id&no=$no&function=backAday>�Ϸ� �ڷ�</a></br>"; 
	//	echo "<a href=$PHP_SELF?id=$id&no=$no&function=resurrection>��Ȱ��Ű��</a></br>"; 
		echo "<a href=$PHP_SELF?id=$id&no=$no&function=postNoManner>�Ҹ� �Խ��ϱ�</a></br>"; 
		if ($is_admin AND 0){?>
		<form   method=post name=writeCommnet action=<?="$PHP_SELF"?>  enctype="multipart/form-data"s>
		<input type=hidden name=id value=<?=$id?>>
		<input type=hidden name=no value=<?=$no?>>
		<input type=hidden name=function value="writeCommnet">

		<input type="submit" name="temp" value="���� ���� �׽�Ʈ" style="background:#000;"></form></td>
		<?}
	// echo "<a href=$PHP_SELF?id=$id&no=$no&function=writeCommnet title='writeCommnet �Լ� �׽�Ʈ'>���� ���� �׽�Ʈ</a></br>"; 
	// echo "<a href=$PHP_SELF?id=$id&no=$no&function=CommentCheck title='��� �÷��̾ �߾��� ���·� �����.'>������ ����</a></br>"; 
	// echo "<a href=$PHP_SELF?id=$id&no=$no&function=CommentNumInit title='��� �÷��̾��� �߾� ���� �ʱ�ȭ�Ѵ�.'>�߾�� �ʱ�ȭ</a>"; 
	 ?>	
</div>
<?}?>

<? if($viewChar){?>
<div class="DisplayBoard">
	<?="<a href='$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewMode=$viewMode' >"?>��� ���� ����� �α� ����</a>
</div>
<?}?>

<? if($gameinfo['state']=="���ӳ�"){?>
<div class="DisplayBoard">
	��õ: <?=$gameinfo['good']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ����õ: <?=$gameinfo['bad']?><br><br>
	<?if($entry['vote']==0 and $entry['player'] ){ echo "<a href=$PHP_SELF?id=$id&no=$no&function=goodGame>���� ��õ�ϱ�</a><br><a href=$PHP_SELF?id=$id&no=$no&function=badGame>���� ����õ�ϱ�</a>";}?>
	<?if($entry['vote']==1){?>��õ�ϼ̽��ϴ�.<?}?>
	<?if($entry['vote']==2){?>����õ�ϼ̽��ϴ�.<?}?>
</div>
<?}?>

<? if($gameinfo['state']=="������" and $gameinfo['seal']=="����"){?>
<div class="DisplayBoard">
	���� ����: <?=$gameinfo['seal_yes']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ���� �ݴ�: <?=$gameinfo['seal_no']?><br><br>
	<?if($entry['seal_vote']==0 and $entry['player'] ){ 
		echo "<a href=$PHP_SELF?id=$id&no=$no&function=seal_yes>���� �����ϱ�</a><br><a href=$PHP_SELF?id=$id&no=$no&function=seal_no>���� �ݴ��ϱ�</a>";
	}?>
	<?if($entry['seal_vote']==1){?>���� �����ϼ̽��ϴ�.<?}?>
	<?if($entry['seal_vote']==2){?>���� �ݴ��ϼ̽��ϴ�.<?}?>
</div>
<?}?>

<div id="menu-foot">
		<?	if($gameinfo['state']=="�غ���" or $is_admin){?>
			<span class="left">
				<?=$a_modify?>&nbsp;[���� ����]</a>
				<a onfocus=blur() href='delete_werewolf.php?<?=$href.$sort?>&no=<?=$no?>'>[���� ����]</a>
			</span>
		<?}?>
	
    <span class="right">
		<?=$a_list?>[���� ���]</a>
		<?=$a_write?>&nbsp;[���� �����]</a>
	</span>
</div>

<?
	//�α� ��Ͽ� ���� �ݱ�
//	fclose($file);    
?>