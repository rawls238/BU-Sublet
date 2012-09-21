<?php
session_start();


$db = new mysqli("subletinterest72.db.7681913.hostedresource.com", "subletinterest72", "BUcompsci123", "subletinterest72");
if($db->connect_error){
  die ('Connect Error ('.$db->connect_errno.')'.$db->connect_error);
}

$length = 0;




$id = $db->real_escape_string($_POST['id']);

$name;
$mail;
$result = $db->query("SELECT `name`, `email` FROM sublet WHERE `fbid` = '".$id."'");
while ($row = $result->fetch_assoc())  {
	$name = $row['name'];
	$mail = $row['mail'];
}
	$mailurl = "mailto:".$mail;
	$url = "http://www.facebook.com/".$id;
	echo "<br><center><a href = '".$url."'>".$name."</a>&nbsp&nbsp&nbsp<a href = '".$mailurl."'>Email</a></center>";




?>