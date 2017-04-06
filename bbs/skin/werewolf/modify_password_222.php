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

	require_once("class/DB.php");
	$db= new DB($id);
?>

<style type="text/css">
textarea{
	border:solid 1;
	border-color:151515;
	background:151515;
	width:100%;
	height:100;
}
input {
	border:solid 0;border-color:ffffff;
	background:151515;
}


table{
	border-collapse:collapse;
	width:100%;
	font-size:11px;
	color:#666;
	margin:25px 0px;
}
table td{
	padding:4px 1px;
}
table thead{
	background:#222;
	text-align:center;
}
table thead td{
	border:1px solid #151515;

}
table tbody{
/*	background:#555;*/
	text-align:left;
}
table  tbody td{
	border-bottom:1px solid #151515;
}
.sidebar{
	border-left:1px solid #151515;
}


</style>


<?


function DB_array($key,$value,$db){
	$temp_result=mysql_query("select * from $db ");

	while($temp_member=@mysql_fetch_array($temp_result)){
			$members[$temp_member[$key]]=$temp_member[$value];
	}

	return $members;
}


function DBselect($name,$head,$id,$value,$DB,$code,$selectedID,$unselectedID){
	$result=mysql_query("select * from $DB order by '$id'");

	if(!is_array($unselectedID)){
		$unselectedID = array($unselectedID);
	}

		
	$DB_select="&nbsp;<select $code name=$name>$head";
	while($temp=mysql_fetch_array($result)) {
		if(!in_array ($temp[$id], $unselectedID)){
			if($temp[$id]==$selectedID)$selected="selected";
			else $selected="";
		
			$DB_select.="<option value=$temp[$id] ".$selected." >". $value[$temp[$id]]."</option>";
		}
	}
	$DB_select.="</select> ";
	return $DB_select;
}


	// 플레이 횟수 체크 10회 이상 게임을 플레이해야 롤 플레잉 세트 제작 가능
	require_once("class/Player.php");
	require_once("class/DB.php");

	$db= new DB($id);
	$player= new Player($db,$member[no]);

	if($mode == "modify"){
		$user_info=@mysql_fetch_array(mysql_query("select *  from  `".$db->member."` where `user_id` = '$userID' "));
		
		if($user_info){
			if($user_info['email'] <> $userEMAIL){
				$mode = "error";
				$msg = "E - Mail이 정확하지 않습니다.";
			}
		}
		else {
			$mode = "error";
			$msg = "존재 하지 않는 ID입니다.";
		}
	}

	if($mode == "modify_ok"){
		$user_info=@mysql_fetch_array(mysql_query("select *  from  `".$db->member."` where `user_id` = '$userID' and `email` = '$userEMAIL' "));

		if($user_info){
			if($newPassword == $passwordConfirm){
				$sql="update $member_table set password=password('$newPassword') where `user_id` = '$userID' and `email` = '$userEMAIL' ";
				@mysql_query($sql);

				$mode = "error";
				$msg = "비밀 번호가 변경되었습니다.";

/*
				$tempPassword=substr(base64_encode(time()),1,10);

				$sql="update $member_table set password=password('$tempPassword') ";
				@mysql_query($sql);


				$name=stripslashes($user_info[name]);
				$to=$user_info[email];
				$subject="안녕하세요, 인랑 임시 비밀 번호입니다.";

				$comment="안녕하세요.\n"."$_sitename 입니다.\n"."$name 님의 회원 아이디와 새롭게 변경된 비밀번호입니다. \n확인후 곧 바로 $_sitename ($_homepage) 에 로그인 하셔서 비밀번호를 변경하여 주시기 바랍니다.\n\nID : $data[user_id]\nPassword : $tempPassword \n\n   위의 비밀번호를 타이핑하기 힘들때 마우스로 더블클릭한후 Ctrl-C 를 눌러서 복사한후,\n 비밀번호 입력칸에서 Ctrl-V를 눌러서 복사하세요.";

				if(!zb_sendmail(0, $to, $name, $_from, "", $subject, $comment)) Error("메일 발송 에러");

				$mode = "error";
				$msg = "임시 암호가 ".$user_info[email]."로 보내졌습니다. <br>로그인 후 변경해 주시기 바랍니다.";
*/
			}
			else{
				$mode = "error";
				$msg = "암호를 다시 입력해 주시기 바랍니다.";
			}
		}
		else {
			$mode = "error";
			$msg = "처음부터 시작해주세요.";
		}
		//movepage("view_role-playing_write.php?id=$id&mode=modify&set=$set");
	}


?>

<form method='post' name="password"  enctype="multipart/form-data" onsubmit="return checkForm(this)">
<input type='hidden' name='id' value=<?=$id?>>

<?if($mode ==""){?>
<input type='hidden' name='mode' value='modify'>
	<table>
		<thead>
			<tr><td>암호 바꾸기 - step 1 </td><td>아이디와 이메일 주소를 입력해주세요.<br>회원 정보와 일치해야 암호를 재 설정하는 페이지로 넘어갑니다.</td></tr>
		</thead>
		<tr><td>ID</td><td><input name='userID' size=30 MAXLENGTH=30 class='input'></td></tr>
		<tr><td>이메일 주소</td><td><input name='userEMAIL' size=30 MAXLENGTH=30 class='input'></td></tr>
	</table>

<?}?>

<?if($mode =="modify"){?>
<input type='hidden' name='mode' value='modify_ok'>
<input type='hidden' name='userID' value='<?=$userID?>'>
<input type='hidden' name='userEMAIL' value='<?=$userEMAIL?>'>

	<table>
		<thead>
			<tr><td>암호 바꾸기 - step 2</td><td>새로운 암호를 입력해주세요.</td></tr>
		</thead>
		<tr><td> 새로운 암호 입력</td><td><input name='newPassword' size=30 MAXLENGTH=30 class='input' type="password"></td></tr>
		<tr><td> 확인 </td><td><input name='passwordConfirm' size=30 MAXLENGTH=30 class='input' type="password"></td></tr>
	</table>

<?}?>

<?if($mode =="error"){?>
	<table>
		<thead>
			<tr><td>결과 </td></tr>
		</thead>
		<tr><td><?=$msg?></td></tr>
	</table>
<?}?>
	<input type='submit' value="[확인]" style="width:100%;height:50">

	
</form>
<?	include "../../../Werewolf/foot.htm";?>