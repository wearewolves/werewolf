<?
function get_date($t) 
{
	return date('D, d M Y H:i:s ', $t) . "+0900";
}
function cut_string($str, $length)
{ 
	if ($length < 0) return $str;
	if ($length == 0) return "";
	if (strlen($str) <= $length) return $str;
	for($i = 0; $i < $length; $i++)
	{
		if (ord($str[$i]) > 127) $over++;
	}
	$tmp = chop(substr($str, 0, $length - $over % 2));
	return $tmp . "...(생략)";
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

function incision_sort($arr, $col)
{
   // Source from php.net
   for($k = 0; $k < sizeof($arr)-1; $k++){
	   // $arr[$k+1] is possibly in the wrong place. Take it out.
	   $t = $arr[$k+1];
	   $i = $k;   
	  
	   // Push $arr[i] to the right until we find the right place for $t.
	   while($i >= 0 && $arr[$i][$col] < $t[$col]){
		   $arr[$i+1] = $arr[$i];
		   $i--;
	   }
	  
	   // Insert $t into the right place.
	   $arr[$i+1] = $t;                           
   }// End sort
   return $arr;       
}

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

function bytexor($a,$b,$l){ 
	$c=""; 

	for($i=0;$i<$l;$i++) { 
		$c.=$a{$i}^$b{$i}; 
	} 
	return($c); 
} 

function binmd5($val){ 
	return(pack("H*",md5($val))); 
} 

function decrypt_md5($msg,$heslo){ 
	$key=$heslo;$sifra=""; 
	$key1=binmd5($key); 

	while($msg) { 
		$m=substr($msg,0,16); 
		$msg=substr($msg,16); 
		$sifra.=$m=bytexor($m,$key1,16); 
		$key1=binmd5($key.$key1.$m); 
	 }
	 
	echo "\n"; 
	return($sifra); 
} 

function crypt_md5($msg,$heslo){ 
	$key=$heslo;$sifra=""; 
	$key1=binmd5($key); 

	while($msg) { 
		$m=substr($msg,0,16); 
		$msg=substr($msg,16); 
		$sifra.=bytexor($m,$key1,16); 
		$key1=binmd5($key.$key1.$m); 
	} 
	echo "\n"; 
	return($sifra); 
} 

function utf8RawUrlDecode ($source) {
   $decodedStr = "";
   $pos = 0;
   $len = strlen ($source);
   while ($pos < $len) {
       $charAt = substr ($source, $pos, 1);
       if ($charAt == '%') {
           $pos++;
           $charAt = substr ($source, $pos, 1);
           if ($charAt == 'u') {
               // we got a unicode character
               $pos++;
               $unicodeHexVal = substr ($source, $pos, 4);
               $unicode = hexdec ($unicodeHexVal);
               $entity = "&#". $unicode . ';';
               $decodedStr .= utf8_encode ($entity);
               $pos += 4;
           }
           else {
               // we have an escaped ascii character
               $hexVal = substr ($source, $pos, 2);
               $decodedStr .= chr (hexdec ($hexVal));
               $pos += 2;
           }
       } else {
           $decodedStr .= $charAt;
           $pos++;
       }
   }
   return $decodedStr;
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

include $_zb_path."lib.php";
//$logfile = @fopen("1-log.txt","a");
@fwrite($logfile,"[".$SID."]코멘트 작성 요청\n"); 

if (!empty($_SERVER['SERVER_SOFTWARE']) && strstr($_SERVER['SERVER_SOFTWARE'], 'Apache/2')){
   header ('Cache-Control: no-cache, pre-check=0, post-check=0, max-age=0');
}
else
{
   header ('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
}
header ('Content-Type: text/xml');

echo "<?xml version=\"1.0\" encoding=\"$ch[encoding]\"?>\n";
echo "  <channel>\n";


$secretKey= $_zb_path;
$UNSID  = $SID;
	echo "<SID><![CDATA[$SID]]></SID>\n";
//$UNSID = urldecode($UNSID);

$UNSID = base64_decode($UNSID);
//$UNSID = decrypt_md5(base64_decode($SID), $secretKey); 
$UNSID = decrypt_md5($UNSID, $secretKey); 
//$UNSID =unserialize($UNSID);

//	list($game, $day, $count,$member,$viewMode) = split("<||>", $UNSID);
 $key = explode("<||>", $UNSID);

 $game = $key[0];
 $day = $key[1];
 $lastComment = $key[2];
 $player = $key[3];
 $viewMode = $key[4];
 $login_ip = $key[5];

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
$DB_secretletter  = $t_board."_".$id."_secretletter";

if($viewMode == "all") $commentType = "('일반','알림','비밀','사망','텔레','메모')";
elseif ($viewMode == "death") $commentType = "('일반','알림','사망')";
elseif ($viewMode == "tele") $commentType = "('일반','알림','텔레')";
elseif ($viewMode == "sec") $commentType = "('일반','알림','비밀')";
elseif($viewMode == "memo") $commentType = "('일반','알림','메모')";
else $commentType = "('일반','알림')";

if($player ==1)$is_admin = true;
else $is_admin = false;

$entry=mysql_fetch_array(mysql_query("select * from $DB_entry where game=$game and player = $player"));

$memo = rawurldecode(iconv("UTF-8","CP949",$memo));
//$memo = mb_convert_encoding ($memo,"CP949","auto");
//$memo= utf8RawUrlDecode($memo);
//$memo = urldecode($memo);
$c_type = rawurldecode(iconv("UTF-8","CP949",$c_type));


if($entry['character']) $character = $entry['character'];
else $character = 0;

$DBLastComment = mysql_fetch_array(mysql_query("select max(comment) from $DB_wereCommentType where `game`='$no' and (`type` in $commentType or `character` ='$character') "));
// 코멘트의 최고 Number 값을 구함 (중복 체크를 위해서)
	$max_no=mysql_fetch_array(mysql_query("select max(no) from $t_comment"."_$id where parent='$no'"));
// 같은 내용이 있는지 검사;;
	$temp=mysql_fetch_array(mysql_query("select count(*) from $t_comment"."_$id where memo='$memo' and no='$max_no[0]'"));

	$gameinfo=mysql_fetch_array(mysql_query("select * from $DB_gameinfo where game=$no"));
	
	//if(!$is_admin or 1) {
	if(!$member) {
				echo "<result>alert</result>\n";
				echo "<alert>로그인 하시기 바랍니다.</alert>";
	}
	if($day <> $gameinfo['day']) {
				echo "<result>alert</result>\n";
				echo "<alert>날짜가 변경되었습니다. 마을에 다시 입장해주세요.</alert>";
	}//	elseif(!$is_admin) {
//				echo "<result>alert</result>\n";
//				echo "<alert>3시 00분 이후부터 로그를 작성할 수 있습니다. 3시 이후에 꼭 새로고침을 해주세요. 안 그러면 로그가 깨집니다.</alert>";
//	}
	elseif($c_type == "일반" and $entry['alive'] == "생존" and $entry['normal'] == 0) {
				echo "<result>alert</result>\n";
				echo "<alert>일반 로그를 더 이상 작성할 수 없습니다.</alert>";
	}
	elseif($temp[0] > 0) {
				echo "<result>alert</result>\n";
				echo "<alert>같은 내용의 글은 등록할 수 없습니다.</alert>";
	}
	elseif(($entry or $is_admin) and (substr_count ( $UNSID,"<||>") == 5)) {
		echo "<result>true</result>\n";
		@fwrite($logfile,"[".$SID."]코멘트 작성 시작\n"); 

		

		if($entry and $gameinfo['state'] == "게임중"){
			$truecharacter =mysql_fetch_array(mysql_query("select * from $DB_truecharacter where no=$entry[truecharacter]"));

			if($truecharacter['secretletter']){
				$sql = "select * from $DB_secretletter where `game`='".$no."' and `day`='".$gameinfo['day']."' and `from` = ".$entry['character'];
				$secretletter=mysql_fetch_array(mysql_query($sql));
			}
			$sql = "select * from $DB_secretletter where `game`='".$no."' and `day`='".($gameinfo['day']-1)."' and `to` = ".$entry['character'];
			$secretmessage=mysql_fetch_array(mysql_query($sql));
		}

		/***************************************************************************
		 * 게시판 설정 체크
		 **************************************************************************/


		// 각종 변수 검사;;
		$memo = str_replace("ㅤ","",$memo);
		if(isblank($memo)) Error("내용을 입력하셔야 합니다");

		// 필터링;; 관리자가 아닐때;;
		if(!$is_admin&&$setup[use_filter] and  0) {
			$filter=explode(",",$setup[filter]);

			$f_memo=eregi_replace("([\_\-\./~@?=%&! ]+)","",strip_tags($memo));
			$f_name=eregi_replace("([\_\-\./~@?=%&! ]+)","",strip_tags($name));
			$f_subject=eregi_replace("([\_\-\./~@?=%&! ]+)","",strip_tags($subject));
			$f_email=eregi_replace("([\_\-\./~@?=%&! ]+)","",strip_tags($email));
			$f_homepage=eregi_replace("([\_\-\./~@?=%&! ]+)","",strip_tags($homepage));
			for($i=0;$i<count($filter);$i++) 
				if(!isblank($filter[$i])) {
				if(eregi($filter[$i],$f_memo)) Error("<b>$filter[$i]</b> 은(는) 등록하기에 적합한 단어가 아닙니다");
				if(eregi($filter[$i],$f_name)) Error("<b>$filter[$i]</b> 은(는) 등록하기에 적합한 단어가 아닙니다");
			}
		}

		// 관리자이거나 HTML허용레벨이 낮을때 태그의 금지유무를 체크
		//	if(!$is_admin) {
		$memo=del_html($memo);// 내용의 HTML 금지;;
		//	}


		// 회원등록이 되어 있을때 이름등을 가져옴;;
		if($member[no]) {
			if($mode=="modify"&&$member[no]!=$s_data[ismember]) {
				$name=$s_data[name];
			} else {
				$name=$member[name];
			}
		}

		// 각종 변수의 addslashes 시킴
		$name=addslashes(del_html($name));
		//	$memo=autolink($memo);
		$memo=addslashes($memo);




		// 쿠키 설정;;
		// 기존 세션 처리 (4.0x용 세션 처리로 인하여 주석 처리)
		//if($c_name) $HTTP_SESSION_VARS["writer_name"]=$name;
		// 4.0x 용 세션 처리
		//	if($c_name) {
		//		$writer_name=$name;
		//		session_register("writer_name");
		//	}

		// 각종 변수 설정
		$reg_date=time(); // 현재의 시간구함;;
		$parent=$no;

		// 해당글이 있는 지를 검사
		$check = mysql_fetch_array(mysql_query("select count(*) from $t_board"."_$id where no = '$no'"));
		if(!$check[0]) Error("원본 글이 존재하지 않습니다.");

		if(!$c_type) $c_type="일반";
		$DB_entry=$t_board."_".$id."_entry";
		$DB_truecharacter=$t_board."_".$id."_truecharacter";
		$DB_gameinfo=$t_board."_".$id."_gameinfo";
		

		//	if(strlen($memo) <20 ) Error("내용이 너무 짧습니다.");	

		$writeComment = false;

		switch($c_type){
			case "일반": if(($entry['alive']=="생존" or (($gameinfo['state']=="게임끝" or $gameinfo['state']=="테스트" or $gameinfo['state']=="버그") and $entry)) and $entry['normal'] > 0) {
								$writeComment = true;
								echo "<writeComment>$writeComment</writeComment>";
								if($gameinfo['state']<>"게임끝" and $gameinfo['state']<>"버그"){
									@mysql_query("update $DB_entry set normal=$entry[normal] - 1 where game=$parent and player = $member[no]") or error(mysql_error());
								}
							}		
							break;
			case "메모": if($entry and $entry['memo'] > 0) {
								$writeComment = true;
								@mysql_query("update $DB_entry set memo=$entry[memo] - 1 where game=$parent and player = $member[no]") or error(mysql_error());
							}		
							break;
			case "비밀": if($entry['alive']=="생존" and $truecharacter['secretchat'] and $entry['secret'] > 0) {
								$writeComment = true;
								@mysql_query("update $DB_entry set secret=$entry[secret] - 1 where game=$parent and player = $member[no]") or error(mysql_error());
							}		
							break;
			case "텔레": if($entry['alive']=="생존" and $truecharacter['telepathy'] and  $entry['telepathy'] > 0) {
								$writeComment = true;
								@mysql_query("update $DB_entry set telepathy=$entry[telepathy] - 1 where game=$parent and player = $member[no]") or error(mysql_error());
							}		
							break;
			case "사망": if($entry['alive']=="사망" and $entry['grave'] > 0) {
								$writeComment = true;
								@mysql_query("update $DB_entry set grave=$entry[grave] - 1 where game=$parent and player = $member[no]") or error(mysql_error());
							}		
							break;
			case "알림":if($is_admin){$writeComment = true;}
							break;
			case "편지":if($entry['alive']=="생존" and $truecharacter['secretletter'] and !$secretletter) {
								$writeComment = true;
							}
							break;
			case "답변":if($entry['alive']=="생존" and $secretmessage['to']==$entry['character'] and $secretmessage['answer']==0) {
								$writeComment = true;
							}
							break;
			case "봉인제안":if($gameinfo['state']=="게임중" and $entry and $entry['seal'] > 0) {
								if($gameinfo['seal'] == '제안'){
									$writeComment = true;
									@mysql_query("update `$DB_entry` set `seal` = $entry[seal] - 1 where game=$parent  and player = $member[no]") or error(mysql_error());
									@mysql_query("update `$DB_gameinfo` set `seal` = '논의' where game = $no" ) or die("게임 상태를 수정 중에 오류가 발생했습니다.");
									@mysql_query("update `$DB_entry` set `seal` = '5' where game = $no") or die("게임에 참여한 플레이어 수를 갱신중에 오류가 발생했습니다.");
								}
								if($gameinfo['seal'] == '논의'){
									$writeComment = true;
									@mysql_query("update `$DB_entry` set `seal` = $entry[seal] - 1 where game=$parent and player = $member[no]") or error(mysql_error());				
								}
							}
							break;
		}
		//if($is_admin){	$writeComment = true;	}

		$entry=mysql_fetch_array(mysql_query("select * from $DB_entry where game=$game and player = $player"));

		echo "<commentType>";
		if($gameinfo['state']=="게임끝" or $gameinfo['state']=="봉인" or $gameinfo['state']=="버그") {
			if($entry)echo "<normal>".$entry['normal']."</normal>";
			if($is_admin)echo "<notice>true</notice>";
		}
		else {
			if($entry['alive']=="생존" ) {
				if($entry['normal'])echo "<normal>".$entry['normal']."</normal>";
				if($truecharacter['secretchat'] and $entry['secret'] )echo "<secret>".$entry['secret']."</secret>";
				if($truecharacter['telepathy'] and  $entry['telepathy'])echo "<telepathy>".$entry['telepathy']."</telepathy>";
				if($truecharacter['secretletter'] and !$secretletter and $c_type <> "편지"){
					$character_list = DB_array("no","character","$DB_character where `set` = '$gameinfo[characterSet]'");

					echo "<secretletter><![CDATA[".DBselect("secretletterTo","","character",$character_list,"$DB_entry where game=$no and alive = '생존'","font-size:9pt;width=100","",$entry['character'])."]]></secretletter>";
				}
				if($secretmessage['to']==$entry['character'] and $secretmessage['answer'] == 0) echo "<secretanswer>true</secretanswer>";
			}
			if($entry['alive']=="사망" and $entry[grave] ){
				echo "<grave>".$entry['grave']."</grave>";
			}
			if($entry and $entry[memo] > 0)echo "<memo>".$entry['memo']."</memo>";
			if($is_admin)echo "<notice>true</notice>";
			if($gameinfo['seal']=="논의" and $entry and $entry['seal'] > 0) echo "<seal>".$entry['seal']."</seal>";
		 }
		echo "</commentType>";

		if($writeComment){
			// 코멘트 입력	
			if($c_type =="편지" ) {				
				$character_list = DB_array("no","character","$DB_character where `set` = '$gameinfo[characterSet]'");
				$memo = "<b>".$character_list[$secretletterTo]."씨에게 보내는 비밀 편지</b><br>".$memo;
				$memo=addslashes($memo);
			}

			mysql_query("insert into $t_comment"."_$id (parent,ismember,name,password,memo,reg_date,ip) values ('$parent','$member[no]','$name','$password','$memo','$reg_date','$server[ip]')") or error(mysql_error());

			// 코멘트 타입 입력
			$commentID=mysql_insert_id();
			//	mysql_query("insert into $t_comment"."_$id"."_commentType (game,comment,type,`character`) values ($parent',$commentID,'$c_type','$entry[character]')") or error(mysql_error());	
			mysql_query("insert into $t_comment"."_$id"."_commentType (game,comment,type,`character`) values ($parent ,$commentID,'$c_type','$entry[character]')") or error(mysql_error());	

			// 코멘트 갯수를 구해서 정리
			$total=mysql_fetch_array(mysql_query("select count(*) from $t_comment"."_$id where parent='$no'"));
			mysql_query("update $t_board"."_$id set total_comment='$total[0]' where no='$no'") or error(mysql_error());

			// 코멘트 여부를 기록
			if($c_type == "일반"  and !$entry['comment']) {
				mysql_query("update $DB_entry set comment = '1' where game = '$parent' and  `character` = '$entry[character]'") or error(mysql_error());
			}

			//비밀 편지
			if($c_type =="편지"){				
				 $sql = 	"INSERT INTO `$DB_secretletter` ( `game` , `day` , `from` ,`to`,`message`) VALUES ('$no', '$gameinfo[day]','$entry[character]' , '$secretletterTo', $commentID);";
				@mysql_query($sql) or die("입력 중에 오류가 발생했습니다.".$sql);
			}

			//비밀 편지 답장
			if($c_type =="답변"){				
				 $sql = 	"update `$DB_secretletter` set `answer` = $commentID where  `game` =$no and  `day` = ($gameinfo[day]-1) ;";
				@mysql_query($sql) or die("입력 중에 오류가 발생했습니다.".$sql);
			}

			// 회원일 경우 해당 해원의 점수 주기
			@mysql_query("update $member_table set point2=point2+1 where no='$member[no]'",$connect) or error(mysql_error());
		}
	}
	else {
//		echo "<result>false</result>\n";
//		echo "<lastComment>$lastComment</lastComment>";
//		echo "<DBLastComment>$DBLastComment[0]</DBLastComment>";
				echo "<result>alert</result>\n";
				echo "<alert>장시간 사용하지 않아 로그아웃 되었습니다.\n 인터넷 옵션에서 쿠키를 지우고 \n다시 로그인 하시기 바랍니다.</alert>";
	}

mysql_close();
@fclose($logfile);    
echo "  </channel>\n";
?>
