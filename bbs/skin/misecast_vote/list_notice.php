<table border=0 width=100% cellspacing=0 cellpadding=0>

<tr>
  <td height=1 bgcolor=#FAFAFA>
</tr>

<tr>
    <td height=20 align=right valign=bottom class=rini_ver3 nowrap>
      <?=$a_reply?><font face="Tahoma"><span style="font-size:8pt;">add article&nbsp;&nbsp;</span></font></a>
      <?=$a_modify?><font face="Tahoma"><span style="font-size:8pt;">modify&nbsp;&nbsp;</span></font></a>
      <?=$a_delete?><font face="Tahoma"><span style="font-size:8pt;">delete</span></font></a>
    </td>
</tr>

<tr>
  <td valign=top style='word-break:break-all; padding-left:5; padding-right:5; padding-top:4;padding-bottom:15;'>
      <b>Notice</b>&nbsp;&nbsp;<?=$data[subject]?>&nbsp;&nbsp;&nbsp;<font color=#FFC319 class=rini_ver3><?=$comment_num?></font>
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

</table>
