<script language='JavaScript'>
	var select_obj;
	function ZB_layerAction(name,status) { 
		// For Chrome, Safari and Opera
		//var obj=document.all[name];
		var obj = document.getElementById(name);
		var _tmpx,_tmpy, marginx, marginy;
		_tmpx = event.clientX + parseInt(obj.offsetWidth);
		_tmpy = event.clientY + parseInt(obj.offsetHeight);
		_marginx = document.body.clientWidth - _tmpx;
		_marginy = document.body.clientHeight - _tmpy;
		if(_marginx < 0)
			_tmpx = event.clientX + document.body.scrollLeft + _marginx;
		else
			_tmpx = event.clientX + document.body.scrollLeft;
		if(_marginy < 0)
			_tmpy = event.clientY + document.body.scrollTop + _marginy + 20;
		else
			_tmpy = event.clientY + document.body.scrollTop;
		//obj.style.posLeft=_tmpx-13;
		//obj.style.posTop=_tmpy-12;
		obj.style.left=_tmpx-13;
		obj.style.top=_tmpy-12;
		if(status=='visible') {
			if(select_obj) {
				select_obj.style.visibility='hidden';
				select_obj=null;
			}
			select_obj=obj;
		}else{
			select_obj=null;
		}
		obj.style.visibility=status; 
	}


	function print_ZBlayer(name, homepage, mail, member_no, boardID, writer, traceID, traceType, isAdmin, isMember) {
		var printHeight = 0;
		var printMain="";
	
		if(member_no) {
			if(isMember) {
				printMain += "<tr onMousedown=window.open('view_info.php?member_no="+member_no+"','view_info','width=400,height=510,toolbar=no,scrollbars=yes');><td style=font-family:����;font-size:9pt height=18 nowrap>&nbsp;<img src=images/n_memo.gif border=0 align=absmiddle>&nbsp;&nbsp;���� ������&nbsp;&nbsp;</td></tr>";
				printHeight = printHeight + 16;
			}

			printMain += "<tr onMousedown=location.href='skin/werewolf/view_private_record.php?id=werewolf"+"&player="+member_no+"';><td style=font-family:����;font-size:9pt height=18 nowrap>&nbsp;<img src=images/n_record.gif border=0 align=absmiddle>&nbsp;&nbsp;���� ���&nbsp;&nbsp;</td></tr>";
			printHeight = printHeight + 16;

			printMain += "<tr onMousedown=location.href='skin/werewolf/view_duo_record.php?id=werewolf"+"&player="+member_no+"';><td style=font-family:����;font-size:9pt height=18 nowrap>&nbsp;<img src=images/n_record.gif border=0 align=absmiddle>&nbsp;&nbsp;��� ���&nbsp;&nbsp;</td></tr>";
			printHeight = printHeight + 16;

			printMain += "<tr onMousedown=window.open('view_info2.php?member_no="+member_no+"','view_info','width=400,height=510,toolbar=no,scrollbars=yes');><td style=font-family:����;font-size:9pt height=18 nowrap>&nbsp;<img src=images/n_information.gif border=0 align=absmiddle>&nbsp;&nbsp;ȸ������ ����&nbsp;&nbsp;</td></tr>";
			printHeight = printHeight + 16;
		}
		if(homepage) {
			printMain += "<tr   onMousedown=window.open('"+homepage+"');><td style=font-family:����;font-size:9pt height=18 nowrap>&nbsp;<img src=images/n_homepage.gif border=0 align=absmiddle>&nbsp;&nbsp;Ȩ������&nbsp;&nbsp;</td></tr>";
			printHeight = printHeight + 16;
		}
		if(mail) {
			printMain += "<tr onMousedown=window.open('open_window.php?mode=m&str="+mail+"','ZBremote','width=1,height=1,left=1,top=1');><td style=font-family:����;font-size:9pt height=18 nowrap>&nbsp;<img src=images/n_mail.gif border=0 align=absmiddle>&nbsp;&nbsp;���� ������&nbsp;&nbsp;</td></tr>";
			printHeight = printHeight + 16;
		}

		if(writer) {
			printMain += "<tr onMousedown=location.href='zboard.php?id="+boardID+"&sn1=on&sn=on&ss=off&sc=off&keyword="+writer+"';><td style=font-family:����;font-size:9pt height=18 nowrap>&nbsp;<img src=images/n_search.gif border=0 align=absmiddle>&nbsp;&nbsp;�̸����� �˻�&nbsp;&nbsp;</td></tr>";
			printHeight = printHeight + 16;
		}
		if(isAdmin) {
			if(member_no) {
				printMain += "<tr onMousedown=location.href='skin/werewolf/view_ip_overlap.php?id=werewolf&player="+member_no+"';><td style=font-family:����;font-size:9pt height=18 nowrap>&nbsp;<img src=images/n_modify.gif border=0 align=absmiddle>&nbsp;&nbsp;<font color=darkred>IP ����&nbsp;&nbsp;</td></tr>";
				printHeight = printHeight + 16;

				printMain += "<tr onMousedown=window.open('open_window.php?mode=i&str="+member_no+"','ZBremote','width=1,height=1,left=1,top=1');><td style=font-family:����;font-size:9pt height=18 nowrap>&nbsp;<img src=images/n_modify.gif border=0 align=absmiddle>&nbsp;&nbsp;<font color=darkred>ȸ������ ����&nbsp;&nbsp;</td></tr>";
				printHeight = printHeight + 16;
			}
			printMain += "<tr onMousedown=window.open('open_window.php?mode="+traceType+"&str="+traceID+"','ZBremote','width=1,height=1,left=1,top=1');><td style=font-family:����;font-size:9pt height=18 nowrap>&nbsp;<img src=images/n_relationlist.gif border=0 align=absmiddle>&nbsp;&nbsp;<font color=darkred>���ñ� ����</font>&nbsp;&nbsp;</td></tr>";
			printHeight = printHeight + 16;				
		}
		var printHeader = "<div id='"+name+"' style='position:absolute; left:10px; top:25px; width:127; height: "+printHeight+"; z-index:1; visibility: hidden' onMousedown=ZB_layerAction('"+name+"','hidden')><table border=0><tr><td colspan=3 onMouseover=ZB_layerAction('"+name+"','hidden') height=3></td></tr><tr><td width=5 onMouseover=ZB_layerAction('"+name+"','hidden') rowspan=2>&nbsp;</td><td height=5></td></tr><tr><td><table style=cursor:hand border='0' cellspacing='1' cellpadding='0' bgcolor='black' width=100% height=100%><tr><td valign=top bgcolor=white><table border=0 cellspacing=0 cellpadding=3 width=100% height=100%>";
		var printFooter = "</table></td></tr></table></td><td width=5 rowspan=2 onMouseover=ZB_layerAction('"+name+"','hidden')>&nbsp;</td></tr><tr><td colspan=3 height=10 onMouseover=ZB_layerAction('"+name+"','hidden')></td></tr></table></div>";
	
		document.writeln(printHeader+printMain+printFooter);
	}
</script>