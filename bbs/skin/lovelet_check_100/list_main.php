<?
if($member[no]==1) {
	$attendee = mysql_fetch_array(mysql_query("select no,name,level from zetyx_member_table where no = $data[ismember]"));

	$checkTime = time() - 86400 * 3;
	$checkDays = date('Y',$checkTime ).date('m',$checkTime ).date('d',$checkTime );

	$temp=mysql_fetch_array(mysql_query("select count(*) from zetyx_board_$id where $checkDays <= subject  and ismember='$attendee[no]'"));
	$mcheck=$temp[0];
?>
		<tr height=25>
			<td align=center><input type=checkbox name=cart value="<?=$data[no]?>"></td>
			<td align=center class=><?=$number?></td>
			<td align=center class=><?=$reg_date?></td>
			<td align=center><?=$name?></td>
			<td align=center><a href="skin/werewolf/view_ip_overlap.php?id=werewolf&player=<?=$attendee[no]?>" ><?=$attendee[level]?></a></td>
			<td align=center><a href="zboard.php?id=attendWerewolf&player=<?=$attendee[no]?>" ><?=$mcheck?></td>
			<?="<td align=center class=><a href=./admin_setup.php?exec=view_member&group_no=$group_no&exec2=modify&no=$attendee[no] target=_blank>Edit</a></td>"?>
		</tr>
<? } ?>