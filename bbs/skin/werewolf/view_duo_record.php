<?
// register_globals�� off�϶��� ���� ���� �� ����
	@extract($HTTP_GET_VARS); 
	@extract($HTTP_POST_VARS); 
	@extract($HTTP_SERVER_VARS); 
	@extract($HTTP_ENV_VARS);

// ���κ��� ���̺귯�� ������
	$_zb_path = realpath("../../")."/";
	include $_zb_path."lib.php";

// DB ���������� ȸ������ ������
	$connect = dbConn();
	$member  = member_info();

// �Խ��� ������ ������
//error($_zb_path);
	$setup = get_table_attrib($id);
	if(!$setup[no]) error("�������� �ʴ� �Խ����Դϴ�.", "window.close");

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
				<a href="#" onMousedown="window.open('../../view_info.php?member_no=<?=$player?>','view_info','width=400,height=510,toolbar=no,scrollbars=yes')">���� ������</a><br>
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
		<td>���� �̸�</td>
		<td>ĳ����</td>
		<td>��ü</td>
		<td>���� ���</td>
		<td>����</td>
		<td>����</td>
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
	

	//����Ÿ ��������
	$sql="select *  from  `".$db->entry."` where player = $player order by game";
	$temp_result=mysql_query($sql);
	
		while($entry=@mysql_fetch_array($temp_result)){?>
			<tr onMouseOver="this.style.backgroundColor='#212120'" onMouseOut=this.style.backgroundColor='' >
				<?
					$game = mysql_fetch_array(mysql_query("select *  from  `".$db->game."` where no = ".$entry['game']));
					$gameinfo = mysql_fetch_array(mysql_query("select *  from  `".$db->gameinfo."` where game = ".$entry['game']));
					$character = mysql_fetch_array(mysql_query("select *  from  `".$db->character."`  where no = ".$entry['character']));

					if($member[no]  <> 1 and  $member[no] <> $player   and $gameinfo['state'] <> "���ӳ�" or $gameinfo['state'] == "����"){
						continue;
					}

					$result = "";
					$deathType = "";
					$death = '';

					switch($entry['deathtype']){
						case "����": $deathType ="��ǥ";
									break;
						default :$deathType=$entry['deathtype'];
									break;
					}

					if ($entry['alive']=="���")
						$death = $entry['deathday']."��° ���-".$deathType;
					else 
						$death = "����";

					if($gameinfo['state'] == "���ӳ�"){
						switch($gameinfo['win']){
							case 0: $gameinfo['state'] = "�ΰ��� ��";
										break;
							case 1: $gameinfo['state'] = "�ζ��� ��";
										break;
							case 2: $gameinfo['state'] = "�ܽ����� ��";
										break;
						}

						if($wintype[$entry['truecharacter']] == $gameinfo['win']){
							$result = "��";
							$style = "blue";
							$win++;
						}
						else{
							$result = "��";
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
	<td>Ƚ��</td>
	<td>��</td>
	<td>��</td>
	<td>�·�</td>
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
		echo "<span title='������ Ƚ���� �ڱ� �ڽſ��Ը� ���Դϴ�.\n���������� �ʵ��� �������ּ���.'>������ Ƚ��:".$suddenDeathCount[0]."</span>";
	}
}?>





<table id="record">
<col width = ></col>
<col width =110></col>
<col width =110></col>
<thead>
	<tr>
		<td>�� �÷��� ��Ʈ</td>
		<td>��� Ƚ��</td>
		<td>ĳ���� ��</td>
	</tr>
</thead>
<tbody>
	<?	
	$wintype = DB_array("no","wintype","`".$db->truecharacter."`");
	$trueCharacterList = DB_array("no","character","`".$db->truecharacter."`");
	$win = 0;
	$lost = 0;
	

	//����Ÿ ��������
	$sql="select *  from  `".$db->entry."` where player = $player order by game";

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