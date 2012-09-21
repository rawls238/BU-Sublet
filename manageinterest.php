<?php

/* Process deletion of suggested listings */ 

$db = new mysqli(/*db information*/););
if($db->connect_error){
  die ('Connect Error ('.$db->connect_errno.')'.$db->connect_error);
}


$myid = $db->real_escape_string($_POST['myid']);
$otherid = $db->real_escape_string($_POST['id']);

if ($otherid === "all") {
$db->query("DELETE FROM `subinterestmatch` WHERE `interestedfbid` LIKE '".$myid."'");
} else {
$db->query("DELETE FROM `subinterestmatch` WHERE `interestedfbid` = '".$myid."' AND `hitfbid` = '".$otherid."'");
}
$db->close();

header("Location: sublettrans.php");

?>