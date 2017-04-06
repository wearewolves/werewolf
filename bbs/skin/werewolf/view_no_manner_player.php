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
	if(!$setup[no]) error("존제하지 않는 게시판 입니다.","window.close");

	include "../../../Werewolf/head.htm";

	if($member[no] <> 1) exit();

	require_once("class/DB.php");
	$db= new DB($id);
?>
<style type="text/css">
#record{	border-collapse:collapse;	width:100%;	font-size:11px;	color:#666;	margin:25px 0px;}
#record td{	padding:4px 1px;}
#record thead{	background:#222;	text-align:center;}
#record thead td{	border:1px solid #151515;}
#record tbody{/*	background:#555;*/	text-align:left;}
#record tbody td{	border-bottom:1px solid #151515;}
.sidebar{	border-left:1px solid #151515;}
.blue{	color:#384887;}
.red{	color:#B66;}
</style>

<table id="record">
<col width = ></col><col width =120></col><col width =70></col><col width =100></col><col width =100></col>
<thead>
	<tr>
		<td>마을 이름</td><td>캐릭터</td><td>정체</td><td>IP</td><td>날짜</td>
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

	//데이타 가져오기
	$sql="select *  from  `".$db->suddenDeath."` order by reg_data,game";
	$temp_result=mysql_query($sql);
	
	while($entry=@mysql_fetch_array($temp_result)){?>
		<tr onMouseOver="this.style.backgroundColor='#212120'" onMouseOut=this.style.backgroundColor='' >
			<?
				$game = mysql_fetch_array(mysql_query("select *  from  `".$db->game."` where no = ".$entry['game']));
				$gameinfo = mysql_fetch_array(mysql_query("select *  from  `".$db->gameinfo."` where game = ".$entry['game']));

				if($member[no]  <> 1 and  $member[no] <> $player   and $gameinfo['state'] <> "게임끝" or $gameinfo['state'] == "버그"){
					continue;
				}
			?>
			<td><a href='../../view.php?id=<?=$id?>&no=<?=$entry['game']?>'><?=$game['subject']?></a></td>
			<td><a href='../../view_private_record.php?id=<?=$id?>&player=<?=$entry['player']?>'><?=$entry['name']?></a></td>
			<td><a href='view_ip_overlap.php?id=<?=$id?>&player=<?=$entry['player']?>'><?=$trueCharacterList[$entry['truecharacter']]?></a></td>
			<td><?=$entry['ip']?></td>
			<td><?=date("y.m.d - H:i",$entry['reg_data']) ?></td>
		</tr>
	<?}
?>
</tbody>
</table>
<?	include "../../../Werewolf/foot.htm";?>