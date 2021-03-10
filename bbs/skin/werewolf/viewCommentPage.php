<?
function get_date($t) 
{
	return date('D, d M Y H:i:s ', $t) . "+0900";
}

function connectDB() 
{
	global $link, $_zb_path;
	$f = @file($_zb_path . "config.php") or die("제로보드의 config.php 파일 에러"); 
	for ($i = 1; $i <= 4; $i++) $f[$i] = trim(str_replace("\n", "", $f[$i]));
	if (!$link) $link = @mysql_connect($f[1], $f[2], $f[3]) or die("DB 접속 에러");
	@mysql_select_db($f[4], $link) or mysql_error();
	return $link;
}

// HTML Tag를 제거하는 함수
/*function del_html( $str ) {
	$str = str_replace( ">", "&gt;",$str );
	$str = str_replace( "<", "&lt;",$str );
	return $str;
}
*/

function DB_array($key,$value,$db){
	$temp_result=mysql_query("select * from $db ");

	while($temp_member=@mysql_fetch_array($temp_result)){
			$members[$temp_member[$key]]=$temp_member[$value];
	}

	return $members;
}

$ch[encoding] = "EUC-KR";

foreach ($ch as $key => $value) 
{
	$ch[$key] = htmlspecialchars($value);
}

$ch[lastBuildDate] = get_date(time());

require_once("config/path_setup.php");
require_once("config/server_setup.php");

require_once("class/SessionID.php");
$SessionID= new SessionID();

include $_zb_path."lib.php";

if (!empty($_SERVER['SERVER_SOFTWARE']) && strstr($_SERVER['SERVER_SOFTWARE'], 'Apache/2')){
   header ('Cache-Control: no-cache, pre-check=0, post-check=0, max-age=0');
}
else
{
   header ('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
}
header ('Expires: ' . $ch[lastBuildDate]);
header ('Last-Modified: ' . $ch[lastBuildDate]);
header ('Content-Type: text/xml');

echo "<?xml version=\"1.0\" encoding=\"$ch[encoding]\"?>\n";
echo "  <channel>\n";

$secretKey= $_zb_path;
$key = $SessionID->decrypt_SID($SID, $secretKey);

$game = $key[0];
$day = $key[1];
$player = $key[3];

$no=$game;
$viewDay=$day;
$id="werewolf";

//$link = connectDB();
$connect=dbConn();  
$member=mysql_fetch_array(mysql_query("select * from $member_table where no ='".$player."'"));
$setup = get_table_attrib($id); 


$t_board ="zetyx_board";
$t_comment =$t_board."_comment";

$DB_entry=$t_board."_".$id."_entry";
$DB_gameinfo=$t_board."_".$id."_gameinfo";
$DB_wereComment =$t_comment."_".$id;
$DB_wereCommentType = $DB_wereComment."_commentType";
$DB_character=$t_board."_".$id."_character";
$DB_truecharacter=$t_board."_".$id."_truecharacter";

$truecharacter_list = DB_array("no","character","$DB_truecharacter");

if($player){
	if($player ==1)$is_admin = true;
	else $is_admin = false;
}

$gameinfo=@mysql_fetch_array(mysql_query("select * from $DB_gameinfo where game=$no"));
$entry=@mysql_fetch_array(mysql_query("select * from $DB_entry where game=$game and player = $player"));

if($entry['character']) $character = $entry['character'];
else $character = 0;

$viewMode = $SessionID->viewMode($SID, $secretKey);
$commentType = $SessionID->commentType($viewMode);


$memo = rawurldecode(iconv("UTF-8","CP949",$memo));
$c_type = rawurldecode(iconv("UTF-8","CP949",$c_type));


if($SessionID->verification($SID, $secretKey)){

	$readLatest = $HTTP_COOKIE_VARS['readLatest'];
	if(!$readLatest or $readLatest <0 or 20 < $readLatest or !is_numeric($readLatest)) $readLatest = 10;


	for($count = 1; $count > 0 ; $count-- ){
		echo "<result>true</result>\n";
		
		if(true){
			if($viewChar and is_numeric($viewChar)) $checkChar = " AND `character` = $viewChar ";
			
			// Hide seal logs until the end of game except for myself and admin
			if($checkChar)
				// game in progress && viewChar != playing character && not admin
				if($gameinfo['state'] == "게임중" && $viewChar != $character && !$is_admin) $checkChar .= "AND type != '봉인제안' ";
				
			if(!$member[no]) $member[no] = 0;


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
			$characterImageFolder = "skin/".$id."/character/".$gameinfo['characterSet']."/";
	
			while($commentDataType=mysql_fetch_array($view_comment_result)){
				$commentData=mysql_fetch_array(mysql_query("select * from $DB_wereComment where no='$commentDataType[comment]'"));
				$commentDate = date("Y-m-d H:i:s",$commentData['reg_date']);


				if($commentDataType['type'] == "알림" || $commentDataType['type'] == "봉인제안") {
					echo "<item>";
						echo "<type><![CDATA[$commentDataType[type]]]></type>";
						echo "<reg_date><![CDATA[$commentDate]]></reg_date>";
						if($viewMode == "all" && $commentDataType['type'] == "봉인제안") {
							$writerTrueChar = mysql_fetch_array(mysql_query("select * from $DB_entry where game=$no and `character` = $commentDataType[character]"));
							echo "<username><![CDATA[$writerTrueChar[name]]]></username>";
						}
						echo "<description><![CDATA[".nl2br(stripslashes($commentData[memo]))."]]></description>";
					echo "</item>";
				}
				else {
					echo "<item>";
						echo "<type><![CDATA[$commentDataType[type]]]></type>";
						$character_detail=mysql_fetch_array(mysql_query("select * from $DB_character where no = $commentDataType[character]"));
						echo "<character><![CDATA[$commentDataType[character]]]></character>";
						echo "<image><![CDATA[$character_detail[half_image]]]></image>";
						echo "<name><![CDATA[$character_detail[character]]]></name>";
						echo "<reg_date><![CDATA[$commentDate]]></reg_date>";
						if($viewMode == "all") {
							$writerTrueChar = mysql_fetch_array(mysql_query("select * from $DB_entry where game=$no and `character` = $commentDataType[character]"));
							echo "<username><![CDATA[$writerTrueChar[name]]]></username>";
							echo "<truecharacter><![CDATA[".$truecharacter_list[$writerTrueChar['truecharacter']]."]]></truecharacter>";
						}
						echo "<description><![CDATA[".nl2br(stripslashes($commentData[memo]))."]]></description>";
					echo "</item>";
				}
				
			}
			break;
		}
	}
}

if($count == 0){
		echo "<div><span>$readLatest </span></div>\n";
}


mysql_close();
echo "  </channel>\n";
?>
