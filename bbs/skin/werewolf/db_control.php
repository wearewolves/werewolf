<?
	// register_globals가 off일 때를 위해 변수 재정의
	@extract($HTTP_SERVER_VARS);
	@extract($HTTP_ENV_VARS);

	// 제로보드 라이브러리 가져옴
	$_zb_path = realpath("../../")."/";
	include $_zb_path."lib.php";

	// DB 연결정보 가져옴
    $connect = dbConn();

    // 테이블 추가
	$seervote_schema = 
	"CREATE TABLE `zetyx_board_werewolf_seervote` (
	  `game` int(20) unsigned NOT NULL default '0',
	  `day` tinyint(5) unsigned NOT NULL default '0',
	  `voter` int(20) unsigned NOT NULL default '0',
	  `candidacy` int(20) unsigned NOT NULL default '0',
	  KEY `headnum` (`game`)
	) TYPE=MyISAM;";
    
    @mysql_query($seervote_schema, $connect) or Error("신규 테이블 seervote 만들기 실패", "");;

    
    $rule_add_data = 
	"INSERT INTO `zetyx_board_werewolf_subrule` (`no`, `name`) VALUES (5, '점대상 공공화');";

	@mysql_query($rule_add_data, $connect) or Error("서브룰 공공점", "");
	
	$mustkill =
	"Update `zetyx_board_werewolf_truecharacter` set character = '흉포한 인랑' where character = '잔혹한 인랑' ;";

	@mysql_query($mustkill, $connect) or Error("잔혹한 인랑 이름 변경", "");
	
	mysql_close($connect);
?>