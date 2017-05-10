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
	
	$query = 
	"update `zetyx_board_werewolf_gameinfo` set state='게임끝' where game=2753;";
	
	@mysql_query($query, $connect) or Error("쿼리 실행 실패", "");
	
	mysql_close($connect);
?>