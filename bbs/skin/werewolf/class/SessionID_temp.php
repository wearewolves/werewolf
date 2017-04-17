<?
class SessionID{
	var $secretKey;

	function SessionID(){
		//$this->secretKey="secretKey";
		$this->secretKey = "very good funny exciting game i will be great game designer.I love you.";
	}

	// ¾ÏÈ£È­  ////////////////////////////
	function bytexor($a,$b,$l){ 
		$c=""; 

		for($i=0;$i<$l;$i++) { 
			$c.=$a{$i}^$b{$i}; 
		} 
		return($c); 
	} 

	function binmd5($val){ 
		return(pack("H*",md5($val))); 
	} 

	function decrypt_md5($msg,$heslo){ 
		$key=$heslo;$sifra=""; 
		$key1=$this->binmd5($key); 

		while($msg) { 
			$m=substr($msg,0,16); 
			$msg=substr($msg,16); 
			$sifra.=$m=$this->bytexor($m,$key1,16); 
			$key1=$this->binmd5($key.$key1.$m); 
		 } 
		echo "\n"; 
		return($sifra); 
	} 

	function crypt_md5($msg,$heslo){ 
		$key=$heslo;$sifra=""; 
		$key1=$this->binmd5($key); 

		while($msg) { 
			$m=substr($msg,0,16); 
			$msg=substr($msg,16); 
			$sifra.=$this->bytexor($m,$key1,16); 
			$key1=$this->binmd5($key.$key1.$m); 
		} 
		echo "\n"; 
		return($sifra); 
	} 


	// Example of usage... 
	/*
	$message = "This is a very long message, but it is very secret and important and we need to keep the contents hidden from nasty people who might want to steal it."; 

	$key = "secret key"; 

	$crypted = crypt_md5($message, $key); 
	echo "Encoded = $crypted<BR>"; // returns ??=? ???`??<?H ???{.?1?{??&#593;?J?V?+?j?e? 

	$uncrypted = decrypt_md5($crypted, $key); 
	echo "Unencoded = $uncrypted"; // returns This is a very long message (etc) 
	*/

	function getSID($game , $day, $lastComment, $member, $viewMode){

		$SID =  $game ."<||>". $day ."<||>". $lastComment ."<||>". $member."<||>". $viewMode;
		$SID = $this->crypt_md5($SID, $this->secretKey);
		$SID = base64_encode($SID) ; 
//		$SID = urlencode($SID);
		return $SID;
	} 
}
?>