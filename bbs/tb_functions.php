<? /****************************************
   /*  만든사람 : 임성욱 (likedy@nownuri.net)
   /*  출처 : woogi.apmsetup.org
   /*  설명 : 트랙백 쏘는 프로그램
   /*  고쳐서 쓰는건 좋은데..
   /*  저작권 지우면 미워요..
   /****************************************/
?>
<?
// 반드시 이 값을 자신에 맞게 고치세요.
$blog_name = "::인랑::"; // your site name
$maxLength = 300;    // 게시물 내용중 일부분을 잘라낼 캐릭터 숫자입니다. 

// 이하는 트랙백을 보내기 위한 함수들입니다.
$xml_tmp_error = 0;
$xml_tmp_message = 0;
$xml_tmp_title = 0;
$xml_tmp_link = 0;
$xml_error = "1";
$xml_message = "Unknown Error.";
$xml_title = "";
$xml_link = "";

function character_data($parser, $data)
{
 global $xml_tmp_error, $xml_tmp_message, $xml_tmp_title, $xml_tmp_link, $xml_error, $xml_message, $xml_title, $xml_link;

 if ($xml_tmp_error==1)
  $xml_error = trim($data);
 if ($xml_tmp_messge==1)
  $xml_message = trim($data);
 if ($xml_tmp_title==1)
  $xml_title = trim($data);
 if ($xml_tmp_link==1)
  $xml_link .= trim($data);
}
function start_element($parser, $name, $attrs)
{
 global $xml_tmp_error, $xml_tmp_message, $xml_tmp_title, $xml_tmp_link;
 switch (strtoupper($name))
 {
  case "ERROR":
   $xml_tmp_error++;
  break;
  case "MESSAGE":
   $xml_tmp_message++;
  break;
  case "TITLE":
   $xml_tmp_title++;
  break;
  case "LINK":
   $xml_tmp_link++;
  break;
 }
}
function end_element($parser, $name)
{
 global $xml_tmp_error, $xml_tmp_message, $xml_tmp_title, $xml_tmp_link;
 switch (strtoupper($name))
 {
  case "ERROR":
   $xml_tmp_error++;
  break;
  case "MESSAGE":
   $xml_tmp_message++;
  break;
  case "TITLE":
   $xml_tmp_title++;
  break;
  case "LINK":
   $xml_tmp_link++;
  break;
 }
}

function postTrackBack($title, $url, $excerpt, $receipt)
{
 global $blog_name, $maxLength;

 // 결과 배열 정의
 $result["value"] = false;
 $result["message"] = "Unknown Error";
 $result["title"] = "";
 $result["link"] = "";

 // mode rss로 정보를 읽어온다.
 if (!strstr($receipt, "__mode=rss") && !strstr($receipt, "?"))
  $receipt_rss = trim($receipt)."?__mode=rss";
 else if (!strstr($receipt, "__mode=rss") && strstr($receipt, "?"))
  $receipt_rss = trim($receipt)."&__mode=rss";

 // 트랙백 받는 측 URL을 요소별로 쪼갠다.
 $receipt_stuff = parse_url(trim($receipt_rss));
 if(!$receipt_stuff[port]) $receipt_stuff[port] = 80;

 // 접속 시도!
 $fp = @fsockopen($receipt_stuff[host], $receipt_stuff[port], $errno, $errstr, 30);

 if (!$fp)
 {
  $result["value"] = false;
  $result["message"] = "$errstr ($errno)";
  $result["title"] = "";
  $result["link"] = "";
  return $result;
 }
 else
 {
  // HTTP 프로토콜로 GET시도!
  fputs ($fp, "GET ".$receipt_stuff[path]."?".$receipt_stuff['query']." HTTP/1.1\r\n");
  fputs ($fp, "Accept: image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, application/x-shockwave-flash, application/vnd.ms-excel, application/vnd.ms-powerpoint, application/msword, */*\r\n");
  fputs ($fp, "Accept-Language: ko\r\n");
  fputs ($fp, "Accept-Encoding: gzip, deflate\r\n");
  fputs ($fp, "User-Agent: Mozilla/4.0\r\n");
  fputs ($fp, "Host: ".$receipt_stuff[host]."\r\n");
  fputs ($fp, "Connection: close\r\n");
  fputs ($fp, "Cache-Control: no-cache\r\n");
  fputs ($fp, "\r\n\r\n");

  // XML을 파싱하여 트랙백 응답 메세지를 검출한다.
  $xml_parser = xml_parser_create();
    xml_set_element_handler($xml_parser, "start_element", "end_element");
    xml_set_character_data_handler($xml_parser, "character_data");

  // HTTP 헤더 부분을 건너뛴다.
  while ($data = fgets($fp, 1024))
  {
   if (strstr($data,"<?xml"))
   {
    xml_parse($xml_parser, $data, feof($fp));
    break;
   }
  }

  while ($data = fgets($fp, 4096))
  {
   xml_parse($xml_parser, $data, feof($fp));

   //무한대기를 피하기 위해 빠져나간다.
   if (strstr(strtoupper($data), "</RESPONSE>"))
    break;
  }
  xml_parser_free($xml_parser);
  fclose ($fp);
 }

 // 트랙백 받는 측 URL을 요소별로 쪼갠다.
 $receipt_stuff = parse_url(trim($receipt));
 if(!$receipt_stuff[port]) $receipt_stuff[port] = 80;

 // 접속 시도!
 $fp = @fsockopen($receipt_stuff[host], $receipt_stuff[port], $errno, $errstr, 30);

 if (!$fp)
 {
  $result["value"] = false;
  $result["message"] = "$errstr ($errno)";
  $result["title"] = "";
  $result["link"] = "";
  return $result;
 }
 else
 {
  // 입력받은 요소들을 URL인코딩하고, POST할 데이터로 가공한다.
  $title = urlencode(str_replace("\r\n"," ", stripSlashes($title)));
  $url = urlencode(str_replace("\r\n"," ", stripSlashes($url)));
  $excerpt = urlencode(nl2br(stripSlashes(cut_strlen(strip_tags($excerpt),$maxLength))));
  $blog_name = urlencode(str_replace("\r\n"," ", stripSlashes($blog_name)));

  $post_data = "title=".$title."&url=".$url."&excerpt=".$excerpt."&blog_name=".$blog_name;

  // HTTP 프로토콜로 POST시도!
  fputs ($fp, "POST ".$receipt_stuff[path]."?".$receipt_stuff['query']." HTTP/1.1\r\n");
  fputs ($fp, "Accept: image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, application/x-shockwave-flash, application/vnd.ms-excel, application/vnd.ms-powerpoint, application/msword, */*\r\n");
  fputs ($fp, "Accept-Language: ko\r\n");
  fputs ($fp, "Content-Type: application/x-www-form-urlencoded\r\n");
  fputs ($fp, "Accept-Encoding: gzip, deflate\r\n");
  fputs ($fp, "User-Agent: Mozilla/4.0\r\n");
  fputs ($fp, "Host: ".$receipt_stuff[host]."\r\n");
  fputs ($fp, "Content-Length: ".strlen($post_data)."\r\n");
  fputs ($fp, "Connection: close\r\n");
  fputs ($fp, "Cache-Control: no-cache\r\n");
  fputs ($fp, "\r\n");
  fputs ($fp, $post_data."\r\n");
  fputs ($fp, "\r\n\r\n");

  // XML을 파싱하여 트랙백 응답 메세지를 검출한다.
  $xml_parser = xml_parser_create();
    xml_set_element_handler($xml_parser, "start_element", "end_element");
    xml_set_character_data_handler($xml_parser, "character_data");

  // HTTP 헤더 부분을 건너뛴다.
  while ($data = fgets($fp, 1024))
  {
   if (strstr($data,"<?xml"))
   {
    xml_parse($xml_parser, $data, feof($fp));
    break;
   }
  }

  while ($data = fgets($fp, 4096))
  {
   xml_parse($xml_parser, $data, feof($fp));

   // 성공인 경우 기록한다. (약간의 편법임)
   if (strstr(strtoupper($data), "<ERROR>0</ERROR>"))
    $success_tmp = true;

   //무한대기를 피하기 위해 빠져나간다.
   if (strstr(strtoupper($data), "</RESPONSE>"))
    break;
  }
  xml_parser_free($xml_parser);
  fclose ($fp);
 }

 global $xml_error, $xml_message, $xml_title, $xml_link;

 if ($success_tmp) $xml_error = "0";

 if ($xml_message == "")
 {
  if ($xml_error == "0") $xml_message = "TrackBack Success.";
  else $xml_message = "TrackBack Failure.";
 }

 if($xml_error == "0")
  $result["value"] = true;
 else
  $result["value"] = false;

 $result["message"] = $xml_message;
 if($xml_title)
 {
  $result["title"] = $xml_title;
  $result["link"] = $xml_link;
 }
 else
 {
  $result["title"] = $receipt;
  $result["link"] = $receipt;
 }

 return $result;
}

function cut_strlen($msg,$cut_size)
{
 if($cut_size<=0) return $msg;
 if(ereg("\[re\]",$msg)) $cut_size=$cut_size+4;
 for($i=0;$i<$cut_size;$i++) if(ord($msg[$i])>127) $han++; else $eng++;
 $cut_size=$cut_size+(int)$han*0.6;
 $point=1;
 for ($i=0;$i<strlen($msg);$i++)
 {
  if ($point>$cut_size) { return $pointtmp."...";}
  if (ord($msg[$i])<=127){
   $pointtmp.= $msg[$i];
   if ($point%$cut_size==0) { return $pointtmp."..."; }
  }else{
   if ($point%$cut_size==0) { return $pointtmp."..."; }
   $pointtmp.=$msg[$i].$msg[++$i];
   $point++;
  }
  $point++;
 }
 return $pointtmp;
}
?>