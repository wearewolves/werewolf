<?
		$view_trackback_result=mysql_query("select * from $t_comment"."_$id where parent='$no' and password = 'TrackBack' order by no asc");
//		$view_trackback_num=mysql_num_rows($view_trackback_result);
require_once("config/path_setup.php");

echo "<table id='tb'>";
echo "<thead><tr><td>트랙백 주소</td><td><span onclick=toClip('".$_zb_url."zerotb.php?id=$id&no=$no') onfocus=blur() title='Click to copy'>
<font color='#BC593B'>".$_zb_url."zerotb.php?id=$id&no=$no</font></span></td></tr></thead>";
echo "<col width=30%><col width=90%>";

			while($trackback_data=mysql_fetch_array($view_trackback_result)) {
				$t_name= stripslashes($trackback_data[name]);

				if($is_admin) $show_ip=" title='$trackback_data[ip]' "; else $show_ip="";    

				$c_memo=trim(stripslashes($trackback_data[memo]));
				$c_reg_date="<span title='".date("Y년 m월 d일 H시 i분 s초",$trackback_data[reg_date])."'>".date("Y/m/d",$trackback_data[reg_date])."</span>";
				if($trackback_data[ismember]) {
					if($trackback_data[ismember]==$member[no]||$is_admin||$member[level]<=$setup[grant_delete]) $a_del="<a onfocus=blur() href='del_comment.php?$href$sort&no=$no&c_no=$trackback_data[no]'>";
					else $a_del="&nbsp;<Zeroboard ";
				} else $a_del="<a onfocus=blur() href='del_comment.php?$href$sort&no=$no&c_no=$trackback_data[no]'>";
	
				if($is_admin) $show_ip=" title='$trackback_data[ip]' "; else $show_ip="";
				$_skinTimeStart = getmicrotime();
				include "view_trackback.php";
				$_skinTime += getmicrotime()-$_skinTimeStart;
				flush();
			}
echo "</table><br>";
?>