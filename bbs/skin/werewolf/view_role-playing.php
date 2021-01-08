<meta name="viewport" content="width=device-width, initial-scale=1.0/">
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
	$setup=get_table_attrib($id);
	if(!$setup[no]) error("존재하지 않는 게시판 입니다.","window.close");

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
</style>

<script>
function changeCharacterSet(selectCharacterSet){
	window.location.replace("view_role-playing.php?id=<?=$id?>&set=" + selectCharacterSet.value);
}

function changeCharacterSetByNum(selectCharacterSet, selectIndex){
	selectCharacterSet = parseInt(selectCharacterSet, 10);
	selectIndex = parseInt(selectIndex, 10);
	
	var tablinks = document.getElementsByClassName("tablinks");
	var tabcontentID = tablinks[0].className.indexOf(" active") !== -1 ? 0 : 1;
	
	window.location.replace("view_role-playing.php?id=<?=$id?>&set=" + selectCharacterSet + "&selectindex=" + selectIndex + "&selectlist=" + tabcontentID);
}
</script>

<!-- role playing set selector js, css files -->
<script type="text/javascript" src="js/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="js/werewolf-role-playing-set.js?ver=<?php echo filemtime('js/werewolf-role-playing-set.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="css/werewolf-role-playing-set.css?ver=<?php echo filemtime('css/werewolf-role-playing-set.css'); ?>">


<?




function DB_array($key,$value,$db){
	$temp_result=mysql_query("select * from $db ");

	while($temp_member=@mysql_fetch_array($temp_result)){
			$members[$temp_member[$key]]=$temp_member[$value];
	}

	return $members;
}

function DBselect($name,$head,$id,$value,$DB,$code,$selectedID,$unselectedID){
	$result=mysql_query("select * from $DB order by '$id'");

	if(!is_array($unselectedID)){
		$unselectedID = array($unselectedID);
	}

		
	$DB_select="&nbsp;<select $code name=$name>$head";
	while($temp=mysql_fetch_array($result)) {
		if(!in_array ($temp[$id], $unselectedID)){
			if($temp[$id]==$selectedID)$selected="selected";
			else $selected="";
		
			$DB_select.="<option value=$temp[$id] ".$selected." >". $value[$temp[$id]]."</option>";
		}
	}
	$DB_select.="</select> ";
	return $DB_select;
}

// Get current characterSet name
function get_characterSetName($query) {
	$result = mysql_fetch_array(mysql_query("select * from $query"));
	return $result[name];
}

// Make sorted go-to characterSet list and select an item
function goto_characterSet($DB, $sort) {
	$result = mysql_query("select * from $DB order by '$sort'");
	
	// Extract no of not used role playing set
	$result0 = mysql_query("select * from $DB where is_use != 1 order by '$sort'");
	$not_used_characterSet_no = "";
	$i = 0;
	while($temp0 = mysql_fetch_array($result0)) {
		$not_used_characterSet_no[$i] = $temp0[no];
		$i++;
	}
	unset($i);
	
	$characterSetList = "";
	$selectIndex = 0;
	while($temp = mysql_fetch_array($result)) {
		$used = true;
		
		// Check not used role playing set
		if($not_used_characterSet_no) {
			foreach($not_used_characterSet_no as $not_used_characterSet_no_value) {
				if($temp[no] == $not_used_characterSet_no_value) {
					$used = false;
					break;
				}
			}
		}

		if($used)
			$characterSetList .= "<li class=\"CS".$selectIndex."\" onclick=\"changeCharacterSetByNum('$temp[no]', '$selectIndex')\">".$temp[name]."</li>";
		else
			$characterSetList .= "<li class=\"CS".$selectIndex."\" onclick=\"changeCharacterSetByNum('$temp[no]', '$selectIndex')\">".$temp[name]." <font color='#ff3838'>(사용 불가)</font></li>";
		
		$selectIndex++;
	}
	return $characterSetList;
}

	if(!$set) $set = 1;
	if(!$selectindex) $selectindex = 0;
	if(!$selectlist) $selectlist = 0;

	$characterSet_list = DB_array("no","name","`".$db->characterSet."`");

	$sql="select *  from  `".$db->characterSet."` where `no` = $set";
	$characterSet	 = mysql_fetch_array(mysql_query($sql));
?>

<table width='100%'>
<tr>
	<!--
	<td width=200><!--?=DBselect("selectCharacterSet","","no",$characterSet_list,"`".$db->characterSet."`","onchange='changeCharacterSet(this)' width=100",$set,"");?></td>
	-->
	<!-- role playing set selector -->
	<td>
		<input type="text" name="characterSetName" class="input" style="width:200px" id="characterSetNameInput" value="<? echo get_characterSetName("`$db->characterSet` where no = $set"); ?>" disabled>
		<button type="button" id="RPSetBtn" onclick="openModalCustomed('<?=$selectindex?>', '<?=$selectlist?>')">선택하기</button>
	</td>
	<td width=><a href="view_private_record.php?id=<?=$id?>&player=<?=$characterSet['ismember']?>"><?=" 제작자:".$characterSet['maker']?></a></td>
</tr>
</table>

<div id="modal-window" class="modal">
	<div class="modal-content">
		<div class="tabheader">
			<span id="closeX">&times;</span>
			<input type="text" id="RPSetInput" onkeyup="searchRPSet()" placeholder="Search for names...">
			
			<div class="tab">
				<button type="button" class="tablinks" onclick="openList(event, 'listByTimeSort')">제작순</button>
				<button type="button" class="tablinks" onclick="openList(event, 'listByAscendingSort')">가나다순</button>
			</div>
		</div>

		<div id="listByTimeSort" class="tabcontent">
			<ul class="RPSetUL">
				<? echo goto_characterSet("`$db->characterSet`", "no"); ?>
			</ul>
		</div>

		<div id="listByAscendingSort" class="tabcontent">
			<ul class="RPSetUL">
				<? echo goto_characterSet("`$db->characterSet`", "name"); ?>
			</ul>
		</div>
	</div>
</div>

<br>

<article style="word-break: keep-all;word-wrap: break-word;">
<?=($characterSet['memo'])?>
</article>

<br>

<table id="record">
<col width = 100></col>
<col width = 130></col>
<col width =></col>
<thead>
	<tr>
		<td>이미지</td>
		<td>캐릭터</td>
		<td></td>
	</tr>
</thead>
<tbody>
	<?	
	//데이타 가져오기
	$sql="select *  from  `".$db->character."` where `set` = $set order by no";
	$temp_result=mysql_query($sql);
	
		while($character=mysql_fetch_array($temp_result)){?>
			<tr>
				<td rowspan=2><img src='character/<?=$set."/".$character['half_image']?>' width="100px" height="100px"></img></td>
				<td rowspan=2 style="border-right:1px solid #151515;text-align:center;"><?=$character['character']?></td>
				<td><?=nl2br($character['greeting'])?></td>
			</tr>
			<tr>
				<td><?=nl2br($character['comment'])?></td>
			</tr>
		<?}
	?>
</tbody>
</table>

<table>
	<thead>
		<tr>
			<td><a href='view_role-playing_write.php?id=<?=$id?>&mode=modify&set=<?=$set?>'>[롤 플레잉 세트 수정]</a></td>
			<td><a href='view_role-playing_write.php?id=<?=$id?>&mode=write'>[롤 플레잉 세트 만들기]</a></td>
		</tr>
	<thead>
</table>


<table id="record">
<col width = ></col>
<col width =50></col>
<col width =110></col>
<col width =30></col>
<col width =30></col>
<col width =60></col>
<thead>
	<tr>
		<td>마을 이름</td>
		<td>룰</td>
		<td>사건 발생</td>
		<td>추천</td>
		<td>인원</td>
		<td>결과</td>
	</tr>
</thead>
<tbody>
	<?	
	$wintype = DB_array("no","wintype","`".$db->truecharacter."`");
	$trueCharacterList = DB_array("no","character","`".$db->truecharacter."`");
	$win = 0;
	$lost = 0;
	

	//데이타 가져오기
	$sql="select *  from  `".$db->entry."` where player = $player order by game";

	$sql ="SELECT  *     FROM  `zetyx_board_werewolf` ,  `zetyx_board_werewolf_gameinfo`  WHERE  NO  = game and characterSet ='".$set."'  ORDER  BY  `no`  DESC ";


	$temp_result=mysql_query($sql);
	
		while($gameinfo=@mysql_fetch_array($temp_result)){
			$deathTime =date("Y",$gameinfo['deathtime'])."-".date("m",$gameinfo['deathtime'])."-".date("d",$gameinfo['deathtime'])."  ".date("H",$gameinfo['deathtime']).":".date("i",$gameinfo['deathtime']);
			?>
			<tr onMouseOver="this.style.backgroundColor='#212120'" onMouseOut=this.style.backgroundColor='' >
				<?
						switch($gameinfo['rule']){
							case 1: $rule = "기본";
										break;
							case 2: $rule = "햄스터"; 
										break;
							case 3: $rule = "익스펜션";
										break;
							case 4: $rule = "신뢰도";
										break;
							case 5: $rule = "인스턴트";
										break;
							case 6: $rule = "참살";
										break;
						}

						switch($gameinfo['win']){
							case 0: $winType = "인간의 승";
										$fontColor="#384887";
										break;
							case 1: $winType = "인랑의 승"; 
										$fontColor ="#BB3333";
										break;
							case 2: $winType = "햄스터 승";
										$fontColor ="#FFCC99";
										break;
							case 3: $winType = "디아블로";
										$fontColor ="red";
										break;
						}
				?>

				<td><a href='../../view.php?id=<?=$id?>&no=<?=$gameinfo['no']?>'><?=$gameinfo['subject']?></a></td>
				<td><?=$rule?></td>
				<td><?=$deathTime ?></td>
				<td><?=$gameinfo['good']?></td>
				<td><?=$gameinfo['players']?></td>
				<td style='color:<?=$fontColor?>'><?=$winType?></td>
			</tr>
		<?}
	?>
</tbody>
</table>

<?	include "../../../Werewolf/foot.htm";?>