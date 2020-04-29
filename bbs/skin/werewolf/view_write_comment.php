<?
if($totalCommentPage>=1) echo "</div>";	
?>
</div>

<?
	$termOfDay="";
	floor($gameinfo['termOfDay']/86400 ) and $termOfDay .= floor($gameinfo['termOfDay']/86400 )."일";
	floor(($gameinfo['termOfDay']%86400)/3600 ) and $termOfDay .= floor(($gameinfo['termOfDay']%86400)/3600 )."시간";
	(($gameinfo['termOfDay']/60)%60) and $termOfDay .= (($gameinfo['termOfDay']/60)%60 ."분");
	$gameinfo['termOfDay']%60 and $termOfDay.= ($gameinfo['termOfDay'] % 60 ."초" )  ;

	if($gameinfo['state']=="게임끝" and $entry)$writeComment = 5;
	elseif($entry[memo]){$writeComment = 1;}
	elseif($entry['alive']=="생존" and ($entry['normal'] or ($truecharacter['secretchat'] and $entry['secret']) or ($truecharacter['telepathy'] and $entry['telepathy']))){ $writeComment =2;}
	elseif($entry['alive']=="사망" and $entry['grave']) $writeComment = 3;
	elseif($is_admin) $writeComment = 4;
	else $writeComment =0 ;


?>	


<?	if (($is_admin or $entry) and $viewDay == $gameinfo['day'] and $writeComment){?>



<form method=post name="writeComment" id="writeComment" action="were_comment_type_ok.php">
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=no value=<?=$no?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=category value="<?=$category?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<input type=hidden name=mode value="<?=$mode?>"> 

<div align=center>
<table border=0 bgcolor=222222 cellspacing=1 cellpadding=0 width=<?=$width?>>
<tr bgcolor=ffffff>
  <td>
	<table border=0 bgcolor=222222 cellspacing=1 cellpadding=2 width=100%>
	<col width=80></col><col width=></col><col width=70></col>
	<tr align=center bgcolor=111111> 
	  <td height=20 rowspan='4' valign='top'>
	  <? // character image on/off 
			if($entry) {
				if($viewImage === "off") {
					echo "<div style='width:100; height:100;'></div>";
				}
				else {
					$player_character=mysql_fetch_array(mysql_query("select * from $DB_character where no = ".$entry['character'].""));
					echo "<img width='100' height='100' src='".$characterImageFolder.$player_character['half_image']."'>";
				}
			} ?>
	</td>
	  <td colspan=2 align='left'><?=$character_list[$entry[character]]?></td>
	 </tr>

	 <? $printCommentType ="";
	if($gameinfo['state']=="게임끝" or $gameinfo['state']=="봉인" or $gameinfo['state']=="버그" ){
		if($entry)$printCommentType .="<INPUT TYPE=radio ID=c_type NAME=c_type value=일반 checked onclick=setColor('일반')> 일반 ($entry[normal]/20)</input>" ;
		if($is_admin)$printCommentType .="<INPUT TYPE=radio ID=c_type ID=c_type NAME=c_type  $checked value=알림 onclick=setColor('알림')> 알림</input>" ;
	}
	 else	{
		if($entry['alive']=="생존" ){		
			if($entry[normal]){
				$printCommentType .="<INPUT TYPE=radio ID=c_type NAME=c_type value=일반  onclick=setColor('일반')>일반($entry[normal]/20)</input>" ;
			}
			if($truecharacter['secretchat'] and $entry[secret] ){
				$printCommentType .="<INPUT TYPE=radio ID=c_type NAME=c_type value=비밀  onclick=setColor('비밀')>비밀($entry[secret]/40)</input>" ;
			}
			if($truecharacter['telepathy'] and  $entry[telepathy])$printCommentType .="<INPUT TYPE=radio ID=c_type NAME=c_type value=텔레 onclick=setColor('텔레')>텔레파시($entry[telepathy]/1)</input>" ;

			if($truecharacter['secretletter']){
				$sql = "select * from $DB_secretletter where `game`='".$no."' and `day`='".$gameinfo[day]."' and `from` = ".$entry['character']."";
				$secretletter=mysql_fetch_array(mysql_query($sql));


				if(!$secretletter){
					$printCommentType .="<INPUT TYPE=radio ID=c_type NAME=c_type value=편지 onclick=setColor('편지')>편지</input>" ;
					$printCommentType .=DBselect("secretletterTo","","character",$character_list,"$DB_entry where game=$no and alive = '생존'","font-size:9pt;width=100","",$entry['character']);
				}
			}

			$sql = "select * from $DB_secretletter where `game`='".$no."' and `day`='".($viewDay-1)."'";
			$secretmessage=mysql_fetch_array(mysql_query($sql));

			if($secretmessage and $secretmessage['to'] ==$entry['character']  and $secretmessage['answer']==0 )$printCommentType .="<INPUT TYPE=radio ID=c_type NAME=c_type value=답변  onclick=setColor('편지')>답장</input>" ;

		}
		if($entry['alive']=="사망" and $entry[grave] ){
			$printCommentType .="<INPUT TYPE=radio ID=c_type NAME=c_type value=사망 checked onclick=setColor('사망')>사망($entry[grave]/20)</input>" ;
		}

		if($entry and $entry[memo]>0 )$printCommentType .="<INPUT TYPE=radio ID=c_type NAME=c_type value=메모 onclick=setColor('메모')>메모($entry[memo]/10)</input>" ;

		if(!$entry) $checked = "checked";
		if($is_admin){
//			$printCommentType .="<INPUT TYPE=radio NAME=c_type value=비밀 checked onclick=setColor('비밀')>비밀($entry[secret]/40)</input>" ;
			$printCommentType .="<INPUT TYPE=radio ID=c_type NAME=c_type  $checked value=알림 onclick=setColor('알림')>알림</input>" ;
		}
		if($gameinfo['state']=="게임중" and $gameinfo['seal'] == '논의' and $entry and $entry['seal']>0){
			$printCommentType .="<INPUT TYPE=radio ID=c_type NAME=c_type value=봉인제안  onclick=setColor('봉인제안')>봉인 논의(".$entry['seal']."/5)</input>" ;
		}
	 }

	 //일반 
		 //if(!$truecharacter['secretchat'] and !$truecharacter['telepathy']){?>
	 <tr align=center bgcolor=111111> 
	  <td align="left"><div id='selectCommentType'><?=$printCommentType?></div></td>
	 </tr>
	 <?//}?>

		 <?=$hide_c_password_start?>
	  <tr align=center bgcolor=111111> 
	  <td height=20 class=red_7>PASSWORD</td>
	    <td >
		<table width=100%><tr><td>
		<input type=password name=password <?=size(8)?> maxlength=20 class=red_input>
		</td></tr></table></td>
	  </tr>
		 <?=$hide_c_password_end?>
	  <tr bgcolor=111111 >
			<td width=100%>
			<textarea name="memo" id="memo" rows="4" class="red_commentw"></textarea>
			</td>
		</tr>

	 <tr align=center bgcolor=111111> 
	  <td width="100%" align='left'>

	  <table>
		<tr>
			<td width="100px">
				<input type="button" rows=4 onclick="addLine()" <?if($browser){?>class=red_submit<?}?> value='창 늘리기(z) ▼'  accesskey="z">
			</td>
			<td width="100px">
				<input type="button" onclick="submitComment(writeComment)" rows=5 <?if($browser){?>class=red_submit<?}?> value='보내기(s)' accesskey="s">
			</td>
			<td>
				 <input type="button" onclick="fastsendComment()" rows=5 style="font-size:10;"  value='비상용 보내기' title="보내기 버튼으로 로그가 올라가지 않을 때 사용하십시오.">
			</td>
		</tr>
	  </table>
	  
	  </td>
	 </tr>
	</table>
  </td>
</tr>
</table>
</form>


<script>
	$(function(){
    checkCommentType();
		initCommentType();
  });
</script>

<?}?>




<?	if ($gameinfo['state']=="게임중" and $gameinfo['day']<>1 and $viewDay == $gameinfo['day']  and $entry['alive']=="생존" and !$vote) {?>
<table width=<?=$width?> cellspacing=0 cellpadding=0>
<form method=post name=vote action=<?="view.php"?>  enctype="multipart/form-data"  onsubmit="return formcheck(this)">
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=no value=<?=$no?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=category value="<?=$category?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<input type=hidden name=mode value="<?=$mode?>"> 
<input type=hidden name=function value="vote">

<tr bgcolor=111111>
	<td height=30 colspan=2>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr>
		<td align=center width=80><font class=red_8>투표</td>
		<td align=left>
			<font class=red_8><?=DBselect("candidacy","","character",$character_list,"$DB_entry where game=$no and alive = '생존'","font-size:9pt;width=100","",$entry['character']);?>
			 의 이름을 투표 용지에 적어 낸다.
		</td>
		<td align=center width=70><font class=red_8><input type=submit rows=5 <?if($browser){?>class=red_submit_s<?}?> value='투표하기' accesskey="v"></td>
	</tr></table>
	</td>
</tr>
</form>
</table>
<?	}elseif($vote){?>
<table width=<?=$width?> cellspacing=0 cellpadding=0>
<form method=post name=voteCancel action=<?="view.php"?>  enctype="multipart/form-data"  onsubmit="return formcheck(this)">
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=no value=<?=$no?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=category value="<?=$category?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<input type=hidden name=mode value="<?=$mode?>"> 
<input type=hidden name=function value="voteCancel">

<tr bgcolor=111111>
	<td height=30 colspan=2>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr>
		<td align=center width=80>투표</td>
		<td><font class=red_8><?=$character_list[$vote[candidacy]]?>에게 표를 던졌습니다.</td>
		<td align=center width=70><font class=red_8><input type=submit rows=5 <?if($browser){?>class=red_submit_s<?}?> value='취소한다' accesskey="v"></td>
	</tr></table>
	</td>
</tr>
</form>
</table>
<?}?>

<?	if($gameinfo['state']=="게임중" and $truecharacter['forecast'] and !$forecast and $viewDay == $gameinfo['day'] and $entry['alive']=="생존") {?>

<?if($truecharacter['forecast']) {
	$forecastArray = DB_array("mystery","mystery","$DB_revelation where game='$no' and prophet='$entry[character]' and type = '점'");

	if($forecastArray )	$orderCondition = orderCondition($forecastArray);
	else 						$orderCondition = "in (0)";

	//echo DBselect("mystery","","character",$character_list,"$DB_entry where game=$no and alive = '생존' and victim = 0 and `character`  not $orderCondition","font-size:9pt;width=100","",$entry['character']);
}?>

<table width=<?=$width?> cellspacing=0 cellpadding=0>
<form method=post name=forecast action=<?="view.php"?>  enctype="multipart/form-data"  onsubmit="return formcheck(this)">
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=no value=<?=$no?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=category value="<?=$category?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<input type=hidden name=mode value="<?=$mode?>"> 
<input type=hidden name=function value="forecast">

<tr bgcolor=111111>
	<td height=30 colspan=2>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr>
		<td align=center width=80><font class=red_8>점</td>
		<td align=left>


			<font class=red_8><?=DBselect("mystery","","character",$character_list,"$DB_entry where game=$no and alive = '생존' and victim = 0 and `character`  not $orderCondition","font-size:9pt;width=100","",$entry['character']);?>
			 <!--의 정체을 알아내기 위해 점를 쳐본다.-->
		</td>
		<td align=center width=70><font class=red_8><input type=submit rows=5 <?if($browser){?>class=red_submit_s<?}?> value='점치기' accesskey="f"></td>
	</tr></table>
	</td>
</tr>
</form>
</table>
<?	}elseif($forecast ){?>
<table width=<?=$width?> cellspacing=0 cellpadding=0>
<form method=post name=forecastCancel action=<?="view.php"?>  enctype="multipart/form-data"  onsubmit="return formcheck(this)">
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=no value=<?=$no?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=category value="<?=$category?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<input type=hidden name=mode value="<?=$mode?>"> 
<input type=hidden name=function value="forecastCancel">

<tr bgcolor=111111>
	<td height=30 colspan=2>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr>
		<td align=center width=80>점 대상 </td>
		<td><font class=red_8><?=$character_list[$forecast['mystery']]?></font></td>
		<td align=center width=70><font class=red_8><input type=submit rows=5 <?if($browser){?>class=red_submit_s<?}?> value='취소한다' accesskey="f"></td>
	</tr></table>
	</td>
</tr>
</form>
</table>
<?}?>


<?	if($gameinfo['state']=="게임중"  and ($gameinfo['day']<>1 or $gameinfo['rule']==5) and $truecharacter['assault'] and !$assault and $viewDay == $gameinfo['day']  and $entry['alive']=="생존") {?>
<table width=<?=$width?> cellspacing=0 cellpadding=0>
<form method=post name=assault action=<?="view.php"?>  enctype="multipart/form-data"    onsubmit="return formcheck(this)">
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=no value=<?=$no?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=category value="<?=$category?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<input type=hidden name=mode value="<?=$mode?>"> 
<input type=hidden name=function value="assault">

<tr bgcolor=111111>
	<td height=30 colspan=2>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr>
		<td align=center width=80><font class=red_8>살해</td>
		<td align=left>
			<font class=red_8>
<?
				$assaultCharacter =  DB_array("no","no","$DB_truecharacter where race  = 1");
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
?>
			
			
			
			
			<?if($CheckAssaultWerewolf){?>
				<?=DBselect("injured","","character",$character_list,"$DB_entry where game=$no and alive = '생존'","font-size:9pt;width=100","","");?>
			<? }else {?>
				<?=DBselect("injured","","character",$character_list,"$DB_entry where game=$no and alive = '생존'","font-size:9pt;width=100","",$assault_list);?>
			<?}?>
			 을 살해 대상으로 지목한다.
		</td>
		<td align=center width=70><font class=red_8><input type=submit rows=5 <?if($browser){?>class=red_submit_s<?}?> value='지목하기' accesskey="f"></td>
	</tr></table>
	</td>
</tr>
</form>
</table>
<?	}elseif($assault){?>
<table width=<?=$width?> cellspacing=0 cellpadding=0>
<form method=post name=assaultCancel action=<?="view.php"?>  enctype="multipart/form-data"   onsubmit="return formcheck(this)">
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=no value=<?=$no?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=category value="<?=$category?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<input type=hidden name=mode value="<?=$mode?>"> 
<input type=hidden name=function value="assaultCancel">

<tr bgcolor=111111>
	<td height=30 colspan=2>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr>
		<td align=center width=80>살해 계획</td>
		<td><font class=red_8>
				당신은 <?=$character_list[$assault[injured]]?>의 목을 노리고 있습니다.</font></td>
		<td align=center width=70><font class=red_8><input type=submit rows=5 <?if($browser){?>class=red_submit_s<?}?> value='취소한다' accesskey="f"></td>
	</tr></table>
	</td>
</tr>
</form>
</table>
<?}?>
<?	if($gameinfo['state']=="게임중" and $truecharacter['guard'] and !$guard and $viewDay == $gameinfo['day'] and $entry['alive']=="생존"  and ($gameinfo['day']<>1 or $gameinfo['rule']==5) ) {?>
<table width=<?=$width?> cellspacing=0 cellpadding=0>
<form method=post name=guard action=<?="view.php"?>   enctype="multipart/form-data"   onsubmit="return formcheck(this)">
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=no value=<?=$no?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=category value="<?=$category?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<input type=hidden name=mode value="<?=$mode?>"> 
<input type=hidden name=function value="guard">

<tr bgcolor=111111>
	<td height=30 colspan=2>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr>
		<td align=center width=80><font class=red_8>보호</td>
		<td align=left>
			<font class=red_8><?=DBselect("purpose","","character",$character_list,"$DB_entry where game=$no and alive = '생존'","font-size:9pt;width=100","",$entry['character']);?>
			 을 보호한다.
		</td>
		<td align=center width=70><font class=red_8><input type=submit rows=5 <?if($browser){?>class=red_submit_s<?}?> value='보호한다' accesskey="f"></td>
	</tr></table>
	</td>
</tr>
</form>
</table>
<?	}elseif($guard){?>
<table width=<?=$width?> cellspacing=0 cellpadding=0>
<form method=post name=guardCancel action=<?="view.php"?>  enctype="multipart/form-data"  onsubmit="return formcheck(this)">
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=no value=<?=$no?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=category value="<?=$category?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<input type=hidden name=mode value="<?=$mode?>"> 
<input type=hidden name=function value="guardCancel">
<tr bgcolor=111111>
	<td height=30 colspan=2>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr>
		<td align=center width=80>보호</td>
		<td><font class=red_8>
				당신은 <?=$character_list[$guard[purpose]]?>씨의 주변에서 경계를 서고 있습니다.</font></td>
		<td align=center width=70><font class=red_8><input type=submit rows=5 <?if($browser){?>class=red_submit_s<?}?> value='취소한다' accesskey="f"></td>
	</tr></table>
	</td>
</tr>
</form>
</table>
<?}?>

<?//	초능력자 정보 출력
	if($gameinfo['state']=="게임중"  and $entry['truecharacter'] == 7 and $viewDay == $gameinfo['day']  and $entry['alive']=="생존" and 0){

		//내가 지금  참여중인  게임이고 and 초능력이고 and 나 이외의
		$sql = "select `character` from `zetyx_board_werewolf_entry` where game = $no and truecharacter = 7 and player != $member[no] ";
		$pair = mysql_fetch_array(mysql_query($sql));
		?>
		<div class='DisplayBoard' >
			당신의 페어는 <?=$character_list[$pair[0]];?> 님 입니다.
		</div>

	<div class="DisplayBoard">
		<h1>시스템에서 출력된 메시지(점, 영매, 보호 결과 등)를 그대로 긁어와서 인용하는 것은 금지입니다. </h1>
	</div>
<?}?>

<?	if( ($guard_result and $assault_result) or ($forecast_result and ($entry['alive']=="생존")) or ($mediumism and ($entry['alive']=="생존")) ) {?>
	<div class="DisplayBoard">
		<h1>시스템에서 출력된 메시지(점, 영매, 보호 결과 등)를 그대로 긁어와서 인용하는 것은 금지입니다. </h1>
	</div>
<?	}?>


<?
// 익스펜션 직업 //////////////////////////////////////////.
//인랑 리더////////////////////////////////////////////////
?>
<?	if($gameinfo['state']=="게임중" and $truecharacter['detect'] and !$detect and $viewDay == $gameinfo['day'] and $entry['alive']=="생존" ) {?>
<table width=<?=$width?> cellspacing=0 cellpadding=0>
<form method=post name=guard action=<?="view.php"?>   enctype="multipart/form-data"   onsubmit="return formcheck(this)">
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=no value=<?=$no?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=category value="<?=$category?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<input type=hidden name=mode value="<?=$mode?>"> 
<input type=hidden name=function value="detect">

<tr bgcolor=111111>
	<td height=30 colspan=2>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr>
		<td align=center width=80><font class=red_8>감지</td>
		<td align=left>
			<font class=red_8>
			<?
				$assaultCharacter =  DB_array("no","no","$DB_truecharacter where race  = 1");
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
//echo "\$assault_list:";print_r($assault_list);echo "<br><br>";
?>
			
			
			
			
			<?=DBselect("purpose","","character",$character_list,"$DB_entry where game=$no and alive = '생존'","font-size:9pt;width=100","",$assault_list);?>
					</td>
		<td align=center width=70><font class=red_8><input type=submit rows=5 <?if($browser){?>class=red_submit_s<?}?> value='감지한다' accesskey="f"></td>
	</tr></table>
	</td>
</tr>
</form>
</table>
<?	}elseif($detect){?>
<table width=<?=$width?> cellspacing=0 cellpadding=0>
<form method=post name=guardCancel action=<?="view.php"?>  enctype="multipart/form-data"  onsubmit="return formcheck(this)">
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=no value=<?=$no?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=category value="<?=$category?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<input type=hidden name=mode value="<?=$mode?>"> 
<input type=hidden name=function value="detectCancel">
<tr bgcolor=111111>
	<td height=30 colspan=2>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr>
		<td align=center width=80>감지</td>
		<td><font class=red_8>



				<?=$character_list[$detect['target']]?>씨의 냄새를 맡고 있다.</font></td>
		<td align=center width=70><font class=red_8><input type=submit rows=5 <?if($browser){?>class=red_submit_s<?}?> value='취소한다' accesskey="f"></td>
	</tr></table>
	</td>
</tr>
</form>
</table>
<?}?>



<?//복수자////////////////////////////////////////////////?>
<?	if($gameinfo['state']=="게임중" and $truecharacter['revenge'] and !$revenge and $viewDay == $gameinfo['day'] and $entry['alive']=="생존"  ) {?>
<table width=<?=$width?> cellspacing=0 cellpadding=0>
<form method=post name=guard action=<?="view.php"?>   enctype="multipart/form-data"   onsubmit="return formcheck(this)">
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=no value=<?=$no?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=category value="<?=$category?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<input type=hidden name=mode value="<?=$mode?>"> 
<input type=hidden name=function value="revenge">

<tr bgcolor=111111>
	<td height=30 colspan=2>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr>
		<td align=center width=80><font class=red_8>복수</td>
		<td align=left>
			<font class=red_8><?=DBselect("purpose","","character",$character_list,"$DB_entry where game=$no and alive = '생존'","font-size:9pt;width=100","",$entry['character']);?>
		</td>
		<td align=center width=70><font class=red_8><input type=submit rows=5 <?if($browser){?>class=red_submit_s<?}?> value='노려본다' accesskey="f"></td>
	</tr></table>
	</td>
</tr>
</form>
</table>
<?	}elseif($revenge){?>
<table width=<?=$width?> cellspacing=0 cellpadding=0>
<form method=post name=guardCancel action=<?="view.php"?>  enctype="multipart/form-data"  onsubmit="return formcheck(this)">
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=no value=<?=$no?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=category value="<?=$category?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<input type=hidden name=mode value="<?=$mode?>"> 
<input type=hidden name=function value="revengeCancel">
<tr bgcolor=111111>
	<td height=30 colspan=2>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr>
		<td align=center width=80>복수</td>
		<td><font class=red_8>
				당신은 <?=$character_list[$revenge['target']]?>씨를 노려보고 있다.</font></td>
		<td align=center width=70><font class=red_8><input type=submit rows=5 <?if($browser){?>class=red_submit_s<?}?> value='취소한다' accesskey="f"></td>
	</tr></table>
	</td>
</tr>
</form>
</table>
<?}?>

<?//반쪽 습격////////////////////////////////////////////////?>

<?	if($gameinfo['state']=="게임중"  and $truecharacter['half-assault'] and !$halfassault and $viewDay == $gameinfo['day']  and $entry['alive']=="생존"and $gameinfo['day']<>1) {?>
<table width=<?=$width?> cellspacing=0 cellpadding=0>
<form method=post name=assault action=<?="view.php"?>  enctype="multipart/form-data"    onsubmit="return formcheck(this)">
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=no value=<?=$no?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=category value="<?=$category?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<input type=hidden name=mode value="<?=$mode?>"> 
<input type=hidden name=function value="halfassault">

<tr bgcolor=111111>
	<td height=30 colspan=2>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr>
		<td align=center width=80><font class=red_8>살해</td>
		<td align=left>
			<font class=red_8>			
			<?=DBselect("injuredhalf","","character",$character_list,"$DB_entry where game=$no and alive = '생존'","font-size:9pt;width=100","",$entry['character']);?>			
			 을 살해 대상으로 지목한다.
		</td>
		<td align=center width=70><font class=red_8><input type=submit rows=5 <?if($browser){?>class=red_submit_s<?}?> value='지목하기' accesskey="f"></td>
	</tr></table>
	</td>
</tr>
</form>
</table>
<?	}elseif($halfassault){?>
<table width=<?=$width?> cellspacing=0 cellpadding=0>
<form method=post name=assaultCancel action=<?="view.php"?>  enctype="multipart/form-data"   onsubmit="return formcheck(this)">
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=no value=<?=$no?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=category value="<?=$category?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<input type=hidden name=mode value="<?=$mode?>"> 
<input type=hidden name=function value="halfassaultCancel">

<tr bgcolor=111111>
	<td height=30 colspan=2>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr>
		<td align=center width=80>살해 계획</td>
		<td><font class=red_8>
				당신은 <?=$character_list[$halfassault[injured]]?>의 목을 노리고 있습니다.</font></td>
		<td align=center width=70><font class=red_8><input type=submit rows=5 <?if($browser){?>class=red_submit_s<?}?> value='취소한다' accesskey="f"></td>
	</tr></table>
	</td>
</tr>
</form>
</table>
<?}?>


<?
// 신뢰도 //////////////////////////////////////////.
// 홀 수 점쟁이////////////////////////////////////////////////
	if($gameinfo['state']=="게임중" and $truecharacter['forecast-odd'] and !$forecastOdd and $viewDay == $gameinfo['day'] and $viewDay%2 == 1 and $entry['alive']=="생존") {?>
<? if($truecharacter['forecast-odd']) {
	$forecastArray = DB_array("mystery","mystery","$DB_revelation where game='$no' and prophet='$entry[character]' and type = '점'");

	if($forecastArray )	$orderCondition = orderCondition($forecastArray);
	else 						$orderCondition = "in (0)";

	//echo DBselect("mystery","","character",$character_list,"$DB_entry where game=$no and alive = '생존' and victim = 0 and `character`  not $orderCondition","font-size:9pt;width=100","",$entry['character']);
}?>

<table width=<?=$width?> cellspacing=0 cellpadding=0>
<form method=post name=forecast action=<?="view.php"?>  enctype="multipart/form-data"  onsubmit="return formcheck(this)">
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=no value=<?=$no?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=category value="<?=$category?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<input type=hidden name=mode value="<?=$mode?>"> 
<input type=hidden name=function value="forecastOdd">

<tr bgcolor=111111>
	<td height=30 colspan=2>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr>
		<td align=center width=80><font class=red_8>점</td>
		<td align=left>


			<font class=red_8><?=DBselect("mystery","","character",$character_list,"$DB_entry where game=$no and alive = '생존' and victim = 0 and `character`  not $orderCondition","font-size:9pt;width=100","",$entry['character']);?>
			 <!--의 정체을 알아내기 위해 점를 쳐본다.-->
		</td>
		<td align=center width=70><font class=red_8><input type=submit rows=5 <?if($browser){?>class=red_submit_s<?}?> value='점치기' accesskey="f"></td>
	</tr></table>
	</td>
</tr>
</form>
</table>
<? }elseif($gameinfo['state']=="게임중" and $truecharacter['forecast-odd'] and !$forecastOdd and $viewDay == $gameinfo['day'] and $viewDay%2 == 0 and $entry['alive']=="생존"){?>
<table width=<?=$width?> cellspacing=0 cellpadding=0>
<tr bgcolor=111111>
	<td height=30 colspan=2>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr>
		<td align=center width=80>점 </td>
		<td><font class=red_8>짝수 날에는 점을 볼 수 없습니다.</font></td>
		<td align=center width=70></td>
	</tr></table>
	</td>
</tr>
</form>
</table>

<?	}elseif($forecastOdd ){?>
<table width=<?=$width?> cellspacing=0 cellpadding=0>
<form method=post name=forecastCancel action=<?="view.php"?>  enctype="multipart/form-data"  onsubmit="return formcheck(this)">
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=no value=<?=$no?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=category value="<?=$category?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<input type=hidden name=mode value="<?=$mode?>"> 
<input type=hidden name=function value="forecastOddCancel">

<tr bgcolor=111111>
	<td height=30 colspan=2>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr>
		<td align=center width=80>점 대상 </td>
		<td><font class=red_8><?=$character_list[$forecastOdd['mystery']]?></font></td>
		<td align=center width=70><font class=red_8><input type=submit rows=5 <?if($browser){?>class=red_submit_s<?}?> value='취소한다' accesskey="f"></td>
	</tr></table>
	</td>
</tr>
</form>
</table>
<?}?>


<?
//

if($gameinfo['state']=="게임중"  and $gameinfo['day']<>1 and $truecharacter['assault-con'] and !$assaultCon and $viewDay == $gameinfo['day']  and $entry['alive']=="생존") {?>
<table width=<?=$width?> cellspacing=0 cellpadding=0>
<form method=post name=assault action=<?="view.php"?>  enctype="multipart/form-data"    onsubmit="return formcheck(this)">
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=no value=<?=$no?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=category value="<?=$category?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<input type=hidden name=mode value="<?=$mode?>"> 
<input type=hidden name=function value="assaultCon">

<tr bgcolor=111111>
	<td height=30 colspan=2>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr>
		<td align=center width=80><font class=red_8>살해</td>
		<td align=left>
			<font class=red_8>
<?
				$assaultCharacter =  DB_array("no","no","$DB_truecharacter where race  = 1");
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
?>
			
			
			
			
			<?=DBselect("injured","","character",$character_list,"$DB_entry where game=$no and alive = '생존'","font-size:9pt;width=100","",$assault_list);?>
			 을 살해 대상으로 지목한다.
		</td>
		<td align=center width=70><font class=red_8><input type=submit rows=5 <?if($browser){?>class=red_submit_s<?}?> value='지목하기' accesskey="f"></td>
	</tr></table>
	</td>
</tr>
</form>
</table>
<?	}elseif($assaultCon){?>
<table width=<?=$width?> cellspacing=0 cellpadding=0>
<form method=post name=assaultCancel action=<?="view.php"?>  enctype="multipart/form-data"   onsubmit="return formcheck(this)">
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=no value=<?=$no?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=category value="<?=$category?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<input type=hidden name=mode value="<?=$mode?>"> 
<input type=hidden name=function value="assaultConCancel">

<tr bgcolor=111111>
	<td height=30 colspan=2>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr>
		<td align=center width=80>살해 계획</td>
		<td><font class=red_8>
				당신은 <?=$character_list[$assaultCon[injured]]?>의 목을 노리고 있습니다.</font></td>
		<td align=center width=70><font class=red_8><input type=submit rows=5 <?if($browser){?>class=red_submit_s<?}?> value='취소한다' accesskey="f"></td>
	</tr></table>
	</td>
</tr>
</form>
</table>
<?}?>

<?
// 참살룰 직업 //////////////////////////////////////////.
//잔혹한 인랑////////////////////////////////////////////////
?>
<?	if($gameinfo['state']=="게임중" and $gameinfo['day']<>1 and $truecharacter['mustkill'] and !$mustkill and $viewDay == $gameinfo['day'] and $entry['alive']=="생존" ) {?>
<table width=<?=$width?> cellspacing=0 cellpadding=0>
<form method=post name=mustkill action=<?="view.php"?>   enctype="multipart/form-data"   onsubmit="return formcheck(this)">
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=no value=<?=$no?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=category value="<?=$category?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<input type=hidden name=mode value="<?=$mode?>"> 
<input type=hidden name=function value="mustkill">

<tr bgcolor=111111>
	<td height=30 colspan=2>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr>
		<td align=center width=80><font class=red_8>참살</td>
		<td align=left>
			<font class=red_8>
			<?
				$assaultCharacter =  DB_array("no","no","$DB_truecharacter where race  = 1");
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
			
			// 2017/09/10 epi : 랑습룰 체크 부분
			$CheckAssaultWerewolf = checkSubRule($gameinfo['subRule'], 1);
//echo "\$assault_list:";print_r($assault_list);echo "<br><br>";
?>
			
			
			
			
			<?if($CheckAssaultWerewolf){?>
				<?=DBselect("purpose","","character",$character_list,"$DB_entry where game=$no and alive = '생존'","font-size:9pt;width=100","","");?>
			<? }else {?>
				<?=DBselect("purpose","","character",$character_list,"$DB_entry where game=$no and alive = '생존'","font-size:9pt;width=100","",$assault_list);?>
			<?}?>
			 에게 살의를 갖는다.
		<td align=center width=70><font class=red_8><input type=submit rows=5 <?if($browser){?>class=red_submit_s<?}?> value='참살한다' accesskey="f"></td>
	</tr></table>
	</td>
</tr>
</form>
</table>
<?	}elseif($mustkill and $mustkill['day'] == $viewDay){?>
<table width=<?=$width?> cellspacing=0 cellpadding=0>
<form method=post name=mustkillCancel action=<?="view.php"?>  enctype="multipart/form-data"  onsubmit="return formcheck(this)">
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=no value=<?=$no?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=category value="<?=$category?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<input type=hidden name=mode value="<?=$mode?>"> 
<input type=hidden name=function value="mustkillCancel">
<tr bgcolor=111111>
	<td height=30 colspan=2>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr>
		<td align=center width=80>참살</td>
		<td><font class=red_8>



				<?=$character_list[$mustkill['target']]?>씨에게 강한 살의를 느끼고 있다.</font></td>
		<td align=center width=70><font class=red_8><input type=submit rows=5 <?if($browser){?>class=red_submit_s<?}?> value='취소한다' accesskey="f"></td>
	</tr></table>
	</td>
</tr>
</form>
</table>
<?}?>





<?//준비////////////////////////////////////////////////?>
<? if($gameinfo['useTimetable'] and $entry['alive']=="생존" and $gameinfo['state']=="게임중"){?>
	<div class="DisplayBoard">
	<?	if($entry['isConfirm']){
		echo "[준비 완료. 모든 플레이어가 준비를 끝낼 때까지 기다려주세요.] <br><br> <span><a href=$PHP_SELF?id=$id&no=$no&function=isConfirm&password=$password>준비 취소</a></span>";
	}elseif($entry  and !$entry['isConfirm']){
		echo "[모든 플레이어가 준비 완료를 누르면 다음 날로 넘어갑니다.] <br><br> <span><a href=$PHP_SELF?id=$id&no=$no&function=isConfirm&password=$password>준비 완료</a></span>&nbsp;&nbsp;&nbsp;&nbsp;";	
	}?>
</div>
<?}?>
