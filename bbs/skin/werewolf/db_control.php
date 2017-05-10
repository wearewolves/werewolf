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
	
	$subrule_data = 
	"INSERT INTO `zetyx_board_werewolf_subrule` (`name`) VALUES 
	('비밀 투표');";
	
	@mysql_query($subrule_data, $connect) or Error("subrule 데이터 삽입 실패", "");
	
	mysql_close($connect);
?>