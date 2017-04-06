<div>
	이 게시판에서 작성한 글은 자동으로 비밀글이 되어 운영자와 작성한 사람만 볼 수 있습니다.<br>
	운영자가 검토한 후에 진행 중인 게임에 영향을 주지 않는다면 공개됩니다.<br>
	공개를 원치 않을 때는 제목에 [공개불가]라고 적어주시기 바랍니다.
</div>

<!-- 폼태그 부분;; 수정하지 않는 것이 좋습니다 -->
<form method="post" name="writeText" action="write_ok.php" onsubmit="return check_submit();" enctype="multipart/form-data">
<input type="hidden" name="page" value="<?=$page?>">
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="no" value="<?=$no?>">
<input type="hidden" name="select_arrange" value="<?=$select_arrange?>">
<input type="hidden" name="desc" value="<?=$desc?>">
<input type="hidden" name="page_num" value="<?=$page_num?>">
<input type="hidden" name="keyword" value="<?=$keyword?>">
<input type="hidden" name="category" value="<?=$category?>">
<input type="hidden" name="sn" value="<?=$sn?>">
<input type="hidden" name="ss" value="<?=$ss?>">
<input type="hidden" name="sc" value="<?=$sc?>">
<input type="hidden" name="mode" value="<?=$mode?>">
<!----------------------------------------------->
<?
if($mode=="write"){
	$secret ="1";
	$checked ="checked";
}
if($mode=="modify"){
	$secret = $secret ==" checked " ? "1" : "0";
	$checked ="checked";
}
?>

<table id="WritePage">
<col width="60"></col>
<col></col>
<?=$hide_start?>
<tr>
	<td><label for="name">Name</label></td>
	<td><input type="text" name="name" id="name" value="<?=$name?>" maxlength="20"></td>
</tr>
<tr>
	<td><label for="password">Password</label></td>
	<td><input type="password" name="password" id="password" maxlength="20"></td>
</tr>
<tr>
	<td><label for="email">E-mail</label></td>
	<td> <input type="text" name="email" id="email"value="<?=$email?>"> </td>
</tr>
<tr>
	<td><label for="homepage">Homepage</label></td>
	<td> <input type="text" name="homepage" id="homepage" value="<?=$homepage?>" maxlength="200"> </td>
</tr>
</tr>
<?=$hide_end?>
<tr>
	<td>Special</td>
	<td> 
		<?=$hide_notice_start?>
			<input type=checkbox name="notice" id="notice" style="width:15px" <?=$notice?> value="1">
			<label for="notice">Notice</label>
		<?=$hide_notice_end?>
		<?=$hide_html_start?>
			<input type=checkbox name="use_html" id="use_html" style="width:15px" <?=$use_html?> value="1">
			<label for="use_html">Html</label>
		<?=$hide_html_end?>

		<?=$hide_secret_start?>
		<?if($is_admin) {?>
			<input type=checkbox name="is_secret" id="is_secret" style="width:15px" <?=$checked?>  value="1">
			<label for="is_secret">Secret</label>
		<?}else{?>
			<input type='hidden' name='is_secret' value='<?=$secret?>'>
		<?}?>

		<?=$hide_secret_end?>
		<?=$category_kind?>
	</td>
</tr>
<tr>
	<td><label for="subject">Subject</label></td>
	<td><input type="text" name="subject" id="subject" value="<?=$subject?>" maxlength="200"> </td>
</tr>
	<td onclick="memo.rows+=4"><label for="memo">Contents<br/>▼</label></td>
	<td>
		<textarea name="memo" id="memo" rows="20"><?=$memo?></textarea>
	</td>
</tr>

<?=$hide_sitelink1_start?>
<tr>
	<td><label for="sitelink1">Link 1</label></td>
	<td><input type="text" name="sitelink1" id="sitelink1" value="<?=$sitelink1?>" maxlength="200"></td>
</tr>
<?=$hide_sitelink1_end?>

<?=$hide_sitelink2_start?>
<tr>
	<td><label for="sitelink2">Link 2</label></td>
	<td><input type="text" name="sitelink2" id="sitelink2" value="<?=$sitelink2?>" maxlength="200"> </td>
</tr>
<?=$hide_sitelink2_end?>

<?=$hide_pds_start?>
<tr>
	<td>&nbsp;</td>
	<td>최대 <?=$upload_limit?> 까지 업로드 가능합니다.</td>
</tr>
<tr>
	<td><label for="file1">File 1</label></td>
	<td><input type="file" name="file1" id="file1" maxlength="255"><?=$file_name1?></td>
</tr>
<tr>
	<td><label for="file2">File 2</label></td>
	<td><input type="file" name="file2" id="file2" maxlength="255"><?=$file_name2?></td>
</tr>

<?=$hide_pds_end?>
</table>

<div align=center>
	<input type="submit" value="write" class="submit">
	<input type="button" value="back" onclick="history.back()" class="submit">
</div>
</form>