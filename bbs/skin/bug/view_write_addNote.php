</table>
<script language="javascript">
strdate = new Date()

function setStatus(stat){
	document.bugDeal.status.value =stat;
}
function checkResult(result){
	if(result==4){
		document.all['inputDate'].style.visibility="visible";
	}else{
		
		document.all['inputDate'].style.visibility="hidden";
	}
	bugDeal.dealResult.value=result;
}
function checkForm(form){
	<?if($bug['status'] == 2){?>
		if(form.dealResult.value==4){
			if(isNaN(form.yearInput.value)||!(form.yearInput.value)){
				alert("연도를 입력해 주십시오.");
				form.yearInput.focus();
				return false;
			}
			if(strdate.getYear() > eval(form.yearInput.value)	  ||  eval(form.yearInput.value) >strdate.getYear()+1  ){
				alert("연도를 "+strdate.getYear()+"년에서 "+(strdate.getYear()+1)+"년 사이로 수정해 주십시오.");
				form.yearInput.focus();
				return false;
			}
		    if(isNaN(form.monthInput.value) || !(form.monthInput.value)){
				alert("월를 입력해주십시오.");
				form.monthInput.focus();
				return false;
			}
			if(strdate.getYear()==eval(form.yearInput.value)){
				if(strdate.getMonth()+1 > eval(form.monthInput.value)  ||  eval(form.monthInput.value) >12  ){
					alert("월을 "+(strdate.getMonth()+1)+"월과 12월 사이로 수정해주십시오.");
					form.monthInput.focus();
					return false;
				}

			}
			else{
				if(1 > eval(form.monthInput.value)  ||  eval(form.monthInput.value) >12  ){
					alert("월을 1월과 12월 사이로 수정해주십시오.");
					form.monthInput.focus();
					return false;
				}
			}
			form.year.value=eval(form.yearInput.value)
			form.month.value=eval(form.monthInput.value)

			return true;		
		}
	<?}else?>
			return true;
	<??>
}
</script>
<?if($bug['reservation']<0)$bug['reservation']=0;?>
<form method=post name=bugDeal action=<?="$dir/view_write_addNote_ok.php"?> onsubmit="return checkForm(this)">
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

<input type=hidden name=status value="<?= $bug['status'];?>"> 
<input type=hidden name=dealResult  value="<?= $bug['dealResult'];?>"> 
<input type=hidden name=repairman  value="<?= $bug['repairman'];?>"> 
<input type=hidden name=year  value=<?=date("Y", $bug['reservation'])?>> 
<input type=hidden name=month  value=<?=date("m", $bug['reservation'])?>> 

<div align=center>
<table border=0 bgcolor=111111 cellspacing=1 cellpadding=0 width=<?=$width?>>
<tr bgcolor=111111>
  <td>
	<table border=0 bgcolor=151515 cellspacing=1 cellpadding=2 width=100%>
	<col width=80></col><col width=></col><col width=70></col>


<?if($bug['status'] == 1 || $bug['status'] == 6 || $bug['status'] == 4){//2번으로 - 담당자 배치 ?>
<script language="javascript">
setStatus(2);
bugDeal.repairman.value =<?=$member[no]?>;
</script>
	<tr align=center bgcolor=222222> 
		<td height=20>
		<img src=images/t.gif border=0 width=80 height=1><br><font class=red_8>버그 접수</font></td>
		<td colspan=2  style='word-break:break-all;' align=left>
			담당자: <?=$member[name]?>
		</td>
	</tr>
<?}?>

<?if($bug['status'] == 2){//3번으로 - 버그를 처리함?>
<script language="javascript">
setStatus(3);
bugDeal.dealResult.value=1;
</script>
	<tr align=center bgcolor=222222> 
		<td height=20>
		<font class=red_8>처리 결과</font></td>
		<td colspan=2  style='word-break:break-all;' align=left>
			<?echo DBselect("deal","","no","name",$DB_dealResult,"onchange=checkResult(value) style=font-size:9pt;width=200","");?>
				<span id="inputDate" style="position:absolute; width:230px; visibility: hidden;" >
					<input name=yearInput size=4 MAXLENGTH=4 class=input >년
					<input name=monthInput size=4 MAXLENGTH=2 class=input >월
				</span>
		</td>
	</tr>
<?}?>	

<?if($bug['status'] == 3 && ($bug['dealResult'] == 1 ||$bug['dealResult'] == 3 ||$bug['dealResult'] == 5)){//버그 수정,버그가 아님,기존에 보고된 버그 - 재 처리 요청or 해결?>
<script language="javascript">
setStatus(5);
</script>
	<tr align=center bgcolor=222222> 
		<td height=20>
		<img src=images/t.gif border=0 width=80 height=1><br><font class=red_8>검증</font></td>
		<td colspan=2  style='word-break:break-all;' align=left>
			<INPUT TYPE="radio" NAME="VERIFIED" onclick="setStatus(5);" checked>해결
			<INPUT TYPE="radio" NAME="VERIFIED" onclick="setStatus(4);">재 확인 요청
		</td>
	</tr>
<?}?>	


<?if($bug['status'] == 3 && ($bug['dealResult'] == 2 )){//파악안됨 - 재 처리 요청?>
<script language="javascript">
setStatus(4);
</script>
	<tr align=center bgcolor=222222> 
		<td height=20>
		<img src=images/t.gif border=0 width=80 height=1><br><font class=red_8></font></td>
		<td colspan=2  style='word-break:break-all;' align=left>버그를 재현할 수 있는 방법을 구체적으로 적어주십시오.	</td>
	</tr>
<?}?>	

<?if($bug['status'] == 3 && ($bug['dealResult'] == 4 )){//차후 수정 ?>
<script language="javascript">
setStatus(3);
bugDeal.dealResult.value=1;
</script>
	<tr align=center bgcolor=222222> 
		<td height=20>
		<img src=images/t.gif border=0 width=80 height=1><br><font class=red_8>처리 결과</font></td>
		<td colspan=2  style='word-break:break-all;' align=left>버그를 수정함</td>
	</tr>
<?}?>	


<?if($bug['status'] == 5){//6번으로 - 버그 다시 발생 ?>
<script language="javascript">
setStatus(6);
</script>
	<tr align=center bgcolor=222222> 
		<td height=20>
		<img src=images/t.gif border=0 width=80 height=1><br><font class=red_8>다시 발생신고</font></td>
		<td colspan=2  style='word-break:break-all;' align=left>
			
		</td>
	</tr>
<?}?>	

	 <tr bgcolor=111111>
	  <td valign=middle align=center>
	  <font class=red_7>COMMENT</font></td>
	  <td><textarea name=memo2 <?=size(40)?> rows=5 class=red_commentw style=width=400></textarea></td>
	  <td><input type=submit rows=5 <?if($browser){?>class=red_submit<?}?> value='SUBMIT' accesskey="s"></td>
	</tr>
	</table>
  </td>
</tr>
</table>
</form>
</div>
