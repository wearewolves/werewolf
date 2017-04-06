<?
class Player{
	var $no;
	var $db;

	function Player($db,$no) {
		$this->db = $db;
		$this->no= $no;		
	}

	function playCount(){
		$sql = "SELECT  count(*) FROM  `".$this->db->entry."` ,`".$this->db->gameinfo."` where player = $this->no and `".$this->db->entry."`.game = `".$this->db->gameinfo."`.game and (state = '게임끝' or state = '버그' or state = '테스트')";

		$playCount =  mysql_fetch_array(mysql_query($sql));
		return $playCount[0];
	}

	function bugCount(){
		$sql = "SELECT  count(*) FROM  `".$this->db->entry."` ,`".$this->db->gameinfo."` where player = $this->no and `".$this->db->entry."`.game = `".$this->db->gameinfo."`.game and (state = '버그')";

		$playCount =  mysql_fetch_array(mysql_query($sql));
		return $playCount[0];
	}
}
?>