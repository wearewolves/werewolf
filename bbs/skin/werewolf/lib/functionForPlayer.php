<?
//[��ǥ, �ɷ� ����]////////////////////////////////////////////////////////////////////////////
// �÷��̾� ��ǥ
	 if ($function == "vote" and $entry['alive'] == "����" and !$vote and $candidacy){
		//error("INSERT INTO `$DB_vote` ( `game` , `day` , `voter` ,  `candidacy`) VALUES ('$no', '$gameinfo[day]','$entry[character]' , '$candidacy' );");
		 if($truecharacter['double-vote']){
			 $sql = "INSERT INTO `$DB_vote` ( `game` , `day` , `voter` ,  `candidacy`) VALUES ('$no', '$gameinfo[day]','$entry[character]' , '$candidacy' );";
			 @mysql_query($sql) or die("��ǥ ������ �Է� �߿� ������ �߻��߽��ϴ�.");		 

			 $sql = "INSERT INTO `$DB_vote` ( `game` , `day` , `voter` ,  `candidacy`) VALUES ('$no', '$gameinfo[day]','$entry[character]' , '$candidacy' );";
			 @mysql_query($sql) or die("��ǥ ������ �Է� �߿� ������ �߻��߽��ϴ�.");

			 //�ڸ�Ʈ �Է�
			 $comment = "���Ȱ��� ".$character_list[$candidacy]."�Կ��� 2ǥ�� �������ϴ�.";
		 }else{
			 $sql = "INSERT INTO `$DB_vote` ( `game` , `day` , `voter` ,  `candidacy`) VALUES ('$no', '$gameinfo[day]','$entry[character]' , '$candidacy' );";
			 @mysql_query($sql) or die("��ǥ ������ �Է� �߿� ������ �߻��߽��ϴ�.");
			 
			 //�ڸ�Ʈ �Է�
			 $comment = $character_list[$entry[character]]."���� ".$character_list[$candidacy]."�Կ��� ǥ�� �������ϴ�.";

		 }

		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'�޸�',$entry['character']);

		// ��� ���� �̸� ����
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// ������ �̵�
	movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }

// �÷��̾� ��ǥ���
	 if ($function == "voteCancel" and $entry['alive'] == "����" and $vote){
		 @mysql_query(
		"delete from `$DB_vote`  where `game`= $no and `day`= $gameinfo[day] and  `voter` = $entry[character] ;") or die("��ǥ ������ ���� �߿� ������ �߻��߽��ϴ�.");

		//�ڸ�Ʈ �Է�
		$comment = $character_list[$entry[character]]."���� ��ǥ�� ����߽��ϴ�.";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'�޸�',$entry['character']);

		// ��� ���� �̸� ����
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// ������ �̵�	
	movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }

	 // 2021/07/26 epi : ������ üũ �κ�
	 $CheckPublicSeer = checkSubRule($gameinfo['subRule'], 5);

// �÷��̾� ��ġ�� ��ǥ
if ($function == "seervote" and $entry['alive'] == "����" and !$seervote and $CheckPublicSeer){
	 if($truecharacter['double-vote']){
		 $sql = "INSERT INTO `$DB_seervote` ( `game` , `day` , `voter` ,  `candidacy`) VALUES ('$no', '$gameinfo[day]','$entry[character]' , '$seercandidacy' );";
		 @mysql_query($sql) or die("��ġ�� ��ǥ ������ �Է� �߿� ������ �߻��߽��ϴ�.");		 

		 $sql = "INSERT INTO `$DB_seervote` ( `game` , `day` , `voter` ,  `candidacy`) VALUES ('$no', '$gameinfo[day]','$entry[character]' , '$seercandidacy' );";
		 @mysql_query($sql) or die("��ġ�� ��ǥ ������ �Է� �߿� ������ �߻��߽��ϴ�.");

		 //�ڸ�Ʈ �Է�
		 $comment = "���Ȱ��� ".$character_list[$seercandidacy]."���� ��ü�� 2��� �ñ����մϴ�.";
	 }else{
		 $sql = "INSERT INTO `$DB_seervote` ( `game` , `day` , `voter` ,  `candidacy`) VALUES ('$no', '$gameinfo[day]','$entry[character]' , '$seercandidacy' );";
		 @mysql_query($sql) or die("��ġ�� ��ǥ ������ �Է� �߿� ������ �߻��߽��ϴ�.");
		 
		 //�ڸ�Ʈ �Է�
		 $comment = $character_list[$entry[character]]."���� ".$character_list[$seercandidacy]."���� ��ü�� �ñ����մϴ�.";

	 }

	writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'�޸�',$entry['character']);

	// ��� ���� �̸� ����
	if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

	// ������ �̵�
movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
 }

// �÷��̾� ��ġ�� ��ǥ���
	 if ($function == "seervoteCancel" and $entry['alive'] == "����" and $seervote and $CheckPublicSeer){
		 @mysql_query(
		"delete from `$DB_seervote`  where `game`= $no and `day`= $gameinfo[day] and  `voter` = $entry[character] ;") or die("��ġ�� ��ǥ ������ ���� �߿� ������ �߻��߽��ϴ�.");

		//�ڸ�Ʈ �Է�
		$comment = $character_list[$entry[character]]."���� ������ ������� �ֹ��� ���߾����ϴ�.";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'�޸�',$entry['character']);

		// ��� ���� �̸� ����
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// ������ �̵�	
	movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }

// �÷��̾� ��ġ��
	 if ($function == "forecast" and $mystery and $entry['alive'] == "����" and $truecharacter['forecast']  and  !$forecast and !$CheckPublicSeer){
		$target_entry = mysql_fetch_array(mysql_query("select * from $DB_entry where `game`='$no' and `character` = '$mystery'")) or die("select * from $DB_entry where `game`='$no' and `character` = '$mystery'");
		$target_truecharacter =mysql_fetch_array(mysql_query("select * from $DB_truecharacter where no='$target_entry[truecharacter]'"));

		 @mysql_query(
		"INSERT INTO `$DB_revelation` ( `game` , `day` , `type`,`prophet` ,  `mystery`,`result`) VALUES ('$no', '$gameinfo[day]','��','$entry[character]' , '$mystery', '$target_truecharacter[race]' );") or die("INSERT INTO `$DB_revelation` ( `game` , `day` , `prophet` ,  `mystery`,`result`) VALUES ('$no', '$gameinfo[day]','$entry[character]' , '$mystery', '$target_truecharacter[race]' );"."���� ������ �Է� �߿� ������ �߻��߽��ϴ�.");
	 
		//�ڸ�Ʈ �Է�
		//$comment = $character_list[$mystery]."���� ��ü�� �˱� ���� ���� ������ ���� �ִ�.";
		$comment = "�� ���: ".$character_list[$mystery];
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'�޸�',$entry['character']);

		// ��� ���� �̸� ����
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// ������ �̵�	
	movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }

// ��ġ�� ���
	if ($function == "forecastCancel" and $entry['alive'] == "����" and $forecast and !$CheckPublicSeer){
	   @mysql_query("delete from $DB_revelation where game=$no and `day`= $gameinfo[day] and prophet  = $entry[character] and type ='��' limit 1;") or die("������ ����� ���� �ϴ� �߿� ������ �߻��߽��ϴ�.delete from $DB_bug where bug=$no limit 1;");

		//�ڸ�Ʈ �Է�
		//$comment = $character_list[$entry[character]]."���� ������ ������� �ֹ��� ���߾����ϴ�.";
		$comment = "�� ���";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'�޸�',$entry['character']);		

		// ��� ���� �̸� ����
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// ������ �̵�	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}	
	
// �÷��̾� ��ġ��
	 if ($function == "forecastOdd" and $mystery and $entry['alive'] == "����" and $truecharacter['forecast-odd']  and  !$forecastOdd and $viewDay%2 == 1 and !$CheckPublicSeer){
		$target_entry = mysql_fetch_array(mysql_query("select * from $DB_entry where `game`='$no' and `character` = '$mystery'")) or die("select * from $DB_entry where `game`='$no' and `character` = '$mystery'");
		$target_truecharacter =mysql_fetch_array(mysql_query("select * from $DB_truecharacter where no='$target_entry[truecharacter]'"));

		 @mysql_query(
		"INSERT INTO `$DB_revelation` ( `game` , `day` , `type`,`prophet` ,  `mystery`,`result`) VALUES ('$no', '$gameinfo[day]','��','$entry[character]' , '$mystery', '$target_truecharacter[race]' );") or die("INSERT INTO `$DB_revelation` ( `game` , `day` , `prophet` ,  `mystery`,`result`) VALUES ('$no', '$gameinfo[day]','$entry[character]' , '$mystery', '$target_truecharacter[race]' );"."���� ������ �Է� �߿� ������ �߻��߽��ϴ�.");
	 
		//�ڸ�Ʈ �Է�
		//$comment = $character_list[$mystery]."���� ��ü�� �˱� ���� ���� ������ ���� �ִ�.";
		$comment = "�� ���: ".$character_list[$mystery];
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'�޸�',$entry['character']);

		// ��� ���� �̸� ����
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// ������ �̵�	
	movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }

// ��ġ�� ���
	if ($function == "forecastOddCancel" and $entry['alive'] == "����" and $forecastOdd and $viewDay%2 == 1 and !$CheckPublicSeer){
	   @mysql_query("delete from $DB_revelation where game=$no and `day`= $gameinfo[day] and prophet  = $entry[character] and type ='��' limit 1;") or die("������ ����� ���� �ϴ� �߿� ������ �߻��߽��ϴ�.delete from $DB_bug where bug=$no limit 1;");

		//�ڸ�Ʈ �Է�
		//$comment = $character_list[$entry[character]]."���� ������ ������� �ֹ��� ���߾����ϴ�.";
		$comment = "�� ���";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'�޸�',$entry['character']);		

		// ��� ���� �̸� ����
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// ������ �̵�	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}	

	// �÷��̾� ����
	 if ($function == "assault" and $injured and $entry['alive'] == "����" and $truecharacter['assault'] and   !$assault){
		$assaultCharacter =  DB_array("no","no","$DB_truecharacter where assault  = 1");
//echo "\$assaultCharacter:";print_r($assaultCharacter);echo "<br>";

		$orderCondition ="in (";

		foreach($assaultCharacter  as $t_assault){
			$orderCondition.=$t_assault.",";
		}
		$orderCondition.=")";

		$orderCondition = str_replace(",)", ")", $orderCondition);
//echo "\$orderCondition:".$orderCondition."<br>";



		$assault_list =  DB_array("no","character","$DB_entry where game = $no and alive='����' and truecharacter $orderCondition");	
		$assault_list = array_values($assault_list);
		
		// 2017/05/07 epi : ������ üũ �κ�
		$CheckAssaultWerewolf = checkSubRule($gameinfo['subRule'], 1);
//echo "\$assault_list:";print_r($assault_list);echo "<br><br>";

//echo "select * from $DB_entry where game=$no and character = $injured<br>";
		$injured = mysql_fetch_array(mysql_query("select * from $DB_entry where game=$no and `character`= $injured "));

		// 2017/05/07 epi : ���� ����ڰ� �´��� Ȯ��, Ȥ�� ���������� Ȯ��
		if(!in_array($injured[character],$assault_list )|| $CheckAssaultWerewolf){
			 @mysql_query(	"INSERT INTO `$DB_deathNote` ( `game` , `day` , `werewolf` ,  `injured`) VALUES ('$no', '$gameinfo[day]','$entry[character]' , '$injured[character]' );") or die("���� ��ȹ�� �Է� �߿� ������ �߻��߽��ϴ�.");

			//�ڸ�Ʈ �Է�
			$comment = "�� �̳� ".$character_list[$injured[character]]."! ������ �� �����̴�!!!";
			writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'���',$entry['character']);
		}


			// ��� ���� �̸� ����
			if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";
			// ������ �̵�	
			movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }

	// �÷��̾� �������
	 if ($function == "assaultCancel" and $entry['alive'] == "����" and $truecharacter['assault']  and $assault){
		 @mysql_query("delete from `$DB_deathNote`  where `game`= $no and `day`= $gameinfo[day] and  `werewolf` = $entry[character] ;") or die("���� ��ȹ�� ���� �߿� ������ �߻��߽��ϴ�.");

		//�ڸ�Ʈ �Է�
		$comment = $character_list[$assault[injured]]."�� ���� ������ ���߾����ϴ�.";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'���',$entry['character']);

		// ��� ���� �̸� ����
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// ������ �̵�	
	movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }

	// �÷��̾� ��ȣ
	 if ($function == "guard" and $purpose and $entry['alive'] == "����" and $truecharacter['guard'] and   !$guard){
		 @mysql_query("INSERT INTO `$DB_guard` ( `game` , `day` ,`hunter`, `purpose` ) VALUES ('$no', '$gameinfo[day]' ,'$entry[character]', '$purpose' );") or die("��ȣ ��ȹ�� �Է� �߿� ������ �߻��߽��ϴ�.");

		$comment = "��ɲ��� ".$character_list[$purpose]."���� �������� ��踦 ���� �ֽ��ϴ�.";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'�޸�',$entry['character']);

		// ��� ���� �̸� ����
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";
		// ������ �̵�	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }
	
	// �÷��̾� ��ȣ���
	 if ($function == "guardCancel" and $entry['alive'] == "����" and $truecharacter['guard']  and $guard){
		 @mysql_query("delete from `$DB_guard`  where `game`= $no and `day`= $gameinfo[day] and  `hunter` = $entry[character] ;") or die("��ȣ ��ȹ�� ���� �߿� ������ �߻��߽��ϴ�.");

		//�ڸ�Ʈ �Է�
		$comment = $character_list[$guard[purpose]]."���� ���� ��踦 ������ϴ�.";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'�޸�',$entry['character']);

		// ��� ���� �̸� ����
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// ������ �̵�	
	movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }



	
	// �÷��̾� ����
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
			error("��ų� ������ ���� ���ӿ� ������ �� �����ϴ�.");
		}	
		elseif($member['no']  <  1){
			echo "�α��� ���ּ���. ";
		}	
		elseif($gameinfo['termOfDay'] > 1800){
			if($member['level'] == 7){
			}
			elseif($member['level'] == 9) error("�ű� ȸ���� 24�ð� ������ ������ �� �����ϴ�.");
		}
		//elseif($gameinfo['termOfDay'] <= 1800) 





		//����� ������ �ƴ϶�� ���� IP�� ����� ����� �����ߴ��� Ȯ���Ѵ�.
		if($data[is_secret] == false and $check_ip){
			$entry_player = DB_array("no","player","$DB_entry where game = $no and victim = 0");
			if($entry_player){
				$entry_player = array_values($entry_player);
				$orderCondition = orderCondition($entry_player);

				$overlap_count =0;

				echo ("<br><br><br><br><br><br>IP�� Ȯ���ϰ� �ֽ��ϴ�. ��� ��ٷ��ּ���");
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

				@fwrite($ipClashFile,"���� - game:".$no."-".$data[subject]." player:".$member[no]." Name:".$member[name]." ip:".$server[ip]." count: ".array_count_values($overlapPlayers)." time: ".date("m",$now)."�� ".date("d",$now)." �� ".date("H",$now)."�� ".date("i",$now)."�� ".date("s",$now)."��\n"); 
				fclose($ipClashFile);  

				@fwrite($file,"���� - game:".$no."-".$data[subject]." player:".$member[no]." Name:".$member[name]." ip:".$server[ip]." count: ".array_count_values($overlapPlayers)." time: ".date("m",$now)."�� ".date("d",$now)." �� ".date("H",$now)."�� ".date("i",$now)."�� ".date("s",$now)."��\n"); 

				Error("���� IP�� ����� ������ �̹� ���� ���Դϴ�.<br /> ��ų� ������ ���� ���� ���� IP�� ����� �ٸ� �÷��̾���� ������ �����ϰ� �ֽ��ϴ�.<br /> ���� �����Ǹ� ����� ����� �Բ� �÷����Ϸ��� ����� ������ �̿��� �ּ���.");

				@fwrite($file,$member['name']."�� ip �ߺ����� ���� �Ұ�"."\n");
			}
		}

		$temp_result=mysql_fetch_array(mysql_query("select * from $DB_entry where game = $no and `character`  = '$selectCharacter'"));
		if(!$temp_result){
			@fwrite($file,"\n".date("Y-m-d H:i:s",time())."- [".$no."]���ӿ� ".$member['name']."��(".$character_list[$selectCharacter].")�� �����մϴ�."."\n");

			$sql = "INSERT INTO `$t_board"."_$id"."_entry` ( `no` , `game` ,`name`, `player` , `character` ,  `truecharacter`,`alive`,`ip`) VALUES ('','$no', '$member[name]', '$member[no]',$selectCharacter ,  '', '����','$server[ip]' );";
			@fwrite($file,"\$sql:".$sql." \n"); 
		 	@mysql_query($sql) or die("������ ������ �Է� �߿� ������ �߻��߽��ϴ�.");

			$gameinfo['players']=$gameinfo['players'] + 1;

			$sql = "update `$t_board"."_$id"."_gameinfo` set `players` = '$gameinfo[players]' where game = $no";
			@fwrite($file,"\$sql:".$sql." \n"); 
			@mysql_query($sql) or die("���ӿ� ������ �÷��̾� ���� �����߿� ������ �߻��߽��ϴ�.");

			$comment = $character_list[$selectCharacter]."���� ������ �����ϼ̽��ϴ�.";
			writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'�˸�',$entry['character']);
		}

		// ��� ���� �̸� ����
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// ������ �̵�	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}



	// �÷��̾� ����
	if ($function == "delPlayer" and $entry<>"" and $gameinfo['day'] == "0"	and (time() +3) <  $gameinfo['deathtime'] ){

	   @fwrite($file,"\n".date("Y-m-d H:i:s",time())."- [".$no."]���ӿ��� ".$member['name']."���� �����ʴϴ�."."\n");

		$sql = "delete from $DB_entry where game=$no and player = $member[no] limit 1;";
		@fwrite($file,"\$sql:".$sql." \n"); 
	   @mysql_query($sql) or die("������ ����� ���� �ϴ� �߿� ������ �߻��߽��ϴ�.delete from $DB_bug where bug=$no limit 1;");
		
		$gameinfo['players']=$gameinfo['players'] -1;

		$sql = "update `$t_board"."_$id"."_gameinfo` set `players` = '$gameinfo[players]' where game = $no";
		@fwrite($file,"\$sql:".$sql." \n"); 
		@mysql_query($sql) or die("���ӿ� ������ �÷��̾� ���� �����߿� ������ �߻��߽��ϴ�.");

		$comment = $character_list[$entry[character]]."���� ������ �������ϴ�.";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'�˸�',$entry['character']);

		$entry = "";
		// ��� ���� �̸� ����
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// ������ �̵�	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}	
	//�غ� �Ϸ�	
	if ($function == "isConfirm" and $entry<>"" ){
		$isConfirm = $entry['isConfirm'] ? 0 : 1;
		$sql = "update `$t_board"."_$id"."_entry` set `isConfirm` = $isConfirm  where game = $entry[game] and player = $entry[player]";
		@mysql_query($sql) or die("���ӿ� ������ �÷��̾� ���� �����߿� ������ �߻��߽��ϴ�.");

		// ��� ���� �̸� ����
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// ������ �̵�	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}	

	// ���� ��õ�ϱ�
	if ($function == "goodGame" and $entry<>"" and $entry['vote'] == 0 and $gameinfo['state'] == "���ӳ�"	){ 
		$gameinfo['good']=$gameinfo['good'] +1;
		@mysql_query("update `$t_board"."_$id"."_gameinfo` set `good` = '$gameinfo[good]' where game = $no" ) or die("���ӿ� ������ �÷��̾� ���� �����߿� ������ �߻��߽��ϴ�.");

		@mysql_query("update `$t_board"."_$id"."_entry` set `vote` = '1' where game = $no  and player = $member[no]" ) or die("���ӿ� ������ �÷��̾� ���� �����߿� ������ �߻��߽��ϴ�.");

		// ��� ���� �̸� ����
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// ������ �̵�	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}

	// ���� ����õ�ϱ�
	if ($function == "badGame" and $entry<>"" and $entry['vote'] == 0 and $gameinfo['state'] == "���ӳ�"	){ 
		$gameinfo['bad']=$gameinfo['bad'] +1;
		@mysql_query("update `$t_board"."_$id"."_gameinfo` set `bad` = '$gameinfo[bad]' where game = $no" ) or die("���ӿ� ������ �÷��̾� ���� �����߿� ������ �߻��߽��ϴ�.");

		@mysql_query("update `$t_board"."_$id"."_entry` set `vote` = '2' where game = $no  and player = $member[no]" ) or die("���ӿ� ������ �÷��̾� ���� �����߿� ������ �߻��߽��ϴ�.");

		// ��� ���� �̸� ����
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// ������ �̵�	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}

	// ���� ���� �����ϱ�
	if ($function == "seal_yes" and $entry<>"" and $entry['seal_vote'] == 0 and $gameinfo['state'] == "������"	 and $gameinfo['seal'] == "����"){ 
		$game_seal_yes =$gameinfo['seal_yes'] +1;
		@mysql_query("update `$t_board"."_$id"."_gameinfo` set `seal_yes` = '$game_seal_yes' where game = $no" ) or die("���ӿ� ������ �÷��̾� ���� �����߿� ������ �߻��߽��ϴ�.");

		@mysql_query("update `$t_board"."_$id"."_entry` set `seal_vote` = '1' where game = $no  and player = $member[no]" ) or die("���ӿ� ������ �÷��̾� ���� �����߿� ������ �߻��߽��ϴ�.");

		// ��� ���� �̸� ����
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// ������ �̵�	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}

	// ���� ���� �ݴ��ϱ�
	if ($function == "seal_no" and $entry<>"" and $entry['seal_vote'] == 0 and $gameinfo['state'] == "������"	and $gameinfo['seal'] == "����"){ 
		$game_seal_no =$gameinfo['seal_no'] +1;
		@mysql_query("update `$t_board"."_$id"."_gameinfo` set `seal_no` = '$game_seal_no' where game = $no" ) or die("���ӿ� ������ �÷��̾� ���� �����߿� ������ �߻��߽��ϴ�.");

		@mysql_query("update `$t_board"."_$id"."_entry` set `seal_vote` = '2' where game = $no  and player = $member[no]" ) or die("���ӿ� ������ �÷��̾� ���� �����߿� ������ �߻��߽��ϴ�.");

		// ��� ���� �̸� ����
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// ������ �̵�	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}

	//[��ǥ, �ɷ� ��]////////////////////////////////////////////////////////////////////////////


	// �÷��̾� ����
	 if ($function == "detect" and $purpose and $entry['alive'] == "����" and $truecharacter['detect'] and   !$detect){
		 @mysql_query("INSERT INTO `$DB_detect` ( `game` , `day` ,`target` ) VALUES ('$no', '$gameinfo[day]' , '$purpose' );") or die("���� �߿� ������ �߻��߽��ϴ�.");

		$comment = "�ζ� ������ ".$character_list[$purpose]."���� ������ �ð� �ִ�.";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'�޸�',$entry['character']);

		// ��� ���� �̸� ����
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";
		// ������ �̵�	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }
	
	// �÷��̾� �������
	 if ($function == "detectCancel" and $entry['alive'] == "����" and $truecharacter['detect']  and $detect){
		 @mysql_query("delete from `$DB_detect`  where `game`= $no and `day`= $gameinfo[day]  ;") or die("���� ��� ���� �߿� ������ �߻��߽��ϴ�.");

		//�ڸ�Ʈ �Է�
		$comment = $character_list[$detect['target']]."���� ���� ������ ������ϴ�.";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'�޸�',$entry['character']);

		// ��� ���� �̸� ����
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// ������ �̵�	
	movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }

	 // �÷��̾� ����
	 if ($function == "mustkill" and $purpose and $entry['alive'] == "����" and $truecharacter['mustkill'] and   !$mustkill){
		@mysql_query("INSERT INTO `$DB_mustkill` ( `game` , `day` ,`target` ) VALUES ('$no', '$gameinfo[day]' , '$purpose' );") or die("���� �õ� �߿� ������ �߻��߽��ϴ�.");

	   $comment = "���� �ΰ��� ���� �ΰ��� ���̴�. ".$character_list[$purpose]."!";
	   writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'���',$entry['character']);

	   // ��� ���� �̸� ����
	   if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";
	   // ������ �̵�	
	   movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}
   
   // �÷��̾� �������
	if ($function == "mustkillCancel" and $entry['alive'] == "����" and $truecharacter['mustkill']  and $mustkill){
		@mysql_query("delete from `$DB_mustkill`  where `game`= $no and `day`= $gameinfo[day]  ;") or die("���� ��ȹ�� ���� �߿� ������ �߻��߽��ϴ�.");

	   //�ڸ�Ʈ �Է�
	   $comment = $character_list[$mustkill['target']]."���� ���� ���� ���ǰ� �������.";
	   writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'���',$entry['character']);

	   // ��� ���� �̸� ����
	   if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

	   // ������ �̵�	
   movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	}

	// �÷��̾� ����
	 if ($function == "revenge" and $purpose and $entry['alive'] == "����" and $truecharacter['revenge'] and   !$revenge){
		 @mysql_query("INSERT INTO `$DB_revenge` ( `game`  ,`target` ) VALUES ('$no' , '$purpose' );") or die("���� �߿� ������ �߻��߽��ϴ�.");

		$comment = "�����ڰ� ".$character_list[$purpose]."���� ������� �ִ�.";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'�޸�',$entry['character']);

		// ��� ���� �̸� ����
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";
		// ������ �̵�	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }
	
	// �÷��̾� �������
	 if ($function == "revengeCancel" and $entry['alive'] == "����" and $truecharacter['revenge']  and $revenge){
		 @mysql_query("delete from `$DB_revenge`  where `game`= $no  ;") or die("��ȣ ��ȹ�� ���� �߿� ������ �߻��߽��ϴ�.");

		//�ڸ�Ʈ �Է�
		$comment = $character_list[$revenge['target']]."���� ���� ������ �������.";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'�޸�',$entry['character']);

		// ��� ���� �̸� ����
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// ������ �̵�	
	movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }


	// �÷��̾� ���� ����
	 if ($function == "halfassault" and $injuredhalf and $entry['alive'] == "����" and $truecharacter['half-assault'] and   !$halfassault){

		 $sql = 	"INSERT INTO `$DB_deathNoteHalf` ( `game` , `day` , `werewolf` ,  `injured`) VALUES ('$no', '$gameinfo[day]','$entry[character]' , '$injuredhalf' );";
		 
		 @mysql_query($sql) or die("���� ��ȹ�� �Է� �߿� ������ �߻��߽��ϴ�.".$sql);

		//�ڸ�Ʈ �Է�
		$comment = "�� �̳� ".$character_list[$injuredhalf]."! ������ �� �����̴�!!!";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'�޸�',$entry['character']);

		// ��� ���� �̸� ����
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";
		// ������ �̵�	
		movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }

	// �÷��̾� ���� �������
	 if ($function == "halfassaultCancel" and $entry['alive'] == "����" and $truecharacter['half-assault']  and $halfassault){
		 @mysql_query("delete from `$DB_deathNoteHalf`  where `game`= $no and `day`= $gameinfo[day] and  `werewolf` = $entry[character] ;") or die("���� ��ȹ�� ���� �߿� ������ �߻��߽��ϴ�.");

		//�ڸ�Ʈ �Է�
		$comment = $character_list[$halfassault[injured]]."�� ���� ������ ���߾����ϴ�.";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'�޸�',$entry['character']);

		// ��� ���� �̸� ����
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// ������ �̵�	
	movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }


	// �÷��̾� ����
	 if ($function == "assaultCon" and $injured and $entry['alive'] == "����" and $truecharacter['assault-con'] and   !$assaultCon){
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



		$assault_list =  DB_array("no","character","$DB_entry where game = $no and alive='����' and truecharacter $orderCondition");	
		$assault_list = array_values($assault_list);
//echo "\$assault_list:";print_r($assault_list);echo "<br><br>";

//echo "select * from $DB_entry where game=$no and character = $injured<br>";
		$injured = mysql_fetch_array(mysql_query("select * from $DB_entry where game=$no and `character`= $injured "));

		if(!in_array($injured[character],$assault_list )){
			 @mysql_query(	"INSERT INTO `$DB_deathNote` ( `game` , `day` , `werewolf` ,  `injured`) VALUES ('$no', '$gameinfo[day]','$entry[character]' , '$injured[character]' );") or die("���� ��ȹ�� �Է� �߿� ������ �߻��߽��ϴ�.");

			//�ڸ�Ʈ �Է�
			$comment = "�� �̳� ".$character_list[$injured[character]]."! ������ �� �����̴�!!!";
			writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'���',$entry['character']);
		}


			// ��� ���� �̸� ����
			if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";
			// ������ �̵�	
			movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }

	// �÷��̾� �������
	 if ($function == "assaultConCancel" and $entry['alive'] == "����" and $truecharacter['assault-con']  and $assaultCon){
		 @mysql_query("delete from `$DB_deathNote`  where `game`= $no and `day`= $gameinfo[day] and  `werewolf` = $entry[character] ;") or die("���� ��ȹ�� ���� �߿� ������ �߻��߽��ϴ�.");

		//�ڸ�Ʈ �Է�
		$comment = $character_list[$assaultCon[injured]]."�� ���� ������ ���߾����ϴ�.";
		writeCommnet($t_comment."_".$id,$no,$member[no],$member[name],$password,$comment,$server[ip],'���',$entry['character']);

		// ��� ���� �̸� ����
		if(!$setup[use_alllist]) $view_file_link="view.php"; else $view_file_link="zboard.php";

		// ������ �̵�	
	movepage("$view_file_link?id=$id&page=$page&page_num=$page_num&select_arrange=$select_arrange&desc=$des&sn=$sn&ss=$ss&sc=$sc&keyword=$keyword&no=$no&category=$category&password=$password");
	 }

	 
	?>