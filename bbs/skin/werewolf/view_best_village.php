<html>
<head>
<title>:: Best ���� ::</title>
<?
// register_globals�� off�϶��� ���� ���� �� ����
	@extract($HTTP_GET_VARS); 
	@extract($HTTP_POST_VARS); 
	@extract($HTTP_SERVER_VARS); 
	@extract($HTTP_ENV_VARS);

// ���κ��� ���̺귯�� ������
	$_zb_path = realpath("../../")."/";
	include $_zb_path."lib.php";

// DB ���������� ȸ������ ������
	$connect = dbConn();
	$member  = member_info();

// �Խ��� ������ ������
//error($_zb_path);
	$setup=get_table_attrib($id);
	if(!$setup[no]) error("�������� �ʴ� �Խ��� �Դϴ�.","window.close");

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
</head>
<body>

<?
	echo "<div class='title'><h1>BEST ����</h1></div><br>";
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
		<td>���� �̸�</td>
		<td>��</td>
		<td>��� �߻�</td>
		<td>��õ</td>
		<td>�ο�</td>
		<td>���</td>
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
	

	//����Ÿ ��������
	$sql="select *  from  `".$db->entry."` where player = $player order by game";

	$sql ="SELECT  *     FROM  `zetyx_board_werewolf` ,  `zetyx_board_werewolf_gameinfo`  WHERE  NO  = game and state ='���ӳ�' and `good` >= floor( (  `players`  - 1 ) * 0.75  )ORDER  BY  `no`  DESC ";


	$temp_result=mysql_query($sql);
	
		while($gameinfo=@mysql_fetch_array($temp_result)){
			$deathTime =date("Y",$gameinfo['deathtime'])."-".date("m",$gameinfo['deathtime'])."-".date("d",$gameinfo['deathtime'])."  ".date("H",$gameinfo['deathtime']).":".date("i",$gameinfo['deathtime']);
			?>
			<tr onMouseOver="this.style.backgroundColor='#212120'" onMouseOut=this.style.backgroundColor='' >
				<?
						switch($gameinfo['rule']){
							case 1: $rule = "�⺻";
										break;
							case 2: $rule = "�ܽ���"; 
										break;
							case 3: $rule = "�ͽ����";
										break;
							case 4: $rule = "�ŷڵ�";
										break;
							case 5: $rule = "�ν���Ʈ";
										break;
						}

						switch($gameinfo['win']){
							case 0: $winType = "�ΰ��� ��";
										$fontColor="#384887";
										break;
							case 1: $winType = "�ζ��� ��"; 
										$fontColor ="#BB3333";
										break;
							case 2: $winType = "�ܽ��� ��";
										$fontColor ="#FFCC99";
										break;
							case 3: $winType = "��ƺ��";
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