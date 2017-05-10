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
	
	$result = mysql_query("select * from `zetyx_board_werewolf_subrule`");
	while($temp = mysql_fetch_array($result)) {
		echo $temp[no]." / ".$temp[name]."<br>";
	}

	mysql_close($connect);
?>