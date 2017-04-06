<?
/***************************************************************************
 * 공통 파일 include
 **************************************************************************/
	include "_head.php";

	if(strpos($HTTP_HOST,':') <> false)	$HTTP_HOST =	substr($HTTP_HOST,0,strpos($HTTP_HOST,':'));
	if(!eregi($HTTP_HOST,$HTTP_REFERER)) Error("정상적으로 글을 작성하여 주시기 바랍니다.");

/***************************************************************************
 * 게시판 설정 체크
 **************************************************************************/

// 대상 파일 이름 정리
	if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

// 사용권한 체크
	if($setup[grant_comment]<$member[level]&&!$is_admin) Error("사용권한이 없습니다","login.php?id=$id&page=$page&page_num=$page_num&category=$category&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&file=$view_file_link");

// 각종 변수 검사;;
	$memo = str_replace("ㅤ","",$memo);
	if(isblank($memo)) Error("내용을 입력하셔야 합니다");
	if(!$member[no]) {
		if(isblank($name)) Error("이름을 입력하셔야 합니다");
		if(isblank($password)) Error("비밀번호를 입력하셔야 합니다");
	}

function DB_array($key,$value,$db){
	$temp_result=mysql_query("select * from $db ");

	while($temp_member=@mysql_fetch_array($temp_result)){
			$members[$temp_member[$key]]=$temp_member[$value];
	}

	return $members;
}

// 필터링;; 관리자가 아닐때;;
	if(!$is_admin&&$setup[use_filter]) {
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

// 패스워드를 암호화
	if($password) {
		$temp=mysql_fetch_array(mysql_query("select password('$password')"));
		$password=$temp[0];   
	}

// 관리자이거나 HTML허용레벨이 낮을때 태그의 금지유무를 체크
	if(!$is_admin&&$setup[grant_html]<$member[level]) {
		$memo=del_html($memo);// 내용의 HTML 금지;;
	}

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
	$memo=autolink($memo);
	$memo=addslashes($memo);

// 코멘트의 최고 Number 값을 구함 (중복 체크를 위해서)
	$max_no=mysql_fetch_array(mysql_query("select max(no) from $t_comment"."_$id where parent='$no'"));

// 같은 내용이 있는지 검사;;
	if(!$is_admin) {
		$temp=mysql_fetch_array(mysql_query("select count(*) from $t_comment"."_$id where memo='$memo' and no='$max_no[0]'"));
		if($temp[0]>0) Error("같은 내용의 글은 등록할수가 없습니다");
	}

// 쿠키 설정;;

	// 기존 세션 처리 (4.0x용 세션 처리로 인하여 주석 처리)
	//if($c_name) $HTTP_SESSION_VARS["writer_name"]=$name;

	// 4.0x 용 세션 처리
	if($c_name) {
		$writer_name=$name;
		session_register("writer_name");
	}

// 각종 변수 설정
	$reg_date=time(); // 현재의 시간구함;;
	$parent=$no;

// 해당글이 있는 지를 검사
	$check = mysql_fetch_array(mysql_query("select count(*) from $t_board"."_$id where no = '$no'", $connect));
	if(!$check[0]) Error("원본 글이 존재하지 않습니다.");

// 플레이어 정보
	$DB_entry=$t_board."_".$id."_entry";
	$DB_gameinfo=$t_board."_".$id."_gameinfo";
	$DB_wereComment =$t_comment."_".$id;
	$DB_wereCommentType = $DB_wereComment."_commentType";
	$DB_character=$t_board."_".$id."_character";
	$DB_truecharacter=$t_board."_".$id."_truecharacter";
	$DB_secretletter  = $t_board."_".$id."_secretletter";


	$entry=mysql_fetch_array(mysql_query("select * from $DB_entry where game=$parent and player = $member[no]"));
	if(!$entry and !$is_admin) Error("게임에 참여하지 않았습니다.");

	$gameinfo=mysql_fetch_array(mysql_query("select * from $DB_gameinfo where game=$no"));

	if($entry and $gameinfo['state'] == "게임중"){
		$truecharacter =mysql_fetch_array(mysql_query("select * from $DB_truecharacter where no=$entry[truecharacter]"));

		if($truecharacter['secretletter']){
			$sql = "select * from $DB_secretletter where `game`='".$no."' and `day`='".$gameinfo['day']."' and `from` = ".$entry['character'];
			$secretletter=mysql_fetch_array(mysql_query($sql));
		}
		$sql = "select * from $DB_secretletter where `game`='".$no."' and `day`='".($gameinfo['day']-1)."' and `to` = ".$entry['character'];
		$secretmessage=mysql_fetch_array(mysql_query($sql));
	}


	$writeComment = false;

	switch($c_type){
		case "일반": if(($entry['alive']=="생존" and $entry['normal'] >0) or (($gameinfo['state']=="게임끝" or $gameinfo['state']=="테스트" or $gameinfo['state']=="버그") and $entry)) {
							$writeComment = true;
							if($gameinfo['state']<>"게임끝"){
								@mysql_query("update $DB_entry set normal=$entry[normal] - 1 where game=$parent and player = $member[no]") or error(mysql_error());
							}
						 }		
						break;
		case "메모": if($entry and $entry['memo'] >0) {
							$writeComment = true;
							@mysql_query("update $DB_entry set memo=$entry[memo] - 1 where game=$parent  and player = $member[no] ") or error(mysql_error());
						 }		
						break;
		case "비밀": if($entry['alive']=="생존" and $truecharacter['secretchat'] and $entry['secret'] >0 ){
							$writeComment = true;
							@mysql_query("update $DB_entry set secret=$entry[secret] - 1 where game=$parent and player = $member[no]") or error(mysql_error());
						 }		
						break;
		case "텔레": if($entry['alive']=="생존" and $truecharacter['telepathy'] and  $entry['telepathy'] >0 ){
							$writeComment = true;
							@mysql_query("update $DB_entry set telepathy=$entry[telepathy] - 1 where game=$parent and player = $member[no]") or error(mysql_error());
						 }		
						break;
		case "사망": if($entry['alive']=="사망" and $entry['grave']>0 ) {
							$writeComment = true;
							@mysql_query("update $DB_entry set grave=$entry[grave] - 1 where game=$parent and player = $member[no] ") or error(mysql_error());
						 }		
						break;
		case "알림":if($is_admin){$writeComment = true;}
						break;
		case "편지":if($entry['alive']=="생존" and $truecharacter['secretletter'] and !$secretletter){
							$writeComment = true;
						}
						break;
		case "답변":if($entry['alive']=="생존" and $secretmessage['to']==$entry['character'] and $secretmessage['answer']==0){
							$writeComment = true;
						}
						break;
	}

	if(!$writeComment) Error("덧글을 쓸 수 없습니다.");

	if($c_type =="편지" ){				
		$character_list = DB_array("no","character","$DB_character where `set` = '$gameinfo[characterSet]'");
		$memo = "<b>".$character_list[$secretletterTo]."씨에게 보내는 비밀 편지</b><br>".$memo;
		$memo=addslashes($memo);
	}


// 코멘트 입력
	$sql = "insert into $t_comment"."_$id (`parent`,`ismember`,`name`,`password`,`memo`,`reg_date`,`ip`) values ('".$parent."','".$member['no']."','".$name."','".$password."','".$memo."','".$reg_date."','".$server['ip']."')";
//	echo "1:".$sql;

	mysql_query($sql) or error(mysql_error());

// 코멘트 타입 입력
	$commentID=mysql_insert_id();

	$sql = "insert into $t_comment"."_$id"."_commentType (game,comment,type,`character`) values ($parent ,$commentID,'$c_type','$entry[character]')";
//	echo "2:".$sql;
	mysql_query($sql) or error(mysql_error());	

// 코맨드 여부를 기록
	if($c_type =="일반"  and !$entry['comment']){
		mysql_query("update $DB_entry set comment = '1' where game = '$parent' and  `character` = '$entry[character]'") or error(mysql_error());
	}

	//비밀 편지
	if($c_type =="편지" ){				
		 $sql = 	"INSERT INTO `$DB_secretletter` ( `game` , `day` , `from` ,`to`,`message`) VALUES ('$no', '$gameinfo[day]','$entry[character]' , '$secretletterTo', $commentID);";
		@mysql_query($sql) or die("입력 중에 오류가 발생했습니다.".$sql);
	}

	//비밀 편지 답장
	if($c_type =="답변" ){				
		 $sql = 	"update `$DB_secretletter` set `answer` = $commentID where  `game` =$no and  `day` = ($gameinfo[day]-1) ;";
		@mysql_query($sql) or die("입력 중에 오류가 발생했습니다.".$sql);
	}



// 코멘트 갯수를 구해서 정리
	$total=mysql_fetch_array(mysql_query("select count(*) from $t_comment"."_$id where parent='$no'"));
	mysql_query("update $t_board"."_$id set total_comment='$total[0]' where no='$no'") or error(mysql_error());


// 회원일 경우 해당 해원의 점수 주기
	@mysql_query("update $member_table set point2=point2+1 where no='$member[no]'",$connect) or error(mysql_error());

	@mysql_close($connect);

// 페이지 이동
	movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category");
?>
