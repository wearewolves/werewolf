<?
//[투표, 능력 시작]////////////////////////////////////////////////////////////////////////////
// 플레이어 투표
	 if ($function == "vote" and $entry['alive'] == "생존" and !$vote and $candidacy){
		//error("INSERT INTO `$DB_vote` ( `game` , `day` , `voter` ,  `candidacy`) VALUES ('$no', '$gameinfo[day]','$entry[character]' , '$candidacy' );");
		 if($truecharacter['double-vote']){
			 $sql = "INSERT INTO `$DB_vote` ( `game` , `day` , `voter` ,  `candidacy`) VALUES ('$no', '$gameinfo[day]','$entry[character]' , '$candidacy' );";
			 @mysql_query($sql) or die("투표 정보를 입력 중에 오류가 발생했습니다.");		 

			 $sql = "INSERT INTO `$DB_vote` ( `game` , `day` , `voter` ,  `candidacy`) VALUES ('$no', '$gameinfo[day]','$entry[character]' , '$candidacy' );";
			 @mysql_query($sql) or die("투표 정보를 입력 중에 오류가 발생했습니다.");

			 //코맨트 입력
			 $comment = "보안관이 ".$character_list[$candidacy]."님에게 2표를 던졌습니다.";
		 }else{
			 $sql = "INSERT INTO `$DB_vote` ( `game` , `day` , `voter` ,  `candidacy`) VALUES ('$no', '$gameinfo[day]','$entry[character]' , '$candidacy' );";
			 @mysql_query($sql) or die("투표 정보를 입력 중에 오류가 발생했습니다.");
			 
			 //코맨트 입력
			 $comment = $character_list[$entry[character]]."님이 ".$character_list[$candidacy]."님에게 표를 던졌습니다.";

		 }

		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'메모',$entry['character']);

		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// 페이지 이동
	movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }

// 플레이어 투표취소
	 if ($function == "voteCancel" and $entry['alive'] == "생존" and $vote){
		 @mysql_query(
		"delete from `$DB_vote`  where `game`= $no and `day`= $gameinfo[day] and  `voter` = $entry[character] ;") or die("투표 정보를 삭제 중에 오류가 발생했습니다.");

		//코맨트 입력
		$comment = $character_list[$entry[character]]."님이 투표를 취소했습니다.";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'메모',$entry['character']);

		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// 페이지 이동	
	movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }

// 플레이어 점치기
	 if ($function == "forecast" and $mystery and $entry['alive'] == "생존" and $truecharacter['forecast']  and  !$forecast ){
		$target_entry = mysql_fetch_array(mysql_query("select * from $DB_entry where `game`='$no' and `character` = '$mystery'")) or die("select * from $DB_entry where `game`='$no' and `character` = '$mystery'");
		$target_truecharacter =mysql_fetch_array(mysql_query("select * from $DB_truecharacter where no='$target_entry[truecharacter]'"));

		 @mysql_query(
		"INSERT INTO `$DB_revelation` ( `game` , `day` , `type`,`prophet` ,  `mystery`,`result`) VALUES ('$no', '$gameinfo[day]','점','$entry[character]' , '$mystery', '$target_truecharacter[race]' );") or die("INSERT INTO `$DB_revelation` ( `game` , `day` , `prophet` ,  `mystery`,`result`) VALUES ('$no', '$gameinfo[day]','$entry[character]' , '$mystery', '$target_truecharacter[race]' );"."예언 정보를 입력 중에 오류가 발생했습니다.");
	 
		//코맨트 입력
		//$comment = $character_list[$mystery]."씨의 정체를 알기 위해 수정 구슬을 보고 있다.";
		$comment = "점 대상: ".$character_list[$mystery];
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'메모',$entry['character']);

		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// 페이지 이동	
	movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }

// 점치기 취소
	if ($function == "forecastCancel" and $entry['alive'] == "생존" and $forecast){
	   @mysql_query("delete from $DB_revelation where game=$no and `day`= $gameinfo[day] and prophet  = $entry[character] and type ='점' limit 1;") or die("참가자 기록을 삭제 하는 중에 오류가 발생했습니다.delete from $DB_bug where bug=$no limit 1;");

		//코맨트 입력
		//$comment = $character_list[$entry[character]]."님이 점괘를 얻기위한 주문을 멈추었습니다.";
		$comment = "점 취소";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'메모',$entry['character']);		

		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// 페이지 이동	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}	
	
// 플레이어 점치기
	 if ($function == "forecastOdd" and $mystery and $entry['alive'] == "생존" and $truecharacter['forecast-odd']  and  !$forecastOdd and $viewDay%2 == 1 ){
		$target_entry = mysql_fetch_array(mysql_query("select * from $DB_entry where `game`='$no' and `character` = '$mystery'")) or die("select * from $DB_entry where `game`='$no' and `character` = '$mystery'");
		$target_truecharacter =mysql_fetch_array(mysql_query("select * from $DB_truecharacter where no='$target_entry[truecharacter]'"));

		 @mysql_query(
		"INSERT INTO `$DB_revelation` ( `game` , `day` , `type`,`prophet` ,  `mystery`,`result`) VALUES ('$no', '$gameinfo[day]','점','$entry[character]' , '$mystery', '$target_truecharacter[race]' );") or die("INSERT INTO `$DB_revelation` ( `game` , `day` , `prophet` ,  `mystery`,`result`) VALUES ('$no', '$gameinfo[day]','$entry[character]' , '$mystery', '$target_truecharacter[race]' );"."예언 정보를 입력 중에 오류가 발생했습니다.");
	 
		//코맨트 입력
		//$comment = $character_list[$mystery]."씨의 정체를 알기 위해 수정 구슬을 보고 있다.";
		$comment = "점 대상: ".$character_list[$mystery];
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'메모',$entry['character']);

		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// 페이지 이동	
	movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }

// 점치기 취소
	if ($function == "forecastOddCancel" and $entry['alive'] == "생존" and $forecastOdd and $viewDay%2 == 1){
	   @mysql_query("delete from $DB_revelation where game=$no and `day`= $gameinfo[day] and prophet  = $entry[character] and type ='점' limit 1;") or die("참가자 기록을 삭제 하는 중에 오류가 발생했습니다.delete from $DB_bug where bug=$no limit 1;");

		//코맨트 입력
		//$comment = $character_list[$entry[character]]."님이 점괘를 얻기위한 주문을 멈추었습니다.";
		$comment = "점 취소";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'메모',$entry['character']);		

		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// 페이지 이동	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}	

	// 플레이어 습격
	 if ($function == "assault" and $injured and $entry['alive'] == "생존" and $truecharacter['assault'] and   !$assault){
		$assaultCharacter =  DB_array("no","no","$DB_truecharacter where assault  = 1");
//echo "\$assaultCharacter:";print_r($assaultCharacter);echo "<br>";

		$orderCondition ="in (";

		foreach($assaultCharacter  as $t_assault){
			$orderCondition.=$t_assault.",";
		}
		$orderCondition.=")";

		$orderCondition = str_replace(",)", ")", $orderCondition);
//echo "\$orderCondition:".$orderCondition."<br>";



		$assault_list =  DB_array("no","character","$DB_entry where game = $no and alive='생존' and truecharacter $orderCondition");	
		$assault_list = array_values($assault_list);
		
		// 2017/05/07 epi : 랑습룰 체크 부분
		$CheckAssaultWerewolf = checkSubRule($gameinfo['subRule'], 1);
//echo "\$assault_list:";print_r($assault_list);echo "<br><br>";

//echo "select * from $DB_entry where game=$no and character = $injured<br>";
		$injured = mysql_fetch_array(mysql_query("select * from $DB_entry where game=$no and `character`= $injured "));

		// 2017/05/07 epi : 살인 대상자가 맞는지 확인, 혹은 랑습룰인지 확인
		if(!in_array($injured[character],$assault_list )|| $CheckAssaultWerewolf){
			 @mysql_query(	"INSERT INTO `$DB_deathNote` ( `game` , `day` , `werewolf` ,  `injured`) VALUES ('$no', '$gameinfo[day]','$entry[character]' , '$injured[character]' );") or die("살해 계획을 입력 중에 오류가 발생했습니다.");

			//코맨트 입력
			$comment = "네 이놈 ".$character_list[$injured[character]]."! 오늘이 네 제삿날이다!!!";
			writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'비밀',$entry['character']);
		}


			// 대상 파일 이름 정리
			if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";
			// 페이지 이동	
			movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }

	// 플레이어 습격취소
	 if ($function == "assaultCancel" and $entry['alive'] == "생존" and $truecharacter['assault']  and $assault){
		 @mysql_query("delete from `$DB_deathNote`  where `game`= $no and `day`= $gameinfo[day] and  `werewolf` = $entry[character] ;") or die("살해 계획를 삭제 중에 오류가 발생했습니다.");

		//코맨트 입력
		$comment = $character_list[$assault[injured]]."을 향한 발톱을 감추었습니다.";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'비밀',$entry['character']);

		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// 페이지 이동	
	movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }

	// 플레이어 보호
	 if ($function == "guard" and $purpose and $entry['alive'] == "생존" and $truecharacter['guard'] and   !$guard){
		 @mysql_query("INSERT INTO `$DB_guard` ( `game` , `day` ,`hunter`, `purpose` ) VALUES ('$no', '$gameinfo[day]' ,'$entry[character]', '$purpose' );") or die("보호 계획을 입력 중에 오류가 발생했습니다.");

		$comment = "사냥꾼이 ".$character_list[$purpose]."씨의 주위에서 경계를 서고 있습니다.";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'메모',$entry['character']);

		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";
		// 페이지 이동	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }
	
	// 플레이어 보호취소
	 if ($function == "guardCancel" and $entry['alive'] == "생존" and $truecharacter['guard']  and $guard){
		 @mysql_query("delete from `$DB_guard`  where `game`= $no and `day`= $gameinfo[day] and  `hunter` = $entry[character] ;") or die("보호 계획를 삭제 중에 오류가 발생했습니다.");

		//코맨트 입력
		$comment = $character_list[$guard[purpose]]."씨에 대한 경계를 멈췄습니다.";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'메모',$entry['character']);

		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// 페이지 이동	
	movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }



	
	// 플레이어 참가
	 if ($function == "addPlayer" and $gameinfo['day'] == "0" and $entry=="" and $gameinfo['players'] < $rule[max_player] and (time() +3) <  $gameinfo['deathtime']  and $member['no'] and ($playCount >= $fiducialPlayCount and $NowPlayingCount < $AttandMaxCountOver3 or $playCount < $fiducialPlayCount and $NowPlayingCount < $AttandMaxCountUnder3)){
		 //($playCount >= 5 and $NowPlayingCount < ( 3 - $suddenDeathCount) or $playCount < 5 and $NowPlayingCount < (2 - $suddenDeathCount))
	/*	$temp_character = DB_array("no","no","$DB_character where `set`  =  $gameinfo[characterSet]");

		$temp_result=mysql_query("select * from $DB_entry where game = $no");

		while($data=mysql_fetch_array($temp_result)){
			unset($temp_character[array_search($data[character],$temp_character)]);
		}
		$temp_character = array_values($temp_character);

		$character= $temp_character[mt_rand (0, count ($temp_character)-1)];

	*/


//					$temp_filename=realpath(__FILE__);
//				echo $temp_filename;

		if($member['level'] == 8){
			error("비매너 상태일 때는 게임에 참여할 수 없습니다.");
		}	
		elseif($member['no']  <  1){
			echo "로그인 해주세요. ";
		}	
		elseif($gameinfo['termOfDay'] > 1800){
			if($member['level'] == 7){
			}
			elseif($member['level'] == 9) error("신규 회원은 24시간 마을에 참여할 수 없습니다.");
		}
		//elseif($gameinfo['termOfDay'] <= 1800) 





		//비공개 마을이 아니라면 같은 IP를 사용한 사람이 참여했는지 확인한다.
		if($data[is_secret] == false and $check_ip){
			$entry_player = DB_array("no","player","$DB_entry where game = $no and victim = 0");
			if($entry_player){
				$entry_player = array_values($entry_player);
				$orderCondition = orderCondition($entry_player);

				$overlap_count =0;

				echo ("<br><br><br><br><br><br>IP를 확인하고 있습니다. 잠시 기다려주세요");
				flush(); 

				$db= new DB($id);
				
				$checkOverlapIdByIp= new CheckOverlapIdByIp($db);
				$checkOverlapIdByIp ->initID($member[no]);
				$checkOverlapIdByIp->detect();

				@fwrite($file,"\$checkOverlapIdByIp->checkedID:".print_r($checkOverlapIdByIp->checkedID,true)); 
				@fwrite($file,"\$entry_player:".print_r($entry_player,true)); 

				$overlapPlayers = array_intersect ($checkOverlapIdByIp->checkedID,$entry_player);

				@fwrite($file,"\$overlapPlayers:".print_r($overlapPlayers,true)); 
			}

			if(count($overlapPlayers)){
				$now = time();
				$ipClashFile = fopen("log/ipClash.txt","a");

				@fwrite($ipClashFile,"게임 - game:".$no."-".$data[subject]." player:".$member[no]." Name:".$member[name]." ip:".$server[ip]." count: ".array_count_values($overlapPlayers)." time: ".date("m",$now)."월 ".date("d",$now)." 일 ".date("H",$now)."시 ".date("i",$now)."분 ".date("s",$now)."초\n"); 
				fclose($ipClashFile);  

				@fwrite($file,"게임 - game:".$no."-".$data[subject]." player:".$member[no]." Name:".$member[name]." ip:".$server[ip]." count: ".array_count_values($overlapPlayers)." time: ".date("m",$now)."월 ".date("d",$now)." 일 ".date("H",$now)."시 ".date("i",$now)."분 ".date("s",$now)."초\n"); 

				Error("같은 IP를 사용한 유저가 이미 참여 중입니다.<br /> 비매너 행위를 막기 위해 같은 IP를 사용한 다른 플레이어와의 참여를 금지하고 있습니다.<br /> 같은 아이피를 사용한 사람과 함께 플레이하려면 비공개 마을을 이용해 주세요.");

				@fwrite($file,$member['name']."님 ip 중복으로 참여 불가"."\n");
			}
		}

		$temp_result=mysql_fetch_array(mysql_query("select * from $DB_entry where game = $no and `character`  = '$selectCharacter'"));
		if(!$temp_result){
			@fwrite($file,"\n".date("Y-m-d H:i:s",time())."- [".$no."]게임에 ".$member['name']."님(".$character_list[$selectCharacter].")이 참여합니다."."\n");

			$sql = "INSERT INTO `$t_board"."_$id"."_entry` ( `no` , `game` ,`name`, `player` , `character` ,  `truecharacter`,`alive`,`ip`) VALUES ('','$no', '$member[name]', '$member[no]',$selectCharacter ,  '', '생존','$server[ip]' );";
			@fwrite($file,"\$sql:".$sql." \n"); 
		 	@mysql_query($sql) or die("참가자 정보를 입력 중에 오류가 발생했습니다.");

			$gameinfo['players']=$gameinfo['players'] + 1;

			$sql = "update `$t_board"."_$id"."_gameinfo` set `players` = '$gameinfo[players]' where game = $no";
			@fwrite($file,"\$sql:".$sql." \n"); 
			@mysql_query($sql) or die("게임에 참여한 플레이어 수를 갱신중에 오류가 발생했습니다.");

			$comment = $character_list[$selectCharacter]."님이 마을에 도착하셨습니다.";
			writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'알림',$entry['character']);
		}

		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// 페이지 이동	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}



	// 플레이어 불참
	if ($function == "delPlayer" and $entry<>"" and $gameinfo['day'] == "0"	and (time() +3) <  $gameinfo['deathtime'] ){

	   @fwrite($file,"\n".date("Y-m-d H:i:s",time())."- [".$no."]게임에서 ".$member['name']."님이 나가십니다."."\n");

		$sql = "delete from $DB_entry where game=$no and player = $member[no] limit 1;";
		@fwrite($file,"\$sql:".$sql." \n"); 
	   @mysql_query($sql) or die("참가자 기록을 삭제 하는 중에 오류가 발생했습니다.delete from $DB_bug where bug=$no limit 1;");
		
		$gameinfo['players']=$gameinfo['players'] -1;

		$sql = "update `$t_board"."_$id"."_gameinfo` set `players` = '$gameinfo[players]' where game = $no";
		@fwrite($file,"\$sql:".$sql." \n"); 
		@mysql_query($sql) or die("게임에 참여한 플레이어 수를 갱신중에 오류가 발생했습니다.");

		$comment = $character_list[$entry[character]]."님이 마을을 떠났습니다.";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'알림',$entry['character']);

		$entry = "";
		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// 페이지 이동	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}	
	//준비 완료	
	if ($function == "isConfirm" and $entry<>"" ){
		$isConfirm = $entry['isConfirm'] ? 0 : 1;
		$sql = "update `$t_board"."_$id"."_entry` set `isConfirm` = $isConfirm  where game = $entry[game] and player = $entry[player]";
		@mysql_query($sql) or die("게임에 참여한 플레이어 수를 갱신중에 오류가 발생했습니다.");

		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// 페이지 이동	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}	

	// 게임 추천하기
	if ($function == "goodGame" and $entry<>"" and $entry['vote'] == 0 and $gameinfo['state'] == "게임끝"	){ 
		$gameinfo['good']=$gameinfo['good'] +1;
		@mysql_query("update `$t_board"."_$id"."_gameinfo` set `good` = '$gameinfo[good]' where game = $no" ) or die("게임에 참여한 플레이어 수를 갱신중에 오류가 발생했습니다.");

		@mysql_query("update `$t_board"."_$id"."_entry` set `vote` = '1' where game = $no  and player = $member[no]" ) or die("게임에 참여한 플레이어 수를 갱신중에 오류가 발생했습니다.");

		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// 페이지 이동	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}

	// 게임 비추천하기
	if ($function == "badGame" and $entry<>"" and $entry['vote'] == 0 and $gameinfo['state'] == "게임끝"	){ 
		$gameinfo['bad']=$gameinfo['bad'] +1;
		@mysql_query("update `$t_board"."_$id"."_gameinfo` set `bad` = '$gameinfo[bad]' where game = $no" ) or die("게임에 참여한 플레이어 수를 갱신중에 오류가 발생했습니다.");

		@mysql_query("update `$t_board"."_$id"."_entry` set `vote` = '2' where game = $no  and player = $member[no]" ) or die("게임에 참여한 플레이어 수를 갱신중에 오류가 발생했습니다.");

		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// 페이지 이동	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}

	// 게임 봉인 찬성하기
	if ($function == "seal_yes" and $entry<>"" and $entry['seal_vote'] == 0 and $gameinfo['state'] == "게임중"	 and $gameinfo['seal'] == "논의"){ 
		$game_seal_yes =$gameinfo['seal_yes'] +1;
		@mysql_query("update `$t_board"."_$id"."_gameinfo` set `seal_yes` = '$game_seal_yes' where game = $no" ) or die("게임에 참여한 플레이어 수를 갱신중에 오류가 발생했습니다.");

		@mysql_query("update `$t_board"."_$id"."_entry` set `seal_vote` = '1' where game = $no  and player = $member[no]" ) or die("게임에 참여한 플레이어 수를 갱신중에 오류가 발생했습니다.");

		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// 페이지 이동	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}

	// 게임 봉인 반대하기
	if ($function == "seal_no" and $entry<>"" and $entry['seal_vote'] == 0 and $gameinfo['state'] == "게임중"	and $gameinfo['seal'] == "논의"){ 
		$game_seal_no =$gameinfo['seal_no'] +1;
		@mysql_query("update `$t_board"."_$id"."_gameinfo` set `seal_no` = '$game_seal_no' where game = $no" ) or die("게임에 참여한 플레이어 수를 갱신중에 오류가 발생했습니다.");

		@mysql_query("update `$t_board"."_$id"."_entry` set `seal_vote` = '2' where game = $no  and player = $member[no]" ) or die("게임에 참여한 플레이어 수를 갱신중에 오류가 발생했습니다.");

		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// 페이지 이동	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}

	//[투표, 능력 끝]////////////////////////////////////////////////////////////////////////////


	// 플레이어 감지
	 if ($function == "detect" and $purpose and $entry['alive'] == "생존" and $truecharacter['detect'] and   !$detect){
		 @mysql_query("INSERT INTO `$DB_detect` ( `game` , `day` ,`target` ) VALUES ('$no', '$gameinfo[day]' , '$purpose' );") or die("감지 중에 오류가 발생했습니다.");

		$comment = "인랑 리더가 ".$character_list[$purpose]."씨의 냄새를 맡고 있다.";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'메모',$entry['character']);

		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";
		// 페이지 이동	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }
	
	// 플레이어 감지취소
	 if ($function == "detectCancel" and $entry['alive'] == "생존" and $truecharacter['detect']  and $detect){
		 @mysql_query("delete from `$DB_detect`  where `game`= $no and `day`= $gameinfo[day]  ;") or die("감지 취소 삭제 중에 오류가 발생했습니다.");

		//코맨트 입력
		$comment = $character_list[$detect['target']]."씨에 대한 감지를 멈췄습니다.";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'메모',$entry['character']);

		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// 페이지 이동	
	movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }

	 // 플레이어 참살
	 if ($function == "mustkill" and $purpose and $entry['alive'] == "생존" and $truecharacter['mustkill'] and   !$mustkill){
		@mysql_query("INSERT INTO `$DB_mustkill` ( `game` , `day` ,`target` ) VALUES ('$no', '$gameinfo[day]' , '$purpose' );") or die("참살 시도 중에 오류가 발생했습니다.");

	   $comment = "좋은 인간은 죽은 인간일 뿐이다. ".$character_list[$purpose]."!";
	   writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'비밀',$entry['character']);

	   // 대상 파일 이름 정리
	   if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";
	   // 페이지 이동	
	   movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}
   
   // 플레이어 참살취소
	if ($function == "mustkillCancel" and $entry['alive'] == "생존" and $truecharacter['mustkill']  and $mustkill){
		@mysql_query("delete from `$DB_mustkill`  where `game`= $no and `day`= $gameinfo[day]  ;") or die("참살 계획을 삭제 중에 오류가 발생했습니다.");

	   //코맨트 입력
	   $comment = $character_list[$mustkill['target']]."씨를 향한 강한 살의가 사라진다.";
	   writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'비밀',$entry['character']);

	   // 대상 파일 이름 정리
	   if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

	   // 페이지 이동	
   movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}

	// 플레이어 복수
	 if ($function == "revenge" and $purpose and $entry['alive'] == "생존" and $truecharacter['revenge'] and   !$revenge){
		 @mysql_query("INSERT INTO `$DB_revenge` ( `game`  ,`target` ) VALUES ('$no' , '$purpose' );") or die("감지 중에 오류가 발생했습니다.");

		$comment = "복수자가 ".$character_list[$purpose]."씨를 노려보고 있다.";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'메모',$entry['character']);

		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";
		// 페이지 이동	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }
	
	// 플레이어 복수취소
	 if ($function == "revengeCancel" and $entry['alive'] == "생존" and $truecharacter['revenge']  and $revenge){
		 @mysql_query("delete from `$DB_revenge`  where `game`= $no  ;") or die("보호 계획를 삭제 중에 오류가 발생했습니다.");

		//코맨트 입력
		$comment = $character_list[$revenge['target']]."씨에 대한 관심이 사라졌다.";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'메모',$entry['character']);

		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// 페이지 이동	
	movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }


	// 플레이어 반쪽 습격
	 if ($function == "halfassault" and $injuredhalf and $entry['alive'] == "생존" and $truecharacter['half-assault'] and   !$halfassault){

		 $sql = 	"INSERT INTO `$DB_deathNoteHalf` ( `game` , `day` , `werewolf` ,  `injured`) VALUES ('$no', '$gameinfo[day]','$entry[character]' , '$injuredhalf' );";
		 
		 @mysql_query($sql) or die("살해 계획을 입력 중에 오류가 발생했습니다.".$sql);

		//코맨트 입력
		$comment = "네 이놈 ".$character_list[$injuredhalf]."! 오늘이 네 제삿날이다!!!";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'메모',$entry['character']);

		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";
		// 페이지 이동	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }

	// 플레이어 반쪽 습격취소
	 if ($function == "halfassaultCancel" and $entry['alive'] == "생존" and $truecharacter['half-assault']  and $halfassault){
		 @mysql_query("delete from `$DB_deathNoteHalf`  where `game`= $no and `day`= $gameinfo[day] and  `werewolf` = $entry[character] ;") or die("살해 계획를 삭제 중에 오류가 발생했습니다.");

		//코맨트 입력
		$comment = $character_list[$halfassault[injured]]."을 향한 발톱을 감추었습니다.";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'메모',$entry['character']);

		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// 페이지 이동	
	movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }


	// 플레이어 습격
	 if ($function == "assaultCon" and $injured and $entry['alive'] == "생존" and $truecharacter['assault-con'] and   !$assaultCon){
		$assaultCharacter =  DB_array("no","no","$DB_truecharacter where assault  = 1");
//echo "\$assaultCharacter:";print_r($assaultCharacter);echo "<br>";

		$orderCondition ="in (";

		foreach($assaultCharacter  as $t_assault){
			$orderCondition.=$t_assault.",";
		}
		if($viewDay <6 )$orderCondition.="16,";
		$orderCondition.=")";

		$orderCondition = str_replace(",)", ")", $orderCondition);
//echo "\$orderCondition:".$orderCondition."<br>";



		$assault_list =  DB_array("no","character","$DB_entry where game = $no and alive='생존' and truecharacter $orderCondition");	
		$assault_list = array_values($assault_list);
//echo "\$assault_list:";print_r($assault_list);echo "<br><br>";

//echo "select * from $DB_entry where game=$no and character = $injured<br>";
		$injured = mysql_fetch_array(mysql_query("select * from $DB_entry where game=$no and `character`= $injured "));

		if(!in_array($injured[character],$assault_list )){
			 @mysql_query(	"INSERT INTO `$DB_deathNote` ( `game` , `day` , `werewolf` ,  `injured`) VALUES ('$no', '$gameinfo[day]','$entry[character]' , '$injured[character]' );") or die("살해 계획을 입력 중에 오류가 발생했습니다.");

			//코맨트 입력
			$comment = "네 이놈 ".$character_list[$injured[character]]."! 오늘이 네 제삿날이다!!!";
			writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'비밀',$entry['character']);
		}


			// 대상 파일 이름 정리
			if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";
			// 페이지 이동	
			movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }

	// 플레이어 습격취소
	 if ($function == "assaultConCancel" and $entry['alive'] == "생존" and $truecharacter['assault-con']  and $assaultCon){
		 @mysql_query("delete from `$DB_deathNote`  where `game`= $no and `day`= $gameinfo[day] and  `werewolf` = $entry[character] ;") or die("살해 계획를 삭제 중에 오류가 발생했습니다.");

		//코맨트 입력
		$comment = $character_list[$assaultCon[injured]]."을 향한 발톱을 감추었습니다.";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'비밀',$entry['character']);

		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// 페이지 이동	
	movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }

	 
	?>