<?
	// register_globals가 off일 때를 위해 변수 재정의
	@extract($HTTP_SERVER_VARS);
	@extract($HTTP_ENV_VARS);

	// 제로보드 라이브러리 가져옴
	$_zb_path = realpath("../../")."/";
	include $_zb_path."lib.php";

	// DB 연결정보 가져옴
    $connect = dbConn();
    
    #$gameinfo_add_mustkill = 
    #"ALTER TABLE `zetyx_board_werewolf_truecharacter` DROP `mustkill`";
    #"ALTER TABLE `zetyx_board_werewolf_truecharacter` ADD `mustkill` int(1) unsigned NOT NULL DEFAULT 0;";
    #@mysql_query($gameinfo_add_mustkill, $connect) or Error("신규 컬럼 삽입 실패", "");

    // 칼럼 추가
	#$insert_data = 
    #"INSERT INTO `zetyx_board_werewolf_truecharacter` 
    #(`no`, `race`, `wintype`, `character`, `secretchat`, `forecast`, 
    #`mediumism`, `assault`, `guard`, `telepathy`, `detect`, `revenge`, `half-assault`, 
    #`secretletter`, `double-vote`, `forecast-odd`, `assault-con`, `mustkill`) VALUES
    #(18, 1, 1, '잔혹한 인랑', 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
    #(19, 0, 0, '은거 귀족', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);";
	
    #@mysql_query($insert_data, $connect) or Error("신규 테이블 삽입 실패", "");

    // 데이터 확인
    #$mustkill_load =
    #"select * from `zetyx_board_werewolf_truecharacter`;";
	
	#$result = mysql_query($mustkill_load, $connect);

	#while($temp = mysql_fetch_array($result)) {
    #    echo $temp[no]." :: ".$temp['forecast-odd']." :: ".$temp['assault-con']." :: ".$temp['mustkill']."<br>";
    #}

    // 테이블 추가
	#$mustkill_schema = 
	#"CREATE TABLE `zetyx_board_werewolf_mustkill` (
	#`game` int(20) unsigned NOT NULL DEFAULT 0,
	#`day` tinyint(5) unsigned NOT NULL DEFAULT 0,
	#`target` int(20) unsigned NOT NULL DEFAULT 0
    #) ENGINE=MyISAM;";
    
    #@mysql_query($mustkill_schema, $connect) or Error("신규 테이블 mustkill 만들기 실패", "");;


    // 데이터 확인
	#$mustkill_schema_load = 
	#"select * from `zetyx_board_werewolf_mustkill`";
	
	#$result2 = mysql_query($mustkill_schema_load, $connect);
	#while($temp = mysql_fetch_array($result2)) {
	#	echo $temp[game]." :: ".$temp[day]." :: ".$temp[target]."<br>";
    #}
    
    #$rule_add_data = 
	#"INSERT INTO `zetyx_board_werewolf_rule` (`no`, `name`, `min_player`, `max_player`) VALUES (6, '참살', 11, 16);";

	#@mysql_query($rule_add_data, $connect) or Error("참살룰 데이터 삽입 실패", "");
	
	mysql_close($connect);
?>