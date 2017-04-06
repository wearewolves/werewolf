<SCRIPT LANGUAGE="JavaScript">
<!--
function formresize(mode) {
        if (mode == 0) {
                document.write.memo.cols  = 80;
                document.write.memo.rows  = 20; }
        if (mode == 1) {
                document.write.memo.cols += 5; }
        if (mode == 2) {
                document.write.memo.rows += 3; }
}
// -->
</SCRIPT>

<table align=center border=0 cellspacing=0 cellpadding=0 width=<?=$width?>>

<!-- 폼태그 부분;; 수정하지 않는 것이 좋습니다 -->
<form method=post name=write action=write_ok.php onsubmit="return check_submit();" enctype=multipart/form-data>
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
<input type=hidden name=memo value="설문조사<?=time()?>">
<input type=hidden name=use_html value=1>
<!----------------------------------------------->

<tr>
 <td height=40 align=center valign=top>
<b>
<?
 if(!$mode||$mode=="write") echo "새로운 설문조사 작성";
 elseif($mode=="reply") echo "설문조사 항목 추가";
 else echo"설문조사 제목(항목) 수정";
?>
</b>
<b>
<?
 if($mode!="modify") $subject="";
?>
</b>
 </td>
</tr>
<tr>
 <td align=center>
 <table border=0 cellspacing=0 cellpadding=0 align=center>

<?=$hide_start?>
<tr height=24>
 <td width=70 align=right><font class=rini_ver>name</font>&nbsp;&nbsp;&nbsp;</td>
 <td align=left><input type=text name=name value="<?=$name?>" <?=size(20)?> maxlength=20 class=rini_input style="width:200;height:18;">
 </td>
</tr>

<tr height=24>
 <td width=70 align=right><font class=rini_ver>password</font>&nbsp;&nbsp;&nbsp;</td>
 <td align=left><input type=password name=password <?=size(20)?> maxlength=20 class=rini_input style="width:200;height:18;">
 </td>
</tr>

<tr height=24>
  <td width=70 align=right><font class=rini_ver>E-mail</font>&nbsp;&nbsp;&nbsp;</td>
  <td align=left><input type=text name=email value="<?=$email?>" <?=size(40)?> maxlength=200 class=rini_input style="width:320;height:18;">
  </td>
</tr>



<tr height=24>
  <td width=70 align=right><font class=rini_ver>homepage</font>&nbsp;&nbsp;&nbsp;</td>
  <td align=left><input type=text name=homepage value="<?=$homepage?>" <?=size(40)?> maxlength=200 class=rini_input style="width:320;height:18;">
  </td>
</tr>
<?=$hide_end?>

<tr height=30>
  <td width=70 align=right><font class=rini_ver>option</font>&nbsp;&nbsp;&nbsp;</td>
  <td>
  <table height=24 border=0 cellpadding=0 cellspacing=0>
      <tr height=24>
       <?=$hide_notice_start?>
       <td><input type=checkbox name=notice <?=$notice?> value=1></td>
       <td>&nbsp;공지사항&nbsp;&nbsp;</td>
       <?=$hide_notice_end?>
      </tr>
   </table>
   </td>
</tr>

<tr height=5><td></td></tr>

<tr height=24>
  <td width=70 align=right><font class=rini_ver>subject</font>&nbsp;&nbsp;&nbsp;</td>
  <td><input type=text name=subject value="<?=$subject?>" <?=size(60)?> maxlength=300 class=rini_input style="width:320;height:18;">
  </td>
</tr>

<tr>
<td height=10 colspan=2></td>
</tr>

</table>

<table border=0 cellspacing=0 cellpadding=0 width=<?=$width?>>
<tr height=30>
  <td align=center>
  <input type=submit value="write" class=rini_submit>
  <input type=button value="back" onclick=history.go(-1) class=rini_submit>
  </td>
</tr>

<tr>
<td height=10></td>
</tr>
</table>

</td>
</tr>
</table>

