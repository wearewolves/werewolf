<SCRIPT LANGUAGE="JavaScript">
<!--
function zb_formresize(obj) {
	obj.rows += 3;
}
function checkBug(){
	if(write.type.value==0){
		alert("버그 타입을 선택해주십시오.");
		write.type.focus();
		return false;
	}
	if(write.serverity.value==0){
		alert("버그의 심각도를 정해주십시오.");
		write.serverity.focus();
		return false;
	}
	if(write.server.value==0){
		alert("버그가 발생한 서버를 선택해주십시오.");
		write.server.focus();
		return false;
	}
	return check_submit();
}
// -->
</SCRIPT>

  <? // 프로젝트 읽어오기
	include("$dir/lib/lib.php"); 

	if($mode=="modify"){
		$bug=mysql_fetch_array(mysql_query("select * from $DB_brief where bug=$no"));
	}	
?>  


<table border=0 width=100% cellspacing=0 cellpadding=0>
<form method=post name=write action=<?="write_bug_ok.php"?> onsubmit="return checkBug();" enctype=multipart/form-data>
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
<tr><td height=20 align=left><!--&nbsp;&nbsp;<?=$title?>--></td>
</tr>
</table>

<!--view-->
<table  border=0 cellspacing=1 cellpadding=2  width=<?=$width?>>
<col width=80></col><col width=></col>

<tr bgcolor=111111>
	<td height=22>
		<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
		<tr >
			<td align=center  width=100% >버그</td>
		</tr>
		</table>
	</td>
	<td>
		<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
		<tr >
			<td align=left   width=100%>&nbsp;&nbsp;&nbsp;<input type=text name=subject value="<?=$subject?>" <?=size(60)?> maxlength=100 style=width:88% class=red_input></td>
		</tr>
		</table>		
	</td>
</tr>

<tr ><td height=22 align=center bgcolor=#222222><b>보고자</td><td>&nbsp;&nbsp;&nbsp;<?=$member[name]?></td></tr>
<tr>
	<td bgcolor=#222222 align=center height=22><b>타입</td>
	<td>&nbsp;&nbsp;<?=DBselect("type","<option value=0>버그 타입</option>","no","name",$DB_type,"style='font-size:9pt;width=200'","$bug[type]");?></td>
</tr>
<tr>
	<td bgcolor=#222222 align=center height=22><b>심각도</td>
	<td>&nbsp;&nbsp;<?= DBselect("serverity","<option value=0>심각도</option>","no","name",$DB_serverity,"style='font-size:9pt;width=200'","$bug[serverity]")?>	
</tr>			
<tr>
	<td bgcolor=#222222 align=center height=22><b>서버</td>
	<td>&nbsp;&nbsp;<?=DBselect("server","<option value=0>서버</option>","SID","name",$DB_server,"style='font-size:9pt;width=200'","$bug[server]")?></td>
</tr>

<tr><td height=1 colspan=2 bgcolor=#151515></td></tr>
<tr><td height=10 colspan=2 ></td></tr>


<tr bgcolor=#111111>
	<td height=22>
		<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
		<tr >
			<td align=center   width=100>세부 내용</td>
		</tr>
		</table>
	</td>
	<td>
		<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
		<tr >
			<td align=center    width=100%>&nbsp;</td>
		</tr>
		</table>		
	</td>
</tr>
<tr bgcolor=#222222>
	<td height=22>
		<table border=0 cellspacing=1 cellpadding=2  width=100% height=100%>
		<tr >
			<td align=center   width=100></td>
		</tr>
		</table>
	</td>
	<td>
		<table border=0 cellspacing=1 cellpadding=2 width=100% height=100%>
		<tr >
			<td align=center    width=100%>게시물 하나에 버그 하나씩 신고해 주세요.<br> 신고할 버그가 한 개 이상이라면 나눠서 작성해 주세요.&nbsp;</td>
		</tr>
		</table>		
	</td>
</tr>


</table>



<!--write-->


<table border=0 width=100% cellspacing=1 cellpadding=0>
<col width=80></col><col width=></col>
<td height=2><img src=<?=$dir?>/t.gif border=0 height=2></td>
<?=$hide_start?>
<tr>
  <td>
  <img src=<?=$dir?>/t.gif border=0 height=1><br>
  <table  cellspacing=0 cellpadding=0 width=100% height=100%>
  <tr><td align=right><font class=red_7>PASSWERD</font></td>
  </tr></table>
  </td>
  <td>&nbsp;<input type=password name=password <?=size(20)?> maxlength=20 class=red_input></td>
</tr>

<tr>
  <td>
  <img src=<?=$dir?>/t.gif border=0 height=1><br>
  <table  cellspacing=0 cellpadding=0 width=100% height=100%>
  <tr><td align=right><font class=red_7>NAME</font></td>
  </tr></table>
  </td> 
  <td>&nbsp;<input type=text name=name value="<?=$name?>" <?=size(20)?> maxlength=20 class=red_input></td>
</tr>

<tr>
  <td>
  <img src=<?=$dir?>/t.gif border=0 height=1><br>
  <table  cellspacing=0 cellpadding=0 width=100% height=100%>
  <tr><td align=right><font class=red_7>E-MAIL</font></td>
  </tr></table></td>
  <td>&nbsp;<input type=text name=email value="<?=$email?>" <?=size(40)?> maxlength=200 class=red_input></td>
</tr>

<tr>
  <td>
  <img src=<?=$dir?>/t.gif border=0 height=1><br>
  <table  cellspacing=0 cellpadding=0 width=100% height=100%>
  <tr><td align=right><font class=red_7>HOMEPAGE</font></td>
  </tr></table>
  </td>
  <td>&nbsp;<input type=text name=homepage value="<?=$homepage?>" <?=size(40)?> maxlength=200 class=red_input></td>
</tr>
<?=$hide_end?>

<tr>
  <td>
  <img src=<?=$dir?>/t.gif border=0 height=1><br>
  <table  cellspacing=0 cellpadding=0 width=100% height=100%>
  <tr><td align=right><font class=red_7>SELECT</font></td>
  </tr></table>
  </td>
  <td>&nbsp;
  <?=$category_kind?>
       <?=$hide_notice_start?>
	   <input type=checkbox name=notice <?=$notice?> value=1>공지사항
	   <?=$hide_notice_end?>
       <?=$hide_html_start?>
	   <input type=checkbox name=use_html <?=$use_html?> value=1>HTML
	   <?=$hide_html_end?>
       <input type=checkbox name=reply_mail <?=$reply_mail?> value=1>답변메일받기
       <?=$hide_secret_start?>
	   <input type=checkbox name=is_secret <?=$secret?> value=1>비밀글
	   <?=$hide_secret_end?>
&nbsp;
  </td>
</tr>


<tr  valign=center><td align=right><img src=<?=$dir?>/b_down.gif border=0 valign=absmiddle style=cursor:hand; onclick=zb_formresize(document.write.memo)></td>
  <td colspan=2 style=padding:5px>&nbsp;
  <textarea name=memo style=width:95% rows=10 class=red_textarea><?=$memo?></textarea></td>
</tr>

<?=$hide_download1_start?>
<tr bgcolor=#222222>
	<td height=22>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr><td align=center bgcolor=#111111><font class=red_7>FILE1</font></td>
	</tr></table>
	</td>
	<td>&nbsp;&nbsp;&nbsp;<input type=file name=file1 <?=size(40)?> maxlength=200 class=red_input><?=$file_name1?></td>
</tr>
<tr><td height=1 colspan=2 bgcolor=#151515></td></tr>
<?=$hide_download1_end?>

<?=$hide_download2_start?>
<tr bgcolor=#222222>
	<td height=22>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr><td align=center bgcolor=#111111><font class=red_7>FILE2</font></td></tr></table></td>
	<td>&nbsp;&nbsp;&nbsp;<input type=file name=file2 <?=size(40)?> maxlength=200 class=red_input><?=$file_name2?></td>
</tr>
<tr><td height=1 colspan=2 bgcolor=#151515></td></tr>
<?=$hide_download2_end?>

<?=$hide_sitelink1_start?>
<tr bgcolor=#222222>
	<td height=22>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr><td align=center bgcolor=#111111><font class=red_7>LINK1</font></td></tr></table></td>
	<td>&nbsp;&nbsp;&nbsp;<input type=text name=sitelink1 value="<?=$sitelink1?>" <?=size(50)?> maxlength=150 class=red_input></td>
</tr>
<tr><td height=1 colspan=2 bgcolor=#151515></td></tr>
<?=$hide_sitelink1_end?>

<?=$hide_sitelink2_start?>
<tr bgcolor=#222222>
	<td height=22>
	<table border=0 cellspacing=0 cellpadding=0 width=100% height=100%>
	<tr><td align=center bgcolor=#111111><font class=red_7>LINK2</font></td></tr></table></td>
	<td>&nbsp;&nbsp;&nbsp;<input type=text name=sitelink2 value="<?=$sitelink2?>" <?=size(50)?> maxlength=150 class=red_input></td>
</tr>
<tr><td height=1 colspan=2 bgcolor=#151515></td></tr>
<?=$hide_sitelink2_end?>


<tr>
	<td colspan=2>
		<table border=0 cellspacing=1 cellpadding=2 width=100% height=40>
		<tr>
			<td align=right>
				<input type=image src=<?=$dir?>/images/b_write.gif border=0 onfocus=blur() border=0 accesskey="s">
				&nbsp;&nbsp;<a href=javascript:void(history.back()) onfocus=blur()><img src=<?=$dir?>/images/b_cancel.gif border=0></a>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
