<?php
session_start();

$db = new mysqli(/*db information*/);
if($db->connect_error){
  die ('Connect Error ('.$db->connect_errno.')'.$db->connect_error);
}



function bedsize($num) {
if ($num === "Studio")
	return 1;
else if ($num === "Two Bedroom")
	return 2;
else if ($num === "Three Bedroom")
	return 3;
else if ($num === "Four Bedroom")
	return 4;
else if ($num === "Five Bedroom")
	return 5;
else if ($num === "Six Bedroom")
	return 6;
}

$fbid = $db->real_escape_string($_POST['editfbid']);
$email = $db->real_escape_string($_POST['editemail']);
$gender = $db->real_escape_string($_POST['gender']);
$_SESSION['useremail'] = $email;
$_SESSION['gender'] = $gender;
$address = $db->real_escape_string($_POST['editaddress']);
$bedrooms = $db->real_escape_string($_POST['editbedroom']);
$bednum = bedsize($bedrooms);
$bath = $db->real_escape_string($_POST['editbath']);
$start= $db->real_escape_string($_POST['editstart']);
$end = $db->real_escape_string($_POST['editend']);
$price = $db->real_escape_string($_POST['editprice']);

$db->query("UPDATE sublet SET `email`='".$email."', `gender` = '".$gender."', `address` = '".$address."', `bath` = '".$bath."', `bedroom` = '".$bedrooms."', `bednum` = '".$bednum."', `start` = '".$start."', `end` = '".$end."', `price` = '".$price."' WHERE `fbid`= '".$fbid."'");
$db->query("UPDATE subinterestmatch SET `hitemail` = '".$email."' WHERE `hitfbid` = '".$fbid."'");
$db->query("UPDATE subinterestmatch SET `interestedemail` = '".$email."' WHERE `interestedfbid` = '".$fbid."'");
$db->query("UPDATE subletusers SET `email` = '".$email."' WHERE `facebookid` = '".$fbid."'");
$db->query("UPDATE subletinterest SET `email` = '".$email."' WHERE `fbid` = '".$fbid."'");
$db->close();
echo "<script> top.location.href = 'roominfo.php' </script>";
?>