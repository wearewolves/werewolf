<?
// register_globals가 off일때를 위해 변수 재 정의
	@extract($HTTP_GET_VARS); 
	@extract($HTTP_POST_VARS); 
	@extract($HTTP_SERVER_VARS); 
	@extract($HTTP_ENV_VARS);

// 제로보드 라이브러리 가져옴
	$_zb_path = realpath("../../")."/";
	include $_zb_path."lib.php";

// DB 연결정보와 회원정보 가져옴
	$connect = dbConn();
	$member  = member_info();

// 게시판 설정을 가져옴
//error($_zb_path);
	$setup=get_table_attrib($id);
	if(!$setup[no]) error("존제하지 않는 게시판 입니다.","window.close");

	include "../../../Werewolf/head.htm";

	if($member[no] <> 1) exit();
?>
<link rel="stylesheet" href="css/table.werewolfStyle.css?ver=<?php echo filemtime('css/table.werewolfStyle.css'); ?>" type="text/css" />
<?


	require_once("class/Player.php");
	require_once("class/DB.php");
	require_once("lib/lib.php");

	$db= new DB($id);
	$player= new Player($db,$no);
	
	echo $player->playCount()."<br>";
	echo $player->bugCount()."<br>";

?>
<?	include "../../../Werewolf/foot.htm";?>