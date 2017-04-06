<table border=0 cellspacing=0 cellpadding=0 width=<?=$width?>>
<tr>
<td colspan=2 height=1 bgcolor=#151515></td>
</tr>

<tr>
<td colspan=2 height=5></td>
</tr>

<tr height=30>
<td width=80 align=right class=rini_ver>subject&nbsp;&nbsp;&nbsp;</td>
<td align=left><?=$subject?>&nbsp;&nbsp;<font class=rini_ver3>:: <?=$vote?> voted</font></td>
</tr>

<tr>
<td colspan=2 height=2></td>
</tr>

<tr>
<td colspan=2 height=1 bgcolor=#151515></td>
</tr>
</table>

<table border=0 cellspacing=0 cellpadding=0 width=<?=$width?>>
<tr>
<td height=20></td>
</tr>
<tr>
 <td align=center>
<?
  //// 삭제하지 마세요;; 설문조사를 위한 프로그램 불러오는 부분입니다 //////
  include "include/vote_check.php";
  //// 위의 파일에서는 현재 스킨디렉토리의 vote_list.php파일을 불러씁니다///
?>
 </td>
</tr>
<tr>
<td height=15></td>
</tr>
</table>

<!-- 간단한 답글 시작하는 부분 -->
<?=$hide_comment_start?>
<table border=0 cellspacing=0 cellpadding=0 width=<?=$width?>>
<?=$hide_comment_end?>
