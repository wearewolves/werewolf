<?
// 제로보드 라이브러리 가져옴
	$_zb_path = realpath("../../")."/";
	include $_zb_path."lib.php";	
//	include("$dir/lib/lib.php");
	$DB_brief		=$t_board."_".$id."_brief";
	$DB_addnote		=$t_board."_".$id."_addnote";
	if(!eregi($HTTP_HOST,$HTTP_REFERER)) Error("정상적으로 글을 작성하여 주시기 바랍니다.");

// DB 연결정보와 회원정보 가져옴
	$connect = dbConn();

// 게시판 설정을 가져옴
	$setup=get_table_attrib($id);
	if(!$setup[no]) error("존제하지 않는 게시판 입니다.","window.close");


/***************************************************************************
 * 게시판 설정 체크
 **************************************************************************/

// 대상 파일 이름 정리
	if(!$setup[use_alllist]) $view_file_link=$_zb_path."view.php"; else $view_file_link=$_zb_path."zboard.php";
	$view_file_link=$_zb_path."view.php";

// 각종 변수의 addslashes 시킴
//	$memo=addslashes($memo2);

// 코멘트의 최고 Number 값을 구함 (중복 체크를 위해서)
	$max_no=mysql_fetch_array(mysql_query("select max(no) from $DB_addnote where parent='$no'"));


// 각종 변수 설정
	$deal_date =time(); // 처리시간(현재 시간)
	$reservation =mktime(0,0,0,$month,1,$year);//나중에 처리할 때

	$parent=$no;

// 해당글이 있는 지를 검사
	$check = mysql_fetch_array(mysql_query("select count(*) from ".$t_board."_".$id." where no = '$no'", $connect));
	if(!$check[0]) Error("원본 글이 존재하지 않습니다.");

// 처리 내용 저장
	@mysql_query("insert into $DB_addnote (parent,memo2,status,dealResult,repairman , deal_date, reservation ) values ('$parent','$memo2','$status','$dealResult','$repairman','$deal_date','$reservation')")  or die(mysql_error());
 
// 버그 처리
	@mysql_query("update $DB_brief  set status='$status',dealResult='$dealResult',repairman='$repairman' ,deal_date='$deal_date',reservation='$reservation' where bug='$no'") or die(mysql_error());

	@mysql_close($connect);

// 페이지 이동

	//http://kijuli.cafe24.com/bbs/view.php?id=$id&no=$no
//	movepage("$view_file_link?id=$id&no=$no");
	//movepage("http://werewolf4.cafe24.com/bbs/view.php?id=$id&no=$no");
	movepage("http://werewolf4.cafe24.com/bbs/view.php?id=$id&no=$no");

?>