
<?
	$t_today = date("Ymd");
// 오늘 출석했는지 안했는지
	$check=mysql_num_rows(mysql_query("select no from zetyx_board_$id where subject='$t_today' and ismember='$nno'"));
      if ($check) {
          $checked = '';
      } else {
          $checked = '[출석하세요~]';
      }
?>
<table  width="<?=$width?>" border="0" cellpadding="0" cellspacing="0" >
	<tr height=25>
		<td width=50%><?=$a_delete_all?>관리자정리</a></td>
		<td width=50% align=right>
			<?
			
//			if($member[level] ==9) echo $a_write.$checked."</a>"; else echo "[신규회원만 출석도장을 찍을 수 있습니다.]";
			switch($member[level]){
				case 8: echo $a_write.$checked."</a>";
							break;
				case 9: echo $a_write.$checked."</a>";
							break;
				case 10: echo "[로그인 해주세요.]";
							break;
				default :  echo "[게임에 참여할 수 있는 등급입니다.]";
							break;
			}			
			?></td>
	</tr>
</form>
</table>