<?

/***************************************************************************
 * 공통 파일 include
 **************************************************************************/
	include "_head.php";

	if(strpos($HTTP_HOST,':') <> false)	$HTTP_HOST =	substr($HTTP_HOST,0,strpos($HTTP_HOST,':'));
	if(!eregi($HTTP_HOST,$HTTP_REFERER)) Error("정상적으로 글을 삭제하여 주시기 바랍니다.");

/***************************************************************************
* 코멘트 삭제 진행
**************************************************************************/

// 패스워드를 암호화
	if($password) {
		$temp=mysql_fetch_array(mysql_query("select password('$password')"));
		$password=$temp[0];   
	}

// 원본글을 가져옴
	$s_data=mysql_fetch_array(mysql_query("select * from $t_comment"."_$id where no='$c_no'"));

// 회원일때를 확인;;
	if(!$is_admin&&$member[level]>$setup[grant_delete]) {
		if(!$s_data[ismember]) {
			if($s_data[password]!=$password) Error("비밀번호가 올바르지 않습니다");
		} else {
			if($s_data[ismember]!=$member[no]) Error("비밀번호를 입력하여 주십시오");
		}
	}

// 코멘트 삭제
	mysql_query("delete from $t_comment"."_$id where no='$c_no'") or error(mysql_error());

// 코멘트 갯수 정리
	$total=mysql_fetch_array(mysql_query("select count(*) from $t_comment"."_$id where parent='$no'"));
	mysql_query("update $t_board"."_$id set total_comment='$total[0]' where no='$no'")  or error(mysql_error()); 

// 회원일 경우 해당 해원의 점수 주기
	if($member[no]==$s_data[ismember]) @mysql_query("update $member_table set point2=point2-1 where no='$member[no]'",$connect) or error(mysql_error());

	@mysql_close($connect);

// 페이지 이동
	if($setup[use_alllist]) movepage("zboard.php?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no");
	else movepage("view.php?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no");
?>
