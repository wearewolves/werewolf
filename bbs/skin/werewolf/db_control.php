<?
	// register_globals가 off일 때를 위해 변수 재정의
	@extract($HTTP_GET_VARS); 
	@extract($HTTP_POST_VARS); 
	@extract($HTTP_SERVER_VARS);
	@extract($HTTP_ENV_VARS);

	// 제로보드 라이브러리 가져옴
	$_zb_path = realpath("../../")."/";
	include $_zb_path."lib.php";

	// DB 연결정보 가져옴
	$connect = dbConn();
	
	$no = 2753;

	// 원본글을 가져옴
	$s_data=mysql_fetch_array(mysql_query("select * from $t_board"."_$id where no='$no'"));

   mysql_query("delete from $t_board"."_$id where no='$no'") or Error(mysql_error()); // 글삭제

   // 파일삭제
   @z_unlink("./".$s_data[file_name1]);
   @z_unlink("./".$s_data[file_name2]);

   minus_division($s_data[division]);

   if($s_data[depth]==0)
   {
    if($s_data[prev_no]) mysql_query("update $t_board"."_$id set next_no='$s_data[next_no]' where next_no='$s_data[no]'"); // 이전글이 있으면 빈자리 메꿈;;;
    if($s_data[next_no]) mysql_query("update $t_board"."_$id set prev_no='$s_data[prev_no]' where prev_no='$s_data[no]'"); // 다음글이 있으면 빈자리 메꿈;;;
   }
   else
   { 
    $temp=mysql_fetch_array(mysql_query("select count(*) from $t_board"."_$id where father='$s_data[father]'"));
    if(!$temp[0]) mysql_query("update $t_board"."_$id set child='0' where no='$s_data[father]'"); // 원본글이 있으면 원본글의 자식글을 없앰;;;
   }

   // 간단한 답글 삭제
   mysql_query("delete from $t_comment"."_$id where parent='$s_data[no]'");

	// 게임 기록 삭제 - 시작
	$DB_gameinfo=$t_board."_".$id."_gameinfo";
	$DB_entry=$t_board."_".$id."_entry";
	$DB_comment_type=$t_comment."_$id"."_commentType";
	$DB_record = $t_board."_".$id."_record";

   mysql_query("delete from $DB_gameinfo where game='$s_data[no]'");
   mysql_query("delete from $DB_entry where game='$s_data[no]'");
   mysql_query("delete from $DB_comment_type where game='$s_data[no]'");
	// 게임 기록 삭제 - 끝


   $total=mysql_fetch_array(mysql_query("select count(*) from $t_board"."_$id "));
   mysql_query("update $admin_table set total_article='$total[0]' where name='$id'");

   // 카테고리 필드 조절
   mysql_query("update $t_category"."_$id set num=num-1 where no='$s_data[category]'",$connect);

   // 회원일 경우 해당 해원의 점수 주기
   if($member[no]==$s_data[ismember]) @mysql_query("update $member_table set point1=point1-1 where no='$member[no]'",$connect) or error(mysql_error());
	
	
	mysql_close($connect);
?>