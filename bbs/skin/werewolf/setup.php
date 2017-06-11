<?include "lib/lib.php";

	require_once("config/admin_setup.php");
	require_once("config/server_setup.php");
	require_once("config/path_setup.php");

	require_once("class/DB.php");
	require_once("class/CheckOverlapIdByIp.php");

	require_once("class/SessionID.php");
	$SessionID= new SessionID();

	$GLOBALS['Database'] = new DB($id);
	$db= new DB($id);
	
?>
<!-- HTML 시작 -->
<!-- 게시판 처음에 글수 페이지수 접속현황 로그인 회원가입 표시 -->
<?if($setup[use_category]){
	echo "<div align='right' class='text'>";
	include "include/print_category.php"; 
	echo "</div>";
}?>

<div align='right'>
	<?=$a_member_join?>&nbsp;join</a>
	<?=$memo_on_sound?><?=$member_memo_icon?><?=$a_member_memo?>&nbsp;memo</a>
	<?=$a_member_modify?>&nbsp;modify</a>
	<?=$a_login?>login</a>
	<?=$a_logout?>&nbsp;logout</a>
	<?=$a_setup?>&nbsp;admin</a>
</div>