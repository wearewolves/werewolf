<?
class CheckOverlapIdByIp{
	var $checkingID;
	var $checkingIP;

	var $overlapedIP;

	var $checkedIP;
	var $waitingIP;

	var $checkedID;
	var $waitingID;
	
	var $id_ip_list;

	function CheckOverlapIdByIp() {
		$this->checkingID= 0;
		$this->checkingIP= 0;

		$this->overlapedIP = array();

		$this->checkedIP = array();
		$this->waitingIP = array();

		$this->checkedID = array();
		$this->waitingID = array();

		$this->id_ip_list=array();

	}

	function initID($id){		
		$this->waitingID[] = $id;
	}

	function initIP($ip){
		$this->waitingIP[] = $ip;
	}

	function extractIPfromID($id){
		$members=array();
		$temp_result=mysql_query("select distinct ip from `".$GLOBALS['Database']->loginlog."` where ismember = $id ");

		while($temp_member=@mysql_fetch_array($temp_result)){
			$members[]=$temp_member['ip'];
		}

		$this->id_ip_list[$id] = $members;
	}

	function extractIDfromIP($ip){

		$members=array();
		$temp_result=mysql_query("select distinct ismember from `".$GLOBALS['Database']->loginlog."` where ip like '$ip' ");

		while($player=@mysql_fetch_array($temp_result)){
			if(!in_array($player['ismember'],$this->waitingID) && !in_array($player['ismember'],$this->checkedID))$this->waitingID[] = $player['ismember'];
		}
	}

	function detect(){
		if(array_count_values($this->waitingIP)){
			//echo "array_count_values(\$this->waitingIP): ".array_count_values($this->waitingIP)."<br>";
			foreach($this->waitingIP as $ip){
				if(!in_array($ip,$this->checkedIP)){
					$this->checkedIP[] = $ip;

					$this->extractIDfromIP($ip);
				}
				else{
					$this->overlapedIP[] = $ip;
				}
			}

			$this->waitingIP = array();
			$this->detect();
		}
		elseif(array_count_values($this->waitingID)){
			foreach($this->waitingID as $id){
				if(!in_array($id,$this->checkedID)){
					$this->checkedID[] = $id;

					$this->extractIPfromID($id);

					foreach($this->id_ip_list[$id] as $ip){
						//if(!in_array($ip,$this->waitingIP) && !in_array($ip,$this->checkedIP))  $this->waitingIP[] = $ip;
						if(!in_array($ip,$this->waitingIP))  $this->waitingIP[] = $ip;
					}
				}
			}

			$this->waitingID = array();
			$this->detect();
		}
	}
}
?>