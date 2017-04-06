<?
/////////////////////////////////////////
//                                     //
//     mics'php - Trackback Sender     //
//                                     //
//     COPYLEFT (c) by micsland.com    //
//                                     //
/////////////////////////////////////////

function send_tb($t_url,$url,$title,$blog_name,$excerpt) {
    global $tb_error_str;

    //내용 정리
    $title = strip_tags($title);
    $excerpt = strip_tags($excerpt);

    $t_data = "url=".rawurlencode($url)."&title=".rawurlencode($title)."&blog_name=".rawurlencode($blog_name)."&excerpt=".rawurlencode($excerpt);

    //주소 처리
    $uinfo = parse_url($t_url);
    if($uinfo[query]) $t_data .= "&".$uinfo[query];
    if(!$uinfo[port]) $uinfo[port] = "80";

    //최종 전송 자료
    $send_str = "POST ".$uinfo[path]." HTTP/1.1\r\n".
                "Host: ".$uinfo[host]."\r\n".
                "User-Agent: MTools\r\n".
                "Content-Type: application/x-www-form-urlencoded\r\n".
                "Content-length: ".strlen($t_data)."\r\n".
                "Connection: close\r\n\r\n".
                $t_data;

    //전송
    $fp = fsockopen($uinfo[host],$uinfo[port]);
    fputs($fp,$send_str);

    //응답 받음
    while(!feof($fp)) $response .= fgets($fp,128);
    fclose($fp);

    //트랙백 URL인지 확인
    if(!strstr($response,"<response>")) {
        $tb_error_str = "올바른 트랙백 URL이 아닙니다.";
        return false;
    }

    //XML 부분만 뽑음
    $response = strchr($response,"<?");
    $response = substr($response,0,strpos($response,"</response>"));

    //에러 검사
    if(strstr($response,"<error>0</error>")) return true;
    else {
        $tb_error_str = strchr($response,"<message>");
        $tb_error_str = substr($tb_error_str,0,strpos($tb_error_str,"</message>"));
        $tb_error_str = str_replace("<message>","",$tb_error_str);
        $tb_error_str = "트랙백 전송중 오류가 발생했습니다: $tb_error_str";
        return false;
    }

}


?> 