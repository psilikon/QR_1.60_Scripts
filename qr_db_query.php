<?php
# qr_db_query.php
#
# This script is designed to exchange information between vicidial.php 
# and the database server for custom QR ACTIONS
# 
# required variables:
#  - $ACTION - ('GET_LEAD_COMMENTS', 'ADD_LEAD_COMMENTS')
#  - $debug - ('true','false')
# optional variables:
#  - $lead_id - ('36524',...)
#  - $additional_comments - ('blah blah Customer',...)
#
# CHANGELOG:
# 10001-20110127 - First build of script

$version = '1.0.0';
$build = '10001-20110127';
require("dbconnect.php");

// vars package
$data = array();
// default required vars
$data["action"] = NULL;
$data["debug"] = false;

//response placeholder
// default to error
$RETURN = "FALSE|";

// fill vars package 
// with get/post vars
foreach($_POST as $name => $val){
	switch(strtolower($name)){
		case "debug":
			if(strtolower($val) == "true"){
				$data["debug"] = true;
			}
			else{
				$data["debug"] = false;
			}
			break;
		case "action":
			$data["action"] = strtoupper($val);
			break;
		default:
			$data[strtolower($name)] = $val;
			break;
	}
}
foreach($_GET as $name => $val){
	switch(strtolower($name)){
		case "debug":
			if(strtolower($val) == "true"){
				$data["debug"] = true;
			}
			else{
				$data["debug"] = false;
			}
			break;
		case "action":
			$data["action"] = strtoupper($val);
			break;
		default:
			$data[strtolower($name)] = $val;
			break;
	}
}

// send html headers
header ("Content-type: text/html; charset=utf-8");
header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
header ("Pragma: no-cache");                          // HTTP/1.0

try{
	switch($data["action"]){
		case "GET_LEAD_COMMENTS":
			$stmt = "SELECT entry_date, comments, user  
					FROM qri_list_comments 
					WHERE lead_id = " . $data["lead_id"] . " 
					ORDER BY entry_date DESC ; ";
			$rslt = mysql_query($stmt, $link);		
			if($rslt){
				$RETURN = "TRUE|";
				while(false !== ($rs = mysql_fetch_assoc($rslt))){
					$tmp_comments = $rs["comments"];
					$tmp_comments = str_replace("--AMP--", "&", $tmp_comments);
					$tmp_comments = str_replace("--QUES--", "?", $tmp_comments);
					$tmp_comments = str_replace("--POUND--", "#", $tmp_comments);					
					$RETURN .= $rs["entry_date"] . " [User: "	. $rs["user"] . "]\n"	. $tmp_comments . "|";
				}
			}		
			break;
		case "ADD_LEAD_COMMENTS":
			$stmt = "INSERT INTO qri_list_comments(lead_id, entry_date, comments, user) 
					VALUES('" . $data["lead_id"] . "', '" . date("Y-m-d H:i:s") . "', '" . $data["additional_comments"] . "', '" . $data["user"] . "') ; ";
			$rslt = mysql_query($stmt, $link);	
			$RETURN = "TRUE";
			break;
		default:
		
			break;
	}
}
catch(Exception $e){
	$RETURN .= $e->getMessage();
}

echo $RETURN;
?>