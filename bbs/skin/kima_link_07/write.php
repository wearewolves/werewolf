<br>

<table align=center border=0 cellspacing=0 cellpadding=0 width=<?=$width?>>

<form method=post name=write action=write_ok.php onsubmit="return check_submit();" enctype=multipart/form-data>
<input type=hidden name=page value=<?=$page?>>
<input type=hidden name=id value=<?=$id?>>
<input type=hidden name=no value=<?=$no?>>
<input type=hidden name=select_arrange value=<?=$select_arrange?>>
<input type=hidden name=desc value=<?=$desc?>>
<input type=hidden name=page_num value=<?=$page_num?>>
<input type=hidden name=keyword value="<?=$keyword?>">
<input type=hidden name=category value="<?=$category?>">
<input type=hidden name=sn value="<?=$sn?>">
<input type=hidden name=ss value="<?=$ss?>">
<input type=hidden name=sc value="<?=$sc?>">
<input type=hidden name=mode value="<?=$mode?>">

<tr>
<td>

<table align=center border=0 cellspacing=0 cellpadding=0 width=90%>
<?=$hide_start?>
<tr>
  <td width=60 nowrap align=right style="padding-right:5;" class=ver7>name</td>
  <td align=left><input type=text name=name value="<?=$name?>" class=input2 style="width:100%;height:21;">
  </td>
</tr>

<tr>
  <td width=60 nowrap align=right style="padding-right:5;" class=ver7>pass</td>
  <td align=left><input type=password name=password class=input2 style="width:100%;height:21;">
  </td>
</tr>

<tr>
  <td width=60 nowrap align=right style="padding-right:5;" class=ver7>e-mail</td>
  <td align=left><input type=text name=email value="<?=$email?>" class=input2 style="width:100%;height:21;">
  </td>
</tr>

<tr>
  <td width=60 nowrap align=right style="padding-right:5;" class=ver7>home</td>
  <td align=left><input type=text name=homepage value="<?=$homepage?>" class=input2 style="width:100%;height:21;">
  </td>
</tr>

<?=$hide_end?>

<tr>
  <td width=60 nowrap align=right style="padding-right:5;" class=ver7>option</td>
  <td align=left class=ver7><?=$category_kind?>
<?=$hide_notice_start?> <input type=checkbox name=notice <?=$notice?> value=1>notice<?=$hide_notice_end?>
<?=$hide_html_start?> <input type=checkbox name=use_html <?=$use_html?> value=1>html<?=$hide_html_end?> 
</tr>

<tr>
  <td width=60 nowrap align=right style="padding-right:5;" class=ver7>subject</td>
  <td align=left><input type=text name=subject value="<?=$subject?>" class=input2 style="width:100%;height:21;">
  </td>
</tr>

<tr>
  <td width=60 nowrap align=right style="padding-right:5;" class=ver7>contents</td>
  <td align=left valign=top><textarea name=memo class=input style="width:100%;height:100;"><?=$memo?></textarea>
  </td>
</tr>

<?=$hide_sitelink1_start?>
<tr>
  <td width=60 nowrap align=right style="padding-right:5;" class=ver7>URL</td>
  <td align=left><input type=text name=sitelink1 value="<?=$sitelink1?>" class=input2 style="width:100%;height:21;">
  </td>
</tr>
<?=$hide_sitelink1_end?>

<?=$hide_sitelink2_start?>
<tr>
  <td width=60 nowrap align=right style="padding-right:5;" class=ver7>banner</td>
  <td align=left><input type=text name=sitelink2 value="<?=$sitelink2?>" class=input2 style="width:100%;height:21;">
  </td>
</tr>
<?=$hide_sitelink2_end?>

<tr><td height=5 colspan=2></td></tr>

<tr>
<td colspan=2 align=right>
 <input onfocus='this.blur()' type=submit value=' write ' align=center class=button>&nbsp;
<input onfocus='this.blur()' type=button value=' back ' align=center class=button onclick="history.go(-1)">
</td>
</tr>

</table>

</td>
</tr>
</table>

<br>