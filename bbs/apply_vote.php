<?
/***************************************************************************
 * 공통파일 include
 **************************************************************************/
	include "_head.php";

// 사용권한 체크
	if(!$member['no'])Error("사용권한이 없습니다","login.php?id=$id&page=$page&page_num=$page_num&category=$category&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&file=zboard.php");

	if($setup[grant_view]<$member[level]&&!$is_admin) Error("사용권한이 없습니다","login.php?id=$id&page=$page&page_num=$page_num&category=$category&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&file=zboard.php");

	$data = mysql_fetch_array(mysql_query("SELECT  *  FROM  $t_board"."_$id WHERE no = $no"));

// 현재글의 Vote수 올림;;
	$sql = "SELECT count(  *  )  FROM  `zetyx_board_comment_survey`  AS  `comment` ,  `zetyx_board_survey`  AS  `parent`  WHERE  `comment`.parent =  `parent`.no AND  `parent`.headnum =  $data[headnum] AND  `comment`.ismember = $member[no]";
	$count = mysql_fetch_array(mysql_query($sql));
	$count = $count['0'];

//	if(!eregi($setup[no]."_".$no,  $HTTP_SESSION_VARS[zb_vote])) {
	if($count == 0){
		mysql_query("update $t_board"."_$id set vote=vote+1 where no='$sub_no'");
		mysql_query("update $t_board"."_$id set vote=vote+1 where no='$no'");

		$name = $member['name'];
		$password = $member['password'];
		$memo ="vote";
		$reg_date =time();
		mysql_query("insert into $t_comment"."_$id (parent,ismember,name,password,memo,reg_date,ip) values ('$sub_no','$member[no]','$name','$password','$memo','$reg_date','$server[ip]')") or error(mysql_error());

		// 4.0x 용 세션 처리
		//$zb_vote = $HTTP_SESSION_VARS[zb_vote] . "," . $setup[no]."_".$no;
		//session_register("zb_vote");

		// 기존 세션 처리 (4.0x용 세션 처리로 인하여 주석 처리)
		//$HTTP_SESSION_VARS[zb_vote] = $HTTP_SESSION_VARS[zb_vote] . "," . $setup[no]."_".$no;
	}
	else{
		Error("이미 참여하셨습니다.","view.php?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&category=$category&no=$no");


	}

	@mysql_close($connect);

// 페이지 이동
	if($setup[use_alllist]) movepage("zboard.php?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&category=$category&no=$no");
	else  movepage("view.php?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&category=$category&no=$no");  
?>
