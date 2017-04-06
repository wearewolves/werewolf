<?
class TableMaker{
	var $tableStyle;

	function setTableStyle($style){
		$this->tableStyle = $style;
	}

	function printTable($heads,$contents,$col=""){
		echo "<table class='$this->tableStyle'>";
		echo $col;

		if($heads){
			echo "<thead><tr>";
				$this->printTD($heads);
			echo "</tr></thead>";			
		}

		foreach($contents as $row){
			echo "<tr onMouseOver=this.style.backgroundColor='#090909' onMouseOut=this.style.backgroundColor=''>";
				$this->printTD($row);
			echo "</tr>";
		}

		echo "</table>";
	}

	function printTD($contents){
		foreach($contents as $data){
			if($data == "") $data ="&nbsp;";
			echo "<td>".$data."</td>";
		}
	}

	function addTD($contents,$style="",$rowscol=0,$rowcols=0){

	}

	function addTR($contents){

	}
}
?>