<?
	// register_globals가 off일 때를 위해 변수 재정의
	@extract($HTTP_SERVER_VARS);
	@extract($HTTP_ENV_VARS);

	// 제로보드 라이브러리 가져옴
	$_zb_path = realpath("../../")."/";
	include $_zb_path."lib.php";

	// DB 연결정보 가져옴
	$connect = dbConn();

	// 칼럼 추가
	$instant_data = 
	"INSERT INTO `zetyx_board_werewolf_rule` (`no`, `name`, `min_player`, `max_player`) VALUES (5, '인스턴트', 7, 8);";
	
	
	@mysql_query($instant_data, $connect) or Error("subrule 데이터 삽입 실패", "");
	
	mysql_close($connect);
?>