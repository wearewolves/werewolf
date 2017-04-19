<?
// Server 1
	$server['ip1'] = $_SERVER["REMOTE_ADDR"];
// Server 2
	$server['ip2'] = $_SERVER["HTTP_X_FORWARDED_FOR"];

	$server['ip']  = ($server['ip2'] == "" ) ? $server['ip1'] : $server['ip2']  ;

	$server['host'] = "example.werewolf.com";

?>