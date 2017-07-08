<? 
	include("$dir/lib/lib.php"); 

?>
<!-- role playing set selector js, css files -->
<script type="text/javascript" src="skin/werewolf/js/werewolf-role-playing-set.js?ver=<?php echo filemtime('skin/werewolf/js/werewolf-role-playing-set.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="skin/werewolf/css/werewolf-role-playing-set.css?ver=<?php echo filemtime('skin/werewolf/css/werewolf-role-playing-set.css'); ?>">
<SCRIPT LANGUAGE="JavaScript">
<!--
function zb_formresize(obj) {
	obj.rows += 3;
}
// -->
</SCRIPT>
<table border=0 width=100% cellspacing=0 cellpadding=0>
<form method=post name="writeText" action=write_werewolf_ok.php onsubmit="return check_submit();" enctype=multipart/form-data>
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
<input type=hidden name=memo1 value="game <?=$member['name']?> <?=$member['point1']?> <?=time()?>" >
<tr><td height=20 align=left><!--&nbsp;&nbsp;<?=$title?>--></td>
</tr>
</table>

<table border=0 width=100% cellspacing=1 cellpadding=0>
<col width=100></col><col width=></col>
<td height=2><img src=<?=$dir?>/t.gif border=0 height=2></td>
<?=$hide_start?>
<tr>
  <td>
  <img src=<?=$dir?>/t.gif border=0 height=1><br>
  <table  cellspacing=0 cellpadding=0 width=100% height=100%>
  <tr><td align=right><font class=red_8>PASSWERD</font></td>
  </tr></table>
  </td>
  <td>&nbsp;</td>
</tr>

<tr>
  <td>
  <img src=<?=$dir?>/t.gif border=0 height=1><br>
  <table  cellspacing=0 cellpadding=0 width=100% height=100%>
  <tr><td align=right><font class=red_8>NAME</font></td>
  </tr></table>
  </td> 
  <td>&nbsp;<input type=text name=name value="<?=$name?>" <?=size(20)?> maxlength=20 class="input"></td>
</tr>

<tr>
  <td>
  <img src=<?=$dir?>/t.gif border=0 height=1><br>
  <table  cellspacing=0 cellpadding=0 width=100% height=100%>
  <tr><td align=right><font class=red_8>E-MAIL</font></td>
  </tr></table></td>
  <td>&nbsp;<input type=text name=email value="<?=$email?>" <?=size(40)?> maxlength=200 class="input"></td>
</tr>

<tr>
  <td>
  <img src=<?=$dir?>/t.gif border=0 height=1><br>
  <table  cellspacing=0 cellpadding=0 width=100% height=100%>
  <tr><td align=right><font class=red_8>HOMEPAGE</font></td>
  </tr></table>
  </td>
  <td>&nbsp;<input type=text name=homepage value="<?=$homepage?>" <?=size(40)?> maxlength=200 class="input"></td>
</tr>
<?=$hide_end?>

<tr>
  <td>
  <img src=<?=$dir?>/t.gif border=0 height=1><br>
  <table  cellspacing=0 cellpadding=0 width=100% height=100%>
  <tr><td align=right><font class=red_8>선택</font></td>
  </tr></table>
  </td>
  <td>&nbsp;
  <?=$category_kind?>
       <?=$hide_notice_start?>
	   공지사항<input type=checkbox name=notice <?=$notice?> value=1>
	   <?=$hide_notice_end?>
       <?=$hide_secret_start?>
	   <?  $disable =  (!$is_admin and ($gameinfo['state'] <> '준비중' and $gameinfo['state'] <> '')) ?  'disabled' : '' ;?>
 		<? if($mode == "modify") $disable= 'disabled' ;?>

	   비밀 마을 <input type=checkbox name=is_secret <?=$secret?> value=1 <?=$disable?> >
	   <?=$hide_secret_end?>
	   <input type=password name=password <?=size(20)?> maxlength=20 class="input"  <?=$disable?>>
&nbsp;
  </td>
</tr>

<tr valign=top>
  <td>
  <img src=<?=$dir?>/t.gif border=0 height=1><br>
  <table  cellspacing=0 cellpadding=0 width=100% height=100%>
  <tr><td align=right><font class=red_8>마을 이름</font></td>
  </tr></table>
  </td>
  <td>&nbsp;
	<input type=text name=subject value="<?=$subject?>" size=30 maxlength=30 style=width:220px  class="input"></td>
</tr>

 <!--
<tr valign=top>
  <td>
  <img src=<?=$dir?>/t.gif border=0 height=1><br>
  <table  cellspacing=0 cellpadding=0 width=100% height=100%>
  <tr><td align=right><font class=red_8>Start time</font></td>
  </tr></table>
  </td>
	<td>&nbsp;
<?
	$DB_gameinfo=$t_board."_".$id."_gameinfo";
	if($data[no]){
		$gameinfo=mysql_fetch_array(mysql_query("select * from $DB_gameinfo where game=$data[no]"));
	}
	/*


	if($gameinfo){
		$yearS=date("Y",$gameinfo['startingTime']);
		$monthS=date("m",$gameinfo['startingTime']);
		$dayS=date("d",$gameinfo['startingTime']);
		$PmAmS=date("A",$gameinfo['startingTime']);
		$hourS=date("h",$gameinfo['startingTime']);
		$minS=date("i",$gameinfo['startingTime']);
	}

	if(!$yearS)$yearS=date("Y");
	if(!$monthS)$monthS=date("m");
	if(!$dayS)$dayS=date("d");
	if(!$PmAmS)	$PmAmS=date("A");
	if(!$hourS)	$hourS=date("h");
	if(!$minS){
		$minS=date("i");
		if(45 <= $minS and $minS <= 59) $minS = 0;
		elseif(00 <= $minS and $minS < 15) $minS = 15;
		elseif(15 <= $minS and $minS < 30) $minS = 30;
		elseif(30 <= $minS and $minS < 45) $minS = 45;
	}

*/?>


				<input name=yearS size=4 MAXLENGTH=4 value=<?=$yearS?>>년
				<input name=monthS size=4 MAXLENGTH=2 value=<?=$monthS?>>월
				<input name=dayS size=4 MAXLENGTH=2  value=<?=$dayS?>>일

				<select name=PmAmS  value=<?=$PmAmS?> >
					<option value="PM" <?if($PmAmS=='PM') echo "SELECTED";?>>PM </option>
					<option value="AM" <?if($PmAmS=='AM') echo "SELECTED";?>>AM </option>
				<select>
				<select name=hourS value=<?=$hourS?>>
					<option value='1' <?if($hourS=='1') echo "SELECTED";?>>1 </option>
					<option value='2' <?if($hourS=='2') echo "SELECTED";?>>2 </option>
					<option value='3' <?if($hourS=='3') echo "SELECTED";?>>3 </option>
					<option value='4' <?if($hourS=='4') echo "SELECTED";?>>4 </option>
					<option value='5' <?if($hourS=='5') echo "SELECTED";?>>5 </option>
					<option value='6' <?if($hourS=='6') echo "SELECTED";?>>6 </option>
					<option value='7' <?if($hourS=='7') echo "SELECTED";?>>7 </option>
					<option value='8' <?if($hourS=='8') echo "SELECTED";?>>8 </option>
					<option value='9' <?if($hourS=='9') echo "SELECTED";?>>9 </option>
					<option value='10' <?if($hourS=='10') echo "SELECTED";?>>10 </option>
					<option value='11' <?if($hourS=='11') echo "SELECTED";?>>11 </option>
					<option value='12' <?if($hourS=='12') echo "SELECTED";?>>12 </option>
				<select> 시
				<input type=text name=minS value='<?=$minS?>'>
	</td>
</tr>
-->

<tr valign=top>
  <td>
  <img src=<?=$dir?>/t.gif border=0 height=1><br>
  <table  cellspacing=0 cellpadding=0 width=100% height=100%>
  <tr><td align=right><font class=red_8>사건 발생 시각</font></td>
  </tr></table>
  </td>
	<td>&nbsp;
<?
	if($gameinfo){
		$yearV=date("Y",$gameinfo['deathtime']);
		$monthV=date("m",$gameinfo['deathtime']);
		$dayV=date("d",$gameinfo['deathtime']);
		$PmAmV=date("A",$gameinfo['deathtime']);
		$hourV=date("H",$gameinfo['deathtime']);
		$minV=date("i",$gameinfo['deathtime']);
		$termOfDay=$gameinfo['termOfDay'];
	}

	if(!$yearV)$yearV=date("Y",time());
	if(!$monthV)$monthV=date("m",time());
	if(!$dayV)$dayV=date("d",time());
	if(!$PmAmV)	$PmAmV=date("A",time());
	if(!$hourV)	$hourV=date("H",time());
	if(!$minV){
		$minV=date("i",time());
		if(45 <= $minV and $minV <= 59) $minV = 0;
		elseif(00 <= $minV and $minV < 15) $minV = 15;
		elseif(15 <= $minV and $minV < 30) $minV = 30;
		elseif(30 <= $minV and $minV < 45) $minV = 45;
	}
?>

				<!-- 하루 길이 판별로 올바른 시작일 구하기 -->
				<? if($server['host'] == "werewolf6.cafe24.com") {
						// 30분 서버
						$d_yearV = $yearV;
						$d_monthV = $monthV;
						$d_dayV = $dayV;
				}
					elseif(!$gameinfo) {
						// 1일 서버 마을 만들 때
						$temp_time = mktime(0, 0, 0, $monthV, $dayV+1, $yearV);
						$d_yearV = date("Y", $temp_time);
						$d_monthV = date("m", $temp_time);
						$d_dayV = date("d", $temp_time);
				}
					else {
						// 1일 서버 마을 수정할 때
						$d_yearV = $yearV;
						$d_monthV = $monthV;
						$d_dayV = $dayV;
				}
				?>
				<input type=hidden name=year size=4 MAXLENGTH=4 value=<?=$yearV?> disabled class="input">
				<input name=dYear size=4 MAXLENGTH=4 value=<?=$d_yearV?> disabled class="input">년
				<input type=hidden name=month size=4 MAXLENGTH=2 value=<?=$monthV?> disabled class="input">
				<input name=dMonth size=4 MAXLENGTH=2 value=<?=$d_monthV?> disabled class="input">월
				<? $disable = $is_admin ?  '' : 'disabled'; ?>
				<input type=hidden name=day size=4 MAXLENGTH=2  value=<?=$dayV?> <?=$disable?> class="input">
				<input name=dDay size=4 MAXLENGTH=2  value=<?=$d_dayV?> <?=$disable?> class="input">일

				<select name=hour value=<?=$hourV?> <? if($gameinfo['state'] <> "준비중" and $mode == "modify") echo "DISABLED"; ?> class="input">
					<option value='0' <?if($hourV=='0') echo "SELECTED";?>>00 </option>
					<option value='1' <?if($hourV=='1') echo "SELECTED";?>>01 </option>
					<option value='2' <?if($hourV=='2') echo "SELECTED";?>>02 </option>
					<option value='3' <?if($hourV=='3') echo "SELECTED";?>>03 </option>
					<option value='4' <?if($hourV=='4') echo "SELECTED";?>>04 </option>
					<option value='5' <?if($hourV=='5') echo "SELECTED";?>>05 </option>
					<option value='6' <?if($hourV=='6') echo "SELECTED";?>>06 </option>
					<option value='7' <?if($hourV=='7') echo "SELECTED";?>>07 </option>
					<option value='8' <?if($hourV=='8') echo "SELECTED";?>>08 </option>
					<option value='9' <?if($hourV=='9') echo "SELECTED";?>>09 </option>
					<option value='10' <?if($hourV=='10') echo "SELECTED";?>>10 </option>
					<option value='11' <?if($hourV=='11') echo "SELECTED";?>>11 </option>
					<option value='12' <?if($hourV=='12') echo "SELECTED";?>>12 </option>
					<option value='13' <?if($hourV=='13') echo "SELECTED";?>>13 </option>
					<option value='14' <?if($hourV=='14') echo "SELECTED";?>>14 </option>
					<option value='15' <?if($hourV=='15') echo "SELECTED";?>>15 </option>
					<option value='16' <?if($hourV=='16') echo "SELECTED";?>>16 </option>
					<option value='17' <?if($hourV=='17') echo "SELECTED";?>>17 </option>
					<option value='18' <?if($hourV=='18') echo "SELECTED";?>>18 </option>
					<option value='19' <?if($hourV=='19') echo "SELECTED";?>>19 </option>
					<option value='20' <?if($hourV=='20') echo "SELECTED";?>>20 </option>
					<option value='21' <?if($hourV=='21') echo "SELECTED";?>>21 </option>
					<option value='22' <?if($hourV=='22') echo "SELECTED";?>>22 </option>
					<option value='23' <?if($hourV=='23') echo "SELECTED";?>>23 </option>
				</select>시
				<input type=text name=min size=4 MAXLENGTH=2 value='<?=$minV?>' <? if($gameinfo['state'] <> "준비중" and $mode == "modify")echo "DISABLED";?> class="input">분
	</td>
</tr>
<script>
function changeTermOfDay(obj){
	if(obj.value <= 1800){
		var today = new Date();

		writeText.year.value=  today.getFullYear()  ;
		writeText.month.value=  today.getMonth() +1 ;
		writeText.day.value=  today.getDate()  ;
	}
	else{
		var today = new Date();

		var tomorrow = new Date(today.getTime() + 86400000);

		writeText.year.value=  tomorrow.getFullYear()  ;
		writeText.month.value=  tomorrow.getMonth() +1 ;
		writeText.day.value=  tomorrow.getDate()  ;
	}


//	Date.getTime();
}
</script>

<tr valign=top>
  <td>
  <img src=<?=$dir?>/t.gif border=0 height=1><br>
  <table  cellspacing=0 cellpadding=0 width=100% height=100%>
  <tr><td align=right><font class=red_8>하루의 길이</font></td>
  </tr></table>
  </td>
	<td>&nbsp;

				<select class='input' onchange=changeTermOfDay(this) name=termOfDay value=<?=$termOfDay?> <? if($mode == "modify")echo "DISABLED=true";?> >
				<? if($server['host'] == "werewolf6.cafe24.com"){ ?>
					<option value='900' <?if($termOfDay=='900') echo "SELECTED";?>>15 분 </option>
					<option value='1200' <?if($termOfDay=='1200') echo "SELECTED";?>>20 분 </option>
					<option value='1500' <?if($termOfDay=='1500') echo "SELECTED";?>>25 분 </option>
					<option value='1800' <?if($termOfDay=='1800') echo "SELECTED";?>>30 분 </option>
				<? } else { ?>
					<option value='86400' <?if($termOfDay=='86400') echo "SELECTED";?>>24 시간 </option>
				<? } ?>
				<!--
					<option value='1080' <?if($termOfDay=='1080') echo "SELECTED";?>>18 분 </option>
					<option value='3600' <?if($termOfDay=='3600') echo "SELECTED";?>>1 시간 </option>
					<option value='43200' <?if($termOfDay=='43200') echo "SELECTED";?>>12 시간 </option>-->
				<!--<option value='172800' <?if($termOfDay=='172800') echo "SELECTED";?>>48 시간 </option>-->
				</select>
	</td>
</tr>

<tr valign=top>
  <td>
  <img src=<?=$dir?>/t.gif border=0 height=1><br>
  <table  cellspacing=0 cellpadding=0 width=100% height=100%>
  <tr><td align=right><font class=red_8>발언 제한 시간</font></td>
  </tr></table>
  </td>
	<td>&nbsp;
		<?
			$bDisabled = ($mode == "modify") ? true : false;
			if($bDisabled) { ?>
				<!--
				<span class="align-right">
					<span>
					-->
						마을 시작 직후 <input type="text" name="delayAfterM" size="4" MAXLENGTH="4" value="<?=$gameinfo['delayAfter'] / 60?>" disabled class="input">분<br>&nbsp;
						사건 발생 직전 <input type="text" name="delayBeforeM" size="4" MAXLENGTH="4" value="<?=$gameinfo['delayBefore'] / 60?>" disabled class="input">분
					<!--
					</span>
				</span>
				-->
			<? } else { ?>
				<!--
				<span class="align-right">
					<span>
					-->
						마을 시작 직후 <input type="text" name="delayAfterM" size="4" MAXLENGTH="4" value="0" class="input">분<br>&nbsp;
						사건 발생 직전 <input type="text" name="delayBeforeM" size="4" MAXLENGTH="4" value="0" class="input">분
					<!--
					</span>
				</span>
				-->
			<? } ?>
	</td>
</tr>

<tr valign=top>
  <td>
  <img src=<?=$dir?>/t.gif border=0 height=1><br>
  <table  cellspacing=0 cellpadding=0 width=100% height=100%>
  <tr><td align=right><font class=red_8>룰</font></td>
  </tr></table>
  </td>
	<td>&nbsp;
		<?
			if($mode == "modify") $disabled= "DISABLED=true";
			else $disabled ="";
			
			echo DBselect1("rule"," ","no","name","$DB_rule","class='input' ".$disabled,$gameinfo['rule'],"");
		?>
	</td>
</tr>

<tr valign=top>
  <td>
  <img src=<?=$dir?>/t.gif border=0 height=1><br>
  <table  cellspacing=0 cellpadding=0 width=100% height=100%>
  <tr><td align=right><font class=red_8>서브룰</font></td>
  </tr></table>
  </td>
	<td>&nbsp;
		<?
			$subrule_result = mysql_query("select * from `zetyx_board_werewolf_subrule`");
			
			$bDisabled = ($mode == "modify") ? true : false;
			
			while($subrule_temp = mysql_fetch_array($subrule_result)) {
				if($bDisabled) {
					if(checkSubRule($gameinfo['subRule'], $subrule_temp[no])) $subruleChecked = "checked";
					else $subruleChecked = "";
				?>
					<input type="checkbox" name="subruleOption[]" value="<?=$subrule_temp[no]?>" <?=$subruleChecked?> disabled> <?=$subrule_temp[name]?>&nbsp;&nbsp;
				<? } else { ?>
					<input type="checkbox" name="subruleOption[]" value="<?=$subrule_temp[no]?>"> <?=$subrule_temp[name]?>&nbsp;&nbsp;
				<? }
				
				if($subrule_temp[no] % 3 == 0) echo "<br>&nbsp;";
			} ?>
	</td>
</tr>

<tr valign=top>
  <td>
  <img src=<?=$dir?>/t.gif border=0 height=1><br>
  <table  cellspacing=0 cellpadding=0 width=100% height=100%>
  <tr><td align=right><font class=red_8>롤 플레잉 세트</font></td>
  </tr></table>
  </td>
	<td>&nbsp;
				<!--?
				if($mode == "modify") $disabled= "DISABLED=true";
				else $disabled ="";
				
				echo DBselect1("characterSet","","no","name","$DB_characterSet where is_use = 1","class='input' ".$disabled,$gameinfo['characterSet'],"");
				?-->
		<!-- role playing set selector -->
		<?
			$bDisabled = ($mode == "modify") ? true : false;

			if($bDisabled) { ?>
				<input type="hidden" name="characterSet" id="characterSetInput" value="<?=$gameinfo['characterSet']?>">
				<input type="text" name="characterSetName" class="input" style="width:200px" id="characterSetNameInput" value="<? echo get_characterSetName("$DB_characterSet where no = $gameinfo[characterSet]"); ?>" disabled>
		<? } else { ?>
				<input type="hidden" name="characterSet" id="characterSetInput" value="<? echo init_characterSet(0, "no", "$DB_characterSet"); ?>">
				<input type="text" name="characterSetName" class="input" style="width:200px" id="characterSetNameInput" value="<? echo init_characterSet(0, "name", "$DB_characterSet"); ?>" disabled>
				<button type="button" id="RPSetBtn" onclick="openModal()">선택하기</button>
		<? } ?>
		
		<div id="modal-window" class="modal">
			<div class="modal-content">
				<div class="tabheader">
					<span id="closeX">&times;</span>
					<input type="text" id="RPSetInput" onkeyup="searchRPSet()" placeholder="Search for names...">
					
					<div class="tab">
						<button type="button" class="tablinks" onclick="openList(event, 'listByTimeSort')">제작순</button>
						<button type="button" class="tablinks" onclick="openList(event, 'listByAscendingSort')">가나다순</button>
					</div>
				</div>

				<div id="listByTimeSort" class="tabcontent">
					<ul class="RPSetUL">
						<? echo set_characterSet("$DB_characterSet where is_use = 1", "no"); ?>
					</ul>
				</div>

				<div id="listByAscendingSort" class="tabcontent">
					<ul class="RPSetUL">
						<? echo set_characterSet("$DB_characterSet where is_use = 1", "name"); ?>
					</ul>
				</div>
			</div>
		</div>
	</td>
</tr>

<?if($data['x'] ==1)$checked_memo="checked"; ?>
<tr>
	<td onclick="memo.rows+=4" align=right><label for="memo"><input type=checkbox name='zx' <?=$checked_memo?> value='1'> 마을 소개<br/>▼</label></td>
	<td>
		<textarea name="memo" id="memo" rows="20" style="width:100%"><?=$memo?></textarea>
	</td>
</tr>

<?=$hide_sitelink1_start?>
<tr>
  <td>
  <img src=<?=$dir?>/t.gif border=0 height=1><br>
  <table  cellspacing=0 cellpadding=0 width=100% height=100%>
  <tr><td align=right><font class=red_8>LINK1</font></td>
  </tr></table>
  </td>
  <td>&nbsp;<input type=text name=sitelink1 value="<?=$sitelink1?>" <?=size(50)?> maxlength=150 class="input"></td>
</tr>
<?=$hide_sitelink1_end?>

<?=$hide_sitelink2_start?>
<tr>
  <td>
  <img src=<?=$dir?>/t.gif border=0 height=1><br>
  <table  cellspacing=0 cellpadding=0 width=100% height=100%>
  <tr><td align=right><font class=red_8>LINK2</font></td>
  </tr></table>
  </td>
  <td>&nbsp;<input type=text name=sitelink2 value="<?=$sitelink2?>" <?=size(50)?> maxlength=150 class="input"></td>
</tr>
<?=$hide_sitelink2_end?>

<?=$hide_pds_start?>
<tr>
  <td>
  <img src=<?=$dir?>/t.gif border=0 height=1><br>
  <table  cellspacing=0 cellpadding=0 width=100% height=100%>
  <tr><td align=right><font class=red_8>FILE1</font></td>
  </tr></table>
  </td>
  <td>&nbsp;<input type=file name=file1 <?=size(40)?> maxlength=200 class="input"><?=$file_name1?></td>
</tr>
<tr>
  <td>
  <img src=<?=$dir?>/t.gif border=0 height=1><br>
  <table  cellspacing=0 cellpadding=0 width=100% height=100%>
  <tr><td align=right><font class=red_8>FILE2</font></td>
  </tr></table>
  </td>
  <td>&nbsp;<input type=file name=file2 <?=size(40)?> maxlength=200 class="input"><?=$file_name2?></td>
</tr>

<?=$hide_pds_end?>

<tr>
	<td colspan=2>
		<table border=0 cellspacing=1 cellpadding=2 width=100% height=40>
		<tr>
			<td align="right">
				<input type="submit" value="" src="skin/werewolf/image/ok.gif" border="0" onfocus="blur()" style="width:58px; height:61px; background-image:url(skin/werewolf/image/ok.gif); display:inline-block; cursor:pointer;" accesskey="s">
				&nbsp;&nbsp;<input type="button" value="" src="skin/werewolf/image/cancel.gif" border="0" onfocus="blur()" style="width:121px; height:61px; background-image:url(skin/werewolf/image/cancel.gif); display:inline-block; cursor:pointer;" onclick="javascript:void(history.back())">
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
</form>