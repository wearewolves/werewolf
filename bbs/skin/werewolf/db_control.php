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
	$gameinfo_add_subrule = 
	"ALTER TABLE `zetyx_board_werewolf_gameinfo` ADD `subRule` INT(20) NOT NULL DEFAULT '0' AFTER `rule`;";
	$gameinfo_add_delay = 
	"ALTER TABLE `zetyx_board_werewolf_gameinfo` ADD `delay` MEDIUMINT(13) NOT NULL DEFAULT '0';";
	
	@mysql_query($gameinfo_add_subrule, $connect) or Error("subRule 칼럼 추가 실패", "");
	@mysql_query($gameinfo_add_delay, $connect) or Error("delay 칼럼 추가 실패", "");
	
	
	// subrule 테이블 생성
	$subrule_schema = 
	"CREATE TABLE `zetyx_board_werewolf_subrule` (
	`no` int(20) unsigned NOT NULL auto_increment,
	`name` varchar(20) default NULL,
	PRIMARY KEY  (`no`)
	) ENGINE=MyISAM;";
	$subrule_data = 
	"INSERT INTO `zetyx_board_werewolf_subrule` (`name`) VALUES 
	('인랑 습격 가능'),
	('NPC 직업 랜덤 부여'),
	('텔레파시 사용 불가'),
	('비밀 투표');";
	
	@mysql_query($subrule_schema, $connect) or Error("subrule 테이블 생성 실패", "");
	@mysql_query($subrule_data, $connect) or Error("subrule 데이터 삽입 실패", "");
	
	
	mysql_close($connect);
?>