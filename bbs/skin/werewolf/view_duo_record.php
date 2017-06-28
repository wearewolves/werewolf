<?
// register_globals가 off일때를 위해 변수 재 정의
	@extract($HTTP_GET_VARS); 
	@extract($HTTP_POST_VARS); 
	@extract($HTTP_SERVER_VARS); 
	@extract($HTTP_ENV_VARS);

// 제로보드 라이브러리 가져옴
	$_zb_path = realpath("../../")."/";
	include $_zb_path."lib.php";

// DB 연결정보와 회원정보 가져옴
	$connect = dbConn();
	$member  = member_info();

// 게시판 설정을 가져옴
//error($_zb_path);
	$setup = get_table_attrib($id);
	if(!$setup[no]) error("존재하지 않는 게시판입니다.", "window.close");

	include "../../../Werewolf/head.htm";

	require_once("class/DB.php");
	$db= new DB($id);
?>

<style type="text/css">

#record{
	border-collapse:collapse;
	width:100%;
	font-size:11px;
	color:#666;
	margin:25px 0px;
}
#record td{
	padding:4px 1px;
}
#record thead{
	background:#222;
	text-align:center;
}
#record thead td{
	border:1px solid #151515;

}
#record tbody{
/*	background:#555;*/
	text-align:left;
}
#record tbody td{
	border-bottom:1px solid #151515;
}
.sidebar{
	border-left:1px solid #151515;
}
.blue{
	color:#384887;
}
.red{
	color:#B66;
	
}

.caption{
	width:100%;
	border-bottom:1px solid #151515;
	margin-left:auto;margin-right:auto;
}

.caption h1{
	width:80%;
	margin-left:auto;margin-right:auto;
}

.caption a{
	float:right;
	padding:0;
	margin:0;
}
</style>

<?
	if($member[no] or $player){
		if(!$player) $player = $member[no];

		$playerInfo=@mysql_fetch_array(mysql_query("select * from zetyx_member_table where no = ".$player));
?>

		<div class="caption">
			<H1><?=$playerInfo['name']?></h1>
			<?if($member[no]){?>
				<a href="#" onMousedown="window.open('../../view_info.php?member_no=<?=$player?>','view_info','width=400,height=510,toolbar=no,scrollbars=yes')">쪽지 보내기</a><br>
			<?
				if($playerInfo['homepage'] and $playerInfo['open_homepage']){
						$homepage = $playerInfo['homepage'];
						echo "<a href='".$homepage."'>".$homepage."</a>";
				}
			}
			?>
		</div>
<?}?>

<table id="record">
<col width = ></col>
<col width =120></col>
<col width =70></col>
<col width =70></col>
<col width =100></col>
<col width = 30></col>
<thead>
	<tr>
		<td>마을 이름</td>
		<td>캐릭터</td>
		<td>정체</td>
		<td>게임 결과</td>
		<td>상태</td>
		<td>승패</td>
	</tr>
</thead>
<tbody>
	<?	
	function DB_array($key,$value,$db){
		$temp_result=mysql_query("select * from $db ");

		while($temp_member=@mysql_fetch_array($temp_result)){
				$members[$temp_member[$key]]=$temp_member[$value];
		}

		return $members;
	}

	$wintype = DB_array("no","wintype","`".$db->truecharacter."`");
	$trueCharacterList = DB_array("no","character","`".$db->truecharacter."`");
	$win = 0;
	$lost = 0;
	

	//데이타 가져오기, 내가 플레이한 마을 중에서 해당 플레이어가 플레이한 마을의 entry를 가져온다
	$sql="select * from `".$db->entry."` where player = $player AND game IN (SELECT game FROM `".$db->entry."` WHERE player = ".$member[no].") ORDER BY game";

	$temp_result=mysql_query($sql);
	
		while($entry=@mysql_fetch_array($temp_result)){?>
			<tr onMouseOver="this.style.backgroundColor='#212120'" onMouseOut=this.style.backgroundColor='' >
				<?
					$game = mysql_fetch_array(mysql_query("select *  from  `".$db->game."` where no = ".$entry['game']));
					$gameinfo = mysql_fetch_array(mysql_query("select *  from  `".$db->gameinfo."` where game = ".$entry['game']));
					$character = mysql_fetch_array(mysql_query("select *  from  `".$db->character."`  where no = ".$entry['character']));

					if($member[no]  <> 1 and  $member[no] <> $player   and $gameinfo['state'] <> "게임끝" or $gameinfo['state'] == "버그"){
						continue;
					}

					$result = "";
					$deathType = "";
					$death = '';

					switch($entry['deathtype']){
						case "심판": $deathType ="투표";
									break;
						default :$deathType=$entry['deathtype'];
									break;
					}

					if ($entry['alive']=="사망")
						$death = $entry['deathday']."일째 사망-".$deathType;
					else 
						$death = "생존";

					if($gameinfo['state'] == "게임끝"){
						switch($gameinfo['win']){
							case 0: $gameinfo['state'] = "인간의 승";
										break;
							case 1: $gameinfo['state'] = "인랑의 승";
										break;
							case 2: $gameinfo['state'] = "햄스터의 승";
										break;
						}

						if($wintype[$entry['truecharacter']] == $gameinfo['win']){
							$result = "승";
							$style = "blue";
							$win++;
						}
						else{
							$result = "패";
							$style = "red";
							$lost++;
						}
					}
				?>

				<td><a href='../../view.php?id=<?=$id?>&no=<?=$entry['game']?>'><?=$game['subject']?></a></td>
				<td><?=$character['character']?></td>
				<td><?=$trueCharacterList[$entry['truecharacter']]?></td>
				<td><?=$gameinfo['state']?></td>
				<td><?=$death?></td>
				<td class='<?=$style?>'><?=$result?></td>
			</tr>
		<?}
	?>
</tbody>
</table>


<table id="record">
<thead>
<tr>
	<td>횟수</td>
	<td>승</td>
	<td>패</td>
	<td>승률</td>
</tr>
</thead>
<?if(($win+$lost)<>0){?>
<tr align='center'>
	<td><?=$win+$lost ?></td>
	<td class='blue'><?=$win?></td>
	<td class='red'><?=$lost?></td>
	<td><?=round($win/($win+$lost),2)*100?>%</td>
</tr>
<?}?>
</table>

<?if(($player == $member[no] and $member[no] > 0) or $member[no] == 1){
	$suddenDeathCount = mysql_fetch_array(mysql_query("select count(*)  from `".$db->suddenDeath."` where player = $player"));

	if($suddenDeathCount[0] <> 0){
		echo "<span title='돌연사 횟수는 자기 자신에게만 보입니다.\n돌연사하지 않도록 주의해주세요.'>돌연사 횟수:".$suddenDeathCount[0]."</span>";
	}
}?>





<table id="record">
<col width = ></col>
<col width =110></col>
<col width =110></col>
<thead>
	<tr>
		<td>롤 플레잉 세트</td>
		<td>사용 횟수</td>
		<td>캐릭터 수</td>
	</tr>
</thead>
<tbody>
	<?	
	$wintype = DB_array("no","wintype","`".$db->truecharacter."`");
	$trueCharacterList = DB_array("no","character","`".$db->truecharacter."`");
	$win = 0;
	$lost = 0;
	

	//데이타 가져오기
	$sql ="SELECT  *     FROM  `zetyx_board_werewolf_characterSet`  WHERE  ismember ='".$player."' AND `no` IN (SELECT `no` FROM `zetyx_board_werewolf_characterSet` WHERE ismember = '".$member[no]."') ORDER  BY  `no`  ";


	$temp_result=mysql_query($sql);
	
		while($gameinfo=@mysql_fetch_array($temp_result)){
			if($gameinfo['reg_date']<>0){
				$reg_date =date("Y",$gameinfo['reg_date'])."-".date("m",$gameinfo['reg_date'])."-".date("d",$gameinfo['reg_date'])."  ".date("H",$gameinfo['reg_date']).":".date("i",$gameinfo['reg_date']);
			}
			else $reg_date ="";

			if($gameinfo['mod_date']<>0){
				$mod_date =date("Y",$gameinfo['mod_date'])."-".date("m",$gameinfo['mod_date'])."-".date("d",$gameinfo['mod_date'])."  ".date("H",$gameinfo['mod_date']).":".date("i",$gameinfo['mod_date']);
			}
			else $mod_date ="";

			$set_used_count = mysql_fetch_array(mysql_query("select count(*)  from  `zetyx_board_werewolf_gameinfo` where `characterSet` = ".$gameinfo['no']));		
			$character_set_count= mysql_fetch_array(mysql_query("select count(*)  from  `zetyx_board_werewolf_character` where `set` = ".$gameinfo['no']));	
			?>
			<tr onMouseOver="this.style.backgroundColor='#212120'" onMouseOut=this.style.backgroundColor='' >
				<td><a href='view_role-playing.php?id=<?=$id?>&set=<?=$gameinfo['no']?>'><?=$gameinfo['name']?></a></td>
				<td><?=$set_used_count[0]?></td>
				<td><?=$character_set_count[0]?></td>
			</tr>
		<?}
	?>
</tbody>
</table>

<?	include "../../../Werewolf/foot.htm";?>