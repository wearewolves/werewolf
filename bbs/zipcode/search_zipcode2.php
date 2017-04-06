<?
	if(!$address) {
?>
		<script>
			alert("우편번호를 입력하셔야 합니다");
			history.back();
		</script>
<?
		exit;
	}

	$url=eregi_replace("search_zipcode.php\?","search_zipcode3.php",$HTTP_REFERER);
	$url=eregi_replace("num=1","",$url);
	$url=eregi_replace("num=2","",$url);
	header("location:http://zeroboard.com/zipcode/search_zipcode2.html?num=$num&url=$url&address=$address");
?>
