<?=$hide_comment_end?>
</div>

<?//답장/////////////////////////////////////////////////
$sql = "select * from $DB_secretletter where `game`='".$no."' and `day`='".($viewDay-1)."'";
$secretmessage=mysql_fetch_array(mysql_query($sql));

if($secretmessage and (($secretmessage['to'] ==$entry['character'] and $entry['alive']=="생존") or $viewMode =="all") and $viewDay>1){?>
<div class="letter">
	<h1>
		어젯밤 <u><?=$character_list[$secretmessage['from']]?></u> 씨로부터 편지가 도착했습니다.
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
if(($gameinfo['state']=="게임중" and $truecharacter['forecast'] and $entry['alive']=="생존")or $gameinfo['state']=="게임끝" and $viewDay > 0){
	$forecast_result =mysql_fetch_array(mysql_query("select * from $DB_revelation where game='$no' and day=$viewDay -1 and type = '점'"));

	if($forecast_result ){
	?>
	<div class="DisplayBoard alignleft">
		<span class="head">점괘 결과</span>
		<span>
			<? echo $character_list[$forecast_result['mystery']];	
			if($forecast_result[result] == 0 or $forecast_result[result] == 2) echo " 씨는 인간";
			else echo " 씨는 인랑";
			?>
		</span>
	</div>
<?}
}?>

<?	
if(($gameinfo['state']=="게임중" and $truecharacter['forecast-odd'] and $entry['alive']=="생존") and $viewDay > 0  ){
	$forecast_result =mysql_fetch_array(mysql_query("select * from $DB_revelation where game='$no' and day=$viewDay -1 and type = '점'"));

	if($forecast_result ){
	?>
	<div class="DisplayBoard alignleft">
		<span class="head">점괘 결과</span>
		<span>
			<? echo $character_list[$forecast_result['mystery']];	
			if($forecast_result[result] == 0 or $forecast_result[result] == 2 ) echo " 씨는 인간";
			else echo " 씨는 인랑";
			?>
		</span>
	</div>
<?}
}?>



<?	if(($gameinfo['state']=="게임중" and $truecharacter['mediumism'] and $entry['alive']=="생존")or $gameinfo['state']=="게임끝" and $viewDay > 2){
	$sql = "SELECT * FROM `$DB_entry` WHERE `game` = $no AND `deathday` = $viewDay -1 AND `deathtype` LIKE '심판'";
	$mediumism =mysql_fetch_array(mysql_query($sql));

	if($mediumism){
?>
	<div class="DisplayBoard alignleft">
		<span class="head">영매 결과</span>
		<span>
			<?
				echo $character_list[$mediumism[character]];
				if($mediumism[truecharacter]==5 or $mediumism[truecharacter]==9 or $mediumism[truecharacter]==10 or $mediumism[truecharacter]==14 or $mediumism[truecharacter]==18) echo " 씨는 인랑"; else echo " 씨는 인간";
			?>
		</span>
	</div>
<?}
}?>


<?	if(($gameinfo['state']=="게임중" and $truecharacter['guard'] and $entry['alive']=="생존") or $gameinfo['state']=="게임끝" and $viewDay > 1) {
		$guard_result =mysql_fetch_array(mysql_query("select * from $DB_guard where game='$no' and day=$viewDay - 1"));
		$assault_result =mysql_fetch_array(mysql_query("select * from $DB_deathNote_result where game='$no' and day=$viewDay - 1"));
		$mustkill_result =mysql_fetch_array(mysql_query("select * from $DB_mustkill where game='$no' and day=$viewDay - 1"));
		
		if($guard_result and $assault_result ){?>
			<div class="DisplayBoard alignleft">
				<span class="head">보호 결과</span>
				<span>
					<?if($mustkill_result and $mustkill_result[target] == $guard_result[purpose]) echo "어젯밤 ".$character_list[$guard_result[purpose]]." 씨를 향한 보호가 소용없었다!?";
					elseif($assault_result[injured] == $guard_result[purpose]) echo "어젯밤 ".$character_list[$guard_result[purpose]]." 씨를 향한 인랑의 습격을 막았다!";
					else echo "어젯밤 ".$character_list[$guard_result[purpose]]." 씨 주변에서는 아무 일도 일어나지 않았다.";?>
				</span>
			</div>
		<?}?>
<?	}?>

<?	if(($gameinfo['state']=="게임중" and $truecharacter['detect'] and $entry['alive']=="생존") or $gameinfo['state']=="게임끝" and $viewDay > 1) {
	$sql="select * from $DB_detect where game='$no' and day=$viewDay -1 ";
	$detectResult =mysql_fetch_array(mysql_query($sql));

	if($detectResult){
		$sql = "select * from $DB_entry where `game`='$no' and `character` = '".$detectResult['target']."'";
		$target_entry = mysql_fetch_array(mysql_query($sql)) or die($sql);
	
		$resultText = $character_list[$detectResult['target']]." 씨는 " ;
		if($target_entry['truecharacter'] == 1) $resultText .= "평범하다";
		else $resultText .= "비범하다";
		?>
	<div class="DisplayBoard alignleft">
		<span class="head">감지 결과</span><span><?= $resultText;?></span>
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

<?if(!($gameinfo['state'] == "게임끝" or $gameinfo['state'] == "테스트")) { 
	if($gameinfo['state'] == "준비중" or $gameinfo['useTimetable']==0){
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
		<span>오늘은 <?=$gameinfo['day']?>일째 날입니다.</span>
	<?}
		else{?>
		<span> 참여 인원 모집 중</span>
	<?}?>
	<br />
	<?echo date("H",$accidentTiem)."시 ".date("i",$accidentTiem)."분";?> - <span id="timer"></span>
</div>
<?}?>

<!-- 환경 설정 -->
<!-- 로그 알림 소리 설정 -->
<span  id="playerpp"></span>

<div id="notice">
<form id="soundConfig" style="display:inline;">
<span id="icon"></span>
<input type="radio" name="sound" value="On" id="soundOn"> <label for="soundOn" title="새로운 덧글이 생기면 소리로 알립니다.">On</label>
<input type="radio" name="sound" value="Off" id="soundOff"> <label for="soundOff"  title="알림 소리를 끕니다.">Off</label>
</form> 

<form id="selectSound" style="display:inline;">
<span id="icon"></span>
<input type="radio" name="selectedSound" value="dog" id="dogSound"> <label for="dogSound" title="강아지 소리">강아지</label>
<input type="radio" name="selectedSound" value="cat" id="catSound"> <label for="catSound"  title="고양이 소리">고양이</label>
</form> 
<!--
<embed name="myMusic" src="dog.wav" type="audio/x-wav" autostart="false" hidden="true" loop="false" mastersound width="0" height="0"></embed>
-->

<script>

</script>

	<!-- 로딩할 로그 개수 설정 -->
	<div>
		<span id="limit"></span>
		<select id="readLimit"><option value="5">5</option><option value="10">10</option><option value="15">15</option><option value="20">20</option></select><label for="readLimit"  title="선택해주세요.">개씩 보기</label>
	</div>
	<!-- 로그 선택 -->
	<div id="">
		<input type="checkbox" id="normalButton" class="commentButton" checked="checked" value="normal"/>
		<label for="normalButton" title="일반 로그를 필터링합니다.">일반</label>
		<input type="checkbox" id="memoButton" class="commentButton" checked="checked" value="memo"/>
		<label for="memoButton" title="메모 로그를 필터링합니다.">메모</label>
		<input type="checkbox" id="secretButton" class="commentButton" checked="checked" value="secret"/>
		<label for="secretButton" title="비밀 로그를 필터링합니다.">비밀 대화</label>
	
	
		<input type="checkbox" id="telepathyButton" class="commentButton" checked="checked" value="telepathy"/>
		<label for="telepathyButton" title="텔레파시 로그를 필터링합니다.">텔레파시</label>
		<input type="checkbox" id="graveButton" class="commentButton" checked="checked" value="grave"/>
		<label for="graveButton" title="무덤 로그를 필터링합니다.">무덤</label>
		<input type="checkbox" id="secretletterButton" class="commentButton" checked="checked" value="secretletter"/>
		<label for="secretletterButton" title="편지 로그를 필터링합니다.">비밀 편지</label>
		<input type="checkbox" id="secretanswerButton" class="commentButton" checked="checked" value="secretanswer"/>
		<label for="secretanswerButton" title="답변 로그를 필터링합니다.">답변</label>
	</div>
</div>

<!-- 새로 고침에 대한 경고 -->
<div id="notice">
<h1>새로운 덧글을 보기 위해 새로 고침을 하지 마세요. 덧글이 자동으로 갱신됩니다.</h1>
</div>

<?=$dayList?>
<?if($gameinfo['state']=="게임끝" or $gameinfo['state']=="봉인" or $gameinfo['state']=="버그" or ($entry == "" and $is_admin)) echo $modeList;?>
<?	if ($viewDay =="0" and $gameinfo[players] <> $rule[max_player] and $gameinfo['state']=="준비중"){?>
<div id="suddendeath">
		<?echo date("m",$gameinfo['deathtime'])."월 ".date("d",$gameinfo['deathtime'])." 일 ".date("H",$gameinfo['deathtime'])."시 ".date("i",$gameinfo['deathtime'])." 분";?>부터 게임이 시작됩니다.
</div>
<?	}?>

<?	if ($viewDay =="0" and $gameinfo[players] == $rule[max_player] and $gameinfo['state']=="준비중"){?>
<div id="suddendeath">
			마을 사람들이 모였습니다.<br> 
			<?echo date("A",$gameinfo['deathtime'])." ".date("h",$gameinfo['deathtime'])."시 ".date("i",$gameinfo['deathtime'])." 분";?>부터 게임이 시작됩니다.
</div>
<?	}?>

<?	$noCommentPlayer_list = DB_array("no","character","$DB_entry where game = $no and alive='생존' and victim = 0 and comment = 0 ");
	if ($gameinfo['state']=="게임중" and $noCommentPlayer_list and $viewDay == $gameinfo['day']  ){?>
<div class="DisplayBoard" id="NoCommentPlayerList">
		<h1>지금까지 발언이 없는 마을 사람입니다.</h1>
		<?if($gameinfo['termOfDay'] > 1800 ){?>
			<h1>하루 동안 일반 로그를 쓰지 않으면 죽습니다. (돌연사)</h1>
		<?}else{?>
			<h1>하루 동안 일반 로그를 쓰지 않은 횟수가 <?=$MaxSuddenCountUnder30M?>회가 되면 죽습니다. (돌연사)</h1>
			

			<?if($entry['suddenCount']){?>
				<h1><?=$entry['suddenCount']?>회 발언을 안 했습니다.</h1>
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

/* 원본코드#56
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

<? if($gameinfo['state']=="준비중"){?>
<div class="DisplayBoard">
<?
	if ($entry['player'] == ""){
		if($gameinfo['players'] < $rule['max_player']){

			if($member['level'] == 8){
				echo "비매너 플레이어로 처리 중입니다. 게임에 참여하려면 <a href=''>[등업 방법]</a>을 읽어주세요.";
			}	
			elseif($member['no']  <  1){
					echo "로그인 해주세요.";
			}			
			elseif($member[no]==1) $canPlay = true;
			elseif($gameinfo['termOfDay'] > 1800){
				if($member['level'] == 7){
					$canPlay = true;
				}
				elseif($member['level'] == 9){
					echo "신규 회원이시군요.<br>";
					echo "24시간 게임에 참여하려면 <a href=''>[등업 방법]</a>을 읽어주세요.<br>";
					echo "30분 마을은 바로 참여할 수 있습니다.";

					$canPlay = false;
				}
			}
			elseif($gameinfo['termOfDay'] <= 1800) $canPlay = true;
		
		}
		elseif($gameinfo['players'] == $rule['max_player']) 
			echo "참여 인원이 모두 모였습니다. 더 이상 참여할 수 없습니다.";
	}
	elseif($entry){
		echo "<span><a href=$PHP_SELF?id=$id&no=$no&function=delPlayer&password=$password>게임 나가기</a></span></td>";
	}

	if($canPlay){
		if($playCount >= $fiducialPlayCount and $NowPlayingCount < $AttandMaxCountOver3 or $playCount < $fiducialPlayCount and $NowPlayingCount < $AttandMaxCountUnder3){
			echo "$NowPlayingCount 개 게임에 참여 중입니다.<br/>";
			$entryCharacter = DB_array("character","character","$DB_entry where game='$no' ");
			if($entryCharacter) $orderCondition = orderCondition($entryCharacter);
			else $orderCondition = "in (0)";
			//#56	$FirstCharacter=mysql_fetch_array(mysql_query("select * from $DB_character where `set` = $gameinfo[characterSet] and   `no`  not $orderCondition"));
			?>
			
			<!-- customize the confirm message for participation -->
			<? if($gameinfo['useTimetable'] == 1) { ?>
				<form method=post name=addPlayer action=<?="$PHP_SELF?id=$id&no=$no&function=addPlayer&password=$password"?>  enctype="multipart/form-datas" 
				onsubmit="return confirm('게임에 참여하기 전에!!\n\n1. 인랑은 대화로 진행되는 게임입니다. 매너 있는 대화를 해주세요.\n\n2. 게임에 참여하면 끝날 때까지 성실히 활동해 주십시오.\n(게임이 시작해서 끝날 때까지 3~4시간 정도가 걸립니다. 중간에 포기하는 일이 없도록 합시다.)\n(불가피한 경우 같이 플레이하는 분들에게 양해를 구하시기 바랍니다.)\n\n동의하시면 확인을 눌러주세요.')">
			<? } else { ?>
				<form method=post name=addPlayer action=<?="$PHP_SELF?id=$id&no=$no&function=addPlayer&password=$password"?>  enctype="multipart/form-datas" 
				onsubmit="return confirm('게임에 참여하기 전에!!\n\n1. 인랑은 대화로 진행되는 게임입니다. 매너 있는 대화를 해주세요.\n\n2. 게임에 참여하면 끝날 때까지 성실히 활동해 주십시오.\n(게임이 시작해서 끝날 때까지 1주일 정도가 걸립니다. 중간에 포기하는 일이 없도록 합시다.)\n(불가피한 경우 같이 플레이하는 분들에게 양해를 구하시기 바랍니다.)\n\n동의하시면 확인을 눌러주세요.')">
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
			<input type="submit" name="temp" value="게임 참여하기" style="border: 2px solid #666666; background-color: #111111; color: #666666; padding: 5px 15px; margin: 4px 2px; text-align: center; font-family: '돋움', '맑은 고딕', '나눔고딕'; text-decoration: none; font-size: 14px;">
			</form>
		<?}
		else echo "$NowPlayingCount 개 게임에 참여 중입니다.<br/><br/>더 이상 게임에 참여할 수 없습니다.";
	}
?>	
</div>
<?} ?>





<? if($is_admin and $useAdminTool){?>
<div class="DisplayBoard">
<h1>관리자 메뉴</h1>
<?
	// echo "<a href=$PHP_SELF?id=$id&no=$no&function=suddenDeathCheck title='발언이 없는 플레이어를 돌연사 체크한다.'>돌연사 체크</a></br>"; 
	//	echo "<a href=$PHP_SELF?id=$id&no=$no&function=delRecord>점수 기록 삭제</a></br>"; 
		echo "<a href=$PHP_SELF?id=$id&no=$no&function=2222>봉인하기</a></br>"; 
		echo "<a href=$PHP_SELF?id=$id&no=$no&function=1111>버그로</a></br>"; 
		echo "<a href=$PHP_SELF?id=$id&no=$no&function=record>점수 기록하기</a></br>"; 
		echo "<a href=$PHP_SELF?id=$id&no=$no&function=preparation>준비하기</a></br>"; 
		echo "<a href=$PHP_SELF?id=$id&no=$no&function=start>시작하기</a></span></br>"; 
		echo "<a href=$PHP_SELF?id=$id&no=$no&function=forwardAday>하루 앞으로</a></br>"; 
		echo "<a href=$PHP_SELF?id=$id&no=$no&function=backAday>하루 뒤로</a></br>"; 
	//	echo "<a href=$PHP_SELF?id=$id&no=$no&function=resurrection>부활시키기</a></br>"; 
		echo "<a href=$PHP_SELF?id=$id&no=$no&function=postNoManner>소명 게시하기</a></br>"; 
		if ($is_admin AND 0){?>
		<form   method=post name=writeCommnet action=<?="$PHP_SELF"?>  enctype="multipart/form-data"s>
		<input type=hidden name=id value=<?=$id?>>
		<input type=hidden name=no value=<?=$no?>>
		<input type=hidden name=function value="writeCommnet">

		<input type="submit" name="temp" value="덧글 쓰기 테스트" style="background:#000;"></form></td>
		<?}
	// echo "<a href=$PHP_SELF?id=$id&no=$no&function=writeCommnet title='writeCommnet 함수 테스트'>덧글 쓰기 테스트</a></br>"; 
	// echo "<a href=$PHP_SELF?id=$id&no=$no&function=CommentCheck title='모든 플레이어를 발언한 상태로 만든다.'>돌연사 금지</a></br>"; 
	// echo "<a href=$PHP_SELF?id=$id&no=$no&function=CommentNumInit title='모든 플레이어의 발언 수를 초기화한다.'>발언수 초기화</a>"; 
	 ?>	
</div>
<?}?>

<? if($viewChar){?>
<div class="DisplayBoard">
	<?="<a href='$PHP_SELF?id=$id&no=$no&viewDay=$viewDay&viewMode=$viewMode' >"?>모든 마을 사람의 로그 보기</a>
</div>
<?}?>

<? if($gameinfo['state']=="게임끝"){?>
<div class="DisplayBoard">
	추천: <?=$gameinfo['good']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 비추천: <?=$gameinfo['bad']?><br><br>
	<?if($entry['vote']==0 and $entry['player'] ){ echo "<a href=$PHP_SELF?id=$id&no=$no&function=goodGame>마을 추천하기</a><br><a href=$PHP_SELF?id=$id&no=$no&function=badGame>마을 비추천하기</a>";}?>
	<?if($entry['vote']==1){?>추천하셨습니다.<?}?>
	<?if($entry['vote']==2){?>비추천하셨습니다.<?}?>
</div>
<?}?>

<? if($gameinfo['state']=="게임중" and $gameinfo['seal']=="논의"){?>
<div class="DisplayBoard">
	봉인 찬성: <?=$gameinfo['seal_yes']?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 봉인 반대: <?=$gameinfo['seal_no']?><br><br>
	<?if($entry['seal_vote']==0 and $entry['player'] ){ 
		echo "<a href=$PHP_SELF?id=$id&no=$no&function=seal_yes>봉인 찬성하기</a><br><a href=$PHP_SELF?id=$id&no=$no&function=seal_no>봉인 반대하기</a>";
	}?>
	<?if($entry['seal_vote']==1){?>봉인 찬성하셨습니다.<?}?>
	<?if($entry['seal_vote']==2){?>봉인 반대하셨습니다.<?}?>
</div>
<?}?>

<div id="menu-foot">
		<?	if($gameinfo['state']=="준비중" or $is_admin){?>
			<span class="left">
				<?=$a_modify?>&nbsp;[마을 수정]</a>
				<a onfocus=blur() href='delete_werewolf.php?<?=$href.$sort?>&no=<?=$no?>'>[마을 삭제]</a>
			</span>
		<?}?>
	
    <span class="right">
		<?=$a_list?>[마을 목록]</a>
		<?=$a_write?>&nbsp;[마을 만들기]</a>
	</span>
</div>

<?
	//로그 기록용 파일 닫기
//	fclose($file);    
?>