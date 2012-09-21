<?php

/* Delete location tracker info */

$db = new mysqli(/*db information*/);
if($db->connect_error){
  die ('Connect Error ('.$db->connect_errno.')'.$db->connect_error);
}


$id = $db->real_escape_string($_POST['facebid']);

$db->query("UPDATE `subletusers` SET `enteredinterest`=0 WHERE `facebookid`= '".$id."'");
$db->query("DELETE FROM `subinterestmatch` WHERE `interestedfacebookid`= '".$id."'");
$db->query("DELETE FROM `subletinterest` WHERE `fbid`= '".$id."'");
$db->close();

header("Location: main.php");

?>