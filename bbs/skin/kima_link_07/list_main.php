<?
unset($s_info);
 $alink="";
 $home_url="$dir/no_img.gif";

 if($data[sitelink2])
 {
    $home_url = $data[sitelink2];
 }

 
 if($data[sitelink1])
 {
	$alink="<a href='$data[sitelink1]' target='_blank' onfocus='this.blur();'>";
 }

?>

<td width=100% style='padding:13 5 0 5;'>

<table cellpadding=0 cellspacing=0 border=0 width=100% align=center  title="<?=$data['memo']?>">
<tr>
<td nowrap rowspan=2 width=<?=$img_w?> height=<?=$img_h?> style='padding-right:5;'>
<?=$alink?><img src=<?=$home_url?> width=<?=$img_w?> height=<?=$img_h?> border=0></a>
</td>
<td>
   <table cellpadding=0 cellspacing=0 border=0>
   <tr>
   <td nowrap><?=stripslashes($data['subject'])?> <?=$a_modify?><img src=<?=$dir?>/modify.gif border=0></a><?=$a_delete?><img src=<?=$dir?>/delete.gif border=0></a></td>
   </tr>
   <tr>
   <td nowrap><? echo"<a href='$data[sitelink1]' target=_blank onfocus=this.blur()><span class=ver7>$data[sitelink1]</span></a>" ?></td>
   </tr>
   </table>
</td>
</tr>
</table>

</td>

<?
  $image_loop++;
  if($image_loop>=$max_show_image)
  {
     echo"
       	</tr>
	<tr>";
     $image_loop=0;
  }
?>