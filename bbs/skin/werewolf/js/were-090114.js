


/*
url-loading object and a request queue built on top of it
*/

/* namespacing object */
var net=new Object();

net.READY_STATE_UNINITIALIZED=0;
net.READY_STATE_LOADING=1;
net.READY_STATE_LOADED=2;
net.READY_STATE_INTERACTIVE=3;
net.READY_STATE_COMPLETE=4;


/*--- content loader object for cross-browser requests ---*/
net.ContentLoader=function(url,onload,onerror,method,params,contentType){
  this.req=null;
  this.onload=onload;
  this.onerror=(onerror) ? onerror : this.defaultError;
  this.loadXMLDoc(url,method,params,contentType);
}

net.ContentLoader.prototype.loadXMLDoc=function(url,method,params,contentType){
  if (!method){
    method="GET";
  }
  if (!contentType && method=="POST"){
    contentType='application/x-www-form-urlencoded';
  }
  if (window.XMLHttpRequest){
    this.req=new XMLHttpRequest();
  } else if (window.ActiveXObject){
    this.req=new ActiveXObject("Microsoft.XMLHTTP");
  }
  if (this.req){
    try{
      var loader=this;
      this.req.onreadystatechange=function(){
        net.ContentLoader.onReadyState.call(loader);
      }
      this.req.open(method,url,true);
      if (contentType){
        this.req.setRequestHeader('Content-Type', contentType);
      }
      this.req.send(params);
    }catch (err){
      this.onerror.call(this);
    }
  }
}

net.ContentLoader.onReadyState=function(){
  var req=this.req;
  var ready=req.readyState;
  if (ready==net.READY_STATE_COMPLETE){
    var httpStatus=req.status;
    if (httpStatus==200 || httpStatus==0){
      this.onload.call(this);
    }else{
      this.onerror.call(this);
    }
  }
}


net.ContentLoader.prototype.defaultError=function(){
 // alert("error fetching data!"
//    +"\n\nreadyState:"+this.req.readyState
//    +"\nstatus: "+this.req.status
//    +"\nheaders: "+this.req.getAllResponseHeaders());
}

function BuildXMLResults(dataXML,target){
	try{
		var xmlDoc = this.req.responseXML.documentElement;		
	}
	catch(ee){
		xmlDoc = (dataXML)?dataXML:xmlDoc;
	}
	var isNewComment;
	if(target){
		target = document.getElementById("commentPage"+target);
		isNewComment = false;
	}
	else{
		target = document.getElementById("commentContainer");
		isNewComment = true;
	}
	target =$(target);
	
//<?if($is_admin){?>
	//alert(xmlDoc.getElementsByTagName('ddd')[0].firstChild.nodeValue);
//	alert(SID+ xmlDoc.getElementsByTagName('result')[0].firstChild.nodeValue);
//<?}?>
	if(xmlDoc.getElementsByTagName('result')[0].firstChild.nodeValue == "true"){
		//commentContainer.innerHTML="";

		var xRows = xmlDoc.getElementsByTagName('item');
		for(iC=0;iC<xRows.length;iC++){
			var type = xRows[iC].getElementsByTagName('type')[0].firstChild.nodeValue;

			if(type =="알림" || type =="봉인제안") {
				var newline=$(document.createElement("div")).addClass("commentNotice");
				target.append(newline);
				newline.hide();
				
				var reg_date=document.createElement("span");
				reg_date.className = "reg_date";
				reg_date.innerHTML = xRows[iC].getElementsByTagName('reg_date')[0].firstChild.nodeValue;
				newline.append(reg_date);
				
				if(type =="봉인제안") {
					try {
						var player_info=document.createElement("span");
						player_info.className = "playerInfo";
						player_info.innerHTML = " "+xRows[iC].getElementsByTagName('username')[0].firstChild.nodeValue;
						newline.append(player_info);
					}
					catch(ee){}
				}

				var memo=document.createElement("div");
				switch(type){
					case "알림": memo.className = "notice";
								break;
					case "봉인제안": memo.className = "seal";
								break;
				}
				var txt=document.createTextNode(xRows[iC].getElementsByTagName('description')[0].firstChild.nodeValue);

				memo.innerHTML=txt.nodeValue;
				newline.append(memo);
				newline.fadeIn();
			}
			else {
				try {
					var newline=$(document.createElement("div")).addClass("comment");
					
					var memoStyle;
					switch(type){
						case "일반": memoStyle = "normal";
											break;
						case "메모": memoStyle = "memo";
											break;
						case "비밀": memoStyle = "secret";						
											break;
						case "텔레":memoStyle = "telepathy";						
											break;
						case "사망": memoStyle = "grave";						
											break;
						case "편지": memoStyle = "secretletter";						
											break;
						case "답변": memoStyle = "secretanswer";						
											break;
						//case "알림":memoStyle = true;
						//					break;
					}
					
					var character= xRows[iC].getElementsByTagName('character')[0].firstChild.nodeValue;	
					
					newline.addClass(memoStyle);
					newline.addClass(character);
					target.append(newline);
					newline.hide();
	
					if(viewImage != "off"){
						var image = xRows[iC].getElementsByTagName('image')[0].firstChild.nodeValue;
						var c_image=document.createElement("div");
						c_image.className = "c_Image";
						c_image.innerHTML = "<img width='100' height='100' src='"+characterImageFolder+image+"'>";
						newline.append(c_image);
					}
					var c_info=document.createElement("div");
					c_info.className = "c_info";
				    newline.append(c_info);
	
						var c_name=document.createElement("span");
						var name = xRows[iC].getElementsByTagName('name')[0].firstChild.nodeValue;
						c_name.className = "c_name";
						c_name.innerHTML = "<label for='"+character+"' title='"+name+"님의 로그를 필터링 합니다.'>"+name+ "</label> ";
						c_info.appendChild(c_name);
	
						var reg_date=document.createElement("span");
						reg_date.className = "reg_date";
						reg_date.innerHTML = xRows[iC].getElementsByTagName('reg_date')[0].firstChild.nodeValue;
						c_info.appendChild(reg_date);
						
						try {
							var player_info=document.createElement("span");
							player_info.className = "playerInfo";
							player_info.innerHTML = " "+xRows[iC].getElementsByTagName('username')[0].firstChild.nodeValue+" / ";
							player_info.innerHTML += xRows[iC].getElementsByTagName('truecharacter')[0].firstChild.nodeValue;
							c_info.appendChild(player_info);
						}
						catch(ee){}
	
					if(viewImage != "off"){
						var ct=document.createElement("div");
						ct.className = "ct";
						ct.innerHTML = "<img  src=skin/werewolf/ctb.gif>";
						newline.append(ct);
					}
	
					var memo=document.createElement("div");
					memo.className = "message";
	
					var txt=document.createTextNode(xRows[iC].getElementsByTagName('description')[0].firstChild.nodeValue);
					memo.innerHTML=txt.nodeValue;
				    newline.append(memo);
				    
				   if(isNewComment || ($("#"+memoStyle+"Button").attr("checked") && $("#"+character).attr("checked")))newline.fadeIn();
				   else if ($("#"+character).size() ==0 )newline.fadeIn();
				}
				catch(ee){};
			}

		}
		try {
			SID =xmlDoc.getElementsByTagName('SID')[0].firstChild.nodeValue;
			if(xmlDoc.getElementsByTagName('sound')[0].firstChild.nodeValue == "play" && soundPlay){
					playsound(soundfile);
			}
			soundPlay = true;
		}
		catch(ee){}
	}
	else if(xmlDoc.getElementsByTagName('result')[0].firstChild.nodeValue == "deleteGame"){
		window.clearTimeout(timer);
		window.clearTimeout(commentLoader);
		
		timer=null;
		delete timer;

		commentLoader=null;
		delete commentLoader;

		document.getElementById('displayTimer').innerHTML="<font color='red'>게임이 삭제되었습니다!!<br><a href='"+gameLink +"zboard.php?id=werewolf'>[게임하기로 이동 ☞]</a></font>"
	}
	else if(xmlDoc.getElementsByTagName('result')[0].firstChild.nodeValue == "goNextDay"){
		window.clearTimeout(timer);
		window.clearTimeout(commentLoader);
		
		timer=null;
		delete timer;

		commentLoader=null;
		delete commentLoader;

		document.getElementById('displayTimer').innerHTML="<font color='red'>날짜가 변경되었습니다!!<br><a href='"+gameLink +"view.php?id=werewolf&no="+gameNo+"&viewDay="+(gameDay+1)+"'>[오늘로 이동 ☞]</a></font>"
	}

//	timer  = setTimeout(load,loadingInterval);
	//load();
}

var postMemo;

function BuildError(){
	postMemo = "";
	//	timer  = setTimeout(load,loadingInterval);
	//load();

	//alert("에러가 발생했습니다!!!!!!! \n\nreadyState:"+ this.req.readyState + "\nstatus: "+ this.req.status +"\nheaders: "+ this.req.getAllResponseHeaders());
}

function fastsendComment() {
	$(window).unbind('beforeunload');
	document.getElementById("writeComment").submit();
}

function submitComment(obj){
	if(obj.memo.value.length<10 ){
		alert("내용이 너무 짧습니다. ("+obj.memo.value.length+")");
		return false;
	}

	if(postMemo == obj.memo.value) {
		alert("같은 내용의 글을 이미 서버로 보냈습니다.\n잠시 기다려주세요.\n전송이 되질 않으면 글을 약간 수정해서 전송을 다시 시도해주세요.^^"
		+"\n\n 서버가 혼잡한 시간이 아닌데도 로그가 올라가질 않으면... "
		+"\n 인터넷 옵션에서 쿠키를 모두 삭제하고 다시 로그인 해보세요."
		);
		return false;
	}
	else 
		postMemo = obj.memo.value;

	try{
		prams="page="+obj.page.value;
		prams+="&id="+obj.id.value;
		prams+="&no="+obj.no.value;
		prams+="&select_arrange="+obj.select_arrange.value;
		prams+="&desc="+obj.desc.value;
		prams+="&page_num="+obj.page_num.value;
		prams+="&keyword="+obj.keyword.value;
		prams+="&category="+obj.category.value;
		prams+="&sn="+obj.sn.value;
		prams+="&ss="+obj.ss.value;
		prams+="&sc="+obj.sc.value;
		prams+="&mode="+obj.mode.value;

		if(obj.c_type.length >= 0)  prams+="&c_type="+encodeURIComponent(obj.c_type[selectedType(obj)].value);
		else	prams+="&c_type="+encodeURIComponent(obj.c_type.value);
		if(obj.secretletterTo)prams+="&secretletterTo="+obj.secretletterTo.value;
		
		prams+="&memo="+encodeURIComponent(obj.memo.value);
	//	prams+="&memo="+escape(obj.memo.value);
		prams+="&SID="+encodeURIComponent(SID);

		soundPlay = false;
		var writer= new net.ContentLoader("skin/werewolf/were_comment_type_ok.php",write_ok,BuildError,"POST",prams);
		//obj.memo.value = obj.memo.value + prams;
		return false;
	}
	catch(ee){
		alert(ee.description );
		return false;
	}
}

function selectedType(obj){
	for(i=0;i<obj.c_type.length;++i){
		if(obj.c_type[i].checked) return i;
	}

}

function write_ok(){
	var xmlDoc = this.req.responseXML.documentElement;

	if(xmlDoc.getElementsByTagName('result')[0].firstChild.nodeValue == "true"){
		$("#memo").empty();
		writeComment.memo.value = "";
		postMemo = "";

		commentType = xmlDoc.getElementsByTagName('commentType')[0].childNodes;
		selectCommentType.innerHTML="";

		for (i=0;i<commentType.length;i++){
			printCommentType(commentType[i].nodeName,commentType[i].firstChild.nodeValue);
		}
		initCommentType();
		load();
//		clearTimeout(timer);
//		timer  = setTimeout(load,1000);	
	}
	else if(xmlDoc.getElementsByTagName('result')[0].firstChild.nodeValue == "alert"){
		alert(xmlDoc.getElementsByTagName('alert')[0].firstChild.nodeValue );
	}
}

function printCommentType(typeName,typeValue){
	switch(typeName){
		case "normal":selectCommentType.innerHTML+= "<INPUT TYPE=radio ID=c_type NAME=c_type value=일반  onclick=setColor('일반')>일반("+ typeValue +"/20)</input>";
						break;
		case "secret":selectCommentType.innerHTML+= "<INPUT TYPE=radio ID=c_type NAME=c_type value=비밀 onclick=setColor('비밀') checked>비밀("+typeValue +"/40)</input>" ;
						break;
		case "memo":selectCommentType.innerHTML+= "<INPUT TYPE=radio ID=c_type NAME=c_type value=메모 onclick=setColor('메모')>메모(" + typeValue +"/10)</input>" ;
						break;
		case "telepathy":selectCommentType.innerHTML+= "<INPUT TYPE=radio ID=c_type NAME=c_type value=텔레 onclick=setColor('텔레')>텔레파시("+ typeValue +"/1)</input>";
						break;
		case "grave":selectCommentType.innerHTML+= "<INPUT TYPE=radio ID=c_type NAME=c_type value=사망  onclick=setColor('사망')>사망(" + typeValue + "/20)</input>";
						break;
		case "notice":selectCommentType.innerHTML+= "<INPUT TYPE=radio ID=c_type NAME=c_type value=알림 onclick=setColor('알림')>알림</input>"
						break;
		case "secretletter":
						selectCommentType.innerHTML+= "<INPUT TYPE=radio ID=c_type NAME=c_type value=편지  onclick=setColor('편지')>편지" + typeValue + "</input>";
						break;
		case "secretanswer":
						selectCommentType.innerHTML+= "<INPUT TYPE=radio ID=c_type NAME=c_type value=답변  onclick=setColor('편지')>답변</input>";
						break;
		case "seal":
						selectCommentType.innerHTML+= "<INPUT TYPE=radio ID=c_type NAME=c_type value=봉인제안  onclick=setColor('봉인제안')>봉인 논의(" + typeValue + "/5)</input>";
						break;
	}
}

function addLine(){
	var row = $('#memo').attr('rows');
	$('#memo').attr('rows', parseInt(row) + 4);
}

function checkCommentType(){
	try{
		var radioButton = $('#writeComment input[type^=radio]');

		if(radioButton.is("[value*=비밀]")){
			radioButton.filter("[value*=비밀]").attr("checked",true);
		}
		else radioButton.filter(":first").attr("checked",true);
	}catch(ee){
		alert(ee.description );
		return false;
	}
}
function setColor(type){
	switch (type)
	{
	case "일반":
		$("#memo").css("backgroundColor","#fff");
		$("#memo").css("color","#000");
		break;
	case "메모":
		$("#memo").css("backgroundColor","#DEDB9C");
		$("#memo").css("color","#000");
		break;
	case "사망":
		$("#memo").css("backgroundColor","#9D9D9D");
		$("#memo").css("color","#000");
		break;
	case "비밀":
		$("#memo").css("backgroundColor","#F77");
		$("#memo").css("color","#000");
		break;
	case "텔레":
		$("#memo").css("backgroundColor","#93B4B7");
		$("#memo").css("color","#000");
		break;
	case "알림":
		$("#memo").css("backgroundColor","#121212");
		$("#memo").css("color","");
		break;	
	case "편지":
		$("#memo").css("backgroundColor","#A6E1C4");
		$("#memo").css("color","#000");
		break;	
	case "답변":
		$("#memo").css("backgroundColor","#A6E1C4");
		$("#memo").css("color","#000");
		break;
	case "봉인제안":
		$("#memo").css("backgroundColor","#D2F3A2");
		$("#memo").css("color","#000");
		break;
	}
	return true;
}

function initCommentType(){
	checkCommentType();
	setColor($(':radio:checked').attr("value"));	
}

var logloader;
function load(){
	var strParams = 'SID=' + encodeURIComponent(SID);
	delete logloader;
	logloader = new net.ContentLoader("skin/werewolf/wolf.php",BuildXMLResults,BuildError,"POST",strParams);
}

/***********************************************
* JavaScript Sound effect-  Dynamic Drive DHTML code library (www.dynamicdrive.com)
* Visit http://www.dynamicDrive.com for hundreds of DHTML scripts
* This notice must stay intact for legal use
***********************************************/

/***********************************************
* JavaScript Sound Effect for HTML5
* By using Audio Object
* 2017.04.11
***********************************************/

// Check browser type before playing sound
var bIE = new RegExp(/MSIE/).test(navigator.userAgent); // MS Internet Explorer

//var soundfile="notify.wav"; //path to sound file, or pass in filename directly into playsound()
var soundfile = bIE ? "notify.wav" : "notify.mp3";
var soundPlay = true;

function playsound(mediaURL) {
	// bIE == true : MS Internet Explorer
	if(bIE) {
		var rpt =1;
		var height=0;
		var width=0;
		var CodeGen = "" 

		CodeGen = '<embed type="application/x-mplayer2" ' + '\n' ;
		CodeGen += ' pluginspage="http://www.microsoft.com/Windows/MediaPlayer/" ' + '\n' ;
		CodeGen += 'Name="Player" ' + 'src="' + mediaURL + '" ' + '\n' ;
		CodeGen += 'autoStart=true ' ;
		CodeGen += 'ShowStatusBar=0 '; 
		CodeGen += 'enableContextMenu=0 cache=0' + '\n' ;
		CodeGen += 'playCount=' + rpt + ' ' ;
		CodeGen += 'volume=100 ' ;
		CodeGen += 'loop=false' ;
		CodeGen += 'hidden=true ';
		CodeGen += 'HEIGHT=' + height + ' WIDTH=' + width + '>'
					
		$('#playerpp').html(CodeGen);
	}
	// bIE == false : All browsers except MS Internet Explorer
	else {
		var audioObj = document.createElement("audio");
		
		if(audioObj.canPlayType("audio/mpeg")) audioObj.setAttribute("src", mediaURL);
		else audioObj.setAttribute("src", mediaURL.substr(-3) + "ogg");
		
		audioObj.play();
	}
}

/*
 * 쿠키 사용 함수
 */
function setCookie(c_name,value,expiredays)
{
	var exdate=new Date();exdate.setDate(exdate.getDate()+expiredays);
	document.cookie=c_name+ "=" +escape(value)+ ((expiredays==null) ? "" : ";expires="+exdate.toGMTString());
} 

function getCookie(c_name)
{
	if (document.cookie.length>0)
	  {
	  c_start=document.cookie.indexOf(c_name + "=")
	  if (c_start!=-1)
		{ 
	    c_start=c_start + c_name.length+1 ;
		c_end=document.cookie.indexOf(";",c_start);
	    if (c_end==-1) c_end=document.cookie.length;
		return unescape(document.cookie.substring(c_start,c_end))
	    } 
	  }
	return "";
}
/*
 * 초기화 함수 
 */
$(function(){
	
	$(window).bind("beforeunload", function(){
    if($('#memo').val()) return "나가시겠습니까? 올리지 않은 로그는 저장되지 않습니다.";
  });
	
	$('#soundOn').click(function(){
		setCookie('sound','on',7);
		$('#selectSound').css("visibility", "visible");

		var selectedSound =  getCookie('selectedSound');
		if(selectedSound =="cat"){
			$('#catSound').attr("checked",true);
			//soundfile="notify.wav";
			soundfile = bIE ? "notify.wav" : "notify.mp3";
		}
		else{
			$('#dogSound').attr("checked",true);
			//soundfile="dog.wav";
			soundfile = bIE ? "dog.wav" : "dog.mp3";
		}
	});
	
	$('#soundOff').click(function(){
		setCookie('sound','off',7);
		$('#selectSound').css("visibility", "hidden");
		soundfile="";
	});

	$('#dogSound').click(function(){
		//soundfile="dog.wav";
		soundfile = bIE ? "dog.wav" : "dog.mp3";
		setCookie('selectedSound','dog',7);
		playsound(soundfile);
	});

	$('#catSound').click(function(){
		//soundfile="notify.wav";
		soundfile = bIE ? "notify.wav" : "notify.mp3";
		setCookie('selectedSound','cat',7);
		playsound(soundfile);
	});
	//checkSound();
	{
		swit =  getCookie('sound');

		if(swit == "off"){
			$('#soundOff').attr("checked",true);
			$('#selectSound').css("visibility","hidden");
			soundfile="";
		}
		else{
			$('#soundOn').attr("checked",true);

			swit =  getCookie('selectedSound');
			if(swit =="cat"){
				$('#catSound').attr("checked",true);
				//soundfile="notify.wav";
				soundfile = bIE ? "notify.wav" : "notify.mp3";
			}
			else{
				$('#dogSound').attr("checked",true);
				//soundfile="dog.wav";
				soundfile = bIE ? "dog.wav" : "dog.mp3";
			}	
		}
	}
	
	//checkRead();
	{
		if(getCookie('readLatest'))$('#readLimit').val(getCookie('readLatest')); 
		else $('#readLimit').val(10);
	}	
	
	$("#readLimit").change(function(event){
		setCookie('readLatest',$(this).val());
		alert("로그를  "+ $(this).val() +"개씩 나눠 보도록 설정 되었습니다.");
		location.href = location.href ;
	});

	$(".buttonCommentPage").click(function (event){
		var commentPage = jQuery(event.target).children("input").val();
		if(commentPage == undefined){
			commentPage  = jQuery(event.target).parent().children("input").val();
		}
		var target = $("#commentPage"+commentPage);
		
		//$(event.target).toggleClass("openPage");
		
		if(target.children().size()==0){
			var strParams = 'SID=' + encodeURIComponent(SID) +'&cPage='+encodeURIComponent(commentPage)+"&viewChar="+viewChar;
			$(event.target).css("background-color","#050505");		
			$.ajax({
				url: "skin/werewolf/viewCommentPage.php",
				data: strParams,
				dataType: "xml",
				success: function(xmlData){
					BuildXMLResults(xmlData,commentPage);
			 	}
			});	

			$(event.target).removeClass("close");
			$(event.target).addClass("open");
			$(event.target).css("background-color","#050505");
		}
		else{
			if(target.css("display")== "none"){
				target.fadeIn();
				$(event.target).removeClass("close");
				$(event.target).addClass("open");
				$(event.target).css("background-color","#050505");						
			}
			else{
				target.fadeOut();
				$(event.target).removeClass("open");
				$(event.target).addClass("close");
				$(event.target).css("background-color","#111111");						
			}
		}
		
		//location.replace("#commentPage"+commentPage);
	});
	
	// Open comment pages all
	$("#buttonOpenCommentPagesAll").click(function (event){
		var commentPages = document.getElementsByClassName("buttonCommentPage");
		
		for(var i = 0; i < commentPages.length; i++) {
			if(commentPages[i].className.indexOf(" close") !== -1)
				commentPages[i].click();
		}
	});
	
	$('.characterButton').each(function(index){
		var character = this;
		var cookie_checked = getCookie(gameNo+"_"+this.value);
		if(cookie_checked =="false"){
			//alert($("."+this.value).size());
			//$("."+this.value).fadeOut();
			var hh = $(this);
			hh.attr("checked",false);
			//hh.click();
			//$(this).click();
		}
	});
	
	$('.characterButton').click(function(event){
		$('.commentButton').each(function(index){
			//alert(event.target.checked + " "+ this.checked);
			//alert("."+event.target.value+" ."+this.value);
			if(event.target.checked && this.checked)
				$("."+event.target.value).filter("."+this.value).fadeIn();
			else
				$("."+event.target.value).filter("."+this.value).fadeOut();			    
		});
		
		setCookie(gameNo+"_"+event.target.value,event.target.checked);
	});
	
	$('.characterButton + label').dblclick(function(event){
		location.replace(gameLink +"view.php?id=werewolf&no="+gameNo+"&viewDay="+gameDay+"&viewMode="+viewMode+"&viewChar="+$(this).attr("for"));
		//location.replace(gameLink +"view.php?id=werewolf&no="+gameNo+"&viewDay="+gameDay+"&viewChar="+$(this).attr("for"));
		/*
		$('.commentButton').each(function(index){
			//alert(event.target.checked + " "+ this.checked);
			//alert("."+event.target.value+" ."+this.value);
			if(event.target.checked && this.checked)
				$("."+event.target.value).filter("."+this.value).fadeIn();
			else
				$("."+event.target.value).filter("."+this.value).fadeOut();
		});
		*/
	});
	
	$('.commentButton').click(function(event){
		$('.characterButton').each(function(index){
			//alert(event.target.checked + " "+ this.checked);
			//alert("."+event.target.value+" ."+this.value);
			if(event.target.checked && this.checked)
				$("."+event.target.value).filter("."+this.value).fadeIn();
			else
				$("."+event.target.value).filter("."+this.value).fadeOut();
		});
	});
	$("#memo").keyup(function(event) {
		//if($(this).val() == "봉인 제안") {
		if($(this).text() == "봉인 제안") {
			var r = confirm("봉인을 제안하시겠습니까? 봉인은 게임 진행을 중단시키는 것입니다.\n\n봉인을 제안하면 봉인을 찬성,반대하는 투표가 시작됩니다.\n사건 발생 시간까지 찬성표가 과반수보다 많으면 게임이 봉인됩니다.\n봉인 논의는 한 게임당 한번만 가능합니다.");
			if (r == true) {
			    var reason = prompt("봉인을 제안하는 이유를 적어주세요.", "자세하게 적어주시기 바랍니다.");

				try {
					var obj = $("#writeComment");
					
					prams="page="+obj.find("[name=page]").val();
					prams+="&id="+obj.find("[name=id]").val();
					prams+="&no="+obj.find("[name=no]").val();
					prams+="&select_arrange="+obj.find("[name=select_arrange]").val();
					prams+="&desc="+obj.find("[name=desc]").val();
					prams+="&page_num="+obj.find("[name=page_num]").val();
					prams+="&keyword="+obj.find("[name=keyword]").val();
					prams+="&category="+obj.find("[name=category]").val();
					prams+="&sn="+obj.find("[name=sn]").val();
					prams+="&ss="+obj.find("[name=ss]").val();
					prams+="&sc="+obj.find("[name=sc]").val();
					prams+="&mode="+obj.find("[name=mode]").val();
					prams+="&c_type="+encodeURIComponent("봉인제안");
					prams+="&memo="+encodeURIComponent(reason);
					prams+="&SID="+encodeURIComponent(SID);

					soundPlay = false;
					var writer= new net.ContentLoader("skin/werewolf/were_comment_type_ok.php",write_ok,BuildError,"POST",prams);
					//obj.memo.value = obj.memo.value + prams;
					return false;
				}
				catch(ee) {
					alert(ee.description);
					return false;
				}

			} else {
				txt = "봉인 제안은 신중하게 해주시기 바랍니다.";
				alert(txt);
			}
		}
	})


	
	//트래픽을 위해 F5키 사용을 막음
	$().keydown(function (event) {
		var eventChooser = event.keyCode;

	    if (eventChooser == 116) {
			$(window).unbind('beforeunload');
			alert("트래픽을 위해 새로 고침 사용을 자제해 주세요.");
			if (!window.netscape)
			{	
				event.keyCode =2;
			}
	        return false;
	    }
	});
});
