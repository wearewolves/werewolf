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
	
	// 테이블 추가
	$subrule_schema = 
	"CREATE TABLE `zetyx_board_werewolf_subrule` (
	`no` int(20) unsigned NOT NULL auto_increment,
	`name` varchar(20) default NULL,
	PRIMARY KEY  (`no`)
	) ENGINE=MyISAM  DEFAULT CHARSET=euckr AUTO_INCREMENT=5 ;";
	
	@mysql_query($subrule_schema, $connect) or Error("subrule 테이블 생성 실패", "");
	
	// 데이터 삽입
	$subrule_data = 
	"INSERT INTO `zetyx_board_werewolf_subrule` (`no`, `name`) VALUES 
	(1, '인랑 습격 가능'),
	(2, 'NPC 직업 랜덤 부여'),
	(3, '텔레파시 사용 불가');";
	
	@mysql_query($subrule_data, $connect) or Error("subrule 데이터 삽입 실패", "");
	
	// 데이터 확인
	$subrule_load = 
	"select * from `zetyx_board_werewolf_subrule`";
	
	$result = mysql_query($subrule_load, $connect);
	while($temp = mysql_fetch_array($result)) {
		echo $temp[no]." :: ".$temp[name]."<br>";
	}
	
	mysql_close($connect);
?>