<?
// 제로보드 라이브러리 가져옴
	$_zb_path = realpath("../../")."/";
	include $_zb_path."lib.php";
//	include("$dir/lib/lib.php");
	$DB_brief		=$t_board."_".$id."_brief";
	$DB_addnote		=$t_board."_".$id."_addnote";
	
	if(!eregi($HTTP_HOST,$HTTP_REFERER)) Error("정상적으로 글을 삭제하여 주시기 바랍니다.");

// DB 연결정보와 회원정보 가져옴
	$connect = dbConn();

// 코멘트 삭제
	mysql_query("delete from $DB_addnote  where no='$c_no'") or error(mysql_error());

//버그 처리 기록 재 설정
	//버그 처리 초기화
	@mysql_query("update $DB_brief  set status='1',dealResult='',repairman='' ,deal_date=report_date where bug='$no'") or die(mysql_error());

	$view_AddNote_result=mysql_query("select * from $DB_addnote where parent='$no' order by no asc");
		while($bug_add=mysql_fetch_array($view_AddNote_result)) {
			// 버그 처리
				@mysql_query("update $DB_brief  set status='$bug_add[status]',dealResult='$bug_add[dealResult]',repairman='$bug_add[repairman]' ,deal_date='$bug_add[deal_date]',reservation='$bug_add[reservation]' where bug='$no'") or die(mysql_error());
	}

//DB닫기
	@mysql_close($connect);


// 페이지 이동
	//if($setup[use_alllist]) movepage("zboard.php?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no");
	//else movepage("view.php?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no");
	//movepage("http://kijuli.cafe24.com/bbs/view.php?id=$id&no=$no");
		movepage("http://werewolf4.cafe24.com/bbs/view.php?id=$id&no=$no");

?>
