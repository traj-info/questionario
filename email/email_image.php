<?php 
/**
 * @author Renato Zuma Bange
 */

// Require files
// -=-=-=-=-=-=-=-=-=-=-=--=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
require_once('../includes/TDbConnector.php');                   # manipulate all database queries
require_once('../includes/util.php');                           # provide some usefull functions we will be needing

$now = NowDatetime();											# get current date/time using MySQL date format
$key = strip_tags($_GET["key"]);
//$key = FilterData($key);								# filter $key to prevent injection

if (strlen($key)!= 36){											# ...if $key length isn't exactly 36 chars: exit script!
	exit();
}
else{															# ...else: connect to database, increment total_views by one and update last_view with current time
	$conn = new TDbConnector();
	$row = $conn->GetResult("SELECT total_views FROM recipients WHERE recipients.key ='$key' LIMIT 1");
	if($row)
	{
		$n = (int)$row['total_views'] + 1;
		$conn->Query("UPDATE recipients 							 
				  SET total_views=$n, last_view='$now' 
				  WHERE recipients.key='$key'");
	}
	
	
}

?>