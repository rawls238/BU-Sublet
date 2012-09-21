<?php

/* Process deletion of room */

$db = new mysqli(/*db information*/);
if($db->connect_error){
  die ('Connect Error ('.$db->connect_errno.')'.$db->connect_error);
}


$id = $db->real_escape_string($_POST['deletefbid']);


$db->query("UPDATE `subletusers` SET `enteredroom`=0 WHERE `facebookid`= '".$id."'");
$db->query("DELETE FROM `sublet` WHERE `fbid`= '".$id."'");
$db->query("DELETE FROM `subinterestmatch` WHERE `hitfacebookid`= '".$id."'");

	$db->close();

//take back to main
	header("Location: main.php");
	?>