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
 $lastComment = $key[2];
 $player = $key[3];
 $login_ip = $key[5];

$no=$game;
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

$viewMode = $SessionID->viewMode($SID, $secretKey);
$commentType = $SessionID->commentType($viewMode);

if($player ==1)$is_admin = true;
else $is_admin = false;

$entry=@mysql_fetch_array(mysql_query("select * from $DB_entry where game=$game and player = $player"));

$memo = rawurldecode(iconv("UTF-8","CP949",$memo));
$c_type = rawurldecode(iconv("UTF-8","CP949",$c_type));


if($entry['character']) $character = $entry['character'];
else $character = 0;

if($SessionID->verification($SID, $secretKey)){

	for($count = 1; $count > 0 ; $count-- ){
		$DBLastComment = mysql_fetch_array(mysql_query("select max(comment) from $DB_wereCommentType where `game`='$no' and (`type` in $commentType or `character` ='$character') "));

		$gameinfo=mysql_fetch_array(mysql_query("select * from $DB_gameinfo where game=$no"));

		if(($day == $gameinfo['day']) and  ($lastComment <> $DBLastComment[0])){
			echo "<result>true</result>\n";

			$SID = $SessionID->getSID($gameinfo['game'],$gameinfo[day],$DBLastComment[0],$player,$viewMode,$login_ip, $secretKey);

			echo "<SID><![CDATA[$SID]]></SID>\n";
			echo "<sound>play</sound>\n";

			if($entry and $gameinfo['state'] == "게임중"){
				$truecharacter =mysql_fetch_array(mysql_query("select * from $DB_truecharacter where no=$entry[truecharacter]"));
			}

			$sql="select * from $DB_wereCommentType where game='$no' and comment > $lastComment and (type in $commentType or `character` = $character) order by comment asc";

			$view_comment_result=mysql_query($sql);
			while($commentDataType=mysql_fetch_array($view_comment_result)){
				$commentData=mysql_fetch_array(mysql_query("select * from $DB_wereComment where no='$commentDataType[comment]'"));
				$commentDate = date("Y-m-d H:i:s",$commentData['reg_date']);

				if($commentDataType[type] =="알림" or $commentDataType[type] =="봉인제안"){
					echo "<item>";
						echo "<type><![CDATA[$commentDataType[type]]]></type>";
						echo "<reg_date><![CDATA[$commentDate]]></reg_date>";	
						echo "<description><![CDATA[".nl2br(stripslashes($commentData[memo]))."]]></description>";		
					echo "</item>";
				}
				else{
					echo "<item>";
						echo "<type><![CDATA[$commentDataType[type]]]></type>";
						$character_detail=mysql_fetch_array(mysql_query("select * from $DB_character where no = $commentDataType[character]"));
						echo "<character><![CDATA[$commentDataType[character]]]></character>";
						echo "<image><![CDATA[$character_detail[half_image]]]></image>";
						echo "<name><![CDATA[$character_detail[character]]]></name>";
						echo "<reg_date><![CDATA[$commentDate]]></reg_date>";	
						echo "<description><![CDATA[".nl2br(stripslashes($commentData[memo]))."]]></description>";		
					echo "</item>";
				}
			}
			break;
		}
		elseif(!$gameinfo){
			echo "<result>deleteGame</result>\n";
		}
		elseif($day <> $gameinfo['day']){
			echo "<result>goNextDay</result>\n";
		}
	}
}

if($count == 0){
		echo "<result>false</result>\n";
}


mysql_close();
echo "  </channel>\n";
?>
