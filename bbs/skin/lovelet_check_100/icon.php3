<html>
<head>
<title>Icon Preview</title>
<link rel="stylesheet" type="text/css" href="style.css">
<script language language="JavaScript">
<!--
function chgicon(num)
{
window.opener.document.write.sitelink1.selectedIndex = num-1;
window.opener.back_c();
self.close();
}
//-->
</script>
</head>
<body bgcolor="white" text="black" link="blue" vlink="purple" alink="red" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0">
<table border=0 cellspacing=0 cellpadding=0 align=center>
	<tr height=40>
		<td align=center>아이콘을 선택하세요~!!!</td>
	</tr>
<tr><td align=center>
<table border=0 cellspacing=0 cellpadding=0 width=320 height=160>
<tr><td align=center>
<?
for($i=1;$i<10;$i++) echo "<a href='javascript:chgicon(0$i)' onfocus='this.blur()'><img src='icon/0$i.png' height=32 width=32 border=0></a>";
?>
<?
for($j=10;$j<51;$j++) {echo "<a href='javascript:chgicon($j)' onfocus='this.blur()'><img src='icon/$j.png' height=32 width=32 border=0></a>";
if($j%10==0) echo "<br>";}
?>
</td></tr></table>
</td></tr>
	<tr height=20>
		<td align=right>icon by <a href=http://homepage1.nifty.com/KUMAZO/ target=_blank>KumazoIcon</a></td>
	</tr>
</table>
</body>
</html>
