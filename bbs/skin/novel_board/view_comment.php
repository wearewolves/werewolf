<table class="comment">
	<tr>
		<td nowrap style='padding:0 15 0 10' class="name"><?=$c_face_image?> <?=$comment_name?></td>
		<td width=100% class=c-reg-date>
			<?=date("Y-m-d H:i:s",$c_data[reg_date])?>&nbsp;&nbsp;
			<span class=c-reg-date><?=$a_del?>X</a></span>
		</td>
	</tr>
	<tr>
		<td colspan=2 height=2 bgcolor='' padding:5 10 0 10'>
			<img src=<?=$dir?>/_cl.gif height=2>
		</td>
	</tr>
    <tr>
		<td colspan=2 style='word-break:break-all; padding:5 10 0 10'>
			<p align=justify><?=nl2br($c_memo)?></p>
		</td>
	</tr>
</table>