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
	
	// 칼럼 추가
	$gameinfo_add_subrule = 
	"ALTER TABLE `zetyx_board_werewolf_gameinfo` ADD `subRule` INT(20) NOT NULL DEFAULT '0' AFTER `rule`;";
	$gameinfo_add_delay = 
	"ALTER TABLE `zetyx_board_werewolf_gameinfo` ADD `delay` MEDIUMINT(13) NOT NULL DEFAULT '0';";
	
	@mysql_query($gameinfo_add_subrule, $connect) or Error("subRule 칼럼 추가 실패", "");
	@mysql_query($gameinfo_add_subrule, $connect) or Error("delay 칼럼 추가 실패", "");
	
	// 칼럼 확인
	$gameinfo_show_subrule = 
	"SHOW COLUMNS FROM `zetyx_board_werewolf_gameinfo` LIKE `subRule`;";
	$gameinfo_show_delay = 
	"SHOW COLUMNS FROM `zetyx_board_werewolf_gameinfo` LIKE `delay`;";
	
	$result1 = mysql_query($gameinfo_show_subrule, $connect);
	$result2 = mysql_query($gameinfo_show_delay, $connect);
	
	if($result1) echo "subRule 칼럼 추가 성공<br>";
	if($result2) echo "delay 칼럼 추가 성공<br>";
	
	mysql_close($connect);
?>