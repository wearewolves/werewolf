<?
class DB{
	var $id;
	var $game;
	var $loginlog;
	var $entry;
	var $suddenDeath;
	var $member;

	function DB($id) {
		$this->id = $id;
		$this->game = "zetyx_board_".$id;
		$this->loginlog = $this->game."_loginlog";
		$this->entry = $this->game."_entry";
		$this->gameinfo = $this->game."_gameinfo";
		$this->suddenDeath = $this->game."_suddenDeath";
		$this->character = $this->game."_character";
		$this->characterSet = $this->game."_characterSet";
		$this->truecharacter= $this->game."_truecharacter";
		$this->suddenDeath= $this->game."_suddenDeath";
		$this->record= $this->game."_record";

		$this->member = "zetyx_member_table";
	}
}
?>