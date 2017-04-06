<?
	// 1분 후에 게임 시작하기
	if ($function == "start" and $is_admin	){
		$newDeathtime  = time() + 60;
		@mysql_query("update `$t_board"."_$id"."_gameinfo` set `deathtime` = '$newDeathtime' where game = $no" ) or die("게임에 참여한 플레이어 수를 갱신중에 오류가 발생했습니다.");

		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// 페이지 이동	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}

	// 하루 앞으로 
	if ($function == "forwardAday" and $is_admin	){
		$newDeathtime  = $gameinfo['deathtime'] - $gameinfo['termOfDay'];
		echo $newDeathtime  ;
		@mysql_query("update `$t_board"."_$id"."_gameinfo` set `deathtime` = '$newDeathtime' where game = $no" ) or die("게임에 참여한 플레이어 수를 갱신중에 오류가 발생했습니다.");

		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// 페이지 이동	
		//movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}

	// 하루 뒤로 
	if ($function == "backAday" and $is_admin	){
		$newDeathtime  = $gameinfo['deathtime'] + $gameinfo['termOfDay'];
		@mysql_query("update `$t_board"."_$id"."_gameinfo` set `deathtime` = '$newDeathtime' where game = $no" ) or die("게임에 참여한 플레이어 수를 갱신중에 오류가 발생했습니다.");

		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// 페이지 이동	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}
	// 게임 준비하기
	if ($function == "preparation" and $is_admin	){
		$char_list = array(4,5);
		$player_list = array(10004,10005);

		$char_list = array(4,5);
		$player_list = array(10004,10005);

		$char_list = array(1,2,3,4,5,6,7,8,9,10);
		$player_list = array(10001,10002,10003,10004,10005,10006,10007,10008,10009,10010);


		for($in = 0 ; $in <count($player_list); $in++){
		 	@mysql_query(
			"INSERT INTO `$t_board"."_$id"."_entry` ( `no` , `game` ,`name`, `player` , `character` ,  `truecharacter`,`alive`,`ip`) VALUES ('','$no', '$member[name]', '$player_list[$in]','$char_list[$in]' ,  '', '생존','$server[ip]' );") or die("참가자 정보를 입력 중에 오류가 발생했습니다.");

			$gameinfo['players']=$gameinfo['players'] + 1;
			@mysql_query("update `$t_board"."_$id"."_gameinfo` set `players` = '$gameinfo[players]' where game = $no" ) or die("게임에 참여한 플레이어 수를 갱신중에 오류가 발생했습니다.");

			$comment = $character_list[$char_list[$in]]."님이 마을에 도착하셨습니다.";
			writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'알림',$char_list[$in]);
		}

		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// 페이지 이동	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}



	// 모든 플레이어가 발언한 상태로 만든다.
	if ($function == "CommentCheck" and $is_admin){
			$CommentPlayer_list = DB_array("no","character","$DB_entry where game = $no and alive='생존' and  victim= '0' ");
			echo "\$CommentPlayer_list:";print_r($CommentPlayer_list);echo "<br>";

			//코맨트  초기화
			if($CommentPlayer_list){
				foreach($CommentPlayer_list  as $CommentPlayer){
					echo "update $DB_entry set comment = '1' where game = '$no' and  `character` = '$CommentPlayer'" ."<br>";
					mysql_query("update $DB_entry set comment = '1' where game = '$no' and  `character` = '$CommentPlayer'") or error(mysql_error());
				}
			}		
		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// 페이지 이동	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}

	//모든 플레이어의 발언 수를 초기화 한다.
	if($function =="CommentNumInit" and $is_admin){
					// 사람들 리스트 뽑아내기!
			$CommentPlayer_list = DB_array("no","character","$DB_entry where game = $no and victim=0 ");			
			echo "\$CommentPlayer_list:";print_r($CommentPlayer_list);echo "<br>";

			//코맨트  초기화
			if($CommentPlayer_list){
				foreach($CommentPlayer_list  as $CommentPlayer){
					echo "update $DB_entry set  normal ='20', memo  ='10' , secret  ='40' , grave  ='20', telepathy   ='1' where game = '$no' and  `character` = '$CommentPlayer'" ."<br>";
					mysql_query("update $DB_entry set  normal ='20', memo  ='10' , secret  ='40' , grave  ='20', telepathy   ='1' where game = '$no' and  `character` = '$CommentPlayer'") or error(mysql_error());
				}
			}
	}

	if($function =="resurrection" and $is_admin){
					// 사람들 리스트 뽑아내기!
			$CommentPlayer_list = DB_array("no","character","$DB_entry where game = $no and  victim= '0' ");
			echo "\$CommentPlayer_list:";print_r($CommentPlayer_list);echo "<br>";

			//코맨트  초기화
			if($CommentPlayer_list){
				foreach($CommentPlayer_list  as $CommentPlayer){
					echo "update $DB_entry set  alive  ='생존', deathday   ='' , deathtype   =''  where game = '$no' and  `character` = '$CommentPlayer'" ."<br>";
					mysql_query("update $DB_entry set  alive  ='생존', deathday   ='' , deathtype   =''  where game = '$no' and  `character` = '$CommentPlayer'") or error(mysql_error());
				}
			}
	}
	if($function =="suddenDeathCheck" and $is_admin){
		suddenDeathCheck($file,$t_board."_".$id,$no,$gameinfo['day']);
	}
	if($function =="postNoManner" and $is_admin){
		postNoManner($file,$t_division,$t_board,$id,$no,$deathday,$server[ip]);
	}
	if($function =="writeCommnet" and $is_admin){
		$comment="씨에 대한 경계를 멈췄습니다.";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'일반',$entry['character']);
	}

	if($gameinfo['state'] =="게임끝" and $is_admin and 0){//$function =="record"
	//게임이 종료되었다면 기록을 남긴다.
		record($file,$t_board."_".$id,$no);
		// 대상 파일 이름 정리
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";			
		// 페이지 이동
		//if(!$is_admin)movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}
if($gameinfo['state'] =="게임끝" and $is_admin and $function =="record"){

	$EndGame_list = DB_array("game","game","$DB_gameinfo where state = '게임끝' and game not in (16,17) ");
	fwrite($file,"\$EndGame_list:".print_r($EndGame_list,true)); 

	if($EndGame_list){
		foreach($EndGame_list  as $EndGame){
			fwrite($file,"\$Game:".$EndGame." \n"); 
			//게임이 종료되었다면 기록을 남긴다.
			record($file,$t_board."_".$id,$EndGame);
		}
	}		

	// 대상 파일 이름 정리
	if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";			
	// 페이지 이동
	//if(!$is_admin)movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
}

if($function =="delRecord" and $is_admin){
	$entry_player = DB_array("player","truecharacter","$DB_entry where game = $no and victim = 0");
	$wintype = DB_array("no","wintype","$DB_truecharacter");
echo "\$entry_player:";print_r($entry_player);echo "<br>";
echo "\$wintype:";print_r($wintype);echo "<br>";

	if($gameinfo['state']=="게임끝"){
			reset($entry_player);
			while (list($player,$truecharacter )=each($entry_player)){
echo "\$player:".$player."<br>";
echo "\$truecharacter:".$truecharacter."<br>";

				$record = @mysql_fetch_array(mysql_query("select * from $DB_record where player = $player"));
echo "\$record:";print_r($record);echo "<br>";

				if($record){
				$player_info = @mysql_fetch_array(mysql_query("select * from $DB_entry where game = $no and player = $player"));
echo "\$player_info:";print_r($player_info);echo "<br>";
					 
					 switch ($wintype[$truecharacter]) {
						case 0:
							if($gameinfo[win] == 0 ) $record[humanWin] -= 1;
							else $record[humanLose] -=1;
						   break;
						case 1:
							if($gameinfo[win] == 1 ) $record[werewolfWin] -= 1;
							else $record[werewolfLose] -=1;
							break;
						case 2:
							if($gameinfo[win] == 2 ) $record[hamsterWin] -= 1;
							else $record[hamsterLose] -=1;
							break;
					}

					 switch ($player_info[deathtype]) {
						case "심판":
							 $record[vothDeath]-=1;
							  break;
						case "습격":
							 $record[assaultDeath] -=1;
							 break;
						case "돌연":
							 $record[suddenDeath]-=1;
							 break;
					}
					
					switch($truecharacter){
						case 1:				 
							 $record[meek]-=1;
							 break;
						case 2:
							 $record[fortuneteller]-=1;
							 break;
						case 3:
							 $record[medium]-=1;
							 break;
						case 4:
							 $record[madman]-=1;
							 break;
						case 5:
							 $record[werewolf] -=1;
							 break;
						case 6:
							 $record[hunter] -=1;
							 break;
						case 7:
							 $record[psychic] -=1;
							 break;
						case 8:
							 $record[hamster] -=1;
							 break;
					}

					echo "update $DB_record set `humanWin`= '$record[humanWin]' ,  `humanLose`= '$record[humanLose]' , `werewolfWin`= '$record[werewolfWin]' ,  `werewolfLose`= '$record[werewolfLose]' , `hamsterWin`= '$record[hamsterWin]' ,  `hamsterLose`= '$record[hamsterLose]' ,  `vothDeath`= '$record[vothDeath]' ,  `assaultDeath`= '$record[assaultDeath]' ,  `suddenDeath`= '$record[suddenDeath]' ,  `meek`= '$record[meek]' ,  `fortuneteller`= '$record[fortuneteller]'  ,  `medium`= '$record[medium]'  ,  `madman`= '$record[madman]'  ,  `werewolf`= '$record[werewolf]'  ,  `hunter`= '$record[hunter]' ,  `psychic`= '$record[psychic]',  `hamster`= '$record[hamster]' where player = $record[player]<br>";

					@mysql_query("update $DB_record set `humanWin`= '$record[humanWin]' ,  `humanLose`= '$record[humanLose]' , `werewolfWin`= '$record[werewolfWin]' ,  `werewolfLose`= '$record[werewolfLose]' , `hamsterWin`= '$record[hamsterWin]' ,  `hamsterLose`= '$record[hamsterLose]' ,  `vothDeath`= '$record[vothDeath]' ,  `assaultDeath`= '$record[assaultDeath]' ,  `suddenDeath`= '$record[suddenDeath]' ,  `meek`= '$record[meek]' ,  `fortuneteller`= '$record[fortuneteller]'  ,  `medium`= '$record[medium]'  ,  `madman`= '$record[madman]'  ,  `werewolf`= '$record[werewolf]'  ,  `hunter`= '$record[hunter]' ,  `psychic`= '$record[psychic]',  `hamster`= '$record[hamster]' where player = $record[player]")or error(mysql_error());
				}
			}
	}

				// 대상 파일 이름 정리
			if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";			
			// 페이지 이동
			if(!$is_admin)movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
}


?>