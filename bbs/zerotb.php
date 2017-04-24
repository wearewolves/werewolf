<? /****************************************
   /*  만든사람 : 임성욱 (likedy@nownuri.net)
   /*  출처 : woogi.apmsetup.org
   /*  설명 : 트랙백 받는 프로그램
   /*  고쳐서 쓰는건 좋은데..
   /*  저작권 지우면 미워요..
   /****************************************/
?>
<?
//$_zb_url = "http://werewolf2.cafe24.com/bbs/";   // 제로보드 루트. url 변수는 끝에 슬래시 "/"가 꼭 있어야 합니다.
//$_zb_path = "/usr/local/apache/htdocs/bbs/"; 	// URL이 아닌 제로보드 폴더(대개 bbs)의 위치 (끝에 / 이 붙어 있음에 유의)
require_once("skin/werewolf/config/path_setup.php");
require_once("skin/werewolf/config/server_setup.php");

$maxLength = 100;    // 게시물 내용중 일부분을 잘라낼 캐릭터 숫자입니다.

$bbs_id = trim($id); // 게시판 이름
$bbs_no = trim($no); // 게시판 글고유번호

$tb_title = trim($title); // 트랙백 제목
$tb_url = trim($url); // 트랙백 URL
$tb_excerpt = trim($excerpt); // 트랙백 EXCERPT
$tb_blog_name = trim($blog_name); // 트랙백 블로그명

if(mb_detect_encoding($tb_blog_name) == "UTF-8") // == "UTF-8"
	$tb_blog_name= rawurldecode(iconv("UTF-8","CP949",$tb_blog_name));

if(mb_detect_encoding($tb_url) == "UTF-8") // == "UTF-8"
	$tb_url= rawurldecode(iconv("UTF-8","CP949",$tb_url));

if(mb_detect_encoding($tb_excerpt) == "UTF-8") // == "UTF-8"
	$tb_excerpt=	rawurldecode(iconv("UTF-8","CP949",$tb_excerpt));

if(mb_detect_encoding($tb_title) == "UTF-8") // == "UTF-8"
	$tb_title =		rawurldecode(iconv("UTF-8","CP949",$tb_title));

$result = receive_trackback();
if ($__mode=="rss")
{
	$result["error"] = "0";
	$result["message"] = "Success";
}

header("Content-Type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"EUC-KR\"?>\r\n";
?>
<response>
	<error><?=$result["error"]?></error>
	<message><?=$result["message"]?></message>
<?
if ($__mode=="rss")
{
?>
	<rss version="0.91">
		<channel>
			<title><?=$result["title"]?></title>
			<link><?=$_zb_url?>zboard.php?id=<?=$bbs_id?>&amp;no=<?=$bbs_no?></link>
			<description><?=$result["description"]?></description>
			<language>euc-kr</language>
		</channel>
	</rss>
<?
}
?>
</response>
<?
// 이하는 트랙백을 받기 위한 함수들입니다.
function receive_trackback()
{
	global $bbs_id, $bbs_no, $tb_title, $tb_url, $tb_excerpt, $tb_blog_name, $maxLength, $__mode;

	$result["error"] = "0";
	$result["message"] = "TrackBack Success.";

	// 모든 값이 넘어왔는지 검사.
	if ((!$bbs_id || !$bbs_no || !$tb_title || !$tb_url || !$tb_excerpt) && !$__mode)
	{
		$result["error"] = "1";
		$result["message"] = "Not Enough Arguments.";
		return $result;
	}

	if(!$connect) $connect=trackback_dbconn();

	if($bbs_id == "werewolf"){
		$sql = "select * from `zetyx_board_".$bbs_id."_gameinfo` where game='".$bbs_no."'";
		$gameinfo=mysql_fetch_array(mysql_query($sql));

		if($gameinfo['state'] <>"게임끝"){
			$result["error"] = "1";
			$result["message"] = "No TrackBack.";
			return $result;
		}
	}

	// comment에 집어넣을 변수로 가공한다.
	if ($tb_blog_name)
		$name = addSlashes(cut_strlen(strip_tags($tb_blog_name),10));

	else
		$name = "TrackBack";
	$password = "TrackBack"; // 암호화 하지 않는 이유는 임의삭제 방지 및 트랙백 코멘트를 구분하는 효과가 있다.

	$tb_excerpt = cut_strlen(str_replace("\r\n"," ",strip_tags($tb_excerpt)), $maxLength);

//	$tb_title = cut_strlen(str_replace("\r\n"," ",strip_tags($tb_title)), 20);


	$memo .= "<a href='$tb_url' target='_tb'><u><font color=#999999>제목 : $tb_title</font></u></a>\r\n";
	$memo .= "$tb_excerpt <a href='$tb_url' target='_tb'>MORE</a>";
	$memo = addSlashes($memo);
	$reg_date=time(); // 현재의 시간구함;;
	$parent=$bbs_no;

	

	// 같은 내용이 있는지 검사
	$max_no=mysql_fetch_array(mysql_query("select max(no) from zetyx_board_comment_".$bbs_id." where parent='".$bbs_no."'"));
	$temp=mysql_fetch_array(mysql_query("select count(*) from zetyx_board_comment_".$bbs_id." where memo='$memo' and no='$max_no[0]'"));
	if($temp[0]>0)
	{
		$result["error"] = "1";
		$result["message"] = "Duplicated TrackBack.";
		return $result;
	}

	// 해당글이 있는 지를 검사
	$check = mysql_fetch_array(mysql_query("select subject, memo from zetyx_board_".$bbs_id." where no = '".$bbs_no."'", $connect));
	if(!$check[0])
	{
		$result["error"] = "1";
		$result["message"] = "Missing Entry.";
		return $result;
	}
	else
	{
		$result["title"] = $check["subject"];
		$result["description"] = cut_strlen(str_replace("\r\n"," ",strip_tags($check["memo"])), $maxLength);
	}

	if (!(!$bbs_id || !$bbs_no || !$tb_title || !$tb_url || !$tb_excerpt))
	{
		// 코멘트 입력
		mysql_query("insert into zetyx_board_comment_".$bbs_id." (parent,ismember,name,password,memo,reg_date,ip) values ('$parent','0','$name','$password','$memo','$reg_date','$server[ip]')")
		or ($result = array("error" => 1, "message" => "Server DB Error."));
	}

	// 코멘트 갯수를 구해서 정리
	$total=mysql_fetch_array(mysql_query("select count(*) from zetyx_board_comment_".$bbs_id." where parent='".$bbs_no."'"));
	mysql_query("update zetyx_board_".$bbs_id." set total_comment='$total[0]' where no='".$bbs_no."'")
	or ($result = array("error" => 1, "message" => "Server DB Error."));

	if($connect) mysql_close($connect);

	return $result;
}

function trackback_dbconn()
{
	global $connect, $_dbconn_is_included;
	if($_dbconn_is_included) return;
	$_dbconn_is_included = true;
	$f=@file($_zb_path."config.php") or Error("config.php파일이 없습니다.<br>DB설정을 먼저 하십시오","install.php");
	for($i=1;$i<=4;$i++) $f[$i]=trim(str_replace("\n","",$f[$i]));
	if(!$connect) $connect = @mysql_connect($f[1],$f[2],$f[3]) or Error("DB 접속시 에러가 발생했습니다");
	@mysql_select_db($f[4], $connect) or Error("DB Select 에러가 발생했습니다","");
	return $connect;
}

function cut_strlen($msg,$cut_size)
{
		if($cut_size<=0) return $msg;
		if(ereg("\[re\]",$msg)) $cut_size=$cut_size+4;
		for($i=0;$i<$cut_size;$i++) if(ord($msg[$i])>127) $han++; else $eng++;
		$cut_size=$cut_size+(int)$han*0.6;
		$point=1;
		for ($i=0;$i<strlen($msg);$i++) {
			if ($point>$cut_size) return $pointtmp."...";
			if (ord($msg[$i])<=127) {
				$pointtmp.= $msg[$i];
				if ($point%$cut_size==0) return $pointtmp."..."; 
			} else {
				if ($point%$cut_size==0) return $pointtmp."...";
				$pointtmp.=$msg[$i].$msg[++$i];
				$point++;
			}
			$point++;
		}
		return $pointtmp;
}



?>

