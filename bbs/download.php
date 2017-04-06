<?
/***************************************************************************
 * 공통 파일 include
 **************************************************************************/
	include "_head.php";

	if(strpos($HTTP_HOST,':') <> false)	$HTTP_HOST =	substr($HTTP_HOST,0,strpos($HTTP_HOST,':'));
	if(!eregi($HTTP_HOST,$HTTP_REFERER)) die();

/***************************************************************************
 * 게시판 설정 체크
 **************************************************************************/

// 사용권한 체크
	if($setup[grant_view]<$member[level]&&!$is_admin) Error("사용권한이 없습니다","login.php?id=$id&page=$page&page_num=$page_num&category=$category&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&file=zboard.php");

// 현재글의 Download 수를 올림;;
    if($filenum==1) {
        mysql_query("update `$t_board"."_$id` set download1=download1+1 where no='$no'");
    } else {
        mysql_query("update `$t_board"."_$id` set download2=download2+1 where no='$no'");
    }

	$data=mysql_fetch_array(mysql_query("select * from  `$t_board"."_$id` where no='$no'"));
  
// 다운로드;;
	$filename="file_name".$filenum;
	header("location:$data[$filename]");

	if($connect) {
		@mysql_close($connect);
		unset($connect);
	}
?>
