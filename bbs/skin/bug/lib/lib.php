<?
function DB_array($key,$value,$db){
	$_dbTimeStart = getmicrotime();
	$temp_result=mysql_query("select * from $db ");

	while($temp_member=@mysql_fetch_array($temp_result)){
			$members[$temp_member[$key]]=$temp_member[$value];
	}

	return $members;
}
function DBselect($name,$head,$id,$value,$DB,$code,$selectedID){
		$_dbTimeStart = getmicrotime();
		$result=mysql_query("select * from $DB order by $id");
		$_dbTime += getmicrotime()-$_dbTimeStart;
		
		$DB_select="&nbsp;<select $code name=$name>$head";
		while($temp=mysql_fetch_array($result)) {
			if($temp[$id]==$selectedID)$selected="selected";
			else $selected="";
			
			$DB_select.="<option value=$temp[$id] ".$selected." > $temp[$value]</option>";
		}
		$DB_select.="</select> ";
		return $DB_select;
  }

	$DB_brief		=$t_board."_".$id."_brief";
	$DB_addnote		=$t_board."_".$id."_addnote";
	$DB_dealResult	=$t_board."_".$id."_dealResult";
	$DB_server 		=$t_board."_".$id."_server";
	$DB_serverity	=$t_board."_".$id."_serverity";
	$DB_status 		=$t_board."_".$id."_status"; 
	$DB_type 		=$t_board."_".$id."_type";

	$server		=DB_array("SID","name",$DB_server);
	$dealResult	=DB_array("no","name",$DB_dealResult);
	$serverity	=DB_array("no","name",$DB_serverity);
	$status		=DB_array("no","name",$DB_status);
	$type		=DB_array("no","name",$DB_type);
?> 