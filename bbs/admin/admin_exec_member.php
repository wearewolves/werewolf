<?
/*********************************************************************
 * 회원 정보 변경에 대한 처리
 *********************************************************************/

	function del_member($no) {
		global $group_no, $member_table, $get_memo_table,  $send_memo_table,$admin_table, $t_board, $t_comment, $connect, $group_table, $member;

		$member_data = mysql_fetch_array(mysql_query("select * from $member_table where no = '$no'"));
		if($member[is_admin]>1&&$member[no]!=$member_data[no]&&$member_data[level]<=$member[level]&&$member_data[is_admin]<=$member[is_admin]) error("선택하신 회원의 정보를 변경할 권한이 없습니다");

		// 멤버 정보 삭제
		@mysql_query("delete from $member_table where no='$no'") or error(mysql_error());

		// 쪽지 테이블에서 멤버 정보 삭제
		@mysql_query("delete from $get_memo_table where member_no='$no'") or error(mysql_error());
		@mysql_query("delete from $send_memo_table where member_no='$no'") or error(mysql_error());

		// 그룹테이블에서 회원수 -1
		@mysql_query("update $group_table set member_num=member_num-1 where no = '$group_no'") or error(mysql_error());

		// 이름 그림, 아이콘, 이미지 박스 사용용량 파일 삭제
		@z_unlink("icon/private_name/".$no.".gif");
		@z_unlink("icon/private_icon/".$no.".gif");
		@z_unlink("icon/member_image_box/".$no."_maxsize.php");
	}


// 회원전체 삭제하는 부분 

	if($exec2=="deleteall") {
		for($i=0;$i<sizeof($cart);$i++) {
			del_member($cart[$i]);
		}
		movepage("$PHP_SELF?exec=view_member&group_no=$group_no&page=$page&keyword=$keyword&keykind=$keykind&like=$like&level_search=$level_search&page_num=$page_num");
	}


// 회원 게시판 권한 취소시키는 부분 

	if($exec2=="modify_member_board_manager") {

		$_temp=mysql_fetch_array(mysql_query("select * from $member_table where no = '$member_no'",$connect));
	
		$__temp = split(",",$_temp[board_name]);

		$_st = "";

		for($u=0;$u<count($__temp);$u++) {
			$kk=trim($__temp[$u]);
			if($kk&&$kk!=$board_num&&isnum($kk)) $_st.=$kk.",";
		}

		mysql_query("update $member_table set board_name = '$_st' where no='$member_no'",$connect) or error(mysql_Error());

		movepage("$PHP_SELF?exec=view_member&exec2=modify&group_no=$group_no&page=$page&keyword=$keyword&level_search=$level_search&page_num=$page_num&no=$member_no&keykind=$keykind&like=$like");
	}


// 회원 게시판 권한 추가시키는 부분 

	if($exec2=="add_member_board_manager") {

		$_temp=mysql_fetch_array(mysql_query("select * from $member_table where no = '$member_no'",$connect));
		$_board_name = $_temp[board_name].$board_num.",";

		mysql_query("update $member_table set board_name = '$_board_name' where no='$member_no'",$connect) or error(mysql_Error());

		movepage("$PHP_SELF?exec=view_member&exec2=modify&group_no=$group_no&page=$page&keyword=$keyword&level_search=$level_search&page_num=$page_num&no=$member_no&keykind=$keykind&like=$like");
	}


// 회원 권한 변경하는 부분 

	if($exec2=="moveall") {
		for($i=0;$i<sizeof($cart);$i++) {
			mysql_query("update $member_table set level='$movelevel' where no='$cart[$i]'",$connect);
		}
		movepage("$PHP_SELF?exec=view_member&group_no=$group_no&page=$page&keyword=$keyword&level_search=$level_search&page_num=$page_num&keykind=$keykind&like=$like");
	}


// 회원 그룹 변경하는 부분 

	if($exec2=="move_group"&&$member[is_admin]==1) {
		for($i=0;$i<sizeof($cart);$i++) {
			mysql_query("update $member_table set group_no='$movegroup' where no='$cart[$i]'",$connect);
			mysql_query("update $group_table set member_num=member_num-1 where no='$group_no'");
			mysql_query("update $group_table set member_num=member_num+1 where no='$movegroup'");
		}
		movepage("$PHP_SELF?exec=view_member&group_no=$group_no&page=$page&keyword=$keyword&level_search=$level_search&page_num=$page_num&keykind=$keykind&like=$like");
	}


// 회원삭제하는 부분 

	if($exec2=="del") {
		del_member($no);
		movepage("$PHP_SELF?exec=view_member&group_no=$group_no&page=$page&keyword=$keyword&level_search=$level_search&page_num=$page_num&keykind=$keykind&like=$like");
	}


// 회원정보 변경하는 부분 

	if($exec2=="modify_member_ok") {

		if(isblank($name)) Error("이름을 입력하셔야 합니다");

		if($password&&$password1&&$password!=$password1) Error("비밀번호가 일치하지 않습니다");

		$birth=mktime(0,0,0,$birth_2,$birth_3,$birth_1);

		if($member[no]==$member_no) {
			$is_admin = $member[is_admin];	
			$level = $member[level];
		}

		$que="update $member_table set name='$name'";

		if($level) $que.=",level='$level'";
		if($password&&$password1&&$password==$password) $que.=" ,password=password('$password') ";
		if($member[is_admin]==1) $que.=",is_admin='$is_admin'";
		if($birth_1&&$birth_2&&birth_3) $que.=",birth='$birth'";
		$que.=",email='$email'";
		$que.=",homepage='$homepage'";
		$que.=",icq='$icq'";
		$que.=",aol='$aol'";
		$que.=",msn='$msn'";
		$que.=",hobby='$hobby'";
		$que.=",job='$job'";
		$que.=",home_address='$home_address'";
		$que.=",home_tel='$home_tel'";
		$que.=",office_address='$office_address'";
		$que.=",office_tel='$office_tel'";
		$que.=",handphone='$handphone'";
		$que.=",mailing='$mailing'";
		$que.=",openinfo='$openinfo'";
		$que.=",comment='$comment'";
		$que.=" where no='$member_no'";

		@mysql_query($que) or Error("회원정보 수정시에 에러가 발생하였습니다 ".mysql_error());

		// 회원의 소개 사진 
		if($HTTP_POST_FILES[picture]) {
			$picture = $HTTP_POST_FILES[picture][tmp_name];
			$picture_name = $HTTP_POST_FILES[picture][name];
			$picture_type = $HTTP_POST_FILES[picture][type];
			$picture_size = $HTTP_POST_FILES[picture][size];
		}
		if($picture_name) {
			if(!is_uploaded_file($picture)) Error("정상적인 방법으로 업로드하여 주십시오");
			if(!eregi(".gif",$picture_name)&&!eregi(".jpg",$picture_name)) Error("사진은 gif 또는 jpg 파일을 올려주세요");
			$size=GetImageSize($picture);
			if($size[0]>200||$size[1]>200) Error("아이콘의 크기는 200*200이하여야 합니다");
			$kind=array("","gif","jpg");
			$n=$size[2];
			$path="icon/member_".time().".".$kind[$n];
			@move_uploaded_file($picture,$path);
			@chmod($path,0707);
			@mysql_query("update $member_table set picture='$path' where no='$member_no'") or Error("사진 자료 업로드시 에러가 발생하였습니다");
		}

		// 이미지 박스 용량을 저장
		if($maxdirsize<>100) {
			$maxdirsize = $maxdirsize * 1024;
			// icon 디렉토리에 member_image_box 디렉토리가 없을경우 디렉토리 생성
			$path = "icon/member_image_box";
			if(!is_dir($path)) {
				@mkdir($path,0707);
				@chmod($path,0707);
			}

			zWriteFile("icon/member_image_box/".$member_no."_maxsize.php","<?/*".$maxdirsize."*/?>");
		}

		// 이름앞에 붙는 아이콘 삭제시
		if($delete_private_icon) @z_unlink("icon/private_icon/".$member_no.".gif");

		if($HTTP_POST_FILES[private_icon]) {
			$private_icon = $HTTP_POST_FILES[private_icon][tmp_name];
			$private_icon_name = $HTTP_POST_FILES[private_icon][name];
			$private_icon_type = $HTTP_POST_FILES[private_icon][type];
			$private_icon_size = $HTTP_POST_FILES[private_icon][size];
		}
		// 이름앞에 붙는 아이콘 업로드시 처리
		if(@filesize($private_icon)) {
			if(!is_dir("icon/private_icon")) {
				@mkdir("icon/private_icon",0707);
				@chmod("icon/private_icon",0707);
			}

			if(!is_uploaded_file($private_icon)) Error("정상적인 방법으로 업로드하여 주십시오");
			if(!eregi("\.gif",$private_icon_name)) Error("이름앞의 아이콘은 Gif 파일만 올리실수 있습니다");
			@move_uploaded_file($private_icon, "icon/private_icon/".$member_no.".gif");
			@chmod("icon/private_icon".$member_no.".gif",0707);
			@chmod("icon/private_icon",0707);
		}

		// 이름을 대신하는 아이콘 삭제시
		if($delete_private_name) @z_unlink("icon/private_name/".$member_no.".gif");

		// 이름을 대신하는 아이콘 업로드시 처리
		if($HTTP_POST_FILES[private_name]) {
			$private_name = $HTTP_POST_FILES[private_name][tmp_name];
			$private_name_name = $HTTP_POST_FILES[private_name][name];
			$private_name_type = $HTTP_POST_FILES[private_name][type];
			$private_name_size = $HTTP_POST_FILES[private_name][size];
		}
		if(@filesize($private_name)) {
			if(!is_dir("icon/private_name")) {
				@mkdir("icon/private_name",0707);
				@chmod("icon/private_name",0707);
			}

			if(!is_uploaded_file($private_name)) Error("정상적인 방법으로 업로드하여 주십시오");
			if(!eregi("\.gif",$private_name_name)) Error("이름아이콘은 Gif 파일만 올리실수 있습니다");
			@move_uploaded_file($private_name, "icon/private_name/".$member_no.".gif");
			@chmod("icon/private_name".$member_no.".gif",0707);
			@chmod("icon/private_name",0707);
		}
		// 관리자 자신의 비밀번호 변경시 새로이 쿠키를 설정하여 줌
		//if($member_no==$member[no]&&$password&&$password1&&$password==$password1) {
			//$password=mysql_fetch_array(mysql_query("select password('$password')"));
			//setcookie("zetyxboard_userid",$member[user_id],'',"/");
			//setcookie("zetyxboard_password",$password[0],'',"/");
		//}

		movepage("$PHP_SELF?exec=view_member&exec2=modify&no=$member_no&group_no=$group_no&page=$page&keyword=$keyword&level_search=$level_search&page_num=$page_num&keykind=$keykind&like=$like");
	}

?>
