<table border=0 width=100% cellspacing=0 cellpadding=0>

<tr>
  <td height=1 bgcolor=#151515>
</tr>

<tr>
    <td height=27 align=right  nowrap><div class=rini_ver2>
      <?=$a_reply?>설문 항목 추가&nbsp;/&nbsp;</a>
      <?=$a_modify?>수정&nbsp;/&nbsp;</a>
      <?=$a_delete?>삭제</a>
      </div>
    </td>
</tr>

<tr>
  <td height=40 valign=top style='word-break:break-all;'>
      <b><?=$loop_number?>. <?=$subject?> (<?=$vote?>)</b>&nbsp;&nbsp;&nbsp;<font color=#000000 class=rini_ver3><?=$comment_num?></font>
  </td>
</tr>

<tr>
  <td style='word-break:break-all;'>

<?
  //// 삭제하지 마세요;; 설문조사를 위한 프로그램 불러오는 부분입니다 //////
  include "include/vote_check.php";
  //// 위의 파일에서는 현재 스킨디렉토리의 vote_list.php파일을 불러씁니다///
?>
  </td>
</tr>

<tr>
  <td height=10>
</tr>

</table>


