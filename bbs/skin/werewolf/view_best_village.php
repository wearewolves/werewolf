<html>
<head>
<title>:: Best 마을 ::</title>
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
	text-align:left;
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

.caption{
	width:100%;
	border-bottom:1px solid #151515;
	margin-left:auto;margin-right:auto;
}

.caption h1{
	width:80%;
	margin-left:auto;margin-right:auto;
}

.caption a{
	float:right;
	padding:0;
	margin:0;
}
.title{
	color:#A30;
	margin:5px 10px;
	padding: 10px 20px;
	background: #111111;
	border: solid 1px #151515;
}
</style>
<meta name="viewport" content="width=device-width, initial-scale=1.0/">
</head>
<body>

<?
	echo "<div class='title'><h1>BEST 마을</h1></div><br>";
?>

<table id="record">
<col width = ></col>
<col width =50></col>
<col width =110></col>
<col width =30></col>
<col width =30></col>
<col width =60></col>
<thead>
	<tr>
		<td>마을 이름</td>
		<td>룰</td>
		<td>사건 발생</td>
		<td>추천</td>
		<td>인원</td>
		<td>결과</td>
	</tr>
</thead>
<tbody>
	<?	
	function DB_array($key,$value,$db){
		$temp_result=mysql_query("select * from $db ");

		while($temp_member=@mysql_fetch_array($temp_result)){
				$members[$temp_member[$key]]=$temp_member[$value];
		}

		return $members;
	}

	$wintype = DB_array("no","wintype","`".$db->truecharacter."`");
	$trueCharacterList = DB_array("no","character","`".$db->truecharacter."`");
	$win = 0;
	$lost = 0;
	

	//데이타 가져오기
	$sql="select *  from  `".$db->entry."` where player = $player order by game";

	$sql ="SELECT  *     FROM  `zetyx_board_werewolf` ,  `zetyx_board_werewolf_gameinfo`  WHERE  NO  = game and state ='게임끝' and `good` >= floor( (  `players`  - 1 ) * 0.75  )ORDER  BY  `no`  DESC ";


	$temp_result=mysql_query($sql);
	
		while($gameinfo=@mysql_fetch_array($temp_result)){
			$deathTime =date("Y",$gameinfo['deathtime'])."-".date("m",$gameinfo['deathtime'])."-".date("d",$gameinfo['deathtime'])."  ".date("H",$gameinfo['deathtime']).":".date("i",$gameinfo['deathtime']);
			?>
			<tr onMouseOver="this.style.backgroundColor='#212120'" onMouseOut=this.style.backgroundColor='' >
				<?
						switch($gameinfo['rule']){
							case 1: $rule = "기본";
										break;
							case 2: $rule = "햄스터"; 
										break;
							case 3: $rule = "익스펜션";
										break;
							case 4: $rule = "신뢰도";
										break;
							case 5: $rule = "인스턴트";
										break;
							case 6: $rule = "참살";
										break;
						}

						switch($gameinfo['win']){
							case 0: $winType = "인간의 승";
										$fontColor="#384887";
										break;
							case 1: $winType = "인랑의 승"; 
										$fontColor ="#BB3333";
										break;
							case 2: $winType = "햄스터 승";
										$fontColor ="#FFCC99";
										break;
							case 3: $winType = "디아블로";
										$fontColor ="red";
										break;
						}
				?>

				<td><a href='../../view.php?id=<?=$id?>&no=<?=$gameinfo['no']?>'><?=$gameinfo['subject']?></a></td>
				<td><?=$rule?></td>
				<td><?=$deathTime ?></td>
				<td><?=$gameinfo['good']?></td>
				<td><?=$gameinfo['players']?></td>
				<td style='color:<?=$fontColor?>'><?=$winType?></td>
			</tr>
		<?}
	?>
</tbody>
</table>


<?	include "../../../Werewolf/foot.htm";?>
</body>
</html>