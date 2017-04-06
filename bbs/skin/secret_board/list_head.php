
<form method=post name=list action=list_all.php>
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=selected>
<input type=hidden name=exec>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">

<table id="list" cellpadding=0 cellspacing=0 > 
	<?=$hide_cart_start?><col width="20"></col><?=$hide_cart_end?>
	<col width="*"></col>
	<col width="100"></col>
	<col width="30"></col>
	<col width="20"></col>
	<col width="20"></col>

	<thead>
	<tr>
		<?=$hide_cart_start?><td><?=$a_cart?>c</a></td><?=$hide_cart_end?>
		<td>	<?=$a_subject?>Subject</td>
		<td>	<?=$a_name?>Name</td>
		<td>	<?=$a_date?>Date</td>
		<td>	<?=$a_vote?>Vote</td>
		<td>	<?=$a_hit?>Hit</td>		
	</tr>
	</thead>
	<tbody>