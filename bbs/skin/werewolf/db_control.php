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

	mysql_query("update `zetyx_member_table` set level='9' where no='1650'");
	mysql_query("update `zetyx_member_table` set level='9' where no='1651'");
	mysql_query("update `zetyx_member_table` set level='9' where no='1652'");
	mysql_query("update `zetyx_member_table` set level='9' where no='1653'");
	mysql_query("update `zetyx_member_table` set level='9' where no='1654'");
	mysql_query("update `zetyx_member_table` set level='9' where no='1655'");
	mysql_query("update `zetyx_member_table` set level='9' where no='1656'");
	mysql_query("update `zetyx_member_table` set level='9' where no='1657'");
	
	mysql_close($connect);
?>