<?
 /* 간단한 답글 쓰기 표시

  -- 간단한 답글 관련
  <?=$hide_comment_start?> <?=$hide_comment_end?> : 간단한 답글 쓰기 보여주기/ 숨기기
  <?=$hide_c_password_start?> <?=$hide_c_password_end?> : 간단한 답글시 비밀번호 입력 보여주기/ 숨기기;;

  <?=$c_name?> : 코멘트시 이름 입력하는 곳;;

  ** view.php 제일 아래쪽에 간답한 답글이 시작하는 <table>태그 시작부분이 있습니다.
     그리고 간단한 답글이 있으면 view_comment_view.php 파일에서 출력을 합니다.

 */
?>
<!-- 간단한 답변글 쓰기 -->
<?=$hide_comment_end?> 

 <form method="post" name="writeComment" action="comment_ok.php">
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

<table border="0" cellspacing="0" cellpadding="0" width="<?=$width?>" id="writeComment">
	<tr>
		<td onclick="memo.rows+=4">▼
		</td>
		<td>
			<textarea name="memo" rows="7"></textarea>
		</td>
	<tr>
	<tr>
 		<td nowrap>
		</td>
		<td align=right>
			<?=$hide_c_password_start?>
				<font class=sly>name</font>	<input type=text name=name class="input">
				<font class=sly>pass</font>	<input type=password name=password class="input">
			<?=$hide_c_password_end?>
			<input type=submit value='Comment'class="submit">
		</td>
	</tr>
	</table>
 </td>
</tr>
<tr>
</table>
</form>