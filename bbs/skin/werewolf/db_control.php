<?
	// register_globals�� off�� ���� ���� ���� ������
	@extract($HTTP_GET_VARS); 
	@extract($HTTP_POST_VARS); 
	@extract($HTTP_SERVER_VARS);
	@extract($HTTP_ENV_VARS);

	// ���κ��� ���̺귯�� ������
	$_zb_path = realpath("../../")."/";
	include $_zb_path."lib.php";

	// DB �������� ������
	$connect = dbConn();
	
	// Į�� �߰�
	$gameinfo_add_subrule = 
	"ALTER TABLE `zetyx_board_werewolf_gameinfo` ADD `subRule` INT(20) NOT NULL DEFAULT '0' AFTER `rule`;";
	$gameinfo_add_delay = 
	"ALTER TABLE `zetyx_board_werewolf_gameinfo` ADD `delay` MEDIUMINT(13) NOT NULL DEFAULT '0';";
	
	//@mysql_query($gameinfo_add_subrule, $connect) or Error("subRule Į�� �߰� ����", "");
	@mysql_query($gameinfo_add_subrule, $connect) or Error("delay Į�� �߰� ����", "");
	
	// Į�� Ȯ��
	$gameinfo_show_subrule = 
	"SHOW COLUMNS FROM `zetyx_board_werewolf_gameinfo` LIKE `subRule`;";
	$gameinfo_show_delay = 
	"SHOW COLUMNS FROM `zetyx_board_werewolf_gameinfo` LIKE `delay`;";
	
	$result1 = mysql_query($gameinfo_show_subrule, $connect);
	$result2 = mysql_query($gameinfo_show_delay, $connect);
	
	if($result1) echo "subRule Į�� �߰� ����<br>";
	if($result2) echo "delay Į�� �߰� ����<br>";
	
	mysql_close($connect);
?>