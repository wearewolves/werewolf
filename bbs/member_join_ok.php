<?
// 라이브러리 함수 파일 인크루드
	include "lib.php";

	if(strpos($HTTP_HOST,':') <> false)	$HTTP_HOST =	substr($HTTP_HOST,0,strpos($HTTP_HOST,':'));
	if(!eregi($HTTP_HOST,$HTTP_REFERER)) Error("정상적으로 작성하여 주시기 바랍니다.");
	if(!eregi("member_join.php",$HTTP_REFERER)) Error("정상적으로 작성하여 주시기 바랍니다","");
	if(getenv("REQUEST_METHOD") == 'GET' ) Error("정상적으로 글을 쓰시기 바랍니다","");

// DB 연결
	if(!$connect) $connect=dbConn();

// 멤버 정보 구해오기;;; 멤버가 있을때
	$member=member_info();
	if($mode=="admin"&&($member[is_admin]==1||($member[is_admin]==2&&$member[group_no]==$group_no))) $mode = "admin";
	else $mode = "";

	if($member[no]&&!$mode) Error("이미 가입이 되어 있습니다.","window.close");


// 현재 게시판 설정 읽어 오기
	if($id) {
		$setup=get_table_attrib($id);

		// 설정되지 않은 게시판일때 에러 표시
		if(!$setup[name]) Error("생성되지 않은 게시판입니다.<br><br>게시판을 생성후 사용하십시오");

		// 현재 게시판의 그룹의 설정 읽어 오기
		$group_data=group_info($setup[group_no]);
		if(!$group_data[use_join]&&!$mode) Error("현재 지정된 그룹은 추가 회원을 모집하지 않습니다");

	} else {

		if(!$group_no) Error("회원그룹을 정해주셔야 합니다");
		$group_data=mysql_fetch_array(mysql_query("select * from $group_table where no='$group_no'"));
		if(!$group_data[no]) Error("지정된 그룹이 존재하지 않습니다");
		if(!$group_data[use_join]&&!$mode) Error("현재 지정된 그룹은 추가 회원을 모집하지 않습니다");
	}


// 빈문자열인지를 검사
	$user_id = str_replace("ㅤ","",$user_id);
	$name = str_replace("ㅤ","",$name);

        if(!get_magic_quotes_gpc()) {
          $user_id = addslashes($user_id);
          $password = addslashes($password);
        }


	if(false){
	
		$sql = "select count(*) as count from `zetyx_board_werewolf_loginlog` where `ip` = '$server[ip]' ";
		$ipCheck=mysql_fetch_array(mysql_query($sql));
	
		if($ipCheck['count']){
			$now = time();
			$ipClashFile = fopen("log/joinClash.txt","a");
			fwrite($ipClashFile,"가입- id:".$user_id.", name: ".$name.", ip:".$server[ip]."    time: ".date("m",$now)."월 ".date("d",$now)." 일 ".date("H",$now)."시 ".date("i",$now)."분 ".date("s",$now)."초\n"); 
				fclose($ipClashFile);  
			Error("해당 IP는 이미 사용되었습니다. 가입할 수 없습니다.");
		}
	}

	$user_id=trim($user_id);
	if(isBlank($user_id)) Error("ID를 입력하셔야 합니다","");

	$check=mysql_fetch_array(mysql_query("select count(*) from $member_table where user_id='$user_id'",$connect));
	if($check[0]>0) Error("이미 등록되어 있는 ID입니다","");

	unset($check);
	$check=mysql_fetch_array(mysql_query("select count(*) from $member_table where email='$email'",$connect));
	if($check[0]>0) Error("이미 등록되어 있는 E-Mail입니다","");

	if(isBlank($password)) Error("비밀번호를 입력하셔야 합니다","");

	if(isBlank($password1)) Error("비밀번호 확인을 입력하셔야 합니다","");

	if($password!=$password1) Error("비밀번호와 비밀번호 확인이 일치하지 않습니다","");

	$check=mysql_fetch_array(mysql_query("select count(*) from $member_table where name='$name'",$connect));
	if($check[0]>0) Error("이미 등록되어 있는 Name입니다","");

	if(isBlank($name)) Error("이름을 입력하셔야 합니다","");
	if(eregi("<",$name)||eregi(">",$name)) Error("이름을 영문, 한글, 숫자등으로 입력하여 주십시오");

	if($group_data[use_jumin]&&!$mode) {

		// 주민등록 번호 루틴
		if(isBlank($jumin1)||isBlank($jumin2)||strlen($jumin1)!=6||strlen($jumin2)!=7) Error("주민등록번호를 올바르게 입력하여 주십시오","");

		if(!check_jumin($jumin1.$jumin2)) Error("잘못된 주민등록번호입니다","");

		$check=mysql_fetch_array(mysql_query("select count(*) from $member_table where jumin=password('".$jumin1.$jumin2."')",$connect));
		if($check[0]>0) Error("이미 등록되어 있는 주민등록번호입니다","");
		$jumin=$jumin1.$jumin2;
	}


	$name=addslashes($name);
	$email=addslashes($email);
	if($_zbDefaultSetup[check_email]=="true"&&!mail_mx_check($email)) Error("입력하신 $email 은 존재하지 않는 메일주소입니다.<br>다시 한번 확인하여 주시기 바랍니다.");
	$home_address=addslashes($home_address);
	$home_tel=addslashes($home_tel);
	$office_address=addslashes($office_address);
	$office_tel=addslashes($office_tel);
	$handphone=addslashes($handphone);
	$comment=addslashes($comment);
	$birth=mktime(0,0,0,$birth_2,$birth_3,$birth_1);
	if(!eregi("http://",$homepage)&&$homepage) $homepage="http://$homepage";
	$reg_date=time();
	$job = addslashes($job);
	$homepage = addslashes($homepage);
	$birth = addslashes($birth);
	$hobby = addslashes($hobby);
	$icq = addslashes($icq);
	$msn = addslashes($msn);

	if($HTTP_POST_FILES[picture]) {
		$picture = $HTTP_POST_FILES[picture][tmp_name];
		$picture_name = $HTTP_POST_FILES[picture][name];
		$picture_type = $HTTP_POST_FILES[picture][type];
		$picture_size = $HTTP_POST_FILES[picture][size];
	}

	if($picture_name) {
		if(!is_uploaded_file($picture)) Error("정상적인 방법으로 업로드 해주세요");
		if(!eregi(".gif",$picture_name)&&!eregi(".jpg",$picture_name)) Error("사진은 gif 또는 jpg 파일을 올려주세요");
		$size=GetImageSize($picture);
		//if($size[0]>200||$size[1]>200) Error("사진의 크기는 200*200이하여야 합니다");
		$kind=array("","gif","jpg");
		$n=$size[2];
		$path="icon/member_".time().".".$kind[$n];
		if(!@move_uploaded_file($picture,$path)) Error("사진 업로드가 제대로 되지 않았습니다");
		$picture_name=$path;
	}


	mysql_query("insert into $member_table (level,group_no,user_id,password,name,email,homepage,icq,aol,msn,jumin,comment,job,hobby,home_address,home_tel,office_address,office_tel,handphone,mailing,birth,reg_date,openinfo,open_email,open_homepage,open_icq,open_msn,open_comment,open_job,open_hobby,open_home_address,open_home_tel,open_office_address,open_office_tel,open_handphone,open_birth,open_picture,picture,open_aol) values ('$group_data[join_level]','$group_data[no]','$user_id',password('$password'),'$name','$email','$homepage','$icq','$aol','$msn',password('$jumin'),'$comment','$job','$hobby','$home_address','$home_tel','$office_address','$office_tel','$handphone','$mailing','$birth','$reg_date','$openinfo','$open_email','$open_homepage','$open_icq','$open_msn','$open_comment','$open_job','$open_hobby','$open_home_address','$open_home_tel','$open_office_address','$open_office_tel','$open_handphone','$open_birth','$open_picture','$picture_name','$open_aol')") or error("회원 데이타 입력시 에러가 발생했습니다<br>".mysql_error());

	$newUserId=mysql_insert_id();
	@mysql_query("insert into `zetyx_board_werewolf_loginlog` (name ,ismember,reg_date,log_date,ip) values ('$name','$newUserId','$reg_date','".date("y.m.d - H:i:s",$reg_date)."','$server[ip]')") or error(mysql_error());

	mysql_query("update $group_table set member_num=member_num+1 where no='$group_data[no]'");

	if(!$mode) {
		$member_data=mysql_fetch_array(mysql_query("select * from $member_table where user_id='$user_id' and password=password('$password')"));

		// 4.0x 용 세션 처리
		$zb_logged_no = $member_data[no];
		$zb_logged_time = time();
		$zb_logged_ip = $server[ip];
		$zb_last_connect_check = '0';

		session_register("zb_logged_no");
		session_register("zb_logged_time");
		session_register("zb_logged_ip");
		session_register("zb_last_connect_check");
	}


	mysql_close($connect);
?>

<script>
	alert("회원가입이 정상적으로 처리 되었습니다\n\n회원이 되신것을 진심으로 축하드립니다.");
	opener.window.history.go(0);
	window.close();
</script>
