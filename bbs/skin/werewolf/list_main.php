<? /////////////////////////////////////////////////////////////////////////
 /*
 목록을 출력하는 부분입니다.
 목록은 여러개이기 때문에 이 파일을 계속 읽어서 출력합니다.
 순환이 되도록 잘 작성하셔야 합니다.
 아래는 HTML 안에 그대로 사용해주시면 순환을 하면서 출력을 합니다.

 <?=$number?> : 가상번호. 즉 순서대로 나오는 번호
 * <?=$data[no]?> : 절대번호, 절대 바뀌지 않는 번호..
 * <?=$loop_number?> : 현재 선택되어 있는 글이라도 번호로 나오게
 <?=$name?> : 메일이 링크되어 있는 이름 * 원래 그대로 <?=$data[name]?>
 <?=$email?> : 메일.. 거의 직접 쓸일은 없음;;
 <?=$subject?> : 링크가 되어 있는 제목  * 원래 그대로 <?=$data[suject]?>
 <?=$memo?> : 내용 부분
 <?=$hit?> : 조회수
 <?=$vote?> : 추천수
 <?=$ip?> : 아피주소
 <?=$comment_num?> : 간단한 답글 수 [ ] 가 둘러싸여 있는것;; <?=$data[comment_num]?> 은 숫자만;;
 <?=$reg_date?> : 글쓴 날자
 <?=$category_name?> : 카테고리 이름

 <?=$face_image?> : 현재 회원상태의 아이콘;;

 <?=$insert?> : 답글일경우 한칸씩 들어가는 깊이를 출력합니다.
 <?=$icon?>   : 현재 글의 상태에 따라서 아이콘을 출력합니다.

 바구니와 카테고리의 경우 사용하지 않는 수가 있으므로 숨겨놓을때 쓰는 변수;;
 <?=$hide_cart_start?> 내용 <?=$hide_cart_end?> : start 와 end 사이에는 사라짐;; 바구니
 <?=$hide_category_start?> 내용 <?=$hide_category_end?> : Start와 end 사이에는 사라짐;; 바구니


 참고: old_head.gif : 원본글이면서 12시간이 넘은 글의 아이콘
       new_head.gif : 12시간에 적히 모든 글. 원본/답글 상관없이
       reply_head.gif : 12시간이 지난 답글의 아이콘
       reply_new_head.gif : 12시간이 지나지 않은 답글의 아이콘;;
       notice_head.gif : 공지사항일때 아이콘
       secret_head.gif : 비밀글을때 나타나는 아이콘
       arror.gif : 현재 리스트에서 선택되어 있는 글 앞에 붙는 아이콘
 */
///////////////////////////////////////////////////////////////////////// ?>
<!-- 목록 부분 시작 -->
<?
	$gameinfo=mysql_fetch_array(mysql_query("SELECT  *  FROM  $DB_gameinfo AS gameinfo,$DB_rule  AS rule WHERE game = $data[no] AND gameinfo.rule = rule.no"));
	

	
	//termString 
	$termDay = floor($gameinfo['termOfDay'] / 86400);
	$termHour = floor(($gameinfo['termOfDay'] % 86400)/3600);
	$termMin = floor(($gameinfo['termOfDay'] % 3600 )/60);
	if($termDay)$termString = $termDay."일";
	if($termHour)$termString = $termHour."시간";
	if($termMin)$termString = $termMin."분";
	
	//deathTime 
	$deathTime =date("m",$gameinfo['deathtime'])."-".date("d",$gameinfo['deathtime'])."  ".date("H",$gameinfo['deathtime']).":".date("i",$gameinfo['deathtime']);

	//accidentTime 
	$accidentTime =$gameinfo['deathtime'] + $gameinfo['termOfDay']*$gameinfo['day'];
	$accidentTime = date("H",$accidentTime).":".date("i",$accidentTime)."";

	//게임 상태
	if($gameinfo['state'] =="게임중"){
		$styleClass = "roomPlaying";
		$gameState = $gameinfo['day']." 日";
		$alivePlayerCount=mysql_fetch_array(mysql_query("SELECT  count(*)  FROM  $DB_entry WHERE game = $data[no] AND alive ='생존'"));
		$alivePlayerCount = $alivePlayerCount[0];
		$deathPlayerCount = $gameinfo['players'] - $alivePlayerCount ;

		?>
		<tr><td colspan=8>
			<table class="<?=$styleClass?>">
				<tr  align="center" height="25">
					<td class="number" nowrap class="number" rowspan=2><?=$number?></td>
					<td class="enter"  rowspan=2><b>진행 중</b><br><?=$gameState?></td>
					<td class="name text"  align=left colspan=4>
						<?=$insert?><?=$icon?>
						<?="<a href=view.php?id=$id&no=$data[no]&viewImage=off title='이미지 없이 게임을 즐기는 모드입니다.'>[T]</a>"?>&nbsp;<?=$subject?>
					</td>
				</tr>
				<tr>
					<td>
						<span class="icons heart" title="생존자"></span> <?=$alivePlayerCount?>
						<span class="icons death" title="사망자"></span> <?=$deathPlayerCount?>
					</td>
					<td><span class="icons clock"></span> <?=$deathTime?></td>
					<td><?=$gameinfo['name']?></td>
					<td><?=	$termString?> 마을</td>
				</tr>
			</table>
		</td></tr>		
		<?
	}
	elseif($gameinfo['state'] =="준비중"){
		$styleClass = "roomReady";
		$gameState = $gameinfo['state'];	
		$fontColor ="";
		?>
		<tr><td colspan=8>
			<table class="<?=$styleClass?>">
				<tr  align="center" height="25">
					<td class="number" nowrap class="number" rowspan=2><?=$number?></td>
					<td class="enter"  rowspan=2><?="<a href=view.php?id=$id&no=$data[no]><img src='skin/werewolf/ready.gif' border=0></a>"?> </td>
					<td class="name text"  align=left colspan=4>
						<?=$insert?><?=$icon?>
						<?="<a href=view.php?id=$id&no=$data[no]&viewImage=off title='이미지 없이 게임을 즐기는 모드입니다.'>[T]</a>"?>&nbsp;<?=$subject?>
					</td>
				</tr>
				<tr>
					<td><span class="icons player"></span><?=$gameinfo['players']?>/ <?=$gameinfo['max_player']?></td>
					<td><span class="icons clock"></span> <?=$deathTime?></td>
					<td><?=$gameinfo['name']?></td>
					<td><?=	$termString?> 마을</td>
				</tr>
			</table>
		</td></tr>
		<?
	}
	elseif($gameinfo['state'] =="버그"){
		$bestIcon = "";
		if(($gameinfo['good'] >= floor(($gameinfo['players']-1)* 0.75))) $bestIcon="<img src='skin/$id/best.gif'>";
		$styleClass ="roomEnd";

	
		$gameState = "버그";
		$fontColor ="red";
		?>
		<tr  align="center" height="25"  class="<?=$styleClass?>">
			<td nowrap class="number"><?=$number?></td>
			<td class="text"  align=left ><?=$insert?><?=$icon?><?=$bestIcon;?>&nbsp;<?=$subject?></td>
			<td><?=$gameinfo['name']?></td>
			<td><?=	$termString?></td>
			<td><?=$deathTime?></td>
			<td><?=$gameinfo['players']?></td>
			<td><font color="<?=$fontColor?>"><?=$gameState?></font></td>
		</tr>
		<?
	}
	elseif($gameinfo['state'] =="게임끝"){
		$bestIcon = "";
		if(($gameinfo['good'] >= floor(($gameinfo['players']-1)* 0.75))) $bestIcon="<img src='skin/$id/best.gif'>";
		$styleClass ="roomEnd";

		switch($gameinfo['win']){
			case 0: $gameState = "인간의 승";
						$fontColor="#384887";
						break;
			case 1: $gameState = "인랑의 승"; 
						$fontColor ="#BB3333";
						break;
			case 2: $gameState = "햄스터 승";
						$fontColor ="#FFCC99";
						break;
			case 3: $gameState = "디아블로";
						$fontColor ="red";
						break;
		}?>
		<tr  align="center" height="25"  class="<?=$styleClass?>">
			<td nowrap class="number"><?=$number?></td>
			<td class="text"  align=left ><?=$insert?><?=$icon?><?=$bestIcon;?>&nbsp;<?=$subject?></td>
			<td><?=$gameinfo['name']?></td>
			<td><?=	$termString?></td>
			<td><?=$deathTime?></td>
			<td><?=$gameinfo['players']?></td>
			<td><font color="<?=$fontColor?>"><?=$gameState?></font></td>
		</tr>
		<?
	}
        else{?>
		<tr  align="center" height="25"  class="<?=$styleClass?>">
			<td nowrap class="number"><?=$number?></td>
			<?=$hide_cart_start?>
				<td class="number"><input type="checkbox" name="cart" value="<?=$data[no]?>"></td>
			<?=$hide_cart_end?>
			<td class="text"><?=$insert?><?=$icon?><?=$subject?><font class="number">&nbsp;&nbsp;<?=$comment_num?></font></td>
			<td class="text"><?=$face_image?><?=$name?></td>
			<td><?=$reg_date?></td>
			<td><?=$vote?></td>
			<td><?=$hit?></td>
		</tr>
 	<?
        }
?>
