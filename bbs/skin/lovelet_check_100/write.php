<?
	$subject = date('Ymd');
	$mm=date('YmdHis');
?>
<?if($member[level] <> 8 and $member[level]<> 9) error("신규회원 또는 돌연사 한 사람만 출석도장을 찍을 수 있습니다.");?>

<?if(true){?>
<table width=250 border="0" cellspacing="0" cellpadding="0">
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
<input type=hidden name=subject value="<?=$subject?>">
<input type=hidden name=memo value="출석체크!<?=$mm?>">
<input type=hidden name=sitelink1 value="01">

	<tr height="30">
		<td align=center bgcolor=660325><font color=ffffff><b>출 석 체 크 !</b></font></td>
	</tr>
<?=$hide_sitelink1_start?>
	<tr height=50>
		<td align=center><img name='face' src='<?=$dir?>/icon/01.png' border=0></td>
	</tr>
<?=$hide_sitelink1_end?>
	<tr height=25>
		<td align=center><input type=submit value=" 확 인 " class=submit accesskey="s">&nbsp;<input type=button value=" 취 소 " class=button onclick=history.back()></td>
	</tr>
</form>
</table>
<?}else{?>
<table width=300 border="0" cellspacing="0" cellpadding="0">
<form method=post name=write action=write_ok.php onsubmit="return check_submit();" enctype=multipart/form-data>
	<tr height="30">
		<td align=center bgcolor=660325><font color=ffffff><b>출 석 체 크 !</b></font></td>
	</tr>
	<tr height=50>
		<td align=center><img name='face' src='<?=$dir?>/icon/01.png' border=0></td>
	</tr>
	<tr height=25>
		<td align=center>출석 도장을 찍을 수 있는 시간이 아닙니다. <br>오후 8시(20시)에서 새벽 2시(02시) 사이에 도장을 찍을 수 있습니다. </td>
	</tr>
</table>
<?}?>