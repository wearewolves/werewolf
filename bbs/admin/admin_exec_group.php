<?
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


	// 그룹추가
	if($exec=="add_group_ok") {
		if($member[is_admin]>1) Error("그룹생성 권한이 없습니다");
		if(isblank($name)) Error("그룹이름은 필수로 지정하셔야 합니다");
		// 중복 이름 검사
		$check=mysql_fetch_array(mysql_query("select count(*) from $group_table where name='$name'"));
		if($check[0]) Error("$name 이라는 이름의 그룹이 이미 있습니다");

    	if($HTTP_POST_FILES[icon]) {
        	$icon = $HTTP_POST_FILES[icon][tmp_name];
        	$icon_name = $HTTP_POST_FILES[icon][name];
        	$icon_type = $HTTP_POST_FILES[icon][type];
        	$icon_size = $HTTP_POST_FILES[icon][size];
    	}

		// 아이콘 파일 업로드시
		if($icon_name) {
			if(!eregi(".gif",$icon_name)&&!eregi(".jpg",$icon_name)) Error("아이콘은 gif 또는 jpg 파일을 올려주세요");
			$size=GetImageSize($icon);
			if($size[0]>24||$size[1]>24) Error("아이콘의 크기는 24*24이하여야 합니다");
			$kind=array("","gif","jpg");
			$n=$size[2];
			@copy($icon,"icon/group_".$name.".".$kind[$n]);
			$icon_name="group_$name.".$kind[$n];
		}

		// 헤더푸터
		$header=addslashes($header);
		$header_url=addslashes($header_url);
		$footer=addslashes($footer);
		$footer_url=addslashes($footer_url);

		//DB에 입력
		@mysql_query("insert into $group_table (name,is_open,icon,use_join,join_return_url, use_icon, header,footer,header_url,footer_url)
						values ('$name','$is_open','$icon_name','$use_join','$join_return_url','$use_icon','$header','$footer','$header_url','$footer_url')") or Error("그룹생성 에러가 났습니다");
		$group_no=mysql_insert_id();
		movepage("$PHP_SELF?exec=view_group&group_no=$group_no");
	}
	// 그룹수정 완료
	elseif($exec=="modify_group_ok") {
		if($member[is_admin]>2) Error("그룹수정 권한이 없습니다");
		if($member[is_admin]==2&&$member[group_no]!=$group_no) Error("그룹수정 권한이 없습니다");
		if(isblank($name)) Error("그룹이름은 필수로 지정하셔야 합니다");
		if($del_icon) $icon_sql=",icon=''";
		// 아이콘 파일 업로드시
        if($HTTP_POST_FILES[icon]) {
            $icon = $HTTP_POST_FILES[icon][tmp_name];
            $icon_name = $HTTP_POST_FILES[icon][name];
            $icon_type = $HTTP_POST_FILES[icon][type];
            $icon_size = $HTTP_POST_FILES[icon][size];
        }

		if($icon_name) {
			if(!eregi(".gif",$icon_name)&&!eregi(".jpg",$icon_name)) Error("아이콘은 gif 또는 jpg 파일을 올려주세요");
			$size=GetImageSize($icon);
			if($size[0]>24||$size[1]>24) Error("아이콘의 크기는 24*24이하여야 합니다");
			$kind=array("","gif","jpg");
			$n=$size[2];
			@copy($icon,"icon/group_".$name.".".$kind[$n]);
			$icon_name="group_$name.".$kind[$n];
			$icon_sql=",icon='$icon_name'";
		}
		// 헤더푸터
		$header=addslashes($header);
		$header_url=addslashes($header_url);
		$footer=addslashes($footer);
		$footer_url=addslashes($footer_url);

		//DB에 입력
		@mysql_query("update $group_table set
						use_hobby='$use_hobby',name='$name',is_open='$is_open' $icon_sql ,use_join='$use_join',join_return_url='$join_return_url',use_icon='$use_icon',
						header='$header',footer='$footer',footer_url='$footer_url',header_url='$header_url' 
						where no='$group_no'") or Error("그룹수정 에러가 났습니다");
		movepage("$PHP_SELF?exec=view_group&group_no=$group_no&exec=modify_group");
	}
	// 그룹삭제 완료
	elseif($exec=="del_group_ok") {
		if($member[is_admin]>1) Error("그룹삭제 권한이 없습니다");
		// 삭제할 그룹의 회원수와 게시판 수를 구함
		$num=mysql_fetch_array(mysql_query("select member_num, board_num from $group_table where no='$group_no'"));

		// 멤버 이동
		if($member_move) {
			@mysql_query("update $member_table set group_no='$member_move' where group_no='$group_no'") or Error("회원 이동시에 에러가 발생하였습니다");
			mysql_query("update $group_table set member_num=member_num+".$num[member_num]." where no='$member_move'");
		} else {
			$result = mysql_query("select no from $member_table where group_no='$group_no'") or Error("회원 이동시에 에러가 발생하였습니다");
			while($data=mysql_fetch_array($result)) {
				$no = $data['no'];
				del_member($no);
			}
		}

		// 게시판이동
		if($board_move) {
			@mysql_query("update $admin_table set group_no='$board_move' where group_no='$group_no'") or Error("게시판 이동시에 에러가 발생하였습니다");
			mysql_query("update $group_table set board_num=board_num+".$num[board_num]." where no='$board_move'");
		} else {
			$temp=mysql_query("select name from $admin_table where group_no='$group_no'");
			while($data=mysql_fetch_array($temp)) {
				$table_name=$data[name];
				$tmpData = mysql_query("select file_name1, file_name2 from $t_board"."_$table_name") or die("첨부파일 삭제 처리중 에러가 발생했습니다");
				while($data=mysql_fetch_array($tmpData)) {
					if($data[file_name1]) @z_unlink("./".$data[file_name1]);
					if($data[file_name2]) @z_unlink("./".$data[file_name2]);
				}
				if(is_dir("./data/".$table_name)) zRmDir("./data/".$table_name);
				mysql_query("delete from $admin_table where no='$no'") or Error("게시판 삭제시 관리자 테이블에서 에러가 발생하였습니다");
				mysql_query("drop table $t_board"."_$table_name") or Error("게시판의 메인 테이블 삭제 에러가 발생하였습니다");
				mysql_query("drop table $t_division"."_$table_name") or Error("게시판의 Division 테이블 삭제 에러가 발생했습니다");
				mysql_query("drop table $t_comment"."_$table_name") or Error("게시판의 코멘트 테이블 삭제 에러가 발생하였습니다");
				mysql_query("drop table $t_category"."_$table_name") or Error("게시판의 카테고리 테이블 삭제 에러가 발생하였습니다");
				mysql_query("update $group_table set board_num=board_num-1 where no='$group_no'");
			}
			@mysql_query("delete from $admin_table where group_no='$group_no'");
		}

		// 그룹삭제                                                                                                
		@mysql_query("delete from $group_table where no='$group_no'") or Error("그룹삭제시 에러가 발생하였습니다");
                                                                                                              
		movepage("$PHP_SELF");                                                                                     
	}                                                                                                           
	// 가입양식 변경                                                                                            
	elseif($exec=="modify_member_join_ok")                                                                      {                                                                                                           
		mysql_query("update $group_table set join_level='$join_level',use_icq='$use_icq',use_aol='$use_aol',use_msn='$use_msn',   
		use_jumin='$use_jumin',use_comment='$use_comment',use_job='$use_job',use_hobby='$use_hobby',          
		use_home_address='$use_home_address',use_home_tel='$use_home_tel',use_office_address='$use_office_address',
		use_office_tel='$use_office_tel',use_handphone='$use_handphone',use_mailing='$use_mailing',          
		use_birth='$use_birth',use_picture='$use_picture' where no='$group_no'") or error(mysql_error());              
		movepage("$PHP_SELF?exec=modify_member_join&group_no=$group_no");                                                  
	}    

?>
