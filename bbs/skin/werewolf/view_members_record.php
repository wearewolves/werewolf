<html>
<head>
<title>:: 수다의 전당 ::</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0/">
<?
// register_globals가 off일때를 위해 변수 재 정의
	@extract($HTTP_GET_VARS); 
	@extract($HTTP_POST_VARS); 
	@extract($HTTP_SERVER_VARS); 
	@extract($HTTP_ENV_VARS);

// 제로보드 라이브러리 가져옴
	$_zb_path = realpath("../../")."/";
	include $_zb_path."lib.php";

// DB 연결정보와 회원정보 가져옴
	$connect = dbConn();
	$member  = member_info();

// 게시판 설정을 가져옴
//error($_zb_path);
	$setup=get_table_attrib($id);
	if(!$setup[no]) error("존제하지 않는 게시판 입니다.","window.close");




	include "../../../Werewolf/head.htm";

	require_once("class/DB.php");
	$db= new DB($id);
?>

<style type="text/css">

#record{
	border-collapse:collapse;
	width:100%;
	font-size:11px;
	color:#666;
	margin:25px 0px;
}
#record td{
	padding:4px 1px;
}
#record thead{
	background:#222;
	text-align:center;
}
#record thead td{
	border:1px solid #151515;

}
#record tbody{
/*	background:#555;*/
	text-align:right;
}
#record tbody td{
	border-bottom:1px solid #151515;
}
.sidebar{
	border-left:1px solid #151515;
}
.blue{
	color:#384887;
}
.red{
	color:#B66;
	
}
.title{
	color:#A30;
	margin:5px 10px;
	padding: 10px 20px;
	background: #111111;
	border: solid 1px #151515;
}
</style>
</head>
<body>

<?
	if(!$year) $year = date('y');
	if(!$month or $month >date('m')) $month = date('m')-1;

	$startDay = mktime(0 ,0, 0, $month,1,$year);
	$endDay = mktime(0 ,0, 0, $month+1,0,$year);

	echo "<div class='title'><h1>".($month)."月 수다의 전당</h1></div><br>";
	echo "일반 로그 작성 순위<br>";
	echo  "(".date("Y",$startDay)."년".date("m",$startDay)."월".date("d",$startDay)."일 00시 ~  ";
	echo  date("Y",$endDay)."년".date("m",$endDay)."월".date("d",$endDay)."일 00시)";
?>

<table id="record">
<col width = 30></col>
<col width = 60></col>
<col width = 60></col>
<col width =></col>
<col width =30></col>
<thead>
	<tr>
		<td> 순위</td>
		<td>일반 로그</td>
		<td>게임 횟수</td>
		<td>회원</td>
		<td>홈피</td>
	</tr>
</thead>
<tbody>
	<?	
// GetTheTable 회원 명단 가져오기
	$sql ="SELECT  `year` ,  `month` ,  `player` ,  `commentCount` ,  `gameCount` ,  `name`,`homepage`,`open_homepage`  FROM  `zetyx_board_werewolf_bigmouth` ,  `zetyx_member_table`  WHERE  NO  = player and `year` =".date("Y",$startDay)." AND  `month`  = ".$month." order by `commentCount` desc ";



//	echo $sql;
	$i=0;$prePoint=0;
	//데이타 가져오기
	$temp_result=mysql_query($sql);
	$member_counter =0;
	
		while($membersRecord=mysql_fetch_array($temp_result)){
			$member_counter++;
//			if($membersRecord['player'] <>1 and array_key_exists($membersRecord['player'],$members)){
				++$i;
				if($count != $membersRecord['commentCount']);			
				?>
				<tr onMouseOver="this.style.backgroundColor='#212120'" onMouseOut=this.style.backgroundColor='<?if ($i==1) echo "#151515"?>' <?if ($i==1) echo "style='background-Color:#151515'"?>>
			<?	if($count == $membersRecord['commentCount'])
				echo "<td></td>";
				else echo "<td  align=center>".$i."</td>";

				$count = $membersRecord['commentCount'];

				echo "<td class=blue><b>".$membersRecord['commentCount']."</b></td>";
				echo "<td>".$membersRecord['gameCount']."</td>";

				if($member['level'])echo "<td align=center><a href=view_private_record.php?id=".$id."&player=$membersRecord[player]> ".stripslashes($membersRecord['name'])."</a></td>";
				else echo "<td align=center>".stripslashes($membersRecord['name'])."</td>";

				if($membersRecord['homepage'] and $membersRecord['open_homepage']){
					$homepage = $membersRecord['homepage'];
					echo "<td><a href='".$homepage."' target='blank'>-</a></td>";
				}
				else echo "<td></td>";

				echo "</tr>";
//			}		
			flush();
		}
	?>
</tbody>
</table>
<?echo $member_counter."명";?>
<?	include "../../../Werewolf/foot.htm";?>
</body>
</html>