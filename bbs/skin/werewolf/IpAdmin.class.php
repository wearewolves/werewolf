<?
class IpAdmin{
	var $checkingID;
	var $checkingIP;

	var $checkedIP;
	var $waitingIP;

	var $checkedID;
	var $waitingID;
	
	var $id_ip_list;

	var $db_id;
	var $db_game;
	var $db_loginlog;
	var $db_entry;
	var $db_suddenDeath;
	var $db_member;

	function IpAdmin($prefix,$id) {
		$this->checkingID= 0;
		$this->checkingIP= 0;

		$this->checkedIP = array();
		$this->waitingIP = array();

		$this->checkedID = array();
		$this->waitingID = array();

		$this->id_ip_list=array();

		$this->db_id = $id;
		$this->db_game = $prefix."_board_".$id;
		$this->db_loginlog = $prefix."_board_".$id."_loginlog";
		$this->db_entry = $prefix."_board_".$id."_entry";
		$this->db_suddenDeath = $prefix."_board_".$id."_suddenDeath";
		$this->db_member = $prefix."_member_table";
	}

	function DB_array($key,$value,$db){
		$temp_result=mysql_query("select $key,$value  from $db ");

		while($temp_member=@mysql_fetch_array($temp_result)){
				$members[$temp_member[$key]]=$temp_member[$value];
		}

		return $members;
	}

	function printData($state){
		if($state == "") $state =  "view";

		if($state =="debug"){
			echo "<br> \$checkedIP <br>";
			asort ($this->checkedIP); 
			foreach($this->checkedIP as $ip){
				echo $ip.",<br>";
			}
	
			echo "<br><br> \$waitingIP <br>";
			print_r($this->waitingIP);

			echo "<br><br> \$checkedID <br>";
			asort ($this->checkedID); 
			foreach($this->checkedID as $id){
	//			echo "\$noMannerPlayers[".$id."]<br>";
	//			echo $id."<br>";
				echo $id.",";

			}	
			echo "<br><br> \$waitingID <br>";
			print_r($this->waitingID);
		}

		if($state =="view"){
			//검사된 ID
//			require_once("TableMaker.class.php");
			$head = array("ID","NAME","Level");


			echo "<table class='tableStyle'><thead><tr><td> ID </td><td> NAME </td><td> Level </td></tr></thead>";
			asort ($this->checkedID); 
			foreach($this->checkedID as $id){
				echo "<tr><td>".$id."</td>";
				$temp_result=mysql_fetch_array(mysql_query("select name,level from `$this->db_member` where no = '$id' "));
				echo "<td> $temp_result[name] </td><td> $temp_result[level] </td></tr>";
			}
			echo "</table>";

			//검사된 IP
			echo "<table class='tableStyle'><thead><tr><td> IP</td></tr></thead>";
			asort ($this->checkedIP); 
			foreach($this->checkedIP as $ip){
				echo "<tr><td>".$ip."</td></tr>";
			}
			echo "</table>";
		}
		echo "<!--시작-->";
		echo "<table  class='tableStyle'>";
		echo "<thead>";
		echo "<tr><td>id</td><td>name</td><td>IP</td><td>마을</td><td>돌연사</td></tr>";
		echo "</thead>";

		$gameNameList =$this->DB_array("no","subject",$this->db_game);
		$gameEntry  =array();

		ksort($this->id_ip_list );
		foreach($this->id_ip_list as $id => $ip_list){
			echo "<tr><td> $id </td>";

			$temp_result=mysql_fetch_array(mysql_query("select name from `$this->db_member` where no = '$id' "));
			echo "<td> $temp_result[0] </td>";
			echo "<td>";
				foreach($ip_list as $ip){
					echo $ip."<br>";
				}
			echo "</td>";
			echo "<td>";
			$temp_result=mysql_query("select distinct game from `$this->db_entry` where player like '$id' ");
			while($temp_member=@mysql_fetch_array($temp_result)){
				echo "<a href='../../view.php?id=$this->db_id&no=$temp_member[game]'>".$gameNameList[$temp_member['game']]."</a><br>";
				$gameEntry[$temp_member['game']][] = $id;
			}			
			echo "</td>";
			echo "<td>";
			$suddenDeathCount = mysql_fetch_array(mysql_query("select count(*)  from `$this->db_suddenDeath` where player = $id"));
			echo $suddenDeathCount[0] ;
			echo "</td></tr>";

		}
		echo "</table>";

		echo "<table  class='tableStyle'>";
		echo "<thead>";
		echo "<tr><td>마을</td><td>name</td></tr>";
		echo "</thead>";

		ksort($gameEntry);
		foreach($gameEntry as $game => $entryList){
			echo "<tr><td><a href='../../view.php?id=$this->db_id&no=$game'>$gameNameList[$game]</td></a><td>";

			foreach($entryList as $id){
				$temp_result=mysql_fetch_array(mysql_query("select name from `$this->db_member` where no = '$id' "));
				echo "$temp_result[0] <br>";
			}
			echo "</td></tr>";
		}
		echo "</table>";


		echo "<table  class='tableStyle'>";
		echo "<thead>";
		echo "<tr><td>no</td><td>접속 시간</td><td>ID</td><td>name</td><td>IP</td></tr>";
		echo "</thead>";

		$orderCondition = $this->orderCondition($this->checkedID);
		$temp_result=mysql_query("select * from `$this->db_loginlog` where ismember $orderCondition order by no desc  limit 50");

		while($loginlog=@mysql_fetch_array($temp_result)){
			echo "<tr><td>".$loginlog['no']."</td><td>".$loginlog['log_date']."</td><td>".$loginlog['ismember']."</td><td>".$loginlog['name']."</td><td>".$loginlog['ip']."</td></tr>";
		}			

		echo "</table>";
		echo "<!--끝-->";

	}

	function orderCondition($orderArray){
			$orderCondition ="in (";

			foreach($orderArray  as $temp_order){
				$orderCondition.=$temp_order.",";
			}
			$orderCondition.=")";

			return str_replace(",)", ")", $orderCondition);
	}


	function initID($id){
		$this->waitingID[] = $id;
	}

	function initIP($ip){
		$this->waitingIP[] = $ip;
	}

	function extractIPfromID($id){
		$members=array();
		$temp_result=mysql_query("select distinct ip from `$this->db_loginlog` where ismember = $id ");

		while($temp_member=@mysql_fetch_array($temp_result)){
			$members[]=$temp_member['ip'];
		}

		$this->id_ip_list[$id] = $members;
	}

	function extractIDfromIP($ip){

		$members=array();
		$temp_result=mysql_query("select distinct ismember from `$this->db_loginlog` where ip like '$ip' ");

		while($temp_member=@mysql_fetch_array($temp_result)){
			//$members[]=$temp_member['id'];
			$this->waitingID[] = $temp_member['ismember'];
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
						if(!in_array($ip,$this->waitingIP) && !in_array($ip,$this->checkedIP))  $this->waitingIP[] = $ip;
					}
				}
			}

			$this->waitingID = array();
			$this->detect();
		}
	}
}
?>