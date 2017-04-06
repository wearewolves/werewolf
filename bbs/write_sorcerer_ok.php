<?
	//set_time_limit(0); 

/***************************************************************************
 * 공통 파일 include
 **************************************************************************/
	include "_head.php";

/***************************************************************************
 * 게시판 설정 체크
 **************************************************************************/

// 편법을 이용한 글쓰기 방지
	$mode = $HTTP_POST_VARS[mode];

	if(strpos($HTTP_HOST,':') <> false)	$HTTP_HOST =	substr($HTTP_HOST,0,strpos($HTTP_HOST,':'));
	if(!eregi($HTTP_HOST,$HTTP_REFERER)) Error("정상적으로 글을 작성하여 주시기 바랍니다.");
	if(getenv("REQUEST_METHOD") == 'GET' ) Error("정상적으로 글을 쓰시기 바랍니다","");
	if(!$mode) $mode = "write";

// 사용권한 체크
	if($mode=="reply"&&$setup[grant_reply]<$member[level]&&!$is_admin) Error("사용권한이 없습니다","login.php?id=$id&page=$page&page_num=$page_num&category=$category&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&file=zboard.php");
	elseif($setup[grant_write]<$member[level]&&!$is_admin) Error("사용권한이 없습니다","login.php?id=$id&page=$page&page_num=$page_num&category=$category&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&file=zboard.php");

	if($min < 0  or  59 < $min) Error("분을 다시 입력해 주시기 바랍니다.");
	if($hour < 1  or  24 < $hour) Error("시를 다시 입력해 주시기 바랍니다.");
	if($termOfDay <> 1800 and $termOfDay <> 86400)	$termOfDay = 86400;

	if(!$is_admin&&$setup[grant_notice]<$member[level]) $notice = 0;

// 각종 변수 검사;;
	/*	
	if(!$member[no]) {
		if(isblank($name)) Error("이름을 입력하셔야 합니다");
		if(isblank($password)) Error("비밀번호를 입력하셔야 합니다");
	} else {
		$password = $member[password];
	}
	*/

	if($is_secret){
		if(isblank($password)) Error("비밀번호를 입력하셔야 합니다");
	} else {
		$password = $member[password];
	}


	$subject = str_replace("ㅤ","",$subject);
	$memo = str_replace("ㅤ","",$memo);
	$name = str_replace("ㅤ","",$name);

	if(isblank($subject)) Error("제목을 입력하셔야 합니다");
	if(isblank($memo)) Error("내용을 입력하셔야 합니다");

	if(!$category) {
		$cate_temp=mysql_fetch_array(mysql_query("select min(no) from $t_category"."_$id",$connect));
		$category=$cate_temp[0];
	}


// 필터링;; 관리자가 아닐때;;
	if(!$is_admin&&$setup[use_filter]) {
		$filter=explode(",",$setup[filter]);
		$f_memo=eregi_replace("([\_\-\./~@?=%&! ]+)","",strip_tags($memo));
		$f_name=eregi_replace("([\_\-\./~@?=%&! ]+)","",strip_tags($name));
		$f_subject=eregi_replace("([\_\-\./~@?=%&! ]+)","",strip_tags($subject));
		$f_email=eregi_replace("([\_\-\./~@?=%&! ]+)","",strip_tags($email));
		$f_homepage=eregi_replace("([\_\-\./~@?=%&! ]+)","",strip_tags($homepage));
		for($i=0;$i<count($filter);$i++) {
			if(!isblank($filter[$i])) {
				if(eregi($filter[$i],$f_memo)) Error("<b>$filter[$i]</b> 은(는) 등록하기에 적합한 단어가 아닙니다");
				if(eregi($filter[$i],$f_name)) Error("<b>$filter[$i]</b> 은(는) 등록하기에 적합한 단어가 아닙니다");
				if(eregi($filter[$i],$f_subject)) Error("<b>$filter[$i]</b> 은(는) 등록하기에 적합한 단어가 아닙니다");
				if(eregi($filter[$i],$f_email)) Error("<b>$filter[$i]</b> 은(는) 등록하기에 적합한 단어가 아닙니다");
				if(eregi($filter[$i],$f_homepage)) Error("<b>$filter[$i]</b> 은(는) 등록하기에 적합한 단어가 아닙니다");
			}
		}
	}

//패스워드를 암호화
	if($password) {
		$temp=mysql_fetch_array(mysql_query("select old_password('$password')"));
		$password=$temp[0];   
	}

// 관리자이거나 HTML허용레벨이 낮을때 태그의 금지유무를 체크
	if(!$is_admin&&$setup[grant_html]<$member[level]) {

		// 내용의 HTML 금지;;
		if(!$use_html||$setup[use_html]==0) $memo=del_html($memo);

		// HTML의 부분허용일때;;
		if($use_html&&$setup[use_html]==1) {
			$memo=str_replace("<","&lt;",$memo);
			$tag=explode(",",$setup[avoid_tag]);
			for($i=0;$i<count($tag);$i++) {
				if(!isblank($tag[$i])) { 
					$memo=eregi_replace("&lt;".$tag[$i]." ","<".$tag[$i]." ",$memo); 
					$memo=eregi_replace("&lt;".$tag[$i].">","<".$tag[$i].">",$memo); 
					$memo=eregi_replace("&lt;/".$tag[$i],"</".$tag[$i],$memo); 
				}
			}  
		}
	} else {
		if(!$use_html) {
			$memo=del_html($memo);
		}
	}


// 원본글을 가져옴
	unset($s_data);
	$s_data=mysql_fetch_array(mysql_query("select * from $t_board"."_$id where no='$no'"));

// 원본글을 이용한 비교
	if($mode=="modify"||$mode=="reply") {
		if(!$s_data[no]) Error("원본글이 존재하지 않습니다");
	}

// 공지글에는 답글이 안 달리게 처리
	if($mode=="reply"&&$s_data[headnum]<=-2000000000) Error("공지글에는 답글을 달수 없습니다");


// 회원등록이 되어 있을때 이름등을 가져옴;;
	if($member[no]) {
		if($mode=="modify"&&$member[no]!=$s_data[ismember]) {
			$name=$s_data[name];
			$email=$s_data[email];
			$homepage=$s_data[homepage];
		} else {
			$name=$member[name];
			$email=$member[email];
			$homepage=$member[homepage];
		}
	}

// 각종 변수의 addslashes 시킴;;
	$name=addslashes(del_html($name));
	if(($is_admin||$member[level]<=$setup[use_html])&&$use_html) $subject=addslashes($subject);
	else $subject=addslashes(del_html($subject));
	$memo=addslashes($memo);
	if($use_html<2) {
		$memo=str_replace("  ","&nbsp;&nbsp;",$memo);
		$memo=str_replace("\t","&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$memo);
	}	
	$sitelink1=addslashes(del_html($sitelink1));
	$sitelink2=addslashes(del_html($sitelink2));
	$email=addslashes(del_html($email));
	$homepage=addslashes(del_html($homepage));
// 예비 추가 필드 사용을 위한 추가 by 유메 ------------- 주목!! 이 아래 두 줄을 추가하면 됩니다. 
	$x=addslashes(del_html($x)); 
	$y=addslashes(del_html($y)); 

// 홈페이지 주소의 경우 http:// 가 없으면 붙임
	if((!eregi("http://",$homepage))&&$homepage) $homepage="http://".$homepage;

// 각종 변수 설정
	$ip=$server[ip]; // 아이피값 구함;;
	$reg_date=time(); // 현재의 시간구함;;

	$x = $zx;
	$y = $zy;

// 도배인지 아닌지 검사;; 우선 같은 아이피대에 30초이내의 글은 도배로 간주;;
	if(!$is_admin&&$mode!="modify") {
		$max_no=mysql_fetch_array(mysql_query("select max(no) from $t_board"."_$id"));
		$temp=mysql_fetch_array(mysql_query("select count(*) from $t_board"."_$id where ip='$ip' and $reg_date - reg_date <30 and no='$max_no[0]'"));
		if($temp[0]>0) {Error("글등록은 30초이상이 지나야 가능합니다"); exit;}
	}

// 같은 내용이 있는지 검사;;
	if(!$is_admin&&$mode!="modify") {
		$max_no=mysql_fetch_array(mysql_query("select max(no) from $t_board"."_$id"));
		$temp=mysql_fetch_array(mysql_query("select count(*) from $t_board"."_$id where memo='$memo' and no='$max_no[0]'"));
		if($temp[0]>0) {Error("같은 내용의 글은 등록할수가 없습니다"); exit; }
	}


// 쿠키 설정;;
	if($mode!="modify") {
		// 기존 세션 처리 (4.0x용 세션 처리로 인하여 주석 처리)
		//if($name) $HTTP_SESSION_VARS["zb_writer_name"] = $name;
		//if($email) $HTTP_SESSION_VARS["zb_writer_email"] = $email;
		//if($homepage) $HTTP_SESSION_VARS["zb_writer_homepage"] = $homepage;

		// 4.0x 용 세션 처리
		if($name) {
			$zb_writer_name = $name;
			session_register("zb_writer_name");
		}
		if($email) {
			$zb_writer_email = $email;
			session_register("zb_writer_email");
		}
		if($homepage) {
			$zb_writer_homepage = $homepage;
			session_register("zb_writer_homepage");
		}
	}


/***************************************************************************
 * 업로드가 있을때
 **************************************************************************/
	if($HTTP_POST_FILES[file1]) {
		$file1 = $HTTP_POST_FILES[file1][tmp_name];
		$file1_name = $HTTP_POST_FILES[file1][name];
		$file1_size = $HTTP_POST_FILES[file1][size];
		$file1_type = $HTTP_POST_FILES[file1][type];
	}
	if($HTTP_POST_FILES[file2]) {
		$file2 = $HTTP_POST_FILES[file2][tmp_name];
		$file2_name = $HTTP_POST_FILES[file2][name];
		$file2_size = $HTTP_POST_FILES[file2][size];
		$file2_type = $HTTP_POST_FILES[file2][type];
	}

	if($file1_size>0&&$setup[use_pds]&&$file1) {

		if(!is_uploaded_file($file1)) Error("정상적인 방법으로 업로드 해주세요");
		if($file1_name==$file2_name) Error("같은 파일은 등록할수 없습니다");
		$file1_size=filesize($file1);

		if($setup[max_upload_size]<$file1_size&&!$is_admin) error("첫번째 파일 업로드는 최고 ".GetFileSize($setup[max_upload_size])." 까지 가능합니다");

		// 업로드 금지
		if($file1_size>0) {
			$s_file_name1=$file1_name;
			if(eregi("\.inc",$s_file_name1)||eregi("\.phtm",$s_file_name1)||eregi("\.htm",$s_file_name1)||eregi("\.shtm",$s_file_name1)||eregi("\.ztx",$s_file_name1)||eregi("\.php",$s_file_name1)||eregi("\.dot",$s_file_name1)||eregi("\.asp",$s_file_name1)||eregi("\.cgi",$s_file_name1)||eregi("\.pl",$s_file_name1)) Error("Html, PHP 관련파일은 업로드할수 없습니다");

			//확장자 검사
			if($setup[pds_ext1]) {
				$temp=explode(".",$s_file_name1);
				$s_point=count($temp)-1;
				$upload_check=$temp[$s_point];
				if(!eregi($upload_check,$setup[pds_ext1])||!$upload_check) Error("첫번째 업로드는 $setup[pds_ext1] 확장자만 가능합니다");
			}

			$file1=eregi_replace("\\\\","\\",$file1);
			$s_file_name1=str_replace(" ","_",$s_file_name1);
			$s_file_name1=str_replace("-","_",$s_file_name1);

			// 디렉토리를 검사함
			if(!is_dir("data/".$id)) { 
				@mkdir("data/".$id,0777);
				@chmod("data/".$id,0706);
			}

			// 중복파일이 있을때;; 
			if(file_exists("data/$id/".$s_file_name1)) {
				@mkdir("data/$id/".$reg_date,0777);
				if(!move_uploaded_file($file1,"data/$id/".$reg_date."/".$s_file_name1)) Error("파일업로드가 제대로 되지 않았습니다");
				$file_name1="data/$id/".$reg_date."/".$s_file_name1;
				@chmod($file_name1,0706);
				@chmod("data/$id/".$reg_date,0707);
			} else {
				if(!move_uploaded_file($file1,"data/$id/".$s_file_name1)) Error("파일업로드가 제대로 되지 않았습니다");
				$file_name1="data/$id/".$s_file_name1;   
				@chmod($file_name1,0706);
			}
		}
  	}

	if($file2_size>0&&$setup[use_pds]&&$file2) {
		if(!is_uploaded_file($file2)) Error("정상적인 방법으로 업로드 해주세요");
		$file2_size=filesize($file2);
		if($setup[max_upload_size]<$file2_size&&!$is_admin) error("파일 업로드는 최고 ".GetFileSize($setup[max_upload_size])." 까지 가능합니다");
		if($file2_size>0) {
			$s_file_name2=$file2_name;
			if(eregi("\.inc",$s_file_name2)||eregi("\.pht",$s_file_name2)||eregi("\.htm",$s_file_name2)||eregi("\.shtml",$s_file_name2)||eregi("\.ztx",$s_file_name2)||eregi("\.php",$s_file_name2)||eregi("\.dot",$s_file_name1)||eregi("\.asp",$s_file_name2)||eregi("\.cgi",$s_file_name2)||eregi("\.pl",$s_file_name2)) Error("Html, PHP 관련파일은 업로드할수 없습니다");

			//확장자 검사
			if($setup[pds_ext2]) {
				$temp=explode(".",$s_file_name2);
				$s_point=count($temp)-1;
				$upload_check=$temp[$s_point];
				if(!eregi($upload_check,$setup[pds_ext2])||!$upload_check) Error("업로드는 $setup[pds_ext2] 확장자만 가능합니다");
			}

			$file2=eregi_replace("\\\\","\\",$file2);
			$s_file_name2=str_replace(" ","_",$s_file_name2);
			$s_file_name2=str_replace("-","_",$s_file_name2);

			// 디렉토리를 검사함
			if(!is_dir("data/".$id)) {
				mkdir("data/".$id,0777);
				@chmod("data/".$id,0706);
			}

			// 중복파일이 있을때;; 
			if(file_exists("data/$id/".$s_file_name2)) {
				@mkdir("data/$id/".$reg_date,0777);
				if(!move_uploaded_file($file2,"data/$id/".$reg_date."/".$s_file_name2)) Error("파일업로드가 제대로 되지 않았습니다");
				$file_name2="data/$id/".$reg_date."/".$s_file_name2;
				@chmod($file_name2,0706);
				@chmod("data/$id/".$reg_date,0707);
			} else {
				if(!move_uploaded_file($file2,"data/$id/".$s_file_name2)) Error("파일업로드가 제대로 되지 않았습니다");
				$file_name2="data/$id/".$s_file_name2;              
				@chmod($file_name2,0706);
			}
		}
	}


/***************************************************************************
 * 수정글일때
 **************************************************************************/
	if($mode=="modify"&&$no) {
		if($s_data[ismember]) {
			if(!$is_admin&&$member[level]>$setup[grant_delete]&&$s_data[ismember]!=$member[no]) Error("정상적인 방법으로 수정하세요");
		}

		// 비밀번호 검사;;
		if($s_data[ismember]!=$member[no]&&!$is_admin) {
			if($password!=$s_data[password]) Error("비밀번호가 틀렸습니다");
		}
		
		// 파일삭제
		if($del_file1==1) {@z_unlink("./".$s_data[file_name1]);$del_que1=",file_name1='',s_file_name1=''";} 
		if($del_file2==1) {@z_unlink("./".$s_data[file_name2]);$del_que2=",file_name2='',s_file_name2=''";} 

		// 파일등록
		if($file_name1) {$del_que1=",file_name1='$file_name1',s_file_name1='$s_file_name1'";}
		if($file_name2) {$del_que2=",file_name2='$file_name2',s_file_name2='$s_file_name2'";}

		// 공지 -> 일반글 
		if(!$notice&&$s_data[headnum]<="-2000000000") {
			$temp=mysql_fetch_array(mysql_query("select max(division) from $t_division"."_$id"));
			$max_division=$temp[0];
			$temp=mysql_fetch_array(mysql_query("select max(division) from $t_division"."_$id where num>0 and division!='$max_division'"));
			if(!$temp[0]) $second_division=0; else $second_division=$temp[0];

			// 헤드넘+1 한값을 가짐;;
			$max_headnum=mysql_fetch_array(mysql_query("select min(headnum) from $t_board"."_$id where (division='$max_division' or division='$second_division') and headnum>-2000000000")); // 공지가 아닌 최소 headnum 구함
			$headnum=$max_headnum[0]-1; 

			$next_data=mysql_fetch_array(mysql_query("select no,headnum,division from $t_board"."_$id where (division='$max_division' or division='$second_division') and headnum='$max_headnum[0]' and arrangenum='0'")); // 다음글을 구함;;
			if(!$next_data[0]) $next_data[0]="0";
			$next_no=$next_data[0];

			if(!$next_data[division]) $division=1; else $division=$next_data[division];

			$prev_data=mysql_fetch_array(mysql_query("select no from $t_board"."_$id where (division='$max_division' or division='$second_division') and headnum<'$headnum' and no!='$no' order by headnum desc limit 1")); // 이전글을 구함;;
			if($prev_data[0]) $prev_no=$prev_data[0]; else $prev_no=0;

			$child="0";
			$depth="0";    
			$arrangenum="0";
			$father="0";
			minus_division($s_data[division]);
			//@mysql_query("update $t_board"."_$id set headnum='$headnum',prev_no='$prev_no',next_no='$next_no',child='$child',depth='$depth',arrangenum='$arrangenum',father='$father',name='$name',email='$email',homepage='$homepage',subject='$subject',memo='$memo',sitelink1='$sitelink1',sitelink2='$sitelink2',use_html='$use_html',reply_mail='$reply_mail',is_secret='$is_secret',category='$category' $del_que1 $del_que2 where no='$no'") or error(mysql_error());
			
			@mysql_query("update $t_board"."_$id set headnum='$headnum',prev_no='$prev_no',next_no='$next_no',child='$child',depth='$depth',arrangenum='$arrangenum',father='$father',name='$name',email='$email',homepage='$homepage',subject='$subject',memo='$memo',sitelink1='$sitelink1',sitelink2='$sitelink2',x='$x',y='$y',use_html='$use_html',reply_mail='$reply_mail',is_secret='$is_secret',category='$category' $del_que1 $del_que2 where no='$no'") or error("1".mysql_error()); 

			plus_division($division);
			
			
			// 다음글의 이전글을 수정
			if($next_no)mysql_query("update $t_board"."_$id set prev_no='$no' where division='$next_data[division]' and headnum='$next_data[headnum]'");  

			// 이전글의 다음글을 수정
			if($prev_no)mysql_query("update $t_board"."_$id set next_no='$no' where no='$prev_no'");                  

			mysql_query("update $t_board"."_$id set prev_no=0 where (division='$max_division' or division='$second_division') and prev_no='$s_data[no]' and headnum!='$next_data[headnum]'");
			mysql_query("update $t_category"."_$id set num=num-1 where no='$s_data[category]'",$connect);
			mysql_query("update $t_category"."_$id set num=num+1 where no='$category'",$connect);
		}

   		// 일반글 -> 공지 
		elseif($notice&&$s_data[headnum]>-2000000000) {
			$temp=mysql_fetch_array(mysql_query("select max(division) from $t_division"."_$id"));
			$max_division=$temp[0];
			$temp=mysql_fetch_array(mysql_query("select max(division) from $t_division"."_$id where num>0 and division!='$max_division'"));
			if(!$temp[0]) $second_division=0; else $second_division=$temp[0];

			$max_headnum=mysql_fetch_array(mysql_query("select min(headnum) from $t_board"."_$id where division='$max_division' or division='$second_division'"));  // 최고글을 구함;;
			$headnum=$max_headnum[0]-1;
			if($headnum>-2000000000) $headnum=-2000000000; // 최고 headnum이 공지가 아니면 현재 글에 공지를 넣음;

			$next_data=mysql_fetch_array(mysql_query("select no,headnum,division from $t_board"."_$id where (division='$max_division' or division='$second_division') and headnum='$max_headnum[0]' and arrangenum='0'"));
			if(!$next_data[0]) $next_data[0]="0";
			$next_no=$next_data[0];
			$prev_no=0;
			$child="0";
			$depth="0";
			$arrangenum="0";
			$father="0";
			minus_division($s_data[division]);
			$division=add_division();
			//@mysql_query("update $t_board"."_$id set division='$division',headnum='$headnum',prev_no='$prev_no',next_no='$next_no',child='$child',depth='$depth',arrangenum='$arrangenum',father='$father',name='$name',email='$email',homepage='$homepage',subject='$subject',memo='$memo',sitelink1='$sitelink1',sitelink2='$sitelink2',use_html='$use_html',reply_mail='$reply_mail',is_secret='$is_secret',category='$category' $del_que1 $del_que2 where no='$no'") or error(mysql_error());

			@mysql_query("update $t_board"."_$id set division='$division',headnum='$headnum',prev_no='$prev_no',next_no='$next_no',child='$child',depth='$depth',arrangenum='$arrangenum',father='$father',name='$name',email='$email',homepage='$homepage',subject='$subject',memo='$memo',sitelink1='$sitelink1',sitelink2='$sitelink2',x='$x',y='$y',use_html='$use_html',reply_mail='$reply_mail',is_secret='$is_secret',category='$category' $del_que1 $del_que2 where no='$no'") or error("2".mysql_error()); 



			if($s_data[father]) mysql_query("update $t_board"."_$id set child='$s_data[child]' where no='$s_data[father]'"); // 답글이었으면 원본글의 답글을 현재글의 답글로 대체
			if($s_data[child]) mysql_query("update $t_board"."_$id set depth=depth-1,father='$s_data[father]' where no='$s_data[child]'"); // 답글이 있으면 현재글의 위치로;;

			// 원래 다음글로 이글을 가지고 있었던 데이타의 prev_no을 바꿈;
			$temp=mysql_fetch_array(mysql_query("select max(headnum) from $t_board"."_$id where headnum<='$s_data[headnum]'"));
			$temp=mysql_fetch_array(mysql_query("select no from $t_board"."_$id where headnum='$temp[0]' and depth='0'"));
			mysql_query("update $t_board"."_$id set prev_no='$temp[no]' where prev_no='$s_data[no]'");

			mysql_query("update $t_board"."_$id set next_no='$s_data[next_no]' where next_no='$s_data[no]'");

			mysql_query("update $t_board"."_$id set prev_no='$no' where prev_no='0' and no!='$no'") or error("3".mysql_error()); // 다음글의 이전글을 설정 
			mysql_query("update $t_category"."_$id set num=num-1 where no='$s_data[category]'",$connect);
			mysql_query("update $t_category"."_$id set num=num+1 where no='$category'",$connect);

		// 일반->일반, 공지->공지 일때 
		} else {
			//@mysql_query("update $t_board"."_$id set name='$name',subject='$subject',email='$email',homepage='$homepage',memo='$memo',sitelink1='$sitelink1',sitelink2='$sitelink2',use_html='$use_html',reply_mail='$reply_mail',is_secret='$is_secret',category='$category' $del_que1 $del_que2 where no='$no'") or error(mysql_error());
			
			@mysql_query("update $t_board"."_$id set name='$name',subject='$subject',email='$email',homepage='$homepage',memo='$memo',sitelink1='$sitelink1',sitelink2='$sitelink2',x='$x',y='$y',use_html='$use_html',reply_mail='$reply_mail',is_secret='$is_secret',password='$password',category='$category' $del_que1 $del_que2 where no='$no'") or error("4".mysql_error()); 


			mysql_query("update $t_category"."_$id set num=num-1 where no='$s_data[category]'",$connect);
			mysql_query("update $t_category"."_$id set num=num+1 where no='$category'",$connect);
		}



/***************************************************************************
 * 답변글일때
 **************************************************************************/
	} elseif($mode=="reply"&&$no) {

		$prev_no=$s_data[prev_no];
		$next_no=$s_data[next_no];
		$father=$s_data[no];
		$child=0;
		$headnum=$s_data[headnum];    
		if($headnum<=-2000000000&&$notice) Error("공지사항에는 답글을 달수가 없습니다");
		$depth=$s_data[depth]+1;
		$arrangenum=$s_data[arrangenum]+1;
		$move_result=mysql_query("select no from $t_board"."_$id where division='$s_data[division]' and headnum='$headnum' and arrangenum>='$arrangenum'");
		while($move_data=mysql_fetch_array($move_result)) {
			mysql_query("update $t_board"."_$id set arrangenum=arrangenum+1 where no='$move_data[no]'");
		}

		$division=$s_data[division];
		plus_division($s_data[division]);
   
		// 답글 데이타 입력;;
		mysql_query("insert into $t_board"."_$id (division,headnum,arrangenum,depth,prev_no,next_no,father,child,ismember,memo,ip,password,name,homepage,email,subject,use_html,reply_mail,category,is_secret,sitelink1,sitelink2,file_name1,file_name2,s_file_name1,s_file_name2,x,y,reg_date,islevel) values ('$division','$headnum','$arrangenum','$depth','$prev_no','$next_no','$father','$child','$member[no]','$memo','$ip','$password','$name','$homepage','$email','$subject','$use_html','$reply_mail','$category','$is_secret','$sitelink1','$sitelink2','$file_name1','$file_name2','$s_file_name1','$s_file_name2','$x','$y','$reg_date','$member[is_admin]')") or error("5".mysql_error());    

		// 원본글과 원본글의 아래글의 속성 변경;;
		$no=mysql_insert_id();
		mysql_query("update $t_board"."_$id set child='$no' where no='$s_data[no]'");
		mysql_query("update $t_category"."_$id set num=num+1 where no='$category'",$connect);

		// 현재글의 조회수를 올릴수 없게 세션 등록
		$hitStr=",".$setup[no]."_".$no;
		$zb_hit=$HTTP_SESSION_VARS["zb_hit"].$hitStr;
		session_register("zb_hit");

		// 현재글의 추천을 할수 없게 세션 등록
		$voteStr=",".$setup[no]."_".$no;
		$zb_vote=$HTTP_SESSION_VARS["zb_vote"].$voteStr;
		session_register("zb_vote");

		// 응답글 보내기일때;;
		if($s_data[reply_mail]&&$s_data[email]) {

			if($use_html<2) $memo=nl2br($memo);
			$memo = stripslashes($memo);

			zb_sendmail($use_html, $s_data[email], $s_data[name], $email, $name, $subject, $memo);
		}

/***************************************************************************
 * 신규 글쓰기일때
 **************************************************************************/
	} elseif($mode=="write") {
		// 공지사항이 아닐때;;
		if(!$notice) {
			$temp=mysql_fetch_array(mysql_query("select max(division) from $t_division"."_$id"));
			$max_division=$temp[0];
			$temp=mysql_fetch_array(mysql_query("select max(division) from $t_division"."_$id where num>0 and division!='$max_division'"));
			if(!$temp[0]) $second_division=0; else $second_division=$temp[0];

			$max_headnum=mysql_fetch_array(mysql_query("select min(headnum) from $t_board"."_$id where (division='$max_division' or division='$second_division') and headnum>-2000000000"));
			if(!$max_headnum[0]) $max_headnum[0]=0;

			$headnum=$max_headnum[0]-1;

			$next_data=mysql_fetch_array(mysql_query("select division,headnum,arrangenum from $t_board"."_$id where (division='$max_division' or division='$second_division') and headnum>-2000000000 order by headnum limit 1"));
			if(!$next_data[0]) $next_data[0]="0";
			else {
				$next_data=mysql_fetch_array(mysql_query("select no,headnum,division from $t_board"."_$id where division='$next_data[division]' and headnum='$next_data[headnum]' and arrangenum='$next_data[arrangenum]'"));
			}
    
			$prev_data=mysql_fetch_array(mysql_query("select no from $t_board"."_$id where (division='$max_division' or division='$second_division') and headnum<=-2000000000 order by headnum desc limit 1"));
			if($prev_data[0]) $prev_no=$prev_data[0]; else $prev_no="0";

		// 공지사항일때;; 
		} else {
			$temp=mysql_fetch_array(mysql_query("select max(division) from $t_division"."_$id"));
			$max_division=$temp[0]+1;
			$temp=mysql_fetch_array(mysql_query("select max(division) from $t_division"."_$id where num>0 and division!='$max_division'"));
			if(!$temp[0]) $second_division=0; else $second_division=$temp[0];

			$max_headnum=mysql_fetch_array(mysql_query("select min(headnum) from $t_board"."_$id where division='$max_division' or division='$second_division'"));
			$headnum=$max_headnum[0]-1;
			if($headnum>-2000000000) $headnum=-2000000000;

			$next_data=mysql_fetch_array(mysql_query("select division,headnum from $t_board"."_$id where division='$max_division' or division='$second_division' order by headnum limit 1"));
			if(!$next_data[0]) $next_data[0]="0";
			else {
				$next_data=mysql_fetch_array(mysql_query("select no,headnum,division from $t_board"."_$id where division='$next_data[division]' and headnum='$next_data[headnum]' and arrangenum='0'"));
			}
			$prev_no=0; 
		}

		$next_no=$next_data[no];
		$child="0";
		$depth="0";
		$arrangenum="0";
		$father="0";
		$division=add_division();

		mysql_query("insert into $t_board"."_$id (division,headnum,arrangenum,depth,prev_no,next_no,father,child,ismember,memo,ip,password,name,homepage,email,subject,use_html,reply_mail,category,is_secret,sitelink1,sitelink2,file_name1,file_name2,s_file_name1,s_file_name2,x,y,reg_date,islevel) values ('$division','$headnum','$arrangenum','$depth','$prev_no','$next_no','$father','$child','$member[no]','$memo','$ip','$password','$name','$homepage','$email','$subject','$use_html','$reply_mail','$category','$is_secret','$sitelink1','$sitelink2','$file_name1','$file_name2','$s_file_name1','$s_file_name2','$x','$y','$reg_date','$member[is_admin]')") or error("6".mysql_error());
		$no=mysql_insert_id();

		// 현재글의 조회수를 올릴수 없게 세션 등록
		$hitStr=",".$setup[no]."_".$no;
		$zb_hit=$HTTP_SESSION_VARS["zb_hit"].$hitStr;
		session_register("zb_hit");

		// 현재글의 추천을 할수 없게 세션 등록
		$voteStr=",".$setup[no]."_".$no;
		$zb_vote=$HTTP_SESSION_VARS["zb_vote"].$voteStr;
		session_register("zb_vote");

		if($prev_no) mysql_query("update $t_board"."_$id set next_no='$no' where no='$prev_no'");
		if($next_no) mysql_query("update $t_board"."_$id set prev_no='$no' where headnum='$next_data[headnum]' and division='$next_data[division]'");
		mysql_query("update $t_category"."_$id set num=num+1 where no='$category'",$connect);
	}

//게임 정보 입력
	if($mode=="modify"&&$no) {
		$DB_gameinfo=$t_board."_".$id."_gameinfo";
		$gameinfo=mysql_fetch_array(mysql_query("select * from $DB_gameinfo where game=$no"));
		
		if($gameinfo['state'] == "준비중"){
			$year=date("Y",$gameinfo['deathtime']);
			$month=date("m",$gameinfo['deathtime']);

			if(!$is_admin or !$day)	$day=date("d",$gameinfo['deathtime']);

			//$deathtime= mktime($hour ,$min, 0, date("m"), date("d"), date("Y"));	
		
			$deathtime= mktime($hour ,$min, 0, $month, $day, $year);			
			$startingTime= mktime($hourS ,$minS, 0, $monthS, $dayS, $yearS);		

			@mysql_query("update `$t_board"."_$id"."_gameinfo` set `startingTime`= '$startingTime',`deathtime`= '$deathtime' where game = $no ;") or die("게임 정보를 입력 중에 오류가 발생했습니다.");

		}
		elseif(!$is_admin and $is_secret){
			$is_secret ='';
			@mysql_query("update `$t_board"."_$id"." set `is_secret`= '$is_secret' where no = $no ;") or die("게임 정보를 입력 중에 오류가 발생했습니다.");
		}


	} elseif($mode=="reply"&&$no) {
		//@mysql_query("INSERT INTO `$t_board"."_$id"."_gameinfo` ( `game` , `day` , `deathtime` , `players` ,  `result`) VALUES ('$no', '0', '$server',  '1', '0' );") or die("버그 내용을 입력 중에 오류가 발생했습니다.");
	} elseif($mode=="write") {

		if(!$is_admin or !$day)$day = date("d",time()+86400);

		$deathtime= mktime($hour ,$min, 0, date("m",time()+86400), $day, date("Y",time()+86400));			
		//$deathtime= mktime($hour ,$min, 0, $month, $day, $year);			
		$startingTime= mktime($hourS ,$minS, 0, $monthS, $dayS, $yearS);			
		@mysql_query(
		"INSERT INTO `$t_board"."_$id"."_gameinfo` ( `game` , `day` ,`startingTime` , `deathtime` , `players` ,  `result`,`state`,`termOfDay`,`characterSet`) VALUES ('$no', '0', '$startingTime', '$deathtime',  '0', '' ,'준비중','$termOfDay','$characterSet');") or error("7".mysql_error());
	


		//관련 함수
		function DB_array($key,$value,$db){
			$temp_result=mysql_query("select * from $db ");

			while($temp_member=@mysql_fetch_array($temp_result)){
					$members[$temp_member[$key]]=$temp_member[$value];
			}

			return $members;
		}

		$c_alarm = "마을이 열렸습니다.";
		$reg_date=time();
		// 코멘트 입력
		mysql_query("insert into $t_comment"."_$id (parent,ismember,name,password,memo,reg_date,ip) values ('$no','$member[no]','$member[name]','$password','$c_alarm','$reg_date','$server[ip]')") or error("insert into $t_comment"."_$id (parent,ismember,name,password,memo,reg_date,ip) values ('$no','$member[no]','$member[name]','$password','$memo','$reg_date','$server[ip]')"."8".mysql_error());

		// 코멘트 타입 입력
		$commentID=mysql_insert_id();
		mysql_query("insert into $t_comment"."_$id"."_commentType (game,comment,type) values ($no,$commentID,'알림')") or error("9".mysql_error());	


	}

// 글의 갯수를 다시 갱신
	$total=mysql_fetch_array(mysql_query("select count(*) from $t_board"."_$id "));
	mysql_query("update $admin_table set total_article='$total[0]' where name='$id'");


// 회원일 경우 해당 해원의 점수 주기
	if($mode=="write"||$mode=="reply") @mysql_query("update $member_table set point1=point1+1 where no='$member[no]'",$connect) or error(mysql_error());

// MySQL 닫기 
	if($connect) {
		mysql_close($connect); 
		unset($connect);
	}

// 페이지 이동
	//if($setup[use_alllist]) $view_file="zboard.php"; else $view_file="view.php";
	$view_file = "zboard.php";
	movepage($view_file."?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category");
?>
