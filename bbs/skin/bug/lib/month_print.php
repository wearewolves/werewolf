<?

//===========달을 테이블에 표시=================//
for($mon=1;$mon<=12;$mon++){


//이번달 표시
if($mon==$month){
	$m_out_color=$today_out_color;
	$m_over_color=$today_over_color;
	$day_color=$today_color;
}

else {//이번달이 아니면...
		$m_out_color=$else_out_color;
		$m_over_color=$else_over_color;
		$day_color=$else_color;
	}


//===========달 보이는곳 (이 부분만 수정해서 사용할것)========================

echo "<A HREF='./zboard.php?id=$id&year=$year&month=$mon' onfocus=blur()>
				<font color='$day_color' style='font-family:tahoma;font-size:8pt;font-weight:bold'>$mon 월</font>
			</a>";

//================달 보이는곳  끝==================================


}

?>
