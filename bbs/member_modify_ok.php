<?
// 라이브러리 함수 파일 인크루드
	include "lib.php";

	if(getenv("REQUEST_METHOD") == 'GET' ) Error("정상적으로 글을 쓰시기 바랍니다","");

// DB 연결
	if(!$connect) $connect=dbConn();

// 멤버 정보 구해오기;;; 멤버가 있을때
	$member=member_info();
	if(!$member[no]) Error("회원정보가 존재하지 않습니다");
	$group=group_info($member[group_no]);

	$name = str_replace("ㅤ","",$name);

	if(isblank($name)) Error("이름을 입력하셔야 합니다");
	if(eregi("<",$name)||eregi(">",$name)) Error("이름에는 태그를 사용하실수 없습니다.");
	if($password&&$password1&&$password!=$password1) Error("비밀번호가 일치하지 않습니다");
	$birth=mktime(0,0,0,$birth_2,$birth_3,$birth_1);

	if($email <> $member['email']){
		$check=mysql_fetch_array(mysql_query("select count(*) from $member_table where email='$email' and no <> ".$member[no],$connect));
		if($check[0]>0) Error("이미 등록되어 있는 E-Mail입니다");
	}

	if($name <> $member['name']){
		$check=mysql_fetch_array(mysql_query("select count(*) from $member_table where name='$name'",$connect));
		if($check[0]>0) Error("이미 등록되어 있는 Name입니다","");
	}

	$name = addslashes(del_html($name));
	$job = addslashes(del_html($job));
	$email = addslashes(del_html($email));
	if($_zbDefaultSetup[check_email]=="true"&&!mail_mx_check($email)) Error("입력하신 $email 은 존재하지 않는 메일주소입니다.<br>다시 한번 확인하여 주시기 바랍니다.");
	if(!eregi("http://",$homepage)&&$homepage) $homepage="http://$homepage";
	$homepage = addslashes(del_html($homepage));
	$birth = addslashes(del_html($birth));
	$hobby = addslashes(del_html($hobby));
	$icq = addslashes(del_html($icq));
	$msn = addslashes(del_html($msn));
	$home_address = addslashes(del_html($home_address));
	$home_tel = addslashes(del_html($home_tel));
	$office_address = addslashes(del_html($office_address));
	$office_tel = addslashes(del_html($office_tel));
	$handphone = addslashes(del_html($handphone));
	$comment = addslashes(del_html($comment));

	$que="update $member_table set name='$name'";
	if($password&&$password1&&$password==$password) $que.=" ,password=password('$password') ";
	if($birth_1&&$birth_2&&birth_3&&$group[use_birth]) $que.=",birth='$birth'";
	if($email) $que.=",email='$email'";
	$que.=",homepage='$homepage'";
	if($group[use_job]) $que.=",job='$job'";
	if($group[use_hobby]) $que.=",hobby='$hobby'";
	if($group[use_icq]) $que.=",icq='$icq'";
	if($group[use_aol]) $que.=",aol='$aol'";
	if($group[use_msn]) $que.=",msn='$msn'";
	if($group[use_home_address]) $que.=",home_address='$home_address'";
	if($group[use_home_tel]) $que.=",home_tel='$home_tel'";
	if($group[use_office_address]) $que.=",office_address='$office_address'";
	if($group[use_office_tel]) $que.=",office_tel='$office_tel'";
	if($group[use_handphone]) $que.=",handphone='$handphone'";
	if($group[use_mailing]) $que.=",mailing='$mailing'";
	$que.=",openinfo='$openinfo'";
	if($group[use_comment]) $que.=",comment='$comment'";
	$que.=",openinfo='$openinfo',open_email='$open_email',open_homepage='$open_homepage',open_icq='$open_icq',open_msn='$open_msn',open_comment='$open_comment',open_job='$open_job',open_hobby='$open_hobby',open_home_address='$open_home_address',open_home_tel='$open_home_tel',open_office_address='$open_office_address',open_office_tel='$open_office_tel',open_handphone='$open_handphone',open_birth='$open_birth',open_picture='$open_picture',open_aol='$open_aol' ";
	$que.=" where no='$member[no]'";

	@mysql_query($que) or Error("회원정보 수정시에 에러가 발생하였습니다 ".mysql_error());

	if($del_picture) {
		@mysql_query("update $member_table set picture='' where no='$member[no]'") or Error("사진 자료 업로드시 에러가 발생하였습니다");
	}

    if($HTTP_POST_FILES[picture]) {
        $picture = $HTTP_POST_FILES[picture][tmp_name];
        $picture_name = $HTTP_POST_FILES[picture][name];
        $picture_type = $HTTP_POST_FILES[picture][type];
        $picture_size = $HTTP_POST_FILES[picture][size];
    }

	if($picture_name) {
		if(!is_uploaded_file($picture)) Error("정상적인 방법으로 업로드 해주세요");
		if(!eregi(".gif\$",$picture_name)&&!eregi(".jpg\$",$picture_name)) Error("사진은 gif 또는 jpg 파일을 올려주세요");
		$size=GetImageSize($picture);
		if($size[0]>200||$size[1]>200) Error("사진의 크기는 200*200이하여야 합니다");
		$kind=array("","gif","jpg");
		$n=$size[2];
		$path="icon/member_".time().".".$kind[$n];
		if(!move_uploaded_file($picture,$path)) Error("사진 업로드가 제대로 되지 않았습니다");
		@mysql_query("update $member_table set picture='$path' where no='$member[no]'") or Error("사진 자료 업로드시 에러가 발생하였습니다");
	}

	mysql_close($connect);
?>
<script>
alert("회원님의 정보 수정이 제대로 처리되었습니다.");
opener.window.history.go(0);
window.close();
</script>
