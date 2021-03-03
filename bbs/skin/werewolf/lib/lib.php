<?
// æœ»£»≠  ////////////////////////////
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
$secretKey= $_zb_path;

// Example of usage... 
/*
$message = "This is a very long message, but it is very secret and important and we need to keep the contents hidden from nasty people who might want to steal it."; 

$key = "secret key"; 

$crypted = crypt_md5($message, $key); 
echo "Encoded = $crypted<BR>"; // returns ??=? ???`??<?H ???{.?1?{??&#593;?J?V?+?j?e? 

$uncrypted = decrypt_md5($crypted, $key); 
echo "Unencoded = $uncrypted"; // returns This is a very long message (etc) 
*/

function getSID($game , $day, $lastComment, $member, $viewMode, $secretKey){ 

	$SID =  $game ."<||>". $day ."<||>". $lastComment ."<||>". $member."<||>". $viewMode;
	$SID = crypt_md5($SID, $secretKey);
	$SID = base64_encode($SID) ; 
	$SID = urlencode($SID);

	return $SID;
}

// DB πËø≠ ////////////////////////////
function DB_array($key,$value,$db){
	$temp_result=mysql_query("select * from $db ");

	while($temp_member=@mysql_fetch_array($temp_result)){
			$members[$temp_member[$key]]=$temp_member[$value];
	}

	return $members;
}

function DB_arrayForIpCheck($key,$value,$db){
	$temp_result=mysql_query("select distinct ip from $db ");

	while($temp_member=@mysql_fetch_array($temp_result)){
		if('192.168.1.254' <>$temp_member[$value]) 			$members[]="'".$temp_member[$value]."'";
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

function DBselect1($name,$head,$id,$value,$DB,$code,$selectedID,$unselectedID){
	$result=mysql_query("select * from $DB order by '$id'");

	if(!is_array($unselectedID)){
		$unselectedID = array($unselectedID);
	}

		
	$DB_select="&nbsp;<select $code name=$name>$head";
	while($temp=mysql_fetch_array($result)) {
		if(!in_array ($temp[$id], $unselectedID)){
			if($temp[$id]==$selectedID)$selected="selected";
			else $selected="";
		
			$DB_select.="<option value=$temp[$id] ".$selected." >". $temp[$value]."</option>";
		}
	}
	$DB_select.="</select> ";
	return $DB_select;
}

// Initialize characterSet
function init_characterSet($index, $value, $DB) {
	$result = mysql_query("select * from $DB order by 'no'");
	$characterSetArr = "";
	$i = 0;
	while($temp = mysql_fetch_array($result)) {
		$characterSetArr[$i] = $temp[$value];
		$i++;
	}
	unset($i);
	
	return $characterSetArr[$index];
}

// Get current characterSet name
function get_characterSetName($query) {
	$result = mysql_fetch_array(mysql_query("select * from $query"));
	return $result[name];
}

// Make sorted characterSet list and select an item
function set_characterSet($DB, $sort) {
	$result = mysql_query("select * from $DB order by '$sort'");
	
	$characterSetList = "";
	while($temp = mysql_fetch_array($result)) {
		$characterSetList .= "<li onclick=\"selectRPSet('$temp[no]', '$temp[name]')\">".$temp[name]."</li>";
	}
	return $characterSetList;
}

function betweenday($day1,$day2,$termOfDay){
	if($termOfDay == 0){
		return 0;
	}
	else{
		return	ceil(abs( $day1- $day2)/$termOfDay);
	}
//	return	date("d",883580400+$day1 - $day2);
}
function orderCondition($orderArray){
			$orderCondition ="in (";

			foreach($orderArray  as $temp_order){
				$orderCondition.=$temp_order.",";
			}
			$orderCondition.=")";

			return str_replace(",)", ")", $orderCondition);
}


function suddenDeathCheck($file,$DB,$no,$deathday){
	$DB_entry = $DB."_entry";
	$DB_suddenDeath = $DB."_suddenDeath";
	$reg_data = time();

//∞‘¿”¿Ã ¡æ∑·µ«æ˙¥Ÿ∏È ±‚∑œ¿ª ≥≤±‰¥Ÿ.
	$noCommentPlayer_list=mysql_query("select * from $DB_entry where game = $no and alive='ª˝¡∏' and comment = 0 and victim = 0 ");

	if($noCommentPlayer_list){
	fwrite($file,"[«—π¯µµ µ°±€¿ª ≥≤±‚¡ˆ æ ¿∫ ªÁ∂˜¿Ã ¿÷¥Ÿ∏È ∫Ò∏≈≥  «√∑π¿ÃæÓ∑Œ ±‚∑œ«—¥Ÿ.]------------ \n");
		while ($noCommentPlayer=mysql_fetch_array($noCommentPlayer_list)){
			$sql = "INSERT INTO $DB_suddenDeath (`no`,`game`,`name`,`player`,`character`,`truecharacter`,`deathday`,`reg_data`,`ip`) VALUES('',$no,'$noCommentPlayer[name]', $noCommentPlayer[player], $noCommentPlayer[character], $noCommentPlayer[truecharacter],$deathday,'$reg_data','$noCommentPlayer[ip]');";
			fwrite($file,"\$sql:".$sql." \n"); 
			@mysql_query($sql) or error(mysql_error());	

			$sql = "update zetyx_member_table set `level`= 8 where no = $noCommentPlayer[player]";
			fwrite($file,"\$sql:".$sql." \n"); 
			@mysql_query($sql) or error(mysql_error());	
		}
	}
}
function suddenDeathCheckUnder30M($file,$DB,$no,$deathday,$MaxSuddenCountUnder30M){
	$DB_entry = $DB."_entry";
	$DB_suddenDeath = $DB."_suddenDeath";
	$reg_data = time();

	$noCommentPlayer_list=mysql_query("select * from $DB_entry where game = $no and alive='ª˝¡∏' and comment = 0 and victim = 0 ");

	if($noCommentPlayer_list){
		while($noCommentPlayer = mysql_fetch_array($noCommentPlayer_list)){
			$sql ="update `$DB_entry` set `suddenCount` = `suddenCount` + 1 where `game` = '$no' and `character` = '$noCommentPlayer[character]';";
				fwrite($file,"\$sql:".$sql." \n"); 
				@mysql_query($sql) or die("µπø¨ªÁ ±‚∑œ ¿ª ¿‘∑¬ ¡ﬂø° ø¿∑˘∞° πﬂª˝«ﬂΩ¿¥œ¥Ÿ.");		
		}
	}

	$noCommentPlayer_list=mysql_query("select * from $DB_entry where game = $no and alive='ª˝¡∏' and `suddenCount` = $MaxSuddenCountUnder30M and victim = 0 ");

	if($noCommentPlayer_list){		
		fwrite($file,"[«—π¯µµ µ°±€¿ª ≥≤±‚¡ˆ æ ¿∫ ªÁ∂˜¿Ã ¿÷¥Ÿ∏È ∫Ò∏≈≥  «√∑π¿ÃæÓ∑Œ ±‚∑œ«—¥Ÿ.]------------ \n");
		while ($noCommentPlayer=mysql_fetch_array($noCommentPlayer_list)){
			$sql = "INSERT INTO $DB_suddenDeath (`no`,`game`,`name`,`player`,`character`,`truecharacter`,`deathday`,`reg_data`,`ip`) VALUES('',$no,'$noCommentPlayer[name]', $noCommentPlayer[player], $noCommentPlayer[character], $noCommentPlayer[truecharacter],$deathday,'$reg_data','$noCommentPlayer[ip]');";
			fwrite($file,"\$sql:".$sql." \n"); 
			@mysql_query($sql) or error(mysql_error());	

			$sql = "update zetyx_member_table set `level`= 8 where no = $noCommentPlayer[player]";
			fwrite($file,"\$sql:".$sql." \n"); 
			@mysql_query($sql) or error(mysql_error());	
		}
	}
}
function postNoManner($file,$t_division,$t_board,$id,$no,$deathday,$ip){
	$game_list  = DB_array("no","subject",$t_board."_".$id);
	$character_list = DB_array("no","character",$t_board."_".$id."_character");
	$truecharacter_list = DB_array("no","character",$t_board."_".$id."_truecharacter");

	$DB_suddenDeath = $t_board.'_'.$id."_suddenDeath";
	$wolfExplanation = $t_board.'_'."wolfExplanation";
	$reg_date = time();

//∞‘¿”¿Ã ¡æ∑·µ«æ˙¥Ÿ∏È ±‚∑œ¿ª ≥≤±‰¥Ÿ.
	$noMannerPlayer_list=mysql_query("select * from $DB_suddenDeath where game = $no");


	if($noMannerPlayer_list){
	fwrite($file,"[∞‘¿” µøæ» ∫Ò∏≈≥  «‡¿ß∏¶ «— ªÁ∂˜µÈ¿ª º“∏Ì ∞‘Ω√∆«ø° ∞‘Ω√«—¥Ÿ.]\n");
		while ($noMannerPlayer=mysql_fetch_array($noMannerPlayer_list)){
			$temp=mysql_fetch_array(mysql_query("select max(division) from $t_division"."_$id"));
			$max_division=$temp[0];
			$temp=mysql_fetch_array(mysql_query("select max(division) from $t_division"."_$id where num>0 and division!='$max_division'"));
			if(!$temp[0]) $second_division=0; else $second_division=$temp[0];

			$sql = "select min(headnum) from $wolfExplanation where (division='$max_division' or division='$second_division') and headnum>-2000000000";
			$max_headnum=mysql_fetch_array(mysql_query($sql));
			if(!$max_headnum[0]) $max_headnum[0]=0;

			$headnum=$max_headnum[0]-1;

			$next_data=mysql_fetch_array(mysql_query("select division,headnum,arrangenum from $t_board"."_$id where (division='$max_division' or division='$second_division') and headnum>-2000000000 order by headnum limit 1"));
			if(!$next_data[0]) $next_data[0]="0";
			else {
				$next_data=mysql_fetch_array(mysql_query("select no,headnum,division from $t_board"."_$id where division='$next_data[division]' and headnum='$next_data[headnum]' and arrangenum='$next_data[arrangenum]'"));
			}
    
			$prev_data=mysql_fetch_array(mysql_query("select no from $t_board"."_$id where (division='$max_division' or division='$second_division') and headnum<=-2000000000 order by headnum desc limit 1"));
			if($prev_data[0]) $prev_no=$prev_data[0]; else $prev_no="0";

			$next_no=$next_data[no];
			$child="0";
			$depth="0";
			$arrangenum="0";
			$father="0";
			$division=add_division();

			$password = '04b192f75f43a6ab';
			$name = '∑πƒÀ∫£∏£' ;
			$homepage ='';
			$email ='';
			$use_html = '';
			$reply_mail ='';
			$category = 1;
			$is_secret = '';
			$member[is_admin] = 1;

			$subject  = "[".$game_list[$noMannerPlayer['game']]."] ".$noMannerPlayer['name'].'¥‘ º“∏Ì«ÿ ¡÷ººø‰.';
			$memo = $character_list[$noMannerPlayer['character']].' ('.$truecharacter_list[$noMannerPlayer['truecharacter']].') ∑Œ ¿÷¿∏∏Èº≠ '.$noMannerPlayer['deathday'].' ¿œ ¬∞ø° µπø¨ªÁ «œºÃΩ¿¥œ¥Ÿ.\n\n';
			$memo .= '¿Ãø° ∞∞¿Ã «√∑π¿Ã«— ∏∂¿ª ªÁ∂˜µÈø°∞‘ º“∏Ì«ÿ ¡÷ººø‰.';


			$sql = "insert into `$wolfExplanation` (division,headnum,arrangenum,depth,prev_no,next_no,father,child,ismember,memo,ip,password,name,homepage,email,subject,use_html,reply_mail,category,is_secret,sitelink1,sitelink2,file_name1,file_name2,s_file_name1,s_file_name2,x,y,reg_date,islevel) values ('$division','$headnum','$arrangenum','$depth','$prev_no','$next_no','$father','$child','$noMannerPlayer[player]','$memo','$ip','$password','$name','$homepage','$email','$subject','$use_html','$reply_mail','$category','$is_secret','$sitelink1','$sitelink2','$file_name1','$file_name2','$s_file_name1','$s_file_name2','$x','$y','$reg_date','$member[is_admin]')";

			fwrite($file,"\$sql:".$sql." \n"); 
			@mysql_query($sql) or error(mysql_error());	
		}
	}
}
function writeCommnet($db,$no,$member_no,$member_name,$password,$comment,$REMOTE_ADDR,$comment_type,$character){
	$member[no] =$member_no;
	$member[name]=$member_name;
	$comment=addslashes($comment);

	$reg_date=time();
	// ƒ⁄∏‡∆Æ ¿‘∑¬
	mysql_query("insert into $db (parent,ismember,name,password,memo,reg_date,ip) values ('$no','$member[no]','$member[name]','$password','$comment','$reg_date','$REMOTE_ADDR')") or error(mysql_error());

	// ƒ⁄∏‡∆Æ ≈∏¿‘ ¿‘∑¬
	$commentID=mysql_insert_id();
	mysql_query("insert into $db"."_commentType (game,comment,type,`character`) values ($no,$commentID,'$comment_type','$character')") or error(mysql_error());	
}

function record($file,$DB,$no){
fwrite($file,"$DB.$no \n"); 
	$DB_entry = $DB."_entry";
	$DB_truecharacter = $DB."_truecharacter";
	$DB_record = $DB."_record";
	$DB_gameinfo = $DB."_gameinfo";

	$gameinfo=mysql_fetch_array(mysql_query("select * from $DB_gameinfo where game=$no"));

fwrite($file,"[∞‘¿” ±‚∑œ ≥≤±‚±‚]-------------------------------------------------------------- \n"); 
fwrite($file,date("Y-m-d A h:i:s",time())." \n"); 
	$entry_player = DB_array("player","truecharacter","$DB_entry where game = $no and victim = 0");
	$wintype = DB_array("no","wintype","$DB_truecharacter");

fwrite($file,"\$entry_player:".print_r($entry_player,true)." \n"); 
fwrite($file,"\$wintype:".print_r($wintype,true)." \n"); 

	reset($entry_player);
	while (list($player,$truecharacter )=each($entry_player)){
fwrite($file,"\$player:".$player." \n"); 
fwrite($file,"\$truecharacter:".$truecharacter." \n"); 

		$record = @mysql_fetch_array(mysql_query("select * from $DB_record where player = $player"));
//fwrite($file,"\$record:".print_r($record,true)." \n"); 
		$player_info = @mysql_fetch_array(mysql_query("select * from $DB_entry where game = $no and player = $player"));
//fwrite($file,"\$player_info:".print_r($player_info,true)." \n"); 

		if(!$record){
			@mysql_query("INSERT INTO $DB_record (`no`,`player`) VALUES('', $player);") or error(mysql_error());	
			$record = @mysql_fetch_array(mysql_query("select * from $DB_record where player = $player"));
//fwrite($file,"\$record:".print_r($record,true)." \n"); 
		}					 
		 switch ($wintype[$truecharacter]) {
			case 0:
				if($gameinfo[win] == 0 ) $record[humanWin] += 1;
				else $record[humanLose] +=1;
			   break;
			case 1:
				if($gameinfo[win] == 1 ) $record[werewolfWin] += 1;
				else $record[werewolfLose] +=1;
				break;
			case 2:
				if($gameinfo[win] == 2 ) $record[hamsterWin] += 1;
				else $record[hamsterLose] +=1;
				break;
		}
		 switch ($player_info[deathtype]) {
			case "Ω…∆«":
				 $record[vothDeath]+=1;
				  break;
			case "Ω¿∞›":
				 $record[assaultDeath] +=1;
				 break;
			case "µπø¨":
				 $record[suddenDeath]+=1;
				 break;
		}					
		switch($truecharacter){
			case 1:				 
				 $record[meek]+=1;
				 break;
			case 2:
				 $record[fortuneteller]+=1;
				 break;
			case 3:
				 $record[medium]+=1;
				 break;
			case 4:
				 $record[madman]+=1;
				 break;
			case 5:
				 $record[werewolf] +=1;
				 break;
			case 6:
				 $record[hunter] +=1;
				 break;
			case 7:
				 $record[psychic] +=1;
				 break;
			case 8:
				 $record[hamster] +=1;
				 break;
		}

		$sql = "update $DB_record set `humanWin`= '$record[humanWin]' ,  `humanLose`= '$record[humanLose]' , `werewolfWin`= '$record[werewolfWin]' ,  `werewolfLose`= '$record[werewolfLose]' , `hamsterWin`= '$record[hamsterWin]' ,  `hamsterLose`= '$record[hamsterLose]' ,  `vothDeath`= '$record[vothDeath]' ,  `assaultDeath`= '$record[assaultDeath]' ,  `suddenDeath`= '$record[suddenDeath]' ,  `meek`= '$record[meek]' ,  `fortuneteller`= '$record[fortuneteller]'  ,  `medium`= '$record[medium]'  ,  `madman`= '$record[madman]'  ,  `werewolf`= '$record[werewolf]'  ,  `hunter`= '$record[hunter]' ,  `psychic`= '$record[psychic]',  `hamster`= '$record[hamster]' where player = $record[player]";

		fwrite($file,"\$sql:".$sql." \n"); 

		mysql_query($sql)or error(mysql_error());
	}			
}

// 2017/05/07 epi : º≠∫Í∑Í √º≈© ∫Œ∫–
function checkSubRule($subrule, $checkval) {
	$inverseval = $checkval * -1;
	$subRule_bin = decbin($subrule); // decimal to binary
	if(strlen($subRule_bin) >= $checkval && substr($subRule_bin, $inverseval, 1)) return 1;
	else return 0;
}

$DB_gameinfo=$t_board."_".$id."_gameinfo";
$DB_entry=$t_board."_".$id."_entry";
$DB_rule=$t_board."_".$id."_rule";
$DB_vote=$t_board."_".$id."_vote";
$DB_character=$t_board."_".$id."_character";
$DB_characterSet=$t_board."_".$id."_characterSet";
$DB_truecharacter=$t_board."_".$id."_truecharacter";
$DB_comment_type=$t_comment."_$id"."_commentType";
$DB_revelation = $t_board."_".$id."_revelation";
$DB_deathNote = $t_board."_".$id."_deathNote";
$DB_deathNote_result = $t_board."_".$id."_deathNote_result";
$DB_guard = $t_board."_".$id."_guard";
$DB_record = $t_board."_".$id."_record";
$DB_setup = $t_board."_".$id."_setup";

$DB_detect = $t_board."_".$id."_detect";
$DB_revenge= $t_board."_".$id."_revenge";
$DB_deathNoteHalf  = $t_board."_".$id."_deathnotehalf";
$DB_secretletter  = $t_board."_".$id."_secretletter";
$DB_mustkill  = $t_board."_".$id."_mustkill";


$truecharacter_list = DB_array("no","character","$DB_truecharacter");

if($member[no]){
/*	$recoardDB = mysql_query("select *,(10+1*humanWin-1*humanLose+werewolfWin-werewolfLose-2*suddenDeath) as point from  $DB_record where player = $member[no]");
	
	if($recoardDB)
	{
		$re = mysql_fetch_array($recoardDB );
		$playCount = $re['werewolfWin']+$re['werewolfLose']+$re['humanWin']+$re['humanLose']+$re['hamsterWin']+$re['hamsterLose'];
	}
*/
	$sql = "SELECT  count(*) FROM  `zetyx_board_werewolf_entry` ,`zetyx_board_werewolf_gameinfo` where player = $member[no] and `zetyx_board_werewolf_entry`.game = `zetyx_board_werewolf_gameinfo`.game and (state = '∞‘¿”≥°' or state = 'πˆ±◊' or state = '≈◊Ω∫∆Æ')";
	$playCount =  mysql_fetch_array(mysql_query($sql));
	$playCount = $playCount[0];

}

//$playCount = DB_array("game","game"," `zetyx_board_werewolf_gameinfo`  WHERE state =  '∞‘¿”≥°'  ");
//$orderCondition = orderCondition($playCount);
//$playCount = mysql_fetch_array(mysql_query("SELECT count(*) FROM `zetyx_board_werewolf_entry` WHERE player = $member[no] and game $orderCondition;"));
//playCount =$playCount[0];

$suddenDeathCount = $re['suddenDeath'];

$NowPlayingGame = DB_array("game","game"," `zetyx_board_werewolf_gameinfo`  WHERE state =  '¡ÿ∫Ò¡ﬂ' OR state =  '∞‘¿”¡ﬂ'");

if($NowPlayingGame and $member[no]){
	$orderCondition = orderCondition($NowPlayingGame);
	$NowPlayerCount = mysql_fetch_array(mysql_query("SELECT count(distinct player) FROM `zetyx_board_werewolf_entry` WHERE victim=0 and game $orderCondition;"));
	$NowPlayerCount =$NowPlayerCount[0];

	$NowPlayingCount = mysql_fetch_array(mysql_query("SELECT count(*) FROM `zetyx_board_werewolf_entry` WHERE player = $member[no] and game $orderCondition;"));
	$NowPlayingCount = $NowPlayingCount[0];
}
/*
$werewolfSetup = mysql_fetch_array(mysql_query("SELECT * FROM $DB_setup WHERE name = 'basic'"));
if(date("Y-m-d",$werewolfSetup['today']) <> date("Y-m-d")){
	$sql = "update $DB_setup set `today`= ".mktime (0,0,0,date("m")  , date("d"), date("Y")).",   `24h-town`=0 ,`30m-town`=0  where name='basic'";
	mysql_query($sql);
	$werewolfSetup = mysql_fetch_array(mysql_query("SELECT * FROM $DB_setup WHERE name = 'basic'"));
}
*/
?> 