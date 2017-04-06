<!-- 목록 부분 시작 -->
 
  
<tr align=center height=20 bgcolor=#000000 onMouseOver=this.style.backgroundColor='FDFDFD' onMouseOut=this.style.backgroundColor=''>
 <td nowrap><font class="number"><?=$number?></td>
 <?=$hide_cart_start?>
 <td><input type=checkbox name=cart value="<?=$data[no]?>"></td>
 <?=$hide_cart_end?>
  <td align=left style='word-break:break-all;'><?=$insert?><?=$icon?><?=$subject?>
 <?
$comment_num="".$data[total_comment]."";
if($data[total_comment]==0) {
  $comment_num="";
}
echo "<span class=comment-no>$comment_num</span>";
?>
</td> 
 <td nowrap><?=$face_image?>&nbsp;&nbsp;<?=$name?>&nbsp;&nbsp;</div></td>
 <td nowrap><font class="reg-date">&nbsp;<?=$reg_date?>&nbsp;</td>
</tr>

















 

 
