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
	return $tmp . "...(����)";
}


function connectDB() 
{
	global $link, $_zb_path;
	$f = @file($_zb_path . "config.php") or die("���κ����� config.php ���� ����"); 
	for ($i = 1; $i <= 4; $i++) $f[$i] = trim(str_replace("\n", "", $f[$i]));
	if (!$link) $link = @mysql_connect($f[1], $f[2], $f[3]) or die("DB ���� ����");
	@mysql_select_db($f[4], $link) or mysql_error();
	return $link;
}

// HTML Tag�� �����ϴ� �Լ�
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

include $_zb_path."lib.php";
//$logfile = @fopen("1-log.txt","a");
@fwrite($logfile,"[".$SID."]�ڸ�Ʈ �ۼ� ��û\n"); 

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


$secretKey="very good funny exciting game i will be great game designer.I love you.";
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

if($viewMode == "all") $commentType = "('�Ϲ�','�˸�','���','���','�ڷ�','�޸�')";
elseif ($viewMode == "death") $commentType = "('�Ϲ�','�˸�','���')";
elseif ($viewMode == "tele") $commentType = "('�Ϲ�','�˸�','�ڷ�')";
elseif ($viewMode == "sec") $commentType = "('�Ϲ�','�˸�','���')";
elseif($viewMode == "memo") $commentType = "('�Ϲ�','�˸�','�޸�')";
else $commentType = "('�Ϲ�','�˸�')";

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
// �ڸ�Ʈ�� �ְ� Number ���� ���� (�ߺ� üũ�� ���ؼ�)
	$max_no=mysql_fetch_array(mysql_query("select max(no) from $t_comment"."_$id where parent='$no'"));
// ���� ������ �ִ��� �˻�;;
	$temp=mysql_fetch_array(mysql_query("select count(*) from $t_comment"."_$id where memo='$memo' and no='$max_no[0]'"));

	$gameinfo=mysql_fetch_array(mysql_query("select * from $DB_gameinfo where game=$no"));
	
	//if(!$is_admin or 1) {
	if(!$member) {
				echo "<result>alert</result>\n";
				echo "<alert>�α��� �Ͻñ� �ٶ��ϴ�.</alert>";
	}
	if($day <> $gameinfo['day']) {
				echo "<result>alert</result>\n";
				echo "<alert>��¥�� ����Ǿ����ϴ�. ������ �ٽ� �������ּ���.</alert>";
	}//	elseif(!$is_admin) {
//				echo "<result>alert</result>\n";
//				echo "<alert>3�� 00�� ���ĺ��� �α׸� �ۼ��� �� �ֽ��ϴ�. 3�� ���Ŀ� �� ���ΰ�ħ�� ���ּ���. �� �׷��� �αװ� �����ϴ�.</alert>";
//	}
	elseif($c_type == "�Ϲ�" and $entry['alive'] == "����" and $entry['normal'] == 0) {
				echo "<result>alert</result>\n";
				echo "<alert>�Ϲ� �α׸� �� �̻� �ۼ��� �� �����ϴ�.</alert>";
	}
	elseif($temp[0] > 0) {
				echo "<result>alert</result>\n";
				echo "<alert>���� ������ ���� ����� �� �����ϴ�.</alert>";
	}
	elseif(($entry or $is_admin) and (substr_count ( $UNSID,"<||>") == 4)) {
		echo "<result>true</result>\n";
		@fwrite($logfile,"[".$SID."]�ڸ�Ʈ �ۼ� ����\n"); 

		

		if($entry and $gameinfo['state'] == "������"){
			$truecharacter =mysql_fetch_array(mysql_query("select * from $DB_truecharacter where no=$entry[truecharacter]"));

			if($truecharacter['secretletter']){
				$sql = "select * from $DB_secretletter where `game`='".$no."' and `day`='".$gameinfo['day']."' and `from` = ".$entry['character'];
				$secretletter=mysql_fetch_array(mysql_query($sql));
			}
			$sql = "select * from $DB_secretletter where `game`='".$no."' and `day`='".($gameinfo['day']-1)."' and `to` = ".$entry['character'];
			$secretmessage=mysql_fetch_array(mysql_query($sql));
		}

		/***************************************************************************
		 * �Խ��� ���� üũ
		 **************************************************************************/


		// ���� ���� �˻�;;
		$memo = str_replace("��","",$memo);
		if(isblank($memo)) Error("������ �Է��ϼž� �մϴ�");

		// ���͸�;; �����ڰ� �ƴҶ�;;
		if(!$is_admin&&$setup[use_filter] and  0) {
			$filter=explode(",",$setup[filter]);

			$f_memo=eregi_replace("([\_\-\./~@?=%&! ]+)","",strip_tags($memo));
			$f_name=eregi_replace("([\_\-\./~@?=%&! ]+)","",strip_tags($name));
			$f_subject=eregi_replace("([\_\-\./~@?=%&! ]+)","",strip_tags($subject));
			$f_email=eregi_replace("([\_\-\./~@?=%&! ]+)","",strip_tags($email));
			$f_homepage=eregi_replace("([\_\-\./~@?=%&! ]+)","",strip_tags($homepage));
			for($i=0;$i<count($filter);$i++) 
				if(!isblank($filter[$i])) {
				if(eregi($filter[$i],$f_memo)) Error("<b>$filter[$i]</b> ��(��) ����ϱ⿡ ������ �ܾ �ƴմϴ�");
				if(eregi($filter[$i],$f_name)) Error("<b>$filter[$i]</b> ��(��) ����ϱ⿡ ������ �ܾ �ƴմϴ�");
			}
		}

		// �������̰ų� HTML��뷹���� ������ �±��� ���������� üũ
		//	if(!$is_admin) {
		$memo=del_html($memo);// ������ HTML ����;;
		//	}


		// ȸ������� �Ǿ� ������ �̸����� ������;;
		if($member[no]) {
			if($mode=="modify"&&$member[no]!=$s_data[ismember]) {
				$name=$s_data[name];
			} else {
				$name=$member[name];
			}
		}

		// ���� ������ addslashes ��Ŵ
		$name=addslashes(del_html($name));
		//	$memo=autolink($memo);
		$memo=addslashes($memo);




		// ��Ű ����;;
		// ���� ���� ó�� (4.0x�� ���� ó���� ���Ͽ� �ּ� ó��)
		//if($c_name) $HTTP_SESSION_VARS["writer_name"]=$name;
		// 4.0x �� ���� ó��
		//	if($c_name) {
		//		$writer_name=$name;
		//		session_register("writer_name");
		//	}

		// ���� ���� ����
		$reg_date=time(); // ������ �ð�����;;
		$parent=$no;

		// �ش���� �ִ� ���� �˻�
		$check = mysql_fetch_array(mysql_query("select count(*) from $t_board"."_$id where no = '$no'"));
		if(!$check[0]) Error("���� ���� �������� �ʽ��ϴ�.");

		if(!$c_type) $c_type="�Ϲ�";
		$DB_entry=$t_board."_".$id."_entry";
		$DB_truecharacter=$t_board."_".$id."_truecharacter";
		$DB_gameinfo=$t_board."_".$id."_gameinfo";
		

		//	if(strlen($memo) <20 ) Error("������ �ʹ� ª���ϴ�.");	

		$writeComment = false;

		switch($c_type){
			case "�Ϲ�": if(($entry['alive']=="����" or (($gameinfo['state']=="���ӳ�" or $gameinfo['state']=="�׽�Ʈ" or $gameinfo['state']=="����") and $entry)) and $entry['normal'] > 0) {
								$writeComment = true;
								echo "<writeComment>$writeComment</writeComment>";
								if($gameinfo['state']<>"���ӳ�" and $gameinfo['state']<>"����"){
									@mysql_query("update $DB_entry set normal=$entry[normal] - 1 where game=$parent and player = $member[no]") or error(mysql_error());
								}
							}		
							break;
			case "�޸�": if($entry and $entry['memo'] > 0) {
								$writeComment = true;
								@mysql_query("update $DB_entry set memo=$entry[memo] - 1 where game=$parent and player = $member[no]") or error(mysql_error());
							}		
							break;
			case "���": if($entry['alive']=="����" and $truecharacter['secretchat'] and $entry['secret'] > 0) {
								$writeComment = true;
								@mysql_query("update $DB_entry set secret=$entry[secret] - 1 where game=$parent and player = $member[no]") or error(mysql_error());
							}		
							break;
			case "�ڷ�": if($entry['alive']=="����" and $truecharacter['telepathy'] and  $entry['telepathy'] > 0) {
								$writeComment = true;
								@mysql_query("update $DB_entry set telepathy=$entry[telepathy] - 1 where game=$parent and player = $member[no]") or error(mysql_error());
							}		
							break;
			case "���": if($entry['alive']=="���" and $entry['grave'] > 0) {
								$writeComment = true;
								@mysql_query("update $DB_entry set grave=$entry[grave] - 1 where game=$parent and player = $member[no]") or error(mysql_error());
							}		
							break;
			case "�˸�":if($is_admin){$writeComment = true;}
							break;
			case "����":if($entry['alive']=="����" and $truecharacter['secretletter'] and !$secretletter) {
								$writeComment = true;
							}
							break;
			case "�亯":if($entry['alive']=="����" and $secretmessage['to']==$entry['character'] and $secretmessage['answer']==0) {
								$writeComment = true;
							}
							break;
			case "��������":if($gameinfo['state']=="������" and $entry and $entry['seal'] > 0) {
								if($gameinfo['seal'] == '����'){
									$writeComment = true;
									@mysql_query("update `$DB_entry` set `seal` = $entry[seal] - 1 where game=$parent  and player = $member[no]") or error(mysql_error());
									@mysql_query("update `$DB_gameinfo` set `seal` = '����' where game = $no" ) or die("���� ���¸� ���� �߿� ������ �߻��߽��ϴ�.");
									@mysql_query("update `$DB_entry` set `seal` = '5' where game = $no") or die("���ӿ� ������ �÷��̾� ���� �����߿� ������ �߻��߽��ϴ�.");
								}
								if($gameinfo['seal'] == '����'){
									$writeComment = true;
									@mysql_query("update `$DB_entry` set `seal` = $entry[seal] - 1 where game=$parent and player = $member[no]") or error(mysql_error());				
								}
							}
							break;
		}
		//if($is_admin){	$writeComment = true;	}

		$entry=mysql_fetch_array(mysql_query("select * from $DB_entry where game=$game and player = $player"));

		echo "<commentType>";
		if($gameinfo['state']=="���ӳ�" or $gameinfo['state']=="����" or $gameinfo['state']=="����") {
			if($entry)echo "<normal>".$entry['normal']."</normal>";
			if($is_admin)echo "<notice>true</notice>";
		}
		else {
			if($entry['alive']=="����" ) {
				if($entry['normal'])echo "<normal>".$entry['normal']."</normal>";
				if($truecharacter['secretchat'] and $entry['secret'] )echo "<secret>".$entry['secret']."</secret>";
				if($truecharacter['telepathy'] and  $entry['telepathy'])echo "<telepathy>".$entry['telepathy']."</telepathy>";
				if($truecharacter['secretletter'] and !$secretletter and $c_type <> "����"){
					$character_list = DB_array("no","character","$DB_character where `set` = '$gameinfo[characterSet]'");

					echo "<secretletter><![CDATA[".DBselect("secretletterTo","","character",$character_list,"$DB_entry where game=$no and alive = '����'","font-size:9pt;width=100","",$entry['character'])."]]></secretletter>";
				}
				if($secretmessage['to']==$entry['character'] and $secretmessage['answer'] == 0) echo "<secretanswer>true</secretanswer>";
			}
			if($entry['alive']=="���" and $entry[grave] ){
				echo "<grave>".$entry['grave']."</grave>";
			}
			if($entry and $entry[memo] > 0)echo "<memo>".$entry['memo']."</memo>";
			if($is_admin)echo "<notice>true</notice>";
			if($gameinfo['seal']=="����" and $entry and $entry['seal'] > 0) echo "<seal>".$entry['seal']."</seal>";
		 }
		echo "</commentType>";

		if($writeComment){
			// �ڸ�Ʈ �Է�	
			if($c_type =="����" ) {				
				$character_list = DB_array("no","character","$DB_character where `set` = '$gameinfo[characterSet]'");
				$memo = "<b>".$character_list[$secretletterTo]."������ ������ ��� ����</b><br>".$memo;
				$memo=addslashes($memo);
			}

			mysql_query("insert into $t_comment"."_$id (parent,ismember,name,password,memo,reg_date,ip) values ('$parent','$member[no]','$name','$password','$memo','$reg_date','$server[ip]')") or error(mysql_error());

			// �ڸ�Ʈ Ÿ�� �Է�
			$commentID=mysql_insert_id();
			//	mysql_query("insert into $t_comment"."_$id"."_commentType (game,comment,type,`character`) values ($parent',$commentID,'$c_type','$entry[character]')") or error(mysql_error());	
			mysql_query("insert into $t_comment"."_$id"."_commentType (game,comment,type,`character`) values ($parent ,$commentID,'$c_type','$entry[character]')") or error(mysql_error());	

			// �ڸ�Ʈ ������ ���ؼ� ����
			$total=mysql_fetch_array(mysql_query("select count(*) from $t_comment"."_$id where parent='$no'"));
			mysql_query("update $t_board"."_$id set total_comment='$total[0]' where no='$no'") or error(mysql_error());

			// �ڸ�Ʈ ���θ� ���
			if($c_type == "�Ϲ�"  and !$entry['comment']) {
				mysql_query("update $DB_entry set comment = '1' where game = '$parent' and  `character` = '$entry[character]'") or error(mysql_error());
			}

			//��� ����
			if($c_type =="����"){				
				 $sql = 	"INSERT INTO `$DB_secretletter` ( `game` , `day` , `from` ,`to`,`message`) VALUES ('$no', '$gameinfo[day]','$entry[character]' , '$secretletterTo', $commentID);";
				@mysql_query($sql) or die("�Է� �߿� ������ �߻��߽��ϴ�.".$sql);
			}

			//��� ���� ����
			if($c_type =="�亯"){				
				 $sql = 	"update `$DB_secretletter` set `answer` = $commentID where  `game` =$no and  `day` = ($gameinfo[day]-1) ;";
				@mysql_query($sql) or die("�Է� �߿� ������ �߻��߽��ϴ�.".$sql);
			}

			// ȸ���� ��� �ش� �ؿ��� ���� �ֱ�
			@mysql_query("update $member_table set point2=point2+1 where no='$member[no]'",$connect) or error(mysql_error());
		}
	}
	else {
//		echo "<result>false</result>\n";
//		echo "<lastComment>$lastComment</lastComment>";
//		echo "<DBLastComment>$DBLastComment[0]</DBLastComment>";
				echo "<result>alert</result>\n";
				echo "<alert>��ð� ������� �ʾ� �α׾ƿ� �Ǿ����ϴ�.\n ���ͳ� �ɼǿ��� ��Ű�� ����� \n�ٽ� �α��� �Ͻñ� �ٶ��ϴ�.</alert>";
	}

mysql_close();
@fclose($logfile);    
echo "  </channel>\n";
?>
