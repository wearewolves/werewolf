<table cellspacing=0 cellpadding=0 width='<?=$width?>' border=0>
	<tr>
		<td valign=top align=center>
			<fieldset>
  	  	<legend>
					&nbsp;<a href='<?=$PHP_SELF?>?id=<?=$id?>&year=<?=$year_prev?>&month=12' title='<?=$year_prev?>'><img src=<?=$dir?>/year_prev.gif border=0></a>
					<font style='font-family:±¼¸²;font-size:9pt;' title='<?=$year?>'><b style='font-family:tahoma;font-size:8pt;'><font color=gray>&nbsp;<?=$year?></font></b></font>&nbsp;
	  			<a href='<?=$PHP_SELF?>?id=<?=$id?>&year=<?=$year_next?>&month=1' title='<?=$year_next?>'><img src=<?=$dir?>/year_next.gif border=0></a>&nbsp;
				</legend>
			<table width=100% cellspacing=0 cellpadding=3 bordercolorlight=Gainsboro bordercolordark=white border=1>
				<tr>
					<td bgcolor=#ffffe9 align=center>
<? include "$dir/script/month_print.php";?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

