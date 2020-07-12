<?require_once("config/notice_setup.php");

	$handleLog = fopen($_zb_server_path."werewolfLatestWorking.txt", "r");

	if($handleLog) {
		$buffer = fgets($handleLog , 4096);
		fclose($handleLog );

		if((time () - $buffer) > 70) {
			//echo "'서버가 작동 중입니다."
			$notice[1] = $notice_serverdown;
			$notice[1] = nl2br($notice[1]);
			$noticeColor ="#FF9966";
		} else {
			$noticeColor ="#CC3333";
		}

	}
if($notice[1]) { ?>
<div class="noticeTop">
<h1 align=left></h1>
	<font color="<?=$noticeColor?>">
	<?=$notice[1]?>
	</font>
<h1 align=left></h1>
</div>
<? } ?>

<? if($playCount<3 and !$is_admin) { ?>
	<div id="notice">
		<h1>인랑에 처음 오셨다면...</h1>
		<ol>
			<? foreach($noviceNotice as $novice)
				echo "<li>".$novice."</li>";
			?>			
		</ol>
	</div>
<? } ?>

<div id="notice">
<h1>게임의 재미를 반감 하는 금지 행동들</h1>
인랑은 설득과 거짓말을 하거나 당하면서 판단, 추리하는 사유를 즐기는 게임입니다.<br />
이런 인랑의 즐거움을 방해하는 행동들을 금지합니다.<br/>

<ul type="disc">
		<? foreach($mannerNotice as $manner)
			echo "<li class='alert'>".$manner."</li>";
		?>	
</ul>

</div>

<?if($is_admin){
	echo "플레이어:".$NowPlayerCount."<br />";	
	echo $server['ip'] ;
}?>

<table border=0 cellspacing=0 cellpadding=0 width=<?=$width?> style="margin-left:auto;margin-right:auto;">
<form method=post name=list action=list_all.php><input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>><input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=selected>
<input type=hidden name=exec>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<col width=5%></col>
<col width=55%></col>
<col width=8%></col>
<col width=5%></col>
<col width=10%></col>
<col width=5%></col>
<col width=10%></col>

<!--
<tr align=center bgcolor="#101010">
	<td height=25><?=$a_no?>번호</a></td>
	<td height=25><?=$a_subject?>마 을</a></td>
	<td height=25>룰	</td>
	<td height=25 title="다음 사건 발생까지 걸리는 시간">하루</td>
	<td height=25><?=$a_date?>사건 시작</td>	
	--<td height=25>발생 시간</td>
	<td height=25>인원</td>
	<td height=25>상태</td>
</tr>
-->
