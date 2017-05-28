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
	$gameinfo_add_delayAfter = 
	"ALTER TABLE `zetyx_board_werewolf_gameinfo` CHANGE `delay` `delayAfter` MEDIUMINT(13) UNSIGNED NOT NULL DEFAULT '0';";
	$gameinfo_add_delayBefore = 
	"ALTER TABLE `zetyx_board_werewolf_gameinfo` ADD `delayBefore` MEDIUMINT(13) UNSIGNED NOT NULL DEFAULT '0';";
	$gameinfo_add_delayAfterUsed = 
	"ALTER TABLE `zetyx_board_werewolf_gameinfo` ADD `delayAfterUsed` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';";
	$gameinfo_add_delayBeforeUsed = 
	"ALTER TABLE `zetyx_board_werewolf_gameinfo` ADD `delayBeforeUsed` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';";
	
	@mysql_query($gameinfo_add_delayAfter, $connect) or Error("delayAfter 칼럼 추가 실패", "");
	@mysql_query($gameinfo_add_delayBefore, $connect) or Error("delayBefore 칼럼 추가 실패", "");
	@mysql_query($gameinfo_add_delayAfterUsed, $connect) or Error("delayAfterUsed 칼럼 추가 실패", "");
	@mysql_query($gameinfo_add_delayBeforeUsed, $connect) or Error("delayBeforeUsed 칼럼 추가 실패", "");
	
	
	mysql_close($connect);
?>